<?php

namespace App\Http\Controllers;

use App\Models\PermohonanTi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PermohonanTiController extends Controller
{
    // ─── Daftar OPD Jawa Barat ──────────────────────────────────────────────
    private array $opdList = [
        'Dinas Komunikasi dan Informatika (Diskominfo) Prov. Jawa Barat',
        'Badan Perencanaan Pembangunan Daerah (Bappeda) Prov. Jawa Barat',
        'Dinas Pendidikan Prov. Jawa Barat',
        'Dinas Kesehatan Prov. Jawa Barat',
        'Dinas Pekerjaan Umum dan Penataan Ruang (DPUPR) Prov. Jawa Barat',
        'Dinas Perhubungan Prov. Jawa Barat',
        'Dinas Sosial Prov. Jawa Barat',
        'Badan Kepegawaian Daerah (BKD) Prov. Jawa Barat',
        'Badan Pengelolaan Keuangan dan Aset Daerah (BPKAD) Prov. Jawa Barat',
        'Dinas Penanaman Modal dan PTSP Prov. Jawa Barat',
        'Dinas Lingkungan Hidup Prov. Jawa Barat',
        'Dinas Pariwisata dan Kebudayaan Prov. Jawa Barat',
        'Dinas Pertanian dan Ketahanan Pangan Prov. Jawa Barat',
        'Dinas Perindustrian dan Perdagangan Prov. Jawa Barat',
        'Dinas Koperasi dan Usaha Kecil Prov. Jawa Barat',
        'Dinas Ketenagakerjaan dan Transmigrasi Prov. Jawa Barat',
        'Dinas Pemberdayaan Perempuan dan Perlindungan Anak Prov. Jawa Barat',
        'Dinas Perpustakaan dan Kearsipan Prov. Jawa Barat',
        'Badan Kesatuan Bangsa dan Politik (Bakesbangpol) Prov. Jawa Barat',
        'Sekretariat Daerah Prov. Jawa Barat',
        'Lainnya',
    ];

    // ─── SLA Target per Prioritas ────────────────────────────────────────────
    private function getSlaHours(string $prioritas): int
    {
        return match ($prioritas) {
            'P1 - Kritis' => 4,
            'P2 - Tinggi' => 24,
            'P3 - Sedang' => 72,
            'P4 - Rendah' => 120,
            default => 72,
        };
    }

    // ─── Simpan file & kembalikan path ───────────────────────────────────────
    private function saveFile($file, string $folder): string
    {
        return $file->store("public/{$folder}");
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // DATA HELPERS UNTUK DASHBOARD PER PERAN
    // ═══════════════════════════════════════════════════════════════════════════

    private function getFormattedTikets()
    {
        return PermohonanTi::with('histories')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'no_rfc' => $t->no_rfc,
                'pemohon' => $t->pemohon,
                'perangkat_daerah' => $t->perangkat_daerah,
                'unit_kerja' => $t->unit_kerja,
                'nama_aplikasi' => $t->nama_aplikasi ?? 'Sistem Layanan TI',
                'perubahan_diharapkan' => $t->perubahan_diharapkan,
                'jenis_perubahan' => $t->jenis_perubahan,
                'status' => $t->status,
                'status_label' => $t->status_label,
                'status_color' => $t->status_color,
                'prioritas' => $t->prioritas,
                'prioritas_color' => $t->prioritas_color,
                'tanggal_permohonan' => $t->tanggal_permohonan?->format('d/m/Y'),
                'target_deadline' => $t->target_deadline?->format('d/m/Y'),
                'created_at' => $t->created_at->diffForHumans(),
            ]);
    }

    private function getFormattedUsersPemohon()
    {
        return \App\Models\User::orderByDesc('created_at')
            ->get()
            ->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'nip' => $u->nip ?? '-',
                'nomor_kontak' => $u->nomor_kontak ?? '-',
                'asal_opd' => $u->asal_opd ?? '-',
                'role' => $u->role ?? 'pemohon',
                'status_registrasi' => $u->status_registrasi ?? 'menunggu_verifikasi',
                'catatan_verifikasi' => $u->catatan_verifikasi,
                'diverifikasi_pada' => $u->diverifikasi_pada?->format('d/m/Y H:i'),
                'diverifikasi_oleh' => $u->diverifikasi_oleh,
                'created_at' => $u->created_at ? $u->created_at->format('d/m/Y H:i') : '-',
            ]);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // FRONTEND PAGES (Inertia) PER ROLE
    // ═══════════════════════════════════════════════════════════════════════════

    public function dashboardPemohon(Request $request): Response
    {
        return Inertia::render('Dashboard/Pemohon', [
            'tikets' => $this->getFormattedTikets(),
            'initialView' => $request->query('view', 'ringkasan'),
        ]);
    }

    public function permohonanSaya(Request $request): Response
    {
        return Inertia::render('Dashboard/Pemohon', [
            'tikets' => $this->getFormattedTikets(),
            'initialView' => 'permohonan',
        ]);
    }

    public function dashboardVerifikator(): Response
    {
        return Inertia::render('Dashboard/Verifikator', [
            'tikets' => $this->getFormattedTikets(),
            'usersPemohon' => $this->getFormattedUsersPemohon(),
        ]);
    }

    public function dashboardPimpinan(): Response
    {
        return Inertia::render('Dashboard/Pimpinan', [
            'tikets' => $this->getFormattedTikets(),
        ]);
    }

    public function dashboardTeknisi(): Response
    {
        return Inertia::render('Dashboard/Teknisi', [
            'tikets' => $this->getFormattedTikets(),
        ]);
    }

    public function index(Request $request): Response
    {
        $role = $request->query('role');
        if ($role === 'verifikator') {
            return $this->dashboardVerifikator();
        } elseif ($role === 'pimpinan') {
            return $this->dashboardPimpinan();
        } elseif ($role === 'teknisi') {
            return $this->dashboardTeknisi();
        } elseif ($role === 'pemohon') {
            return $this->dashboardPemohon();
        }

        // Jika rute form-perubahan-it tanpa parameter role, render view utama RFC/Index atau default DashboardPemohon
        $tikets = $this->getFormattedTikets();
        $usersPemohon = $this->getFormattedUsersPemohon();

        return Inertia::render('RFC/Index', compact('tikets', 'usersPemohon'));
    }

    public function create(): Response
    {
        return Inertia::render('RFC/Create', ['opdList' => $this->opdList]);
    }

    public function show(string $rfc): Response
    {
        $tiket = PermohonanTi::with('histories')
            ->where('no_rfc', $rfc)
            ->firstOrFail();

        return Inertia::render('RFC/Show', [
            'tiket' => $this->formatTiket($tiket),
            'histories' => $tiket->histories->map(fn ($h) => [
                'id' => $h->id,
                'actor_name' => $h->actor_name,
                'actor_role' => $h->actor_role,
                'actor_role_label' => $h->actor_role_label,
                'status_sebelumnya' => $h->status_sebelumnya,
                'status_baru' => $h->status_baru,
                'keterangan' => $h->keterangan,
                'catatan' => $h->catatan,
                'created_at' => $h->created_at->format('d/m/Y H:i'),
            ]),
        ]);
    }

    public function workspace(string $rfc): Response
    {
        $tiket = PermohonanTi::with('histories')
            ->where('no_rfc', $rfc)
            ->firstOrFail();

        return Inertia::render('RFC/Workspace', [
            'tiket' => $this->formatTiket($tiket),
            'histories' => $tiket->histories->map(fn ($h) => [
                'id' => $h->id,
                'actor_name' => $h->actor_name,
                'actor_role' => $h->actor_role,
                'actor_role_label' => $h->actor_role_label,
                'status_sebelumnya' => $h->status_sebelumnya,
                'status_baru' => $h->status_baru,
                'keterangan' => $h->keterangan,
                'catatan' => $h->catatan,
                'created_at' => $h->created_at->format('d/m/Y H:i'),
            ]),
            'opdList' => $this->opdList,
        ]);
    }

    public function dokumen(string $rfc): Response
    {
        $tiket = PermohonanTi::with('histories')
            ->where('no_rfc', $rfc)
            ->firstOrFail();

        return Inertia::render('RFC/Document', [
            'doc' => [
                'no_rfc' => $tiket->no_rfc,
                'status_label' => $tiket->status_label,
                'tanggal' => $tiket->tanggal_permohonan?->format('d F Y') ?? now()->format('d F Y'),
                'pemohon' => $tiket->pemohon,
                'perangkat_daerah' => $tiket->perangkat_daerah,
                'unit_kerja' => $tiket->unit_kerja ?? 'Perangkat Daerah Jawa Barat',
                'nomor_kontak' => $tiket->nomor_kontak,
                'email_dinas' => $tiket->email_dinas,
                'nama_aplikasi' => $tiket->nama_aplikasi ?? 'Sistem Informasi Layanan TI',
                'jenis_perubahan' => is_array($tiket->jenis_perubahan) ? implode(', ', $tiket->jenis_perubahan) : $tiket->jenis_perubahan,
                'prioritas' => $tiket->prioritas,
                'perubahan_diharapkan' => $tiket->perubahan_diharapkan,
                'latar_belakang' => $tiket->latar_belakang,
                'rincian_perubahan' => $tiket->rincian_perubahan,
                'risiko_tidak_dilakukan' => $tiket->risiko_tidak_dilakukan,
                'solusi_diharapkan' => $tiket->solusi_diharapkan ?? $tiket->catatan_implementasi,
            ]
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // REST API ENDPOINTS
    // ═══════════════════════════════════════════════════════════════════════════

    public function apiIndex(Request $request)
    {
        $query = PermohonanTi::query();
        if ($request->search) {
            $q = $request->search;
            $query->where(fn ($qb) => $qb->where('no_rfc', 'like', "%$q%")
                ->orWhere('pemohon', 'like', "%$q%")
                ->orWhere('perangkat_daerah', 'like', "%$q%"));
        }
        if ($request->status) $query->where('status', $request->status);
        return response()->json($query->orderByDesc('created_at')->paginate(15));
    }

    public function opd()
    {
        return response()->json(['opd' => $this->opdList]);
    }

    public function apiShow(string $rfc)
    {
        $tiket = PermohonanTi::with('histories')->where('no_rfc', $rfc)->firstOrFail();
        return response()->json(['tiket' => $this->formatTiket($tiket), 'histories' => $tiket->histories]);
    }

    // TAHAP 1: Simpan permohonan baru
    public function store(Request $request)
    {
        // Normalisasi alias field dari frontend form
        $pemohon = $request->input('pemohon') ?? $request->input('nama_pemohon');
        $nip = $request->input('nip');
        if ($nip && $pemohon && !str_contains($pemohon, 'NIP')) {
            $pemohonDisplay = "{$pemohon} (NIP. {$nip})";
        } else {
            $pemohonDisplay = $pemohon;
        }

        $perangkatDaerah = $request->input('perangkat_daerah') ?? $request->input('opd');
        $nomorKontak = $request->input('nomor_kontak') ?? $request->input('contact_person') ?? $request->input('nomor_surat');
        $emailDinas = $request->input('email_dinas') ?? $request->input('email');
        $ttdInput = $request->input('tanda_tangan_file') ?? $request->input('tanda_tangan');

        // Merge ke request untuk validasi yang seragam
        $request->merge([
            'pemohon' => $pemohonDisplay,
            'perangkat_daerah' => $perangkatDaerah,
            'nomor_kontak' => $nomorKontak,
            'email_dinas' => $emailDinas,
        ]);

        $validated = $request->validate([
            'pemohon' => 'required|string|max:255',
            'unit_kerja' => 'required|string|max:255',
            'perangkat_daerah' => 'nullable|string|max:255',
            'nomor_kontak' => 'required|string|max:100',
            'email_dinas' => 'required|email|max:255',
            'tanggal_permohonan' => 'required|date',
            'jenis_perubahan' => 'required|array|min:1',
            'perubahan_diharapkan' => 'required|string',
            'latar_belakang' => 'required|string',
            'rincian_perubahan' => 'required|string',
            'risiko_tidak_dilakukan' => 'nullable|string',
            'kriteria_risiko' => 'nullable',
            'keterangan' => 'nullable|string',
            'solusi_diharapkan' => 'nullable|string',
            'risiko_perubahan' => 'nullable|string',
            'alternatif_perubahan' => 'nullable|string',
            'biaya_perubahan' => 'nullable|string',
            'waktu_perubahan' => 'nullable|string',
            'nama_aplikasi' => 'nullable|string',
            'alamat_aplikasi' => 'nullable|string',
            'alamat_repository' => 'nullable|string',
            'tanda_tangan_file' => 'nullable|string',
            'tanda_tangan' => 'nullable|string',
            'dokumen_pendukung_file.*' => 'nullable|file|mimes:pdf,doc,docx,zip,png,jpg,jpeg|max:10240',
            'lampiran.*' => 'nullable|file|mimes:pdf,doc,docx,zip,png,jpg,jpeg|max:10240',
        ]);

        // Simpan file lampiran
        $dokumenPaths = [];
        $files = $request->file('dokumen_pendukung_file') ?? $request->file('lampiran') ?? [];
        if (!empty($files)) {
            foreach ($files as $file) {
                if (count($dokumenPaths) >= 5) break;
                $dokumenPaths[] = $this->saveFile($file, 'documents');
            }
        }

        // Simpan TTD (base64 canvas)
        $ttdPath = null;
        if ($ttdInput && str_starts_with($ttdInput, 'data:image')) {
            $ttdPath = $this->saveBase64Image($ttdInput, 'signatures');
        }

        $userId = auth()->id();
        if (!$userId && $emailDinas) {
            $userByEmail = \App\Models\User::where('email', $emailDinas)->first();
            $userId = $userByEmail?->id;
        }

        $kriteriaRisiko = $request->input('kriteria_risiko');
        if (is_string($kriteriaRisiko)) {
            $kriteriaRisiko = [$kriteriaRisiko];
        }

        $tiket = PermohonanTi::create(array_merge($validated, [
            'user_id' => $userId,
            'no_rfc' => PermohonanTi::generateNoRfc(),
            'kriteria_risiko' => $kriteriaRisiko,
            'dokumen_pendukung_file' => $dokumenPaths ?: null,
            'tanda_tangan_file' => $ttdPath,
            'status' => 'menunggu_verifikasi',
        ]));

        $tiket->addHistory($validated['pemohon'], 'pemohon', 'menunggu_verifikasi', 'Permohonan RFC diajukan', null, $userId);

        return response()->json([
            'message' => 'Permohonan berhasil diajukan.',
            'no_rfc' => $tiket->no_rfc,
            'data' => [
                'id' => $tiket->id,
                'no_rfc' => $tiket->no_rfc,
            ]
        ], 201);
    }

    // TAHAP 1b: Revisi dari pemohon
    public function revisi(Request $request, int $id)
    {
        $tiket = PermohonanTi::findOrFail($id);
        if ($tiket->status !== 'perlu_revisi') {
            return response()->json(['message' => 'Tiket tidak dalam status perlu revisi.'], 422);
        }

        $validated = $request->validate([
            'perubahan_diharapkan' => 'required|string',
            'latar_belakang' => 'required|string',
            'rincian_perubahan' => 'required|string',
            'tanda_tangan_file' => 'nullable|string',
        ]);

        $ttdPath = null;
        if ($request->tanda_tangan_file && str_starts_with($request->tanda_tangan_file, 'data:image')) {
            $ttdPath = $this->saveBase64Image($request->tanda_tangan_file, 'signatures');
        }

        $tiket->update(array_merge($validated, [
            'status' => 'menunggu_verifikasi',
            'catatan_revisi' => null,
            'tanda_tangan_file' => $ttdPath ?? $tiket->tanda_tangan_file,
        ]));

        $tiket->addHistory($tiket->pemohon, 'pemohon', 'menunggu_verifikasi', 'Revisi formulir diajukan ulang');

        return response()->json(['message' => 'Revisi berhasil dikirim.']);
    }

    // TAHAP 2: Verifikasi
    public function verifikasi(Request $request, int $id)
    {
        $tiket = PermohonanTi::findOrFail($id);
        $validated = $request->validate([
            'verifikator_nama' => 'required|string',
            'verifikator_catatan' => 'nullable|string',
            'verifikator_rekomendasi' => 'required|in:lanjut_pimpinan,perlu_revisi,tolak',
            'prioritas' => 'required|in:P1 - Kritis,P2 - Tinggi,P3 - Sedang,P4 - Rendah',
            'catatan_revisi' => 'nullable|string',
            'tanda_tangan_file' => 'nullable|string',
        ]);

        $newStatus = match ($validated['verifikator_rekomendasi']) {
            'lanjut_pimpinan' => 'menunggu_keputusan_pimpinan',
            'perlu_revisi' => 'perlu_revisi',
            'tolak' => 'ditolak',
        };

        $ttdPath = null;
        if ($request->tanda_tangan_file && str_starts_with($request->tanda_tangan_file, 'data:image')) {
            $ttdPath = $this->saveBase64Image($request->tanda_tangan_file, 'signatures');
        }

        $slaHours = $this->getSlaHours($validated['prioritas']);

        $tiket->update([
            'verifikator_nama' => $validated['verifikator_nama'],
            'verifikator_catatan' => $validated['verifikator_catatan'],
            'verifikator_rekomendasi' => $validated['verifikator_rekomendasi'],
            'verifikator_tanggal' => now(),
            'verifikator_ttd' => $ttdPath,
            'prioritas' => $validated['prioritas'],
            'catatan_revisi' => $validated['catatan_revisi'],
            'target_sla' => now()->addHours($slaHours),
            'status' => $newStatus,
        ]);

        $keterangan = match ($newStatus) {
            'menunggu_keputusan_pimpinan' => 'Verifikasi selesai, diteruskan ke Pimpinan',
            'perlu_revisi' => 'Verifikator meminta revisi formulir',
            'ditolak' => 'Permohonan ditolak pada tahap verifikasi',
        };

        $tiket->addHistory($validated['verifikator_nama'], 'verifikator', $newStatus, $keterangan, $validated['verifikator_catatan']);

        return response()->json(['message' => 'Verifikasi berhasil disimpan.']);
    }

    // TAHAP 3: Keputusan Pimpinan
    public function keputusanPimpinan(Request $request, int $id)
    {
        $tiket = PermohonanTi::findOrFail($id);
        $validated = $request->validate([
            'pimpinan_nama' => 'required|string',
            'pimpinan_keputusan' => 'required|in:disetujui,ditolak,ditunda',
            'pimpinan_catatan' => 'nullable|string',
            'assigned_to' => 'nullable|string',
            'target_deadline' => 'nullable|date',
            'tanda_tangan_file' => 'nullable|string',
        ]);

        $ttdPath = null;
        if ($request->tanda_tangan_file && str_starts_with($request->tanda_tangan_file, 'data:image')) {
            $ttdPath = $this->saveBase64Image($request->tanda_tangan_file, 'signatures');
        }

        $tiket->update([
            'pimpinan_nama' => $validated['pimpinan_nama'],
            'pimpinan_keputusan' => $validated['pimpinan_keputusan'],
            'pimpinan_catatan' => $validated['pimpinan_catatan'],
            'pimpinan_tanggal' => now(),
            'pimpinan_ttd' => $ttdPath,
            'assigned_to' => $validated['assigned_to'],
            'target_deadline' => $validated['target_deadline'],
            'status' => $validated['pimpinan_keputusan'],
        ]);

        $keterangan = match ($validated['pimpinan_keputusan']) {
            'disetujui' => 'Disetujui Pimpinan' . ($validated['assigned_to'] ? ", disposisi ke {$validated['assigned_to']}" : ''),
            'ditolak' => 'Ditolak oleh Pimpinan',
            'ditunda' => 'Ditunda oleh Pimpinan',
        };

        $tiket->addHistory($validated['pimpinan_nama'], 'approver', $validated['pimpinan_keputusan'], $keterangan, $validated['pimpinan_catatan']);

        return response()->json(['message' => 'Keputusan pimpinan berhasil disimpan.']);
    }

    // TAHAP 4: Telaah Teknis
    public function telaahTeknis(Request $request, int $id)
    {
        $tiket = PermohonanTi::findOrFail($id);
        $validated = $request->validate([
            'teknisi_nama' => 'required|string',
            'telaah_teknis' => 'required|string',
            'catatan_implementasi' => 'nullable|string',
            'tautan_perubahan' => 'nullable|string|url',
            'tgl_mulai_pengerjaan' => 'nullable|date',
            'tgl_selesai_pengerjaan' => 'nullable|date',
            'progress_status' => 'required|in:dalam_pengerjaan,menunggu_bast',
        ]);

        $tiket->update([
            'teknisi_nama' => $validated['teknisi_nama'],
            'telaah_teknis' => $validated['telaah_teknis'],
            'catatan_implementasi' => $validated['catatan_implementasi'],
            'tautan_perubahan' => $validated['tautan_perubahan'],
            'tgl_mulai_pengerjaan' => $validated['tgl_mulai_pengerjaan'],
            'tgl_selesai_pengerjaan' => $validated['tgl_selesai_pengerjaan'],
            'status' => $validated['progress_status'],
        ]);

        $keterangan = $validated['progress_status'] === 'menunggu_bast'
            ? 'Pengerjaan selesai, menunggu BAST'
            : 'Telaah teknis & implementasi sedang berjalan';

        $tiket->addHistory($validated['teknisi_nama'], 'teknisi', $validated['progress_status'], $keterangan, $validated['catatan_implementasi']);

        return response()->json(['message' => 'Telaah teknis berhasil disimpan.']);
    }

    // TAHAP 5: BAST
    public function bast(Request $request, int $id)
    {
        $tiket = PermohonanTi::findOrFail($id);
        $validated = $request->validate([
            'agen_nama' => 'required|string',
            'nomor_bast' => 'required|string|max:100',
            'tanggal_bast' => 'required|date',
            'catatan_bast' => 'nullable|string',
            'tanda_tangan_file' => 'nullable|string',
        ]);

        $fileBastPath = null;
        if ($request->hasFile('file_bast')) {
            $fileBastPath = $this->saveFile($request->file('file_bast'), 'bast');
        }

        $ttdPath = null;
        if ($request->tanda_tangan_file && str_starts_with($request->tanda_tangan_file, 'data:image')) {
            $ttdPath = $this->saveBase64Image($request->tanda_tangan_file, 'signatures');
        }

        $tiket->update([
            'agen_nama' => $validated['agen_nama'],
            'nomor_bast' => $validated['nomor_bast'],
            'tanggal_bast' => $validated['tanggal_bast'],
            'catatan_bast' => $validated['catatan_bast'],
            'agen_ttd' => $ttdPath,
            'file_bast' => $fileBastPath,
            'status' => 'selesai',
        ]);

        $tiket->addHistory($validated['agen_nama'], 'agen', 'selesai', "BAST diterbitkan: {$validated['nomor_bast']}", $validated['catatan_bast']);

        return response()->json(['message' => 'BAST berhasil diterbitkan. Tiket ditutup.']);
    }

    public function updateStatus(Request $request, int $id)
    {
        $tiket = PermohonanTi::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);
        $tiket->update(['status' => $validated['status']]);
        $tiket->addHistory('Administrator', 'admin', $validated['status'], $validated['keterangan'] ?? 'Status diperbarui oleh Admin');
        return response()->json(['message' => 'Status diperbarui.']);
    }

    public function assign(Request $request, int $id)
    {
        $tiket = PermohonanTi::findOrFail($id);
        $validated = $request->validate(['assigned_to' => 'required|string']);
        $tiket->update(['assigned_to' => $validated['assigned_to']]);
        $tiket->addHistory('Administrator', 'admin', $tiket->status, "Disposisi diperbarui ke: {$validated['assigned_to']}");
        return response()->json(['message' => 'Penugasan diperbarui.']);
    }

    public function verifyUser(Request $request, int $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:terverifikasi,ditolak',
            'catatan' => 'nullable|string',
            'diverifikasi_oleh' => 'nullable|string',
        ]);

        $user->update([
            'status_registrasi' => $validated['status'],
            'catatan_verifikasi' => $validated['catatan'] ?? null,
            'diverifikasi_pada' => now(),
            'diverifikasi_oleh' => $validated['diverifikasi_oleh'] ?? 'Raka Pratama',
        ]);

        return response()->json([
            'message' => 'Status registrasi pemohon berhasil diperbarui.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'status_registrasi' => $user->status_registrasi,
                'diverifikasi_pada' => $user->diverifikasi_pada?->format('d/m/Y H:i'),
            ],
        ]);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function formatTiket(PermohonanTi $tiket): array
    {
        return [
            'id' => $tiket->id,
            'no_rfc' => $tiket->no_rfc,
            'pemohon' => $tiket->pemohon,
            'unit_kerja' => $tiket->unit_kerja,
            'perangkat_daerah' => $tiket->perangkat_daerah,
            'nomor_kontak' => $tiket->nomor_kontak,
            'email_dinas' => $tiket->email_dinas,
            'tanggal_permohonan' => $tiket->tanggal_permohonan?->format('d/m/Y'),
            'jenis_perubahan' => $tiket->jenis_perubahan,
            'perubahan_diharapkan' => $tiket->perubahan_diharapkan,
            'latar_belakang' => $tiket->latar_belakang,
            'rincian_perubahan' => $tiket->rincian_perubahan,
            'risiko_tidak_dilakukan' => $tiket->risiko_tidak_dilakukan,
            'kriteria_risiko' => $tiket->kriteria_risiko,
            'keterangan' => $tiket->keterangan,
            'solusi_diharapkan' => $tiket->solusi_diharapkan,
            'risiko_perubahan' => $tiket->risiko_perubahan,
            'alternatif_perubahan' => $tiket->alternatif_perubahan,
            'biaya_perubahan' => $tiket->biaya_perubahan,
            'waktu_perubahan' => $tiket->waktu_perubahan,
            'nama_aplikasi' => $tiket->nama_aplikasi,
            'deskripsi_aplikasi' => $tiket->deskripsi_aplikasi,
            'alamat_aplikasi' => $tiket->alamat_aplikasi,
            'alamat_repository' => $tiket->alamat_repository,
            'dokumen_pendukung_file' => collect($tiket->dokumen_pendukung_file ?? [])->map(fn($p) => Storage::url($p))->values(),
            'tanda_tangan_url' => $tiket->tanda_tangan_file ? Storage::url($tiket->tanda_tangan_file) : null,
            'status' => $tiket->status,
            'status_label' => $tiket->status_label,
            'status_color' => $tiket->status_color,
            'prioritas' => $tiket->prioritas,
            'prioritas_color' => $tiket->prioritas_color,
            'target_sla' => $tiket->target_sla?->format('d/m/Y H:i'),
            'catatan_revisi' => $tiket->catatan_revisi,
            'verifikator_nama' => $tiket->verifikator_nama,
            'verifikator_catatan' => $tiket->verifikator_catatan,
            'verifikator_rekomendasi' => $tiket->verifikator_rekomendasi,
            'verifikator_tanggal' => $tiket->verifikator_tanggal?->format('d/m/Y'),
            'verifikator_ttd_url' => $tiket->verifikator_ttd ? Storage::url($tiket->verifikator_ttd) : null,
            'pimpinan_nama' => $tiket->pimpinan_nama,
            'pimpinan_keputusan' => $tiket->pimpinan_keputusan,
            'pimpinan_catatan' => $tiket->pimpinan_catatan,
            'pimpinan_tanggal' => $tiket->pimpinan_tanggal?->format('d/m/Y'),
            'pimpinan_ttd_url' => $tiket->pimpinan_ttd ? Storage::url($tiket->pimpinan_ttd) : null,
            'assigned_to' => $tiket->assigned_to,
            'target_deadline' => $tiket->target_deadline?->format('d/m/Y'),
            'teknisi_nama' => $tiket->teknisi_nama,
            'telaah_teknis' => $tiket->telaah_teknis,
            'catatan_implementasi' => $tiket->catatan_implementasi,
            'tautan_perubahan' => $tiket->tautan_perubahan,
            'tgl_mulai_pengerjaan' => $tiket->tgl_mulai_pengerjaan?->format('d/m/Y'),
            'tgl_selesai_pengerjaan' => $tiket->tgl_selesai_pengerjaan?->format('d/m/Y'),
            'nomor_bast' => $tiket->nomor_bast,
            'tanggal_bast' => $tiket->tanggal_bast?->format('d/m/Y'),
            'file_bast_url' => $tiket->file_bast ? Storage::url($tiket->file_bast) : null,
            'catatan_bast' => $tiket->catatan_bast,
            'agen_nama' => $tiket->agen_nama,
            'agen_ttd_url' => $tiket->agen_ttd ? Storage::url($tiket->agen_ttd) : null,
            'created_at' => $tiket->created_at->format('d/m/Y H:i'),
            'updated_at' => $tiket->updated_at->format('d/m/Y H:i'),
        ];
    }

    private function saveBase64Image(string $base64, string $folder): string
    {
        $data = explode(',', $base64, 2);
        $imageData = base64_decode($data[1]);
        $ext = str_contains($data[0], 'png') ? 'png' : 'jpg';
        $filename = "public/{$folder}/" . uniqid('ttd_', true) . ".{$ext}";
        Storage::put($filename, $imageData);
        return $filename;
    }
}

