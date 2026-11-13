?php

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
    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â
    // DASHBOARD PER PERAN
    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â

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
        $antrian = PermohonanRfc::whereIn('status_terkini', ['disetujui', 'sedang_dikerjakan'])
            ->with('opd', 'kategori')
            ->orderBy('created_at')
            ->get()
            ->map(fn ($rfc) => $this->formatRfcRingkas($rfc));

        $hasilSelesai = PermohonanRfc::where('status_terkini', 'selesai')
            ->with('opd', 'kategori', 'eksekusi.teknisi')
            ->orderByDesc('updated_at')
            ->limit(30)
            ->get()
            ->map(function ($rfc) {
                $eks = $rfc->eksekusi->first();

                return array_merge($this->formatRfcRingkas($rfc), [
                    'hasil_pengerjaan' => $eks?->hasil_pengerjaan,
                    'uraian_pekerjaan' => $eks?->uraian_pekerjaan,
                    'teknisi'          => $eks?->teknisi?->nama_lengkap ?: $eks?->teknisi?->name,
                    'selesai_pada'     => $eks?->selesai_pada?->format('d/m/Y H:i'),
                ]);
            });

        return Inertia::render('Dashboard/Teknisi', [
            'antrian'      => $antrian,
            'hasilSelesai' => $hasilSelesai,
            'initialView'  => $request->query('view', 'ringkasan'),
        ]);
    }

    public function index(Request $request)
    {
        // Generic index Ã¢â‚¬â€ redirect ke dashboard peran masing-masing
        return redirect()->route('dashboard');
    }

    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â
    // FORM RFC BARU
    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â

    public function create()
    {
        $user = auth()->user();
        return Inertia::render('RFC/Create', [
            'kategoriList' => KategoriRfc::aktif()->orderBy('nama_kategori')->get(['id', 'nama_kategori', 'deskripsi']),
            'opdList'      => \App\Models\Opd::aktif()->orderBy('nama_opd')->get(['id', 'nama_opd']),
            'opdUser'      => $user?->opd ? [
                'id'       => $user->opd_id,
                'nama_opd' => $user->opd->nama_opd,
            ] : null,
            'userProfile'  => $user ? [
                'nama_lengkap' => $user->nama_lengkap ?? $user->name,
                'nip'          => $user->nip,
                'nomor_kontak' => $user->nomor_kontak,
                'email'        => $user->email,
                'opd_id'       => $user->opd_id,
            ] : null,
        ]);
    }

    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â
    // STORE / SIMPAN RFC
    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â

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
                'kode_pelacakan' => $rfc->kode_pelacakan,
                'kategori_id' => $rfc->kategori_id,
                'unit_kerja' => $rfc->unit_kerja,
                'nomor_surat' => $rfc->nomor_surat,
                'nomor_kontak' => $rfc->nomor_kontak,
                'email_dinas' => $rfc->email_dinas,
                'tanggal_permohonan' => $rfc->tanggal_permohonan?->format('Y-m-d'),
                'jenis_perubahan' => is_array($rfc->jenis_perubahan) ? $rfc->jenis_perubahan : json_decode($rfc->jenis_perubahan ?? '[]', true),
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
                'tanda_tangan' => $rfc->tanda_tangan_pemohon ? Storage::url($rfc->tanda_tangan_pemohon) : null,
            ],
            'kategoriList' => KategoriRfc::aktif()->orderBy('nama_kategori')->get(['id', 'nama_kategori', 'deskripsi']),
            'opdList'      => \App\Models\Opd::aktif()->orderBy('nama_opd')->get(['id', 'nama_opd']),
            'opdUser' => auth()->user()?->opd ? [
                'id' => auth()->user()->opd_id,
                'nama_opd' => auth()->user()->opd->nama_opd,
            ] : null,
            'userProfile'  => auth()->user() ? [
                'nama_lengkap' => auth()->user()->nama_lengkap ?? auth()->user()->name,
                'nip'          => auth()->user()->nip,
                'nomor_kontak' => auth()->user()->nomor_kontak,
                'email'        => auth()->user()->email,
                'opd_id'       => auth()->user()->opd_id,
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
        
        $validated = $request->validate($this->aturanIsiRfc($simpanDraf));

        if (! $simpanDraf && ! $rfc->tanda_tangan_pemohon && ! str_starts_with((string) $request->input('tanda_tangan'), 'data:image')) {
            return response()->json(['message' => 'Tanda tangan digital wajib diisi sebelum permohonan diajukan.'], 422);
        }

        $pathLampiran = $rfc->path_lampiran ?? [];
        $sisa = max(0, 5 - count($pathLampiran));
        if ($sisa > 0 && $request->hasFile('lampiran')) {
            $pathLampiran = array_merge($pathLampiran, $this->simpanLampiran($request, $sisa));
        }
        
        // Handle signature
        $ttdPath = $rfc->tanda_tangan_pemohon;
        if ($request->filled('tanda_tangan') && str_starts_with($request->tanda_tangan, 'data:image')) {
            if ($ttdPath && Storage::exists($ttdPath)) Storage::delete($ttdPath);
            $ttdPath = $this->simpanBase64Image($request->tanda_tangan, 'tanda_tangan');
        }
        
        $statusLama = $rfc->status_terkini;
        $statusBaru = $statusLama;
        if (! $simpanDraf && in_array($statusLama, ['draf', 'perlu_revisi'], true)) {
            $statusBaru = 'diajukan';
        }

        $rfc->update(array_merge($this->payloadIsiRfc($validated, $user, $simpanDraf), [
            'path_lampiran'        => $pathLampiran ?: null,
            'tanda_tangan_pemohon' => $ttdPath,
            'status_terkini'       => $statusBaru,
        ]));

        if ($statusLama === 'perlu_revisi' && $statusBaru === 'diajukan') {
            RevisiRfc::where('rfc_id', $rfc->id)
                ->where('status_revisi', 'menunggu_perbaikan')
                ->update(['status_revisi' => 'selesai_diperbaiki']);
        }

        $keterangan = $statusBaru === 'diajukan' && $statusLama !== 'diajukan'
            ? 'Permohonan RFC diajukan ke verifikator'
            : 'RFC diperbarui';
        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'pemohon', $statusBaru, $keterangan, null, $statusLama);

        if ($statusBaru === 'diajukan' && $statusLama !== 'diajukan') {
            $this->kirimNotifikasiRfcDiajukan($rfc, $statusLama === 'perlu_revisi' ? 'revisi' : 'baru');
        }

        return response()->json([
            'message' => $statusBaru === 'diajukan' && $statusLama !== 'diajukan'
                ? 'Permohonan RFC berhasil diajukan.'
                : 'RFC berhasil diperbarui.',
            'kode_pelacakan' => $rfc->kode_pelacakan,
        ]);
    }


    public function store(Request $request)
    {
        $simpanDraf = $request->boolean('simpan_draf', false);
        $user       = auth()->user();

        $validated = $request->validate($this->aturanIsiRfc($simpanDraf));

        if (! $simpanDraf && ! str_starts_with((string) $request->input('tanda_tangan'), 'data:image')) {
            return response()->json(['message' => 'Tanda tangan digital wajib diisi sebelum permohonan diajukan.'], 422);
        }

        $pathLampiran = $this->simpanLampiran($request, 5);

        // Simpan tanda tangan base64
        $ttdPath = null;
        $ttdInput = $request->input('tanda_tangan');
        if ($ttdInput && str_starts_with($ttdInput, 'data:image')) {
            $ttdPath = $this->simpanBase64Image($ttdInput, 'tanda_tangan');
        }

        $statusTerkini = $simpanDraf ? 'draf' : 'diajukan';

        $rfc = PermohonanRfc::create(array_merge($this->payloadIsiRfc($validated, $user, $simpanDraf), [
            'kode_pelacakan'       => PermohonanRfc::generateKodePelacakan(),
            'pengguna_id'          => $user->id,
            'nama_pemohon'         => $user->nama_lengkap ?: $user->name,
            'nip'                  => $user->nip,
            'path_lampiran'        => $pathLampiran ?: null,
            'tanda_tangan_pemohon' => $ttdPath,
            'status_terkini'       => $statusTerkini,
        ]));

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

    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â
    // AJUKAN DRAF
    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â

    public function ajukanDraf(int $id)
    {
        $rfc  = PermohonanRfc::where('pengguna_id', auth()->id())->findOrFail($id);
        $user = auth()->user();

        if ($rfc->status_terkini !== 'draf') {
            return response()->json(['message' => 'Hanya RFC berstatus draf yang dapat diajukan.'], 422);
        }

        $kurang = $this->fieldWajibKosong($rfc);
        if ($kurang !== []) {
            return response()->json([
                'message' => 'Lengkapi draf sebelum diajukan: '.implode(', ', $kurang).'.',
            ], 422);
        }

        $rfc->update(['status_terkini' => 'diajukan']);
        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'pemohon', 'diajukan', 'Draf RFC diajukan ke verifikator', null, 'draf');

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

    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â
    // REVISI PEMOHON
    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â

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

        unset($validated['tanda_tangan']);

        $rfc->update(array_merge($validated, [
            'status_terkini'       => 'diajukan',
            'tanda_tangan_pemohon' => $ttdPath ?? $rfc->tanda_tangan_pemohon,
        ]));

        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'pemohon', 'diajukan', 'Revisi formulir dikirim ulang ke verifikator', null, 'perlu_revisi');

        $this->kirimNotifikasiRfcDiajukan($rfc, 'revisi');

        return response()->json(['message' => 'Revisi berhasil dikirim.']);
    }

    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â
    // TELAAH VERIFIKATOR
    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â

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
            'rekomendasi'    => ['required', 'in:lanjut_pimpinan,perlu_revisi,tolak'],
            'catatan_revisi' => ['required_unless:rekomendasi,lanjut_pimpinan', 'nullable', 'string'],
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

        $statusLama = $rfc->status_terkini;
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

        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'verifikator', $statusBaru, $keterangan, $validated['catatan_revisi'] ?? null, $statusLama);

        // Notifikasi
        $this->kirimNotifikasiPerubahanStatus($rfc, $statusBaru);

        return response()->json(['message' => 'Telaah verifikator berhasil disimpan.']);
    }

    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â
    // KEPUTUSAN PIMPINAN
    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â

    public function keputusanPimpinan(Request $request, int $id)
    {
        $rfc  = PermohonanRfc::findOrFail($id);
        $user = auth()->user();

        $validated = $request->validate([
            'keputusan'           => ['required', 'in:disetujui,ditolak,ditunda'],
            'catatan_keputusan'   => ['required_unless:keputusan,disetujui', 'nullable', 'string'],
            'teknisi_id'          => ['required_if:keputusan,disetujui', 'nullable', 'exists:users,id'],
            'jadwal_tinjau_ulang' => ['required_if:keputusan,ditunda', 'nullable', 'date'],
        ]);

        $statusBaru = match ($validated['keputusan']) {
            'disetujui' => 'disetujui',
            'ditolak'   => 'ditolak_pimpinan',
            'ditunda'   => 'ditunda',
        };

        try {
            $rfc->validasiTransisi($statusBaru);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        // Simpan ke tabel persetujuan_rfc
        PersetujuanRfc::create([
            'rfc_id'              => $rfc->id,
            'pimpinan_id'         => $user->id,
            'keputusan'           => $validated['keputusan'],
            'catatan_keputusan'   => $validated['catatan_keputusan'],
            'jadwal_tinjau_ulang' => $validated['jadwal_tinjau_ulang'] ?? null,
            'diputuskan_pada'     => now(),
        ]);

        $statusLama = $rfc->status_terkini;
        $rfc->update(['status_terkini' => $statusBaru]);

        // Jika disetujui dan ada teknisi, buat eksekusi entry
        if ($statusBaru === 'disetujui' && !empty($validated['teknisi_id'])) {
            EksekusiRfc::updateOrCreate(
                ['rfc_id' => $rfc->id],
                [
                    'teknisi_id'      => $validated['teknisi_id'],
                    'status_eksekusi' => 'menunggu',
                    'dimulai_pada'    => null,
                ]
            );
        }

        $keterangan = match ($statusBaru) {
            'disetujui'        => 'RFC disetujui oleh Pimpinan',
            'ditolak_pimpinan' => 'RFC ditolak oleh Pimpinan',
            'ditunda'          => 'RFC ditunda oleh Pimpinan',
        };

        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'pimpinan', $statusBaru, $keterangan, $validated['catatan_keputusan'] ?? null, $statusLama);

        $this->kirimNotifikasiPerubahanStatus($rfc, $statusBaru);

        return response()->json(['message' => 'Keputusan pimpinan berhasil disimpan.']);
    }

    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â
    // MULAI EKSEKUSI (TEKNISI)
    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â

    public function mulaiEksekusi(Request $request, int $id)
    {
        $rfc  = PermohonanRfc::findOrFail($id);
        $user = auth()->user();

        if ($rfc->status_terkini !== 'disetujui') {
            return response()->json(['message' => 'RFC harus dalam status disetujui untuk memulai eksekusi.'], 422);
        }

        $validated = $request->validate([
            'catatan_eksekusi' => ['nullable', 'string'],
        ]);

        // Update atau buat eksekusi entry
        EksekusiRfc::updateOrCreate(
            ['rfc_id' => $rfc->id, 'teknisi_id' => $user->id],
            [
                'catatan_eksekusi' => $validated['catatan_eksekusi'],
                'status_eksekusi'  => 'proses_pengerjaan',
                'dimulai_pada'     => now(),
            ]
        );

        $rfc->update(['status_terkini' => 'sedang_dikerjakan']);
        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'teknisi', 'sedang_dikerjakan', 'Teknisi mulai mengerjakan RFC');

        $this->kirimNotifikasiPerubahanStatus($rfc, 'sedang_dikerjakan');

        return response()->json(['message' => 'Eksekusi RFC dimulai.']);
    }

    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â
    // UPLOAD BAST (TEKNISI)
    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â

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

    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â
    // DETAIL RFC
    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â

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

    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â
    // API
    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â

    // â”€â”€â”€ Teknisi List (untuk dropdown) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    public function teknisiList(): \Illuminate\Http\JsonResponse
    {
        $teknisi = User::where('status_aktivasi', 'aktif')
            ->whereHas('peran', fn ($q) => $q->where('nama_peran', 'teknisi'))
            ->orWhere('role', 'teknisi')
            ->where('status_aktivasi', 'aktif')
            ->get(['id', 'nama_lengkap', 'name'])
            ->map(fn ($u) => ['id' => $u->id, 'nama' => $u->nama_lengkap ?: $u->name]);

        return response()->json($teknisi);
    }

    public function apiIndex(Request $request)
    {        $query = PermohonanRfc::with('pengguna', 'opd', 'kategori')
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

    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â
    // HELPERS PRIVAT
    // Ã¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢ÂÃ¢â€¢Â

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
            // Field untuk progress bar di dashboard pemohon
            'nama_aplikasi'           => $rfc->nama_aplikasi,
            'deskripsi_perubahan'     => $rfc->deskripsi_perubahan,
            'unit_kerja'              => $rfc->unit_kerja,
            'nomor_kontak'            => $rfc->nomor_kontak,
            'jenis_perubahan'         => $rfc->jenis_perubahan,
            'risiko_tidak_dilakukan'  => $rfc->risiko_tidak_dilakukan,
            'solusi_diharapkan'       => $rfc->solusi_diharapkan,
            'rencana_pemulihan'       => $rfc->rencana_pemulihan,
            'nama_aplikasi'           => $rfc->nama_aplikasi,
            'path_lampiran'           => $rfc->path_lampiran,
            'tanda_tangan_pemohon'    => $rfc->tanda_tangan_pemohon,
            'updated_at'      => $rfc->updated_at?->diffForHumans(),
            // Status
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
            'created_at'        => $h->created_at?->format('d/m/Y H:i') ?? $h->dibuat_pada?->format('d/m/Y H:i'),
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

