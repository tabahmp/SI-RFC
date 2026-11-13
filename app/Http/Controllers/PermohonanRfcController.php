<?php

namespace App\Http\Controllers;

use App\Models\DokumenBast;
use App\Models\EksekusiRfc;
use App\Models\KategoriRfc;
use App\Models\Opd;
use App\Models\PermohonanRfc;
use App\Models\PersetujuanRfc;
use App\Models\RevisiRfc;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PermohonanRfcController extends Controller
{
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
    // DASHBOARD PER PERAN
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

    public function dashboardPemohon(Request $request)
    {
        $user = auth()->user();

        $rfcQuery = PermohonanRfc::where('pengguna_id', $user->id)
            ->with('kategori', 'opd')
            ->orderByDesc('created_at');

        // Apply filters
        if ($request->filled('status')) $rfcQuery->where('status_terkini', $request->status);
        if ($request->filled('kategori_id')) $rfcQuery->where('kategori_id', $request->kategori_id);
        if ($request->filled('urgensi')) $rfcQuery->where('urgensi', $request->urgensi);
        if ($request->filled('search')) {
            $search = $request->search;
            $rfcQuery->where(function($q) use ($search) {
                $q->where('kode_pelacakan', 'like', "%{$search}%")
                  ->orWhere('judul_perubahan', 'like', "%{$search}%")
                  ->orWhere('deskripsi_perubahan', 'like', "%{$search}%");
            });
        }
        if ($request->filled('tanggal_mulai')) $rfcQuery->whereDate('created_at', '>=', $request->tanggal_mulai);
        if ($request->filled('tanggal_akhir')) $rfcQuery->whereDate('created_at', '<=', $request->tanggal_akhir);

        $rfcList = $rfcQuery->get()->map(fn ($rfc) => $this->formatRfcRingkas($rfc));

        return Inertia::render('Dashboard/Pemohon', [
            'rfc'         => $rfcList,
            'filters'     => $request->only(['status', 'kategori_id', 'urgensi', 'search', 'tanggal_mulai', 'tanggal_akhir']),
            'kategoriList'=> \App\Models\KategoriRfc::aktif()->get(['id', 'nama_kategori']),
            'countDraf'   => PermohonanRfc::where('pengguna_id', $user->id)->where('status_terkini', 'draf')->count(),
            'countAktif'  => PermohonanRfc::where('pengguna_id', $user->id)
                                           ->whereNotIn('status_terkini', ['draf', 'selesai', 'ditolak_verifikator', 'ditolak_pimpinan'])
                                           ->count(),
            'countSelesai'=> PermohonanRfc::where('pengguna_id', $user->id)->where('status_terkini', 'selesai')->count(),
            'initialView' => $request->query('view', 'ringkasan'),
        ]);
    }

    public function dashboardVerifikator(Request $request)
    {
        $query = PermohonanRfc::where('status_terkini', 'diajukan')
            ->with('pengguna', 'opd', 'kategori');

        // Apply filters
        if ($request->filled('urgensi')) $query->where('urgensi', $request->urgensi);
        if ($request->filled('kategori_id')) $query->where('kategori_id', $request->kategori_id);
        if ($request->filled('opd_id')) $query->where('opd_id', $request->opd_id);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_pelacakan', 'like', "%{$search}%")
                  ->orWhere('nama_pemohon', 'like', "%{$search}%");
            });
        }

        $antrean = $query->orderBy('urgensi', 'desc')
            ->orderBy('created_at', 'asc')
            ->limit(30)
            ->get()
            ->map(fn ($rfc) => $this->formatRfcRingkas($rfc));

        $slaKritis = PermohonanRfc::whereIn('status_terkini', ['diajukan', 'menunggu_persetujuan'])
            ->whereNotNull('target_sla')
            ->where('target_sla', '<=', now()->addHours(24))
            ->with('opd')
            ->orderBy('target_sla', 'asc')
            ->limit(10)
            ->get()
            ->map(fn ($rfc) => array_merge($this->formatRfcRingkas($rfc), [
                'target_sla' => $rfc->target_sla?->toIso8601String(),
            ]));

        return Inertia::render('Dashboard/Verifikator', [
            'antrean'       => $antrean,
            'filters'       => $request->only(['urgensi', 'kategori_id', 'opd_id', 'search']),
            'kategoriList'  => \App\Models\KategoriRfc::aktif()->get(['id', 'nama_kategori']),
            'opdList'       => \App\Models\Opd::aktif()->get(['id', 'nama_opd']),
            'countAntrean'  => PermohonanRfc::where('status_terkini', 'diajukan')->count(),
            'slaKritis'     => $slaKritis,
            'statistikBulanIni' => $this->statistikBulanIni(),
            'initialView'   => $request->query('view', 'ringkasan'),
        ]);
    }

    public function dashboardPimpinan(Request $request)
    {
        $menunggu = PermohonanRfc::where('status_terkini', 'menunggu_persetujuan')
            ->with('pengguna', 'opd', 'kategori')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn ($rfc) => $this->formatRfcRingkas($rfc));

        return Inertia::render('Dashboard/Pimpinan', [
            'menungguKeputusan' => $menunggu,
            'countMenunggu'     => $menunggu->count(),
            'statistik'         => $this->statistikBulanIni(),
            'initialView'       => $request->query('view', 'ringkasan'),
        ]);
    }

    public function dashboardTeknisi(Request $request)
    {
        $user = auth()->user();

        $tugasSaya = EksekusiRfc::where('teknisi_id', $user->id)
            ->where('status_eksekusi', 'proses_pengerjaan')
            ->with(['rfc.opd', 'rfc.kategori'])
            ->orderBy('dimulai_pada', 'asc')
            ->get()
            ->map(fn ($eks) => array_merge(
                $this->formatRfcRingkas($eks->rfc),
                [
                    'eksekusi_id'    => $eks->id,
                    'dimulai_pada'   => $eks->dimulai_pada?->format('d/m/Y H:i'),
                    'catatan_eksekusi'=> $eks->catatan_eksekusi,
                ]
            ));

        // RFC disetujui yang belum punya eksekusi
        $rfcTersedia = PermohonanRfc::where('status_terkini', 'disetujui')
            ->doesntHave('eksekusi')
            ->with('opd', 'kategori')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn ($rfc) => $this->formatRfcRingkas($rfc));

        $arsipBast = DokumenBast::whereHas('rfc', fn ($q) => $q->whereHas('eksekusi', fn ($q2) => $q2->where('teknisi_id', $user->id)))
            ->with('rfc.opd')
            ->orderByDesc('dibuat_pada')
            ->limit(20)
            ->get()
            ->map(fn ($bast) => [
                'id'            => $bast->id,
                'nomor_dokumen' => $bast->nomor_dokumen,
                'rfc_kode'      => $bast->rfc?->kode_pelacakan,
                'opd'           => $bast->rfc?->opd?->nama_opd ?? '-',
                'dibuat_pada'   => $bast->dibuat_pada?->format('d/m/Y'),
                'path_file_url' => Storage::url($bast->path_file),
            ]);

        return Inertia::render('Dashboard/Teknisi', [
            'tugasSaya'    => $tugasSaya,
            'rfcTersedia'  => $rfcTersedia,
            'arsipBast'    => $arsipBast,
            'initialView'  => $request->query('view', 'ringkasan'),
        ]);
    }

    public function index(Request $request)
    {
        // Generic index â€” redirect ke dashboard peran masing-masing
        return redirect()->route('dashboard');
    }

    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
    // FORM RFC BARU
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

    public function create()
    {
        return Inertia::render('RFC/Create', [
            'kategoriList' => KategoriRfc::aktif()->orderBy('nama_kategori')->get(['id', 'nama_kategori', 'deskripsi']),
            'opdUser'      => auth()->user()?->opd ? [
                'id'       => auth()->user()->opd_id,
                'nama_opd' => auth()->user()->opd->nama_opd,
            ] : null,
        ]);
    }

    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
    // STORE / SIMPAN RFC
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

    /**
     * Show form for editing RFC (draf or perlu_revisi only)
     */
    public function edit(int $id)
    {
        $rfc = PermohonanRfc::where('pengguna_id', auth()->id())->findOrFail($id);
        
        if (!in_array($rfc->status_terkini, ['draf', 'perlu_revisi'])) {
            return redirect()->route('rfc.show', $rfc->kode_pelacakan)
                ->with('error', 'RFC dengan status ini tidak dapat diedit.');
        }
        
        return Inertia::render('RFC/Create', [
            'mode' => 'edit',
            'rfc' => [
                'id' => $rfc->id,
                'kategori_id' => $rfc->kategori_id,
                'unit_kerja' => $rfc->unit_kerja,
                'nomor_surat' => $rfc->nomor_surat,
                'nomor_kontak' => $rfc->nomor_kontak,
                'email_dinas' => $rfc->email_dinas,
                'tanggal_permohonan' => $rfc->tanggal_permohonan?->format('Y-m-d'),
                'jenis_perubahan' => $rfc->jenis_perubahan,
                'judul_perubahan' => $rfc->judul_perubahan,
                'deskripsi_perubahan' => $rfc->deskripsi_perubahan,
                'latar_belakang' => $rfc->latar_belakang,
                'rincian_perubahan' => $rfc->rincian_perubahan,
                'urgensi' => $rfc->urgensi,
                'risiko_tidak_dilakukan' => $rfc->risiko_tidak_dilakukan,
                'kriteria_risiko' => $rfc->kriteria_risiko,
                'keterangan_risiko' => $rfc->keterangan_risiko,
                'rencana_pemulihan' => $rfc->rencana_pemulihan,
                'solusi_diharapkan' => $rfc->solusi_diharapkan,
                'risiko_perubahan' => $rfc->risiko_perubahan,
                'alternatif_perubahan' => $rfc->alternatif_perubahan,
                'biaya_perubahan' => $rfc->biaya_perubahan,
                'waktu_perubahan' => $rfc->waktu_perubahan,
                'nama_aplikasi' => $rfc->nama_aplikasi,
                'deskripsi_aplikasi' => $rfc->deskripsi_aplikasi,
                'alamat_aplikasi' => $rfc->alamat_aplikasi,
                'alamat_repository' => $rfc->alamat_repository,
                'path_lampiran' => $rfc->path_lampiran,
                'tanda_tangan_pemohon' => $rfc->tanda_tangan_pemohon ? Storage::url($rfc->tanda_tangan_pemohon) : null,
            ],
            'kategoriList' => KategoriRfc::aktif()->orderBy('nama_kategori')->get(['id', 'nama_kategori', 'deskripsi']),
            'opdUser' => auth()->user()?->opd ? [
                'id' => auth()->user()->opd_id,
                'nama_opd' => auth()->user()->opd->nama_opd,
            ] : null,
        ]);
    }

    /**
     * Update RFC (draf or perlu_revisi only)
     */
    public function update(Request $request, int $id)
    {
        $rfc = PermohonanRfc::where('pengguna_id', auth()->id())->findOrFail($id);
        $user = auth()->user();
        
        if (!in_array($rfc->status_terkini, ['draf', 'perlu_revisi'])) {
            return response()->json(['message' => 'RFC dengan status ini tidak dapat diedit.'], 422);
        }
        
        $simpanDraf = $request->boolean('simpan_draf', false);
        
        $validated = $simpanDraf ? $request->validate([
            'kategori_id' => ['nullable', 'exists:kategori_rfc,id'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'deskripsi_perubahan' => ['nullable', 'string'],
            'urgensi' => ['nullable', 'in:rendah,sedang,tinggi,kritis'],
        ]) : $request->validate([
            'kategori_id' => ['required', 'exists:kategori_rfc,id'],
            'unit_kerja' => ['required', 'string', 'max:255'],
            'nomor_kontak' => ['required', 'string', 'max:50'],
            'email_dinas' => ['required', 'email'],
            'tanggal_permohonan' => ['required', 'date'],
            'jenis_perubahan' => ['required', 'array', 'min:1'],
            'deskripsi_perubahan' => ['required', 'string'],
            'latar_belakang' => ['required', 'string'],
            'rincian_perubahan' => ['required', 'string'],
            'urgensi' => ['required', 'in:rendah,sedang,tinggi,kritis'],
        ]);
        
        // Handle file uploads
        $pathLampiran = $rfc->path_lampiran ?? [];
        if ($request->hasFile('lampiran')) {
            foreach (array_slice($request->file('lampiran'), 0, 5 - count($pathLampiran)) as $file) {
                $fileMime = $file->getMimeType();
                if (!in_array($fileMime, ['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','image/jpeg','image/png'])) {
                    return response()->json(['message' => 'Tipe file tidak diizinkan'], 422);
                }
                if ($file->getSize() > 5242880) return response()->json(['message' => 'File terlalu besar'], 422);
                $pathLampiran[] = $file->storeAs('public/lampiran', time().'_'.uniqid().'_'.preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName()));
            }
        }
        
        // Handle signature
        $ttdPath = $rfc->tanda_tangan_pemohon;
        if ($request->filled('tanda_tangan') && str_starts_with($request->tanda_tangan, 'data:image')) {
            if ($ttdPath && Storage::exists($ttdPath)) Storage::delete($ttdPath);
            $ttdPath = $this->simpanBase64Image($request->tanda_tangan, 'tanda_tangan');
        }
        
        $rfc->update(array_merge($validated, [
            'path_lampiran' => $pathLampiran ?: null,
            'tanda_tangan_pemohon' => $ttdPath,
        ]));
        
        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'pemohon', $rfc->status_terkini, 'RFC diperbarui');
        
        return response()->json(['message' => 'RFC berhasil diperbarui.', 'kode_pelacakan' => $rfc->kode_pelacakan]);
    }


    public function store(Request $request)
    {
        $simpanDraf = $request->boolean('simpan_draf', false);
        $user       = auth()->user();

        if ($simpanDraf) {
            // Validasi minimal untuk draf - semua field opsional
            $validated = $request->validate([
                'kategori_id'         => ['nullable', 'exists:kategori_rfc,id'],
                'unit_kerja'          => ['nullable', 'string', 'max:255'],
                'deskripsi_perubahan' => ['nullable', 'string'],
                'urgensi'             => ['nullable', 'in:rendah,sedang,tinggi,kritis'],
                'nomor_surat'         => ['nullable', 'string', 'max:100'],
                'nomor_kontak'        => ['nullable', 'string', 'max:50'],
                'email_dinas'         => ['nullable', 'email'],
                'tanggal_permohonan'  => ['nullable', 'date'],
                'jenis_perubahan'     => ['nullable', 'array'],
                'nama_aplikasi'       => ['nullable', 'string'],
            ]);
        } else {
            // Validasi penuh untuk pengajuan
            $validated = $request->validate([
                'kategori_id'          => ['required', 'exists:kategori_rfc,id'],
                'unit_kerja'           => ['required', 'string', 'max:255'],
                'nomor_surat'          => ['nullable', 'string', 'max:100'],
                'nomor_kontak'         => ['required', 'string', 'max:50'],
                'email_dinas'          => ['required', 'email'],
                'tanggal_permohonan'   => ['required', 'date'],
                'jenis_perubahan'      => ['required', 'array', 'min:1'],
                'deskripsi_perubahan'  => ['required', 'string'],
                'latar_belakang'       => ['required', 'string'],
                'rincian_perubahan'    => ['required', 'string'],
                'urgensi'              => ['required', 'in:rendah,sedang,tinggi,kritis'],
                'risiko_tidak_dilakukan' => ['nullable', 'string'],
                'kriteria_risiko'      => ['nullable', 'string'],
                'keterangan_risiko'    => ['nullable', 'string'],
                'rencana_pemulihan'    => ['nullable', 'string'],
                'solusi_diharapkan'    => ['nullable', 'string'],
                'risiko_perubahan'     => ['nullable', 'string'],
                'alternatif_perubahan' => ['nullable', 'string'],
                'biaya_perubahan'      => ['nullable', 'string', 'max:100'],
                'waktu_perubahan'      => ['nullable', 'string', 'max:100'],
                'nama_aplikasi'        => ['nullable', 'string'],
                'deskripsi_aplikasi'   => ['nullable', 'string'],
                'alamat_aplikasi'      => ['nullable', 'string'],
                'alamat_repository'    => ['nullable', 'string'],
                'tanda_tangan'         => ['nullable', 'string'],
            ]);
        }

        // Simpan lampiran dengan validasi keamanan
        $pathLampiran = [];
        $files = $request->file('lampiran') ?? [];
        
        $allowedMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'image/jpeg',
            'image/png',
            'application/zip',
        ];
        
        foreach (array_slice($files, 0, 5) as $file) {
            // Validate MIME type from file header (not just extension)
            $fileMime = $file->getMimeType();
            $fileSize = $file->getSize();
            
            if (!in_array($fileMime, $allowedMimes)) {
                return response()->json([
                    'message' => 'Tipe file tidak diizinkan: ' . $file->getClientOriginalName(),
                    'error' => 'File harus berupa PDF, Word, Excel, JPG, PNG, atau ZIP'
                ], 422);
            }
            
            if ($fileSize > 5 * 1024 * 1024) { // 5MB max
                return response()->json([
                    'message' => 'Ukuran file terlalu besar: ' . $file->getClientOriginalName(),
                    'error' => 'Maksimal ukuran file adalah 5MB'
                ], 422);
            }
            
            // Sanitize filename
            $originalName = $file->getClientOriginalName();
            $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
            $uniqueName = time() . '_' . uniqid() . '_' . $safeName;
            
            $pathLampiran[] = $file->storeAs('public/lampiran', $uniqueName);
        }

        // Simpan tanda tangan base64
        $ttdPath = null;
        $ttdInput = $request->input('tanda_tangan');
        if ($ttdInput && str_starts_with($ttdInput, 'data:image')) {
            $ttdPath = $this->simpanBase64Image($ttdInput, 'tanda_tangan');
        }

        $statusTerkini = $simpanDraf ? 'draf' : 'diajukan';

        $rfc = PermohonanRfc::create([
            'kode_pelacakan'       => PermohonanRfc::generateKodePelacakan(),
            'pengguna_id'          => $user->id,
            'opd_id'               => $user->opd_id,
            'kategori_id'          => $validated['kategori_id'] ?? null,
            'nama_pemohon'         => $user->nama_lengkap ?: $user->name,
            'nip'                  => $user->nip,
            'unit_kerja'           => $validated['unit_kerja'] ?? '',
            'nomor_surat'          => $validated['nomor_surat'] ?? null,
            'nomor_kontak'         => $validated['nomor_kontak'] ?? $user->nomor_kontak ?? '',
            'email_dinas'          => $validated['email_dinas'] ?? $user->email,
            'tanggal_permohonan'   => $validated['tanggal_permohonan'] ?? now()->toDateString(),
            'jenis_perubahan'      => $validated['jenis_perubahan'] ?? null,
            'deskripsi_perubahan'  => $validated['deskripsi_perubahan'],
            'latar_belakang'       => $validated['latar_belakang'] ?? null,
            'rincian_perubahan'    => $validated['rincian_perubahan'] ?? null,
            'urgensi'              => $validated['urgensi'] ?? 'sedang',
            'risiko_tidak_dilakukan'=> $validated['risiko_tidak_dilakukan'] ?? null,
            'kriteria_risiko'      => $validated['kriteria_risiko'] ?? null,
            'keterangan_risiko'    => $validated['keterangan_risiko'] ?? null,
            'rencana_pemulihan'    => $validated['rencana_pemulihan'] ?? null,
            'solusi_diharapkan'    => $validated['solusi_diharapkan'] ?? null,
            'risiko_perubahan'     => $validated['risiko_perubahan'] ?? null,
            'alternatif_perubahan' => $validated['alternatif_perubahan'] ?? null,
            'biaya_perubahan'      => $validated['biaya_perubahan'] ?? null,
            'waktu_perubahan'      => $validated['waktu_perubahan'] ?? null,
            'nama_aplikasi'        => $validated['nama_aplikasi'] ?? null,
            'deskripsi_aplikasi'   => $validated['deskripsi_aplikasi'] ?? null,
            'alamat_aplikasi'      => $validated['alamat_aplikasi'] ?? null,
            'alamat_repository'    => $validated['alamat_repository'] ?? null,
            'path_lampiran'        => $pathLampiran ?: null,
            'tanda_tangan_pemohon' => $ttdPath,
            'status_terkini'       => $statusTerkini,
        ]);

        $keterangan = $simpanDraf ? 'Permohonan RFC disimpan sebagai draf' : 'Permohonan RFC diajukan';
        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'pemohon', $statusTerkini, $keterangan);

        // Trigger notifikasi jika diajukan
        if (! $simpanDraf) {
            $this->kirimNotifikasiRfcDiajukan($rfc);
        }

        return response()->json([
            'message'        => $simpanDraf ? 'Draf berhasil disimpan.' : 'Permohonan RFC berhasil diajukan.',
            'kode_pelacakan' => $rfc->kode_pelacakan,
            'id'             => $rfc->id,
        ], 201);
    }

    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
    // AJUKAN DRAF
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

    public function ajukanDraf(int $id)
    {
        $rfc  = PermohonanRfc::where('pengguna_id', auth()->id())->findOrFail($id);
        $user = auth()->user();

        if ($rfc->status_terkini !== 'draf') {
            return response()->json(['message' => 'Hanya RFC berstatus draf yang dapat diajukan.'], 422);
        }

        $rfc->update(['status_terkini' => 'diajukan']);
        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'pemohon', 'diajukan', 'Draf RFC diajukan ke verifikator');

        $this->kirimNotifikasiRfcDiajukan($rfc);

        return response()->json(['message' => 'RFC berhasil diajukan ke verifikator.']);
    }

    // -----------------------------------------------------------------------
    // HAPUS DRAF
    // -----------------------------------------------------------------------

    public function hapusDraf(int $id)
    {
        $rfc = PermohonanRfc::where('pengguna_id', auth()->id())
                             ->where('status_terkini', 'draf')
                             ->findOrFail($id);
        $rfc->delete();

        return response()->json(['message' => 'Draf berhasil dihapus.']);
    }

    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
    // REVISI PEMOHON
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

    public function revisiPemohon(Request $request, int $id)
    {
        $rfc  = PermohonanRfc::where('pengguna_id', auth()->id())->findOrFail($id);
        $user = auth()->user();

        if ($rfc->status_terkini !== 'perlu_revisi') {
            return response()->json(['message' => 'RFC tidak dalam status perlu revisi.'], 422);
        }

        $validated = $request->validate([
            'deskripsi_perubahan' => ['required', 'string'],
            'latar_belakang'      => ['required', 'string'],
            'rincian_perubahan'   => ['required', 'string'],
            'tanda_tangan'        => ['nullable', 'string'],
        ]);

        // Tandai revisi sebelumnya sebagai selesai
        RevisiRfc::where('rfc_id', $rfc->id)
            ->where('status_revisi', 'menunggu_perbaikan')
            ->update(['status_revisi' => 'selesai_diperbaiki']);

        $ttdPath = null;
        if ($request->input('tanda_tangan') && str_starts_with($request->input('tanda_tangan'), 'data:image')) {
            $ttdPath = $this->simpanBase64Image($request->input('tanda_tangan'), 'tanda_tangan');
        }

        $rfc->update(array_merge($validated, [
            'status_terkini'       => 'diajukan',
            'tanda_tangan_pemohon' => $ttdPath ?? $rfc->tanda_tangan_pemohon,
        ]));

        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'pemohon', 'diajukan', 'Revisi formulir dikirim ulang ke verifikator');

        $this->kirimNotifikasiRfcDiajukan($rfc, 'revisi');

        return response()->json(['message' => 'Revisi berhasil dikirim.']);
    }

    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
    // TELAAH VERIFIKATOR
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

    public function telaahVerifikator(Request $request, int $id)
    {
        $rfc  = PermohonanRfc::findOrFail($id);
        $user = auth()->user();

        try {
            $rfc->validasiTransisi(match($request->input('rekomendasi')) {
                'lanjut_pimpinan' => 'menunggu_persetujuan',
                'perlu_revisi'    => 'perlu_revisi',
                'tolak'           => 'ditolak_verifikator',
                default           => throw new \InvalidArgumentException('Rekomendasi tidak valid'),
            });
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $validated = $request->validate([
            'rekomendasi'     => ['required', 'in:lanjut_pimpinan,perlu_revisi,tolak'],
            'catatan_revisi'  => ['nullable', 'string'],
        ]);

        $statusBaru = match ($validated['rekomendasi']) {
            'lanjut_pimpinan' => 'menunggu_persetujuan',
            'perlu_revisi'    => 'perlu_revisi',
            'tolak'           => 'ditolak_verifikator',
        };

        // Simpan ke tabel revisi_rfc
        RevisiRfc::create([
            'rfc_id'          => $rfc->id,
            'verifikator_id'  => $user->id,
            'catatan_revisi'  => $validated['catatan_revisi'] ?? 'Tidak ada catatan.',
            'status_revisi'   => $statusBaru === 'perlu_revisi' ? 'menunggu_perbaikan' : 'selesai_diperbaiki',
        ]);

        $updateData = ['status_terkini' => $statusBaru];

        // Set target SLA jika lanjut ke pimpinan
        if ($statusBaru === 'menunggu_persetujuan') {
            $updateData['target_sla'] = now()->addHours($rfc->hitungSlaDalamJam());
        }

        $rfc->update($updateData);

        $keterangan = match ($statusBaru) {
            'menunggu_persetujuan' => 'Verifikasi selesai, diteruskan ke Pimpinan untuk keputusan',
            'perlu_revisi'         => 'Verifikator meminta perbaikan formulir dari pemohon',
            'ditolak_verifikator'  => 'RFC ditolak pada tahap verifikasi',
        };

        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'verifikator', $statusBaru, $keterangan, $validated['catatan_revisi']);

        // Notifikasi
        $this->kirimNotifikasiPerubahanStatus($rfc, $statusBaru);

        return response()->json(['message' => 'Telaah verifikator berhasil disimpan.']);
    }

    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
    // KEPUTUSAN PIMPINAN
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

    public function keputusanPimpinan(Request $request, int $id)
    {
        $rfc  = PermohonanRfc::findOrFail($id);
        $user = auth()->user();

        $validated = $request->validate([
            'keputusan'        => ['required', 'in:disetujui,ditolak,ditunda'],
            'catatan_keputusan'=> ['nullable', 'string'],
            'teknisi_id'       => ['nullable', 'exists:users,id'],
        ]);

        try {
            $rfc->validasiTransisi($validated['keputusan'] === 'disetujui' ? 'disetujui'
                : ($validated['keputusan'] === 'ditolak' ? 'ditolak_pimpinan' : 'ditunda'));
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $statusBaru = match ($validated['keputusan']) {
            'disetujui' => 'disetujui',
            'ditolak'   => 'ditolak_pimpinan',
            'ditunda'   => 'ditunda',
        };

        // Simpan ke tabel persetujuan_rfc
        PersetujuanRfc::create([
            'rfc_id'           => $rfc->id,
            'pimpinan_id'      => $user->id,
            'keputusan'        => $validated['keputusan'],
            'catatan_keputusan'=> $validated['catatan_keputusan'],
            'diputuskan_pada'  => now(),
        ]);

        $rfc->update(['status_terkini' => $statusBaru]);

        $keterangan = match ($statusBaru) {
            'disetujui'       => 'RFC disetujui oleh Pimpinan',
            'ditolak_pimpinan'=> 'RFC ditolak oleh Pimpinan',
            'ditunda'         => 'RFC ditunda oleh Pimpinan',
        };

        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'pimpinan', $statusBaru, $keterangan, $validated['catatan_keputusan']);

        $this->kirimNotifikasiPerubahanStatus($rfc, $statusBaru);

        // Notifikasi ke teknisi yang di-assign jika ada
        if ($statusBaru === 'disetujui' && $validated['teknisi_id']) {
            $this->kirimNotifikasiKeTeknisi($rfc, $validated['teknisi_id']);
        }

        return response()->json(['message' => 'Keputusan pimpinan berhasil disimpan.']);
    }

    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
    // MULAI EKSEKUSI (TEKNISI)
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

    public function mulaiEksekusi(Request $request, int $id)
    {
        $rfc  = PermohonanRfc::findOrFail($id);
        $user = auth()->user();

        try {
            $rfc->validasiTransisi('sedang_dikerjakan');
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $validated = $request->validate([
            'catatan_eksekusi' => ['nullable', 'string'],
        ]);

        EksekusiRfc::create([
            'rfc_id'           => $rfc->id,
            'teknisi_id'       => $user->id,
            'catatan_eksekusi' => $validated['catatan_eksekusi'],
            'status_eksekusi'  => 'proses_pengerjaan',
            'dimulai_pada'     => now(),
        ]);

        $rfc->update(['status_terkini' => 'sedang_dikerjakan']);
        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'teknisi', 'sedang_dikerjakan', 'Teknisi mulai mengerjakan RFC');

        $this->kirimNotifikasiPerubahanStatus($rfc, 'sedang_dikerjakan');

        return response()->json(['message' => 'Eksekusi RFC dimulai.']);
    }

    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
    // UPLOAD BAST (TEKNISI)
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

    public function unggahBast(Request $request, int $id)
    {
        $rfc  = PermohonanRfc::findOrFail($id);
        $user = auth()->user();

        try {
            $rfc->validasiTransisi('selesai');
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $validated = $request->validate([
            'nomor_dokumen'  => ['required', 'string', 'max:100'],
            'catatan'        => ['nullable', 'string'],
            'tanda_tangan'   => ['nullable', 'string'],
        ]);

        // Upload file BAST
        $pathFile = null;
        if ($request->hasFile('file_bast')) {
            $request->validate(['file_bast' => ['file', 'mimes:pdf,docx,zip', 'max:10240']]);
            $pathFile = $request->file('file_bast')->store('public/bast');
        }

        if (! $pathFile) {
            return response()->json(['message' => 'File BAST wajib diunggah.'], 422);
        }

        // Simpan TTD
        $ttdPath = null;
        if ($request->input('tanda_tangan') && str_starts_with($request->input('tanda_tangan'), 'data:image')) {
            $ttdPath = $this->simpanBase64Image($request->input('tanda_tangan'), 'tanda_tangan');
        }

        DokumenBast::create([
            'rfc_id'               => $rfc->id,
            'nomor_dokumen'        => $validated['nomor_dokumen'],
            'path_file'            => $pathFile,
            'tanda_tangan_teknisi' => $ttdPath,
            'catatan'              => $validated['catatan'],
        ]);

        // Update eksekusi
        EksekusiRfc::where('rfc_id', $rfc->id)
            ->where('teknisi_id', $user->id)
            ->where('status_eksekusi', 'proses_pengerjaan')
            ->update([
                'status_eksekusi' => 'selesai_dikerjakan',
                'selesai_pada'    => now(),
            ]);

        $rfc->update(['status_terkini' => 'selesai']);
        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'teknisi', 'selesai', "BAST diterbitkan: {$validated['nomor_dokumen']}", $validated['catatan']);

        $this->kirimNotifikasiPerubahanStatus($rfc, 'selesai');

        return response()->json(['message' => 'BAST berhasil diunggah. RFC ditutup sebagai selesai.']);
    }

    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
    // DETAIL RFC
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

    public function show(string $kode)
    {
        $rfc = $this->loadRfcLengkap($kode);
        
        // Authorization check
        $this->authorizeRfcAccess($rfc);

        return Inertia::render('RFC/Show', [
            'rfc'       => $this->formatRfcLengkap($rfc),
            'histories' => $rfc->histories->map(fn ($h) => $this->formatHistory($h)),
        ]);
    }

    public function workspace(string $kode)
    {
        $rfc  = $this->loadRfcLengkap($kode);
        $user = auth()->user();
        
        // Authorization check
        $this->authorizeRfcAccess($rfc);

        $namaPeran = $user->peran?->nama_peran ?? $user->role ?? '';

        return Inertia::render('RFC/Workspace', [
            'rfc'       => $this->formatRfcLengkap($rfc),
            'histories' => $rfc->histories->map(fn ($h) => $this->formatHistory($h)),
            'izin'      => [
                'bisa_telaah'    => $namaPeran === 'verifikator' && $rfc->status_terkini === 'diajukan',
                'bisa_keputusan' => $namaPeran === 'pimpinan'    && $rfc->status_terkini === 'menunggu_persetujuan',
                'bisa_eksekusi'  => $namaPeran === 'teknisi'     && $rfc->status_terkini === 'disetujui',
                'bisa_bast'      => $namaPeran === 'teknisi'     && $rfc->status_terkini === 'sedang_dikerjakan',
            ],
        ]);
    }

    public function dokumen(string $kode)
    {
        $rfc = $this->loadRfcLengkap($kode);
        
        // Authorization check
        $this->authorizeRfcAccess($rfc);

        return Inertia::render('RFC/Document', [
            'rfc' => $this->formatRfcLengkap($rfc),
        ]);
    }
    
    /**
     * Check if current user has access to view RFC
     */
    private function authorizeRfcAccess(PermohonanRfc $rfc): void
    {
        $user = auth()->user();
        $userRole = $user->peran?->nama_peran ?? $user->role ?? '';
        
        // Allow if: owner, admin, or role with relevant access
        $isAuthorized = $rfc->pengguna_id === $user->id
            || $userRole === 'admin'
            || $userRole === 'verifikator'
            || $userRole === 'pimpinan'
            || ($userRole === 'teknisi' && in_array($rfc->status_terkini, ['disetujui', 'sedang_dikerjakan', 'selesai']));
        
        if (!$isAuthorized) {
            abort(403, 'Anda tidak memiliki akses ke RFC ini.');
        }
    }

    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
    // API
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

    public function apiIndex(Request $request)
    {
        $query = PermohonanRfc::with('pengguna', 'opd', 'kategori')
            ->orderByDesc('created_at');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('kode_pelacakan', 'like', "%{$s}%")
                                       ->orWhere('nama_pemohon', 'like', "%{$s}%")
                                       ->orWhere('deskripsi_perubahan', 'like', "%{$s}%"));
        }
        if ($request->filled('status'))      $query->where('status_terkini', $request->status);
        if ($request->filled('opd_id'))      $query->where('opd_id', $request->opd_id);
        if ($request->filled('kategori_id')) $query->where('kategori_id', $request->kategori_id);
        if ($request->filled('urgensi'))     $query->where('urgensi', $request->urgensi);

        return response()->json(
            $query->paginate(15)->through(fn ($r) => $this->formatRfcRingkas($r))
        );
    }

    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
    // HELPERS PRIVAT
    // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•

    private function loadRfcLengkap(string $kode): PermohonanRfc
    {
        return PermohonanRfc::with([
            'pengguna',
            'opd',
            'kategori',
            'revisi.verifikator',
            'persetujuan.pimpinan',
            'eksekusi.teknisi',
            'dokumenBast',
            'histories.pengguna',
        ])->where('kode_pelacakan', $kode)->firstOrFail();
    }

    private function formatRfcRingkas(PermohonanRfc $rfc): array
    {
        return [
            'id'              => $rfc->id,
            'kode_pelacakan'  => $rfc->kode_pelacakan,
            'nama_pemohon'    => $rfc->nama_pemohon,
            'opd'             => $rfc->opd?->nama_opd ?? '-',
            'kategori'        => $rfc->kategori?->nama_kategori ?? '-',
            'deskripsi'       => $rfc->deskripsi_perubahan,
            'urgensi'         => $rfc->urgensi,
            'urgensi_label'   => $rfc->urgensi_label,
            'urgensi_warna'   => $rfc->urgensi_warna,
            'status_terkini'  => $rfc->status_terkini,
            'status_label'    => $rfc->status_terkini_label,
            'warna_badge'     => $rfc->warna_badge,
            'target_sla'      => $rfc->target_sla?->toIso8601String(),
            'created_at'      => $rfc->created_at->format('d/m/Y H:i'),
            'created_at_human'=> $rfc->created_at->diffForHumans(),
        ];
    }

    private function formatRfcLengkap(PermohonanRfc $rfc): array
    {
        $revisiTerbaru = $rfc->revisi->first();
        $persetujuan   = $rfc->persetujuan;
        $eksekusiAktif = $rfc->eksekusi->first();
        $bast          = $rfc->dokumenBast;

        return array_merge($this->formatRfcRingkas($rfc), [
            // Seksi 1
            'nip'                    => $rfc->nip,
            'unit_kerja'             => $rfc->unit_kerja,
            'nomor_surat'            => $rfc->nomor_surat,
            'nomor_kontak'           => $rfc->nomor_kontak,
            'email_dinas'            => $rfc->email_dinas,
            'tanggal_permohonan'     => $rfc->tanggal_permohonan?->format('d/m/Y'),
            // Seksi 2
            'jenis_perubahan'        => $rfc->jenis_perubahan ?? [],
            // Seksi 3
            'latar_belakang'         => $rfc->latar_belakang,
            'rincian_perubahan'      => $rfc->rincian_perubahan,
            // Seksi 4
            'risiko_tidak_dilakukan' => $rfc->risiko_tidak_dilakukan,
            'kriteria_risiko'        => $rfc->kriteria_risiko,
            'keterangan_risiko'      => $rfc->keterangan_risiko,
            'rencana_pemulihan'      => $rfc->rencana_pemulihan,
            // Seksi 5
            'solusi_diharapkan'      => $rfc->solusi_diharapkan,
            'risiko_perubahan'       => $rfc->risiko_perubahan,
            'alternatif_perubahan'   => $rfc->alternatif_perubahan,
            'biaya_perubahan'        => $rfc->biaya_perubahan,
            'waktu_perubahan'        => $rfc->waktu_perubahan,
            // Seksi 6
            'nama_aplikasi'          => $rfc->nama_aplikasi,
            'deskripsi_aplikasi'     => $rfc->deskripsi_aplikasi,
            'alamat_aplikasi'        => $rfc->alamat_aplikasi,
            'alamat_repository'      => $rfc->alamat_repository,
            // Lampiran
            'lampiran_urls'          => collect($rfc->path_lampiran ?? [])->map(fn ($p) => Storage::url($p))->values()->all(),
            'tanda_tangan_url'       => $rfc->tanda_tangan_pemohon ? Storage::url($rfc->tanda_tangan_pemohon) : null,
            // Verifikasi
            'revisi'                 => $rfc->revisi->map(fn ($r) => [
                'id'              => $r->id,
                'catatan_revisi'  => $r->catatan_revisi,
                'status_revisi'   => $r->status_revisi,
                'verifikator'     => $r->verifikator?->nama_lengkap ?? $r->verifikator?->name,
                'dibuat_pada'     => $r->dibuat_pada?->format('d/m/Y H:i'),
            ])->all(),
            // Keputusan
            'persetujuan'            => $persetujuan ? [
                'keputusan'         => $persetujuan->keputusan,
                'catatan_keputusan' => $persetujuan->catatan_keputusan,
                'pimpinan'          => $persetujuan->pimpinan?->nama_lengkap ?? $persetujuan->pimpinan?->name,
                'diputuskan_pada'   => $persetujuan->diputuskan_pada?->format('d/m/Y H:i'),
            ] : null,
            // Eksekusi
            'eksekusi'               => $eksekusiAktif ? [
                'catatan_eksekusi' => $eksekusiAktif->catatan_eksekusi,
                'status_eksekusi'  => $eksekusiAktif->status_eksekusi,
                'teknisi'          => $eksekusiAktif->teknisi?->nama_lengkap ?? $eksekusiAktif->teknisi?->name,
                'dimulai_pada'     => $eksekusiAktif->dimulai_pada?->format('d/m/Y H:i'),
                'selesai_pada'     => $eksekusiAktif->selesai_pada?->format('d/m/Y H:i'),
            ] : null,
            // BAST
            'bast'                   => $bast ? [
                'nomor_dokumen'       => $bast->nomor_dokumen,
                'catatan'             => $bast->catatan,
                'path_file_url'       => Storage::url($bast->path_file),
                'dibuat_pada'         => $bast->dibuat_pada?->format('d/m/Y H:i'),
            ] : null,
        ]);
    }

    private function formatHistory($h): array
    {
        return [
            'id'                => $h->id,
            'nama_aktor'        => $h->nama_aktor,
            'peran_aktor'       => $h->peran_aktor,
            'status_sebelumnya' => $h->status_sebelumnya,
            'status_baru'       => $h->status_baru,
            'keterangan'        => $h->keterangan,
            'catatan'           => $h->catatan,
            'dibuat_pada'       => $h->dibuat_pada?->format('d/m/Y H:i'),
        ];
    }

    private function simpanBase64Image(string $base64, string $folder): string
    {
        $data      = explode(',', $base64, 2);
        $imageData = base64_decode($data[1]);
        $ext       = str_contains($data[0], 'png') ? 'png' : 'jpg';
        $filename  = "public/{$folder}/" . uniqid('ttd_', true) . ".{$ext}";
        Storage::put($filename, $imageData);
        return $filename;
    }

    private function statistikBulanIni(): array
    {
        return PermohonanRfc::selectRaw('status_terkini, COUNT(*) as jumlah')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('status_terkini')
            ->pluck('jumlah', 'status_terkini')
            ->toArray();
    }

    private function kirimNotifikasiRfcDiajukan(PermohonanRfc $rfc, string $tipe = 'baru'): void
    {
        try {
            if (class_exists(\App\Services\NotifikasiService::class)) {
                $judul = $tipe === 'revisi' ? 'RFC Diajukan Ulang' : 'RFC Baru Masuk';
                $pesan = "RFC {$rfc->kode_pelacakan} dari {$rfc->nama_pemohon} memerlukan telaah.";

                app(\App\Services\NotifikasiService::class)->kirimKePeran(
                    'verifikator',
                    $judul,
                    $pesan,
                    "/rfc/{$rfc->kode_pelacakan}/workspace"
                );
            }

            if (class_exists(\App\Mail\RfcDiajukan::class)) {
                $verifikators = User::whereHas('peran', fn ($q) => $q->where('nama_peran', 'verifikator'))
                                    ->where('status_aktivasi', 'aktif')
                                    ->get();
                foreach ($verifikators as $v) {
                    \Mail::to($v->email)->queue(new \App\Mail\RfcDiajukan($rfc, $v));
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Gagal kirim notifikasi RFC diajukan: ' . $e->getMessage());
        }
    }

    private function kirimNotifikasiPerubahanStatus(PermohonanRfc $rfc, string $statusBaru): void
    {
        try {
            if (! class_exists(\App\Services\NotifikasiService::class)) return;
            $service = app(\App\Services\NotifikasiService::class);

            $pemohon = $rfc->pengguna;

            switch ($statusBaru) {
                case 'perlu_revisi':
                    if ($pemohon) {
                        $service->kirim($pemohon->id, 'RFC Perlu Revisi', "RFC {$rfc->kode_pelacakan} dikembalikan untuk diperbaiki.", "/rfc/{$rfc->kode_pelacakan}");
                    }
                    break;
                case 'ditolak_verifikator':
                    if ($pemohon) {
                        $service->kirim($pemohon->id, 'RFC Ditolak', "RFC {$rfc->kode_pelacakan} ditolak pada tahap verifikasi.", "/rfc/{$rfc->kode_pelacakan}");
                    }
                    break;
                case 'menunggu_persetujuan':
                    $service->kirimKePeran('pimpinan', 'RFC Siap Diputuskan', "RFC {$rfc->kode_pelacakan} telah diverifikasi dan menunggu keputusan Anda.", "/rfc/{$rfc->kode_pelacakan}/workspace");
                    break;
                case 'disetujui':
                    if ($pemohon) {
                        $service->kirim($pemohon->id, 'RFC Disetujui', "RFC {$rfc->kode_pelacakan} telah disetujui oleh Pimpinan.", "/rfc/{$rfc->kode_pelacakan}");
                    }
                    $service->kirimKePeran('teknisi', 'Tugas Baru Tersedia', "RFC {$rfc->kode_pelacakan} siap untuk dikerjakan.", "/rfc/{$rfc->kode_pelacakan}/workspace");
                    break;
                case 'sedang_dikerjakan':
                    if ($pemohon) {
                        $service->kirim($pemohon->id, 'RFC Sedang Dikerjakan', "RFC {$rfc->kode_pelacakan} sedang dalam proses pengerjaan oleh tim teknisi.", "/rfc/{$rfc->kode_pelacakan}");
                    }
                    break;
                case 'selesai':
                    if ($pemohon) {
                        $service->kirim($pemohon->id, 'RFC Selesai', "RFC {$rfc->kode_pelacakan} telah selesai dikerjakan. BAST telah diterbitkan.", "/rfc/{$rfc->kode_pelacakan}");
                    }
                    break;
            }
        } catch (\Exception $e) {
            \Log::warning('Gagal kirim notifikasi perubahan status: ' . $e->getMessage());
        }
    }

    private function kirimNotifikasiKeTeknisi(PermohonanRfc $rfc, int $teknisiId): void
    {
        try {
            if (class_exists(\App\Services\NotifikasiService::class)) {
                app(\App\Services\NotifikasiService::class)->kirim(
                    $teknisiId,
                    'RFC Ditugaskan ke Anda',
                    "RFC {$rfc->kode_pelacakan} telah ditugaskan kepada Anda untuk segera dikerjakan.",
                    "/rfc/{$rfc->kode_pelacakan}/workspace"
                );
            }
        } catch (\Exception $e) {
            \Log::warning('Gagal kirim notifikasi ke teknisi: ' . $e->getMessage());
        }
    }
}

