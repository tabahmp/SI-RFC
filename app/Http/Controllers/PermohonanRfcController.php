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
use App\Rules\EmailJabarprov;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PermohonanRfcController extends Controller
{
    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â
    // DASHBOARD PER PERAN
    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â

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

        $user->loadMissing('opd');
        $tampilkanModalAktivasi = session('tampilkan_modal_aktivasi', false)
            || (is_null($user->modal_aktivasi_dilihat_pada) && $user->status_aktivasi === 'aktif')
            || $request->boolean('preview_aktivasi', false);

        return Inertia::render('Dashboard/Pemohon', [
            'rfc'                    => $rfcList,
            'filters'                => $request->only(['status', 'kategori_id', 'urgensi', 'search', 'tanggal_mulai', 'tanggal_akhir']),
            'kategoriList'           => \App\Models\KategoriRfc::aktif()->get(['id', 'nama_kategori']),
            'countDraf'              => PermohonanRfc::where('pengguna_id', $user->id)->where('status_terkini', 'draf')->count(),
            'countAktif'             => PermohonanRfc::where('pengguna_id', $user->id)
                                           ->whereNotIn('status_terkini', ['draf', 'selesai', 'ditolak_verifikator', 'ditolak_pimpinan'])
                                           ->count(),
            'countSelesai'           => PermohonanRfc::where('pengguna_id', $user->id)->where('status_terkini', 'selesai')->count(),
            'initialView'            => $request->query('view', 'ringkasan'),
            'tampilkanModalAktivasi' => $tampilkanModalAktivasi,
            'dataAkunAktivasi'       => [
                'nama'              => $user->nama_lengkap ?: $user->name,
                'email'             => $user->email,
                'opd'               => $user->opd?->nama_opd ?? $user->asal_opd ?? 'Pemerintah Provinsi Jawa Barat',
                'unit_kerja'        => $user->unit_kerja ?? 'Unit Kerja / Bidang Pemohon',
                'diverifikasi_oleh' => $user->diverifikasi_oleh ?? 'Administrator SI-RFC',
                'diverifikasi_pada' => $user->diverifikasi_pada ? $user->diverifikasi_pada->isoFormat('D MMMM Y, HH:mm') . ' WIB' : 'Baru saja',
            ],
        ]);
    }

    /**
     * Tandai modal aktivasi akun telah dilihat oleh pemohon.
     */
    public function tandaiModalAktivasiDilihat(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $user->update(['modal_aktivasi_dilihat_pada' => now()]);
        }
        return response()->json(['success' => true]);
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
            ->with(['pengguna', 'opd', 'kategori', 'revisi.verifikator'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($rfc) {
                $item = $this->formatRfcRingkas($rfc);
                $revisiTerakhir = $rfc->revisi->sortByDesc('id')->first();
                $item['catatan_verifikator'] = $revisiTerakhir?->catatan_revisi;
                $item['nama_verifikator']    = $revisiTerakhir?->verifikator?->nama_lengkap ?? $revisiTerakhir?->verifikator?->name;
                $item['target_sla']          = $rfc->target_sla?->toIso8601String();
                $item['sisa_sla']            = $rfc->target_sla ? now()->diffForHumans($rfc->target_sla, ['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) : null;
                $item['sla_terlewat']        = $rfc->target_sla ? now()->greaterThan($rfc->target_sla) : false;
                return $item;
            });

        $riwayatKeputusan = \App\Models\PersetujuanRfc::with(['rfc.opd', 'rfc.pengguna', 'rfc.kategori'])
            ->orderByDesc('diputuskan_pada')
            ->limit(20)
            ->get()
            ->map(fn ($p) => [
                'id'                    => $p->id,
                'rfc_id'                => $p->rfc_id,
                'kode_pelacakan'        => $p->rfc?->kode_pelacakan,
                'deskripsi'             => $p->rfc?->deskripsi_perubahan,
                'opd'                   => $p->rfc?->opd?->nama_opd,
                'pemohon'               => $p->rfc?->nama_pemohon,
                'urgensi'               => $p->rfc?->urgensi,
                'keputusan'             => $p->keputusan,
                'catatan_keputusan'     => $p->catatan_keputusan,
                'diputuskan_pada'       => $p->diputuskan_pada?->format('d/m/Y H:i'),
                'diputuskan_pada_human' => $p->diputuskan_pada?->diffForHumans(),
                'status_terkini'        => $p->rfc?->status_terkini,
            ]);

        $distribusiKategori = \App\Models\KategoriRfc::withCount('permohonanRfc')
            ->orderByDesc('permohonan_rfc_count')
            ->limit(6)
            ->get(['id', 'nama_kategori', 'permohonan_rfc_count'])
            ->map(fn ($k) => [
                'id'               => $k->id,
                'nama_kategori'    => $k->nama_kategori,
                'permohonan_count' => $k->permohonan_rfc_count,
            ]);

        $ditunda = PermohonanRfc::where('status_terkini', 'ditunda')
            ->with(['pengguna', 'opd', 'kategori', 'revisi.verifikator', 'persetujuan'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($rfc) {
                $item = $this->formatRfcRingkas($rfc);
                $persetujuan = $rfc->persetujuan;
                $item['jadwal_tinjau_ulang'] = $persetujuan?->jadwal_tinjau_ulang?->format('d/m/Y');
                $item['jadwal_tinjau_raw']   = $persetujuan?->jadwal_tinjau_ulang?->toDateString();
                $item['catatan_tunda']       = $persetujuan?->catatan_keputusan;
                $item['sudah_jatuh_tempo']   = $persetujuan?->jadwal_tinjau_ulang ? now()->startOfDay()->greaterThanOrEqualTo($persetujuan->jadwal_tinjau_ulang->startOfDay()) : true;
                return $item;
            });

        return Inertia::render('Dashboard/Pimpinan', [
            'menungguKeputusan'  => $menunggu,
            'countMenunggu'      => $menunggu->count(),
            'tiketDitunda'       => $ditunda,
            'countDitunda'       => $ditunda->count(),
            'riwayatKeputusan'   => $riwayatKeputusan,
            'distribusiKategori' => $distribusiKategori,
            'statistik'          => $this->statistikBulanIni(),
            'initialView'        => $request->query('view', 'ringkasan'),
        ]);
    }

    public function dashboardTeknisi(Request $request)
    {
        $antrian = PermohonanRfc::whereIn('status_terkini', ['disetujui', 'sedang_dikerjakan'])
            ->with(['opd', 'kategori', 'pengguna'])
            ->orderBy('created_at')
            ->get()
            ->map(fn ($rfc) => $this->formatRfcRingkas($rfc));

        $hasilSelesai = PermohonanRfc::where('status_terkini', 'selesai')
            ->with(['opd', 'kategori', 'eksekusi.teknisi', 'dokumenBast', 'pengguna'])
            ->orderByDesc('updated_at')
            ->limit(30)
            ->get()
            ->map(function ($rfc) {
                $eks  = $rfc->eksekusi->first();
                $bast = $rfc->dokumenBast;

                $pathUrl = null;
                if ($bast?->path_file) {
                    $clean   = preg_replace('#^(public/)+#', '', $bast->path_file);
                    $pathUrl = str_starts_with($clean, 'http') ? $clean : asset('storage/' . $clean);
                }

                return array_merge($this->formatRfcRingkas($rfc), [
                    'hasil_pengerjaan'   => $eks?->hasil_pengerjaan,
                    'uraian_pekerjaan'   => $eks?->uraian_pekerjaan,
                    'teknisi'            => $eks?->teknisi?->nama_lengkap ?: $eks?->teknisi?->name,
                    'selesai_pada'       => $eks?->selesai_pada?->format('d/m/Y H:i'),
                    'nomor_dokumen_bast' => $bast?->nomor_dokumen,
                    'path_file_bast'     => $pathUrl,
                    'tanggal_bast'       => $bast?->dibuat_pada?->format('d/m/Y H:i'),
                ]);
            });

        $distribusiKategori = \App\Models\KategoriRfc::aktif()
            ->withCount('permohonanRfc')
            ->get()
            ->map(fn ($k) => [
                'id'               => $k->id,
                'nama_kategori'    => $k->nama_kategori,
                'permohonan_count' => $k->permohonan_rfc_count,
            ]);

        return Inertia::render('Dashboard/Teknisi', [
            'antrian'            => $antrian,
            'hasilSelesai'       => $hasilSelesai,
            'distribusiKategori' => $distribusiKategori,
            'statistik'          => $this->statistikBulanIni(),
            'initialView'        => $request->query('view', 'ringkasan'),
        ]);
    }

    public function index(Request $request)
    {
        // Generic index ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â redirect ke dashboard peran masing-masing
        return redirect()->route('dashboard');
    }

    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â
    // FORM RFC BARU
    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â

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
                'unit_kerja'   => $user->unit_kerja,
                'nomor_kontak' => $user->nomor_kontak,
                'email'        => $user->email,
                'opd_id'       => $user->opd_id,
            ] : null,
        ]);
    }

    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â
    // STORE / SIMPAN RFC
    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â

    /**
     * Show form for editing RFC (draf or perlu_revisi only)
     */
    public function edit(int $id)
    {
        $rfc = PermohonanRfc::with(['revisi.verifikator', 'histories.pengguna'])
            ->where('pengguna_id', auth()->id())
            ->findOrFail($id);
        
        if (!in_array($rfc->status_terkini, ['draf', 'perlu_revisi'])) {
            return redirect()->route('rfc.workspace', $rfc->kode_pelacakan)
                ->with('error', 'RFC dengan status ini tidak dapat diedit.');
        }

        // Ambil catatan revisi verifikator terbaru
        $revisiAktif = $rfc->revisi->where('status_revisi', 'menunggu_perbaikan')->sortByDesc('id')->first()
            ?? $rfc->revisi->sortByDesc('id')->first();

        $catatanRevisiData = $revisiAktif ? [
            'id'              => $revisiAktif->id,
            'catatan'         => $revisiAktif->catatan_revisi,
            'verifikator'     => $revisiAktif->verifikator?->nama_lengkap ?? $revisiAktif->verifikator?->name ?? 'Verifikator Diskominfo Jabar',
            'nip_verifikator' => $revisiAktif->verifikator?->nip ?? '-',
            'tanggal'         => $revisiAktif->created_at?->translatedFormat('d F Y, H:i') ?? $revisiAktif->created_at?->format('d/m/Y H:i'),
            'tanggal_human'   => $revisiAktif->created_at?->diffForHumans(),
            'status_revisi'   => $revisiAktif->status_revisi,
        ] : null;

        $riwayatRevisi = $rfc->revisi->sortByDesc('id')->map(fn ($r) => [
            'id'             => $r->id,
            'catatan'        => $r->catatan_revisi,
            'verifikator'    => $r->verifikator?->nama_lengkap ?? $r->verifikator?->name ?? 'Verifikator Diskominfo Jabar',
            'nip_verifikator'=> $r->verifikator?->nip ?? '-',
            'tanggal'        => $r->created_at?->translatedFormat('d F Y, H:i') ?? $r->created_at?->format('d/m/Y H:i'),
            'status_revisi'  => $r->status_revisi,
        ])->values()->all();
        
        return Inertia::render('RFC/Create', [
            'mode'          => 'edit',
            'catatanRevisi' => $catatanRevisiData,
            'riwayatRevisi' => $riwayatRevisi,
            'rfc' => [
                'id'                     => $rfc->id,
                'kode_pelacakan'         => $rfc->kode_pelacakan,
                'status_terkini'         => $rfc->status_terkini,
                'catatan_verifikator'    => $catatanRevisiData['catatan'] ?? null,
                'nama_verifikator'       => $catatanRevisiData['verifikator'] ?? null,
                'tanggal_revisi'         => $catatanRevisiData['tanggal'] ?? null,
                'catatanRevisi'          => $catatanRevisiData,
                'riwayatRevisi'          => $riwayatRevisi,
                // Seksi 1
                'nama_pemohon'           => $rfc->nama_pemohon,
                'nip'                    => $rfc->nip,
                'unit_kerja'             => $rfc->unit_kerja,
                'opd_id'                 => $rfc->opd_id,
                'nomor_surat'            => $rfc->nomor_surat,
                'nomor_kontak'           => $rfc->nomor_kontak,
                'email_dinas'            => $rfc->email_dinas,
                'tanggal_permohonan'     => $rfc->tanggal_permohonan?->format('Y-m-d'),
                // Seksi 2
                'kategori_id'            => $rfc->kategori_id,
                'jenis_perubahan'        => is_array($rfc->jenis_perubahan) ? $rfc->jenis_perubahan : json_decode($rfc->jenis_perubahan ?? '[]', true),
                // Seksi 3
                'deskripsi_perubahan'    => $rfc->deskripsi_perubahan,
                'latar_belakang'         => $rfc->latar_belakang,
                'rincian_perubahan'      => $rfc->rincian_perubahan,
                // Seksi 4
                'urgensi'                => $rfc->urgensi,
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
                // Seksi 7
                'tanda_tangan'           => $rfc->tanda_tangan_pemohon ? Storage::url($rfc->tanda_tangan_pemohon) : null,
                'nama_penanggung_jawab'  => $rfc->nama_penanggung_jawab,
                'nip_penanggung_jawab'   => $rfc->nip_penanggung_jawab,
                'tanda_tangan_penanggung_jawab' => $rfc->tanda_tangan_penanggung_jawab ? Storage::url($rfc->tanda_tangan_penanggung_jawab) : null,
                // Lampiran existing
                'lampiran_existing'      => collect(
                    is_array($rfc->path_lampiran)
                        ? $rfc->path_lampiran
                        : (json_decode($rfc->getRawOriginal('path_lampiran') ?? '[]', true) ?: [])
                )->map(fn ($p) => [
                    'url'  => Storage::url($p),
                    'nama' => preg_replace('/^\d+_[a-f0-9]+_/', '', basename($p)), // hapus prefix timestamp
                    'path' => $p,
                ])->values()->all(),
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
                'unit_kerja'   => auth()->user()->unit_kerja,
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

        if ($rfc->status_terkini === 'perlu_revisi' && $simpanDraf) {
            return response()->json(['message' => 'Permohonan dengan status Perlu Revisi tidak dapat disimpan sebagai draf.'], 422);
        }

        if ($simpanDraf) {
            $validated = $request->validate([
                // Seksi 1
                'kategori_id'            => ['nullable', 'exists:kategori_rfc,id'],
                'unit_kerja'             => ['nullable', 'string', 'max:255'],
                'nomor_surat'            => ['nullable', 'string', 'max:100'],
                'nomor_kontak'           => ['nullable', 'string', 'max:50'],
                'email_dinas'            => ['nullable', 'email', new EmailJabarprov],
                'tanggal_permohonan'     => ['nullable', 'date'],
                // Seksi 2
                'jenis_perubahan'        => ['nullable', 'array'],
                // Seksi 3
                'deskripsi_perubahan'    => ['nullable', 'string'],
                'latar_belakang'         => ['nullable', 'string'],
                'rincian_perubahan'      => ['nullable', 'string'],
                // Seksi 4
                'urgensi'                => ['nullable', 'in:rendah,sedang,tinggi,kritis'],
                'risiko_tidak_dilakukan' => ['nullable', 'string'],
                'kriteria_risiko'        => ['nullable', 'string'],
                'keterangan_risiko'      => ['nullable', 'string'],
                'rencana_pemulihan'      => ['nullable', 'string'],
                // Seksi 5
                'solusi_diharapkan'      => ['nullable', 'string'],
                'risiko_perubahan'       => ['nullable', 'string'],
                'alternatif_perubahan'   => ['nullable', 'string'],
                'biaya_perubahan'        => ['nullable', 'string', 'max:100'],
                'waktu_perubahan'        => ['nullable', 'string', 'max:100'],
                // Seksi 6
                'nama_aplikasi'          => ['nullable', 'string'],
                'deskripsi_aplikasi'     => ['nullable', 'string'],
                'alamat_aplikasi'        => ['nullable', 'string'],
                'alamat_repository'      => ['nullable', 'string'],
                // Seksi 7
                'tanda_tangan'                  => ['nullable', 'string'],
                'nama_penanggung_jawab'         => ['nullable', 'string', 'max:255'],
                'nip_penanggung_jawab'          => ['nullable', 'string', 'max:50'],
                'tanda_tangan_penanggung_jawab' => ['nullable', 'string'],
            ]);
        } else {
            $validated = $request->validate([
                // Seksi 1
                'nama_pemohon'                  => ['nullable', 'string', 'max:255'],
                'nip'                           => ['nullable', 'string', 'max:50'],
                'opd_id'                        => ['nullable', 'exists:opd,id'],
                'kategori_id'                   => ['required', 'exists:kategori_rfc,id'],
                'unit_kerja'                    => ['required', 'string', 'max:255'],
                'nomor_surat'                   => ['nullable', 'string', 'max:100'],
                'nomor_kontak'                  => ['required', 'string', 'max:50'],
                'email_dinas'                   => ['required', 'email'],
                'tanggal_permohonan'            => ['required', 'date'],
                // Seksi 2
                'jenis_perubahan'               => ['required', 'array', 'min:1'],
                // Seksi 3
                'deskripsi_perubahan'           => ['required', 'string'],
                'latar_belakang'                => ['required', 'string'],
                'rincian_perubahan'             => ['required', 'string'],
                // Seksi 4
                'urgensi'                       => ['required', 'in:rendah,sedang,tinggi,kritis'],
                'risiko_tidak_dilakukan'        => ['nullable', 'string'],
                'kriteria_risiko'               => ['nullable', 'string'],
                'keterangan_risiko'             => ['nullable', 'string'],
                'rencana_pemulihan'             => ['nullable', 'string'],
                // Seksi 5
                'solusi_diharapkan'             => ['nullable', 'string'],
                'risiko_perubahan'              => ['nullable', 'string'],
                'alternatif_perubahan'          => ['nullable', 'string'],
                'biaya_perubahan'               => ['nullable', 'string', 'max:100'],
                'waktu_perubahan'               => ['nullable', 'string', 'max:100'],
                // Seksi 6
                'nama_aplikasi'                 => ['nullable', 'string'],
                'deskripsi_aplikasi'            => ['nullable', 'string'],
                'alamat_aplikasi'               => ['nullable', 'string'],
                'alamat_repository'             => ['nullable', 'string'],
                // Seksi 7
                'tanda_tangan'                  => ['nullable', 'string'],
                'nama_penanggung_jawab'         => ['nullable', 'string', 'max:255'],
                'nip_penanggung_jawab'          => ['nullable', 'string', 'max:50'],
                'tanda_tangan_penanggung_jawab' => ['nullable', 'string'],
            ]);

            if (!$rfc->tanda_tangan_pemohon && !str_starts_with((string) $request->input('tanda_tangan', ''), 'data:image')) {
                return response()->json(['message' => 'Tanda tangan digital wajib diisi sebelum permohonan diajukan.'], 422);
            }
        }

        // Lampiran: merge existing yang dipertahankan + file baru
        $lampiranExisting = $request->input('lampiran_existing', []);
        $pathLampiran = is_array($lampiranExisting) ? array_values($lampiranExisting) : [];
        $sisa = max(0, 5 - count($pathLampiran));
        if ($sisa > 0 && $request->hasFile('lampiran')) {
            foreach (array_slice($request->file('lampiran'), 0, $sisa) as $file) {
                $safeName = time().'_'.uniqid().'_'.preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName());
                $file->storeAs('lampiran', $safeName, 'public');
                $pathLampiran[] = 'public/lampiran/'.$safeName;
            }
        }

        // Tanda tangan Pemohon
        $ttdPath = $rfc->tanda_tangan_pemohon;
        if ($request->filled('tanda_tangan') && str_starts_with($request->tanda_tangan, 'data:image')) {
            if ($ttdPath && Storage::exists($ttdPath)) Storage::delete($ttdPath);
            $ttdPath = $this->simpanBase64Image($request->tanda_tangan, 'tanda_tangan');
        }

        // Tanda tangan Penanggung Jawab OPD (Kabid)
        $ttdKabidPath = $rfc->tanda_tangan_penanggung_jawab;
        if ($request->filled('tanda_tangan_penanggung_jawab') && str_starts_with($request->tanda_tangan_penanggung_jawab, 'data:image')) {
            if ($ttdKabidPath && Storage::exists($ttdKabidPath)) Storage::delete($ttdKabidPath);
            $ttdKabidPath = $this->simpanBase64Image($request->tanda_tangan_penanggung_jawab, 'tanda_tangan');
        }

        $statusLama = $rfc->status_terkini;
        $statusBaru = $statusLama;
        if (!$simpanDraf && in_array($statusLama, ['draf', 'perlu_revisi'], true)) {
            $statusBaru = 'diajukan';
        }

        $rfc->update(array_merge($validated, [
            'path_lampiran'                 => $pathLampiran ?: null,
            'tanda_tangan_pemohon'          => $ttdPath,
            'tanda_tangan_penanggung_jawab' => $ttdKabidPath,
            'status_terkini'                => $statusBaru,
        ]));

        // Sinkronkan unit kerja ke profil user jika ada pengisian/perubahan
        if (!empty($validated['unit_kerja']) && $user && $user->unit_kerja !== $validated['unit_kerja']) {
            $user->update(['unit_kerja' => $validated['unit_kerja']]);
        }

        if ($statusLama === 'perlu_revisi' && $statusBaru === 'diajukan') {
            RevisiRfc::where('rfc_id', $rfc->id)
                ->where('status_revisi', 'menunggu_perbaikan')
                ->update(['status_revisi' => 'selesai_diperbaiki']);
        }

        $keterangan = $statusBaru === 'diajukan' && $statusLama !== 'diajukan'
            ? ($statusLama === 'perlu_revisi' ? 'Perbaikan permohonan RFC telah diajukan kembali ke verifikator' : 'Permohonan RFC diajukan ke verifikator')
            : 'RFC diperbarui';
        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'pemohon', $statusBaru, $keterangan, null, $statusLama);

        if ($statusBaru === 'diajukan' && $statusLama !== 'diajukan') {
            $this->kirimNotifikasiRfcDiajukan($rfc, $statusLama === 'perlu_revisi' ? 'revisi' : 'baru');
        }

        return response()->json([
            'message'        => $statusBaru === 'diajukan' && $statusLama !== 'diajukan'
                ? 'Permohonan RFC berhasil diajukan.'
                : 'RFC berhasil diperbarui.',
            'kode_pelacakan' => $rfc->kode_pelacakan,
        ]);
    }

    public function store(Request $request)
    {
        $simpanDraf = $request->boolean('simpan_draf', false);
        $user       = auth()->user();

        if ($simpanDraf) {
            $validated = $request->validate([
                // Seksi 1
                'kategori_id'            => ['nullable', 'exists:kategori_rfc,id'],
                'unit_kerja'             => ['nullable', 'string', 'max:255'],
                'nomor_surat'            => ['nullable', 'string', 'max:100'],
                'nomor_kontak'           => ['nullable', 'string', 'max:50'],
                'email_dinas'            => ['nullable', 'email', new EmailJabarprov],
                'tanggal_permohonan'     => ['nullable', 'date'],
                // Seksi 2
                'jenis_perubahan'        => ['nullable', 'array'],
                // Seksi 3
                'deskripsi_perubahan'    => ['nullable', 'string'],
                'latar_belakang'         => ['nullable', 'string'],
                'rincian_perubahan'      => ['nullable', 'string'],
                // Seksi 4
                'urgensi'                => ['nullable', 'in:rendah,sedang,tinggi,kritis'],
                'risiko_tidak_dilakukan' => ['nullable', 'string'],
                'kriteria_risiko'        => ['nullable', 'string'],
                'keterangan_risiko'      => ['nullable', 'string'],
                'rencana_pemulihan'      => ['nullable', 'string'],
                // Seksi 5
                'solusi_diharapkan'      => ['nullable', 'string'],
                'risiko_perubahan'       => ['nullable', 'string'],
                'alternatif_perubahan'   => ['nullable', 'string'],
                'biaya_perubahan'        => ['nullable', 'string', 'max:100'],
                'waktu_perubahan'        => ['nullable', 'string', 'max:100'],
                // Seksi 6
                'nama_aplikasi'          => ['nullable', 'string'],
                'deskripsi_aplikasi'     => ['nullable', 'string'],
                'alamat_aplikasi'        => ['nullable', 'string'],
                'alamat_repository'      => ['nullable', 'string'],
                // Seksi 7
                'tanda_tangan'                  => ['nullable', 'string'],
                'nama_penanggung_jawab'         => ['nullable', 'string', 'max:255'],
                'nip_penanggung_jawab'          => ['nullable', 'string', 'max:50'],
                'tanda_tangan_penanggung_jawab' => ['nullable', 'string'],
            ]);
        } else {
            $validated = $request->validate([
                'kategori_id'          => ['required', 'exists:kategori_rfc,id'],
                'unit_kerja'           => ['required', 'string', 'max:255'],
                'nomor_surat'          => ['nullable', 'string', 'max:100'],
                'nomor_kontak'         => ['required', 'string', 'max:50'],
                'email_dinas'          => ['required', 'email', new EmailJabarprov],
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
                'tanda_tangan'                  => ['nullable', 'string'],
                'nama_penanggung_jawab'         => ['nullable', 'string', 'max:255'],
                'nip_penanggung_jawab'          => ['nullable', 'string', 'max:50'],
                'tanda_tangan_penanggung_jawab' => ['nullable', 'string'],
            ]);

            // Tanda tangan wajib saat submit
            if (!str_starts_with((string) $request->input('tanda_tangan', ''), 'data:image')) {
                return response()->json(['message' => 'Tanda tangan digital wajib diisi sebelum permohonan diajukan.'], 422);
            }
        }

        // Simpan lampiran
        $pathLampiran = [];
        $allowedMimes = [
            'application/pdf','application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'image/jpeg','image/png','application/zip',
        ];
        foreach (array_slice($request->file('lampiran') ?? [], 0, 5) as $file) {
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return response()->json(['message' => 'Tipe file tidak diizinkan: '.$file->getClientOriginalName()], 422);
            }
            if ($file->getSize() > 10 * 1024 * 1024) {
                return response()->json(['message' => 'File terlalu besar (maks 10MB): '.$file->getClientOriginalName()], 422);
            }
            $safeName = time().'_'.uniqid().'_'.preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName());
            $pathLampiran[] = $file->storeAs('public/lampiran', $safeName);
        }

        // Simpan tanda tangan Pemohon
        $ttdPath = null;
        $ttdInput = $request->input('tanda_tangan');
        if ($ttdInput && str_starts_with($ttdInput, 'data:image')) {
            $ttdPath = $this->simpanBase64Image($ttdInput, 'tanda_tangan');
        }

        // Simpan tanda tangan Penanggung Jawab OPD (Kabid)
        $ttdKabidPath = null;
        $ttdKabidInput = $request->input('tanda_tangan_penanggung_jawab');
        if ($ttdKabidInput && str_starts_with($ttdKabidInput, 'data:image')) {
            $ttdKabidPath = $this->simpanBase64Image($ttdKabidInput, 'tanda_tangan');
        }

        $statusTerkini = $simpanDraf ? 'draf' : 'diajukan';

        $rfc = PermohonanRfc::create([
            'kode_pelacakan'                => PermohonanRfc::generateKodePelacakan(),
            'pengguna_id'                   => $user->id,
            'opd_id'                        => $user->opd_id,
            'kategori_id'                   => $validated['kategori_id'] ?? null,
            'nama_pemohon'                  => $user->nama_lengkap ?: $user->name,
            'nip'                           => $user->nip,
            'unit_kerja'                    => $validated['unit_kerja'] ?? '',
            'nomor_surat'                   => $validated['nomor_surat'] ?? null,
            'nomor_kontak'                  => $validated['nomor_kontak'] ?? $user->nomor_kontak ?? '',
            'email_dinas'                   => $validated['email_dinas'] ?? $user->email,
            'tanggal_permohonan'            => $validated['tanggal_permohonan'] ?? now()->toDateString(),
            'jenis_perubahan'               => $validated['jenis_perubahan'] ?? null,
            'deskripsi_perubahan'           => $validated['deskripsi_perubahan'] ?? null,
            'latar_belakang'                => $validated['latar_belakang'] ?? null,
            'rincian_perubahan'             => $validated['rincian_perubahan'] ?? null,
            'urgensi'                       => $validated['urgensi'] ?? 'sedang',
            'risiko_tidak_dilakukan'        => $validated['risiko_tidak_dilakukan'] ?? null,
            'kriteria_risiko'               => $validated['kriteria_risiko'] ?? null,
            'keterangan_risiko'             => $validated['keterangan_risiko'] ?? null,
            'rencana_pemulihan'             => $validated['rencana_pemulihan'] ?? null,
            'solusi_diharapkan'             => $validated['solusi_diharapkan'] ?? null,
            'risiko_perubahan'              => $validated['risiko_perubahan'] ?? null,
            'alternatif_perubahan'          => $validated['alternatif_perubahan'] ?? null,
            'biaya_perubahan'               => $validated['biaya_perubahan'] ?? null,
            'waktu_perubahan'               => $validated['waktu_perubahan'] ?? null,
            'nama_aplikasi'                 => $validated['nama_aplikasi'] ?? null,
            'deskripsi_aplikasi'            => $validated['deskripsi_aplikasi'] ?? null,
            'alamat_aplikasi'               => $validated['alamat_aplikasi'] ?? null,
            'alamat_repository'             => $validated['alamat_repository'] ?? null,
            'path_lampiran'                 => $pathLampiran ?: null,
            'tanda_tangan_pemohon'          => $ttdPath,
            'nama_penanggung_jawab'         => $validated['nama_penanggung_jawab'] ?? null,
            'nip_penanggung_jawab'          => $validated['nip_penanggung_jawab'] ?? null,
            'tanda_tangan_penanggung_jawab' => $ttdKabidPath,
            'status_terkini'                => $statusTerkini,
        ]);

        // Sinkronkan unit kerja ke profil user jika ada pengisian/perubahan
        if (!empty($validated['unit_kerja']) && $user && $user->unit_kerja !== $validated['unit_kerja']) {
            $user->update(['unit_kerja' => $validated['unit_kerja']]);
        }

        $keterangan = $simpanDraf ? 'Permohonan RFC disimpan sebagai draf' : 'Permohonan RFC diajukan';
        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'pemohon', $statusTerkini, $keterangan);

        if (!$simpanDraf) {
            $this->kirimNotifikasiRfcDiajukan($rfc);
        }

        return response()->json([
            'message'        => $simpanDraf ? 'Draf berhasil disimpan.' : 'Permohonan RFC berhasil diajukan.',
            'kode_pelacakan' => $rfc->kode_pelacakan,
            'id'             => $rfc->id,
        ], 201);
    }

    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â
    // AJUKAN DRAF
    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â

    public function ajukanDraf(int $id)
    {
        $rfc  = PermohonanRfc::where('pengguna_id', auth()->id())->findOrFail($id);
        $user = auth()->user();

        if ($rfc->status_terkini !== 'draf') {
            return response()->json(['message' => 'Hanya RFC berstatus draf yang dapat diajukan.'], 422);
        }

        // Cek field wajib sebelum diajukan
        $fieldWajib = ['unit_kerja', 'nomor_kontak', 'email_dinas', 'deskripsi_perubahan', 'latar_belakang', 'rincian_perubahan'];
        $kurang = [];
        foreach ($fieldWajib as $field) {
            if (empty($rfc->$field)) $kurang[] = $field;
        }
        if (!empty($kurang)) {
            return response()->json([
                'message' => 'Lengkapi draf sebelum diajukan: ' . implode(', ', $kurang) . '.',
            ], 422);
        }

        $rfc->update(['status_terkini' => 'diajukan']);
        $rfc->tambahHistory($user, $user->nama_lengkap ?: $user->name, 'pemohon', 'diajukan', 'Draf RFC diajukan ke verifikator', null, 'draf');

        $this->kirimNotifikasiRfcDiajukan($rfc);

        return response()->json([
            'message'        => 'RFC berhasil diajukan ke verifikator.',
            'kode_pelacakan' => $rfc->kode_pelacakan,
        ]);
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

    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â
    // REVISI PEMOHON
    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â

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

    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â
    // TELAAH VERIFIKATOR
    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â

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

    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â
    // KEPUTUSAN PIMPINAN
    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â

    public function keputusanPimpinan(Request $request, int $id)
    {
        $rfc  = PermohonanRfc::findOrFail($id);
        $user = auth()->user();

        $validated = $request->validate([
            'keputusan'           => ['required', 'in:disetujui,ditolak,ditunda'],
            'catatan_keputusan'   => ['required_unless:keputusan,disetujui', 'nullable', 'string'],
            'teknisi_id'          => ['required_if:keputusan,disetujui', 'nullable', 'exists:users,id'],
            'jadwal_tinjau_ulang' => ['required_if:keputusan,ditunda', 'nullable', 'date'],
            'tanda_tangan'        => ['nullable', 'string'],
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

        // Tanda tangan pimpinan jika disertakan
        $ttdPimpinan = null;
        if ($request->filled('tanda_tangan') && str_starts_with($request->tanda_tangan, 'data:image')) {
            $ttdPimpinan = $this->simpanBase64Image($request->tanda_tangan, 'tanda_tangan');
        }

        // Simpan ke tabel persetujuan_rfc
        PersetujuanRfc::create([
            'rfc_id'                => $rfc->id,
            'pimpinan_id'           => $user->id,
            'keputusan'             => $validated['keputusan'],
            'catatan_keputusan'     => $validated['catatan_keputusan'],
            'tanda_tangan_pimpinan' => $ttdPimpinan,
            'jadwal_tinjau_ulang'   => $validated['jadwal_tinjau_ulang'] ?? null,
            'diputuskan_pada'       => now(),
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

    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â
    // MULAI EKSEKUSI (TEKNISI)
    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â

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

    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â
    // UPLOAD BAST (TEKNISI)
    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â

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

        // Upload file BAST (simpan di disk public agar dapat diunduh)
        $pathFile = null;
        if ($request->hasFile('file_bast')) {
            $request->validate(['file_bast' => ['file', 'mimes:pdf,docx,zip', 'max:10240']]);
            $pathFile = $request->file('file_bast')->store('bast', 'public');
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

    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â
    // DETAIL RFC
    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â

    public function show(string $kode)
    {
        $rfc = $this->loadRfcLengkap($kode);
        
        // Authorization check
        $this->authorizeRfcAccess($rfc);

        // Jika status masih draf: tidak boleh membuka halaman detail.
        // Detail hanya dapat dibuka ketika pemohon telah berhasil mengirimkan permohonan RFC.
        if ($rfc->status_terkini === 'draf') {
            if (auth()->id() === $rfc->pengguna_id) {
                return redirect()->route('rfc.edit', $rfc->id)
                    ->with('info', 'Permohonan masih berstatus draf. Silakan lanjutkan pengisian formulir.');
            }
            return redirect()->route('dashboard')
                ->with('error', 'Permohonan RFC ini masih berstatus draf dan belum diajukan.');
        }

        return redirect()->route('rfc.workspace', $rfc->kode_pelacakan);
    }

    public function workspace(string $kode)
    {
        $rfc  = $this->loadRfcLengkap($kode);
        $user = auth()->user();

        // Authorization check
        $this->authorizeRfcAccess($rfc);

        $namaPeran = $user->peran?->nama_peran ?? $user->role ?? '';

        // Jika pemohon membuka workspace untuk RFC draf/perlu_revisi,
        // redirect ke form edit yang sesuai
        if ($namaPeran === 'pemohon') {
            if (in_array($rfc->status_terkini, ['draf', 'perlu_revisi'])) {
                return redirect()->route('rfc.edit', $rfc->id)
                    ->with('info', 'Silakan lanjutkan pengisian formulir RFC.');
            }
        }

        return Inertia::render('RFC/Workspace', [
            'rfc'       => $this->formatRfcLengkap($rfc),
            'histories' => $rfc->histories->map(fn ($h) => $this->formatHistory($h)),
            'izin'      => [
                'bisa_telaah'    => $namaPeran === 'verifikator' && $rfc->status_terkini === 'diajukan',
                'bisa_keputusan' => $namaPeran === 'pimpinan'    && in_array($rfc->status_terkini, ['menunggu_persetujuan', 'ditunda']),
                'bisa_eksekusi'  => $namaPeran === 'teknisi'     && $rfc->status_terkini === 'disetujui',
                'bisa_bast'      => $namaPeran === 'teknisi'     && $rfc->status_terkini === 'sedang_dikerjakan',
            ],
        ]);
    }

    public function dokumen(Request $request, string $kode)
    {
        $rfc = $this->loadRfcLengkap($kode);

        // Authorization check
        $this->authorizeRfcAccess($rfc);

        // Jika status masih draf: tidak boleh membuka dokumen resmi FR-015
        if ($rfc->status_terkini === 'draf') {
            return redirect()->route('rfc.edit', $rfc->id)
                ->with('error', 'Dokumen FR-015 baru dapat diakses setelah permohonan diajukan.');
        }

        // Unduh PDF resmi FR-015 bila ada query ?download=1 atau ?pdf=1
        if ($request->boolean('download') || $request->query('download') == '1' || $request->has('pdf')) {
            return $this->downloadPdfFr015($rfc);
        }

        return Inertia::render('RFC/Document', [
            'rfc' => $this->formatRfcLengkap($rfc),
        ]);
    }

    /**
     * Endpoint terpisah untuk unduh PDF FR-015 resmi.
     */
    public function unduhPdf(Request $request, string $kode)
    {
        $rfc = $this->loadRfcLengkap($kode);
        $this->authorizeRfcAccess($rfc);

        if ($rfc->status_terkini === 'draf') {
            return redirect()->route('rfc.edit', $rfc->id)
                ->with('error', 'Dokumen FR-015 baru dapat diakses setelah permohonan diajukan.');
        }

        return $this->downloadPdfFr015($rfc);
    }

    /**
     * Generate dan kirim unduhan PDF FR-015 resmi via Python + docx2pdf.
     */
    private function downloadPdfFr015(PermohonanRfc $rfc): \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
    {
        $kode     = $rfc->kode_pelacakan;
        $basePath = base_path();

        // Siapkan data JSON untuk generator
        $eksekusi    = $rfc->eksekusi->first();
        $bast        = $rfc->dokumenBast;
        $persetujuan = $rfc->persetujuan;

        // Normalisasi path TTD: strip prefix public/ berulang ke path absolut
        $normalizeTtdPath = function (?string $p) use ($basePath): ?string {
            if (! $p) return null;
            // Jika path mengandung prefiks "public/public/", hapus duplikasi
            $cleaned = preg_replace('#^(public/)+#', 'public/', ltrim($p, '/'));
            // Coba direktori storage/app/public
            $abs = $basePath . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cleaned);
            if (file_exists($abs)) return $abs;
            // Fallback: coba tanpa awalan public/
            $cleaned2 = preg_replace('#^public/#', '', $cleaned);
            $abs2 = $basePath . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cleaned2);
            if (file_exists($abs2)) return $abs2;
            return null;
        };

        $jenis = $rfc->jenis_perubahan ?? [];
        if (is_string($jenis)) {
            $jenis = json_decode($jenis, true) ?: [];
        }

        // Lamp: tampilkan jumlah + nama file lampiran
        $lampiranArr = $rfc->path_lampiran ?? [];
        if (is_string($lampiranArr)) {
            $lampiranArr = json_decode($lampiranArr, true) ?: [];
        }
        $lampiranText = count($lampiranArr) > 0
            ? count($lampiranArr) . ' berkas terlampir'
            : '-';

        $data = [
            'no_rfc'              => $rfc->kode_pelacakan,
            'pemohon_nama'        => $rfc->nama_pemohon,
            'pemohon_nip'         => $rfc->nip ?? '-',
            'unit_kerja'          => $rfc->unit_kerja ?? '-',
            'perangkat_daerah'    => $rfc->opd?->nama_opd ?? '-',
            'no_surat'            => $rfc->nomor_surat ?? '-',
            'kontak'              => $rfc->nomor_kontak ?? '-',
            'email'               => $rfc->email_dinas ?? '-',
            'jenis_perubahan'     => $jenis,
            'sistem_aplikasi'     => $rfc->nama_aplikasi ?? '-',
            'perubahan_diharapkan' => $rfc->deskripsi_perubahan ?? '-',
            'latar_belakang'      => $rfc->latar_belakang ?? '-',
            'rincian'             => $rfc->rincian_perubahan ?? '-',
            'kriteria_risiko'     => $rfc->kriteria_risiko ?? '',
            'keterangan_risiko'   => $rfc->keterangan_risiko ?? '-',
            'rencana_pemulihan'   => $rfc->rencana_pemulihan ?? '-',
            'solusi'              => $rfc->solusi_diharapkan ?? '-',
            'risiko_perubahan'    => $rfc->risiko_perubahan ?? '-',
            'alternatif'          => $rfc->alternatif_perubahan ?? '-',
            'biaya'               => $rfc->biaya_perubahan ?? '-',
            'waktu'               => $rfc->waktu_perubahan ?? '-',
            'lampiran'            => $lampiranText,
            // Tanggal & TTD Pemohon
            'tgl_dibuat'          => $rfc->tanggal_permohonan?->format('d/m/Y') ?? ($rfc->created_at?->format('d/m/Y') ?? '-'),
            'ttd_pemohon_path'    => $normalizeTtdPath($rfc->tanda_tangan_pemohon),
            // Koordinator (verifikator yang menerima)
            'tgl_diterima'        => $rfc->revisi->first()?->created_at?->format('d/m/Y') ?? $rfc->created_at?->format('d/m/Y') ?? '-',
            'ttd_koordinator_path' => null, // Verifikator tidak ada TTD di sistem saat ini
            'koordinator_nama'    => 'Koordinator Agen',
            'koordinator_nip'     => '-',
            // Penanggung Jawab (Kabid OPD)
            'tgl_disetujui'       => $rfc->updated_at?->format('d/m/Y') ?? '-',
            'penanggung_jabatan'  => $rfc->opd?->nama_opd ?? '-',
            'ttd_penanggung_path' => $normalizeTtdPath($rfc->tanda_tangan_penanggung_jawab),
            'penanggung_nama'     => $rfc->nama_penanggung_jawab ?? '-',
            'penanggung_nip'      => $rfc->nip_penanggung_jawab ?? '-',
            // Teknisi/Agen
            'tgl_dilaksanakan'    => $eksekusi?->selesai_pada?->format('d/m/Y') ?? $eksekusi?->dimulai_pada?->format('d/m/Y') ?? '-',
            'status_pelaksanaan'  => $eksekusi?->status_eksekusi === 'selesai' ? '✓ SELESAI DIKERJAKAN' : ($eksekusi?->status_eksekusi ?? ''),
            'bast'                => $bast ? 'BAST: ' . ($bast->nomor_dokumen ?? 'Terlampir') : '',
            'ttd_agen_path'       => $normalizeTtdPath($bast?->tanda_tangan_teknisi),
            'agen_nama'           => $eksekusi?->teknisi?->nama_lengkap ?? $eksekusi?->teknisi?->name ?? '-',
            'agen_nip'            => $eksekusi?->teknisi?->nip ?? '-',
            // Data persetujuan pimpinan (koordinator agen = pimpinan di sini)
            'tgl_diterima'        => $persetujuan?->diputuskan_pada?->format('d/m/Y') ?? $rfc->updated_at?->format('d/m/Y') ?? '-',
            'koordinator_nama'    => $persetujuan?->pimpinan?->nama_lengkap ?? $persetujuan?->pimpinan?->name ?? 'Koordinator Agen',
            'koordinator_nip'     => $persetujuan?->pimpinan?->nip ?? '-',
            'ttd_koordinator_path' => $normalizeTtdPath($persetujuan?->tanda_tangan_pimpinan),
        ];

        // Tulis JSON sementara
        $tmpDir  = storage_path('app/pdf_rfc');
        if (! is_dir($tmpDir)) mkdir($tmpDir, 0755, true);

        $jsonPath = $tmpDir . DIRECTORY_SEPARATOR . $kode . '_data.json';
        $pdfPath  = $tmpDir . DIRECTORY_SEPARATOR . 'FR-015_' . $kode . '.pdf';

        file_put_contents($jsonPath, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        $scriptPath = $basePath . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'generate_fr015_pdf.py';

        // Jalankan generator Python
        $cmd    = escapeshellarg('python') . ' ' . escapeshellarg($scriptPath) . ' ' . escapeshellarg($jsonPath) . ' ' . escapeshellarg($pdfPath);
        $output = [];
        $code   = 0;
        exec($cmd . ' 2>&1', $output, $code);

        // Bersihkan file JSON sementara
        @unlink($jsonPath);

        if ($code !== 0 || ! file_exists($pdfPath)) {
            \Log::error("FR-015 PDF generator gagal untuk {$kode}", ['output' => implode("\n", $output)]);
            return response('Gagal generate PDF FR-015. Silakan hubungi administrator.', 500)
                ->header('Content-Type', 'text/plain');
        }

        $fileName = "FR-015_{$kode}.pdf";

        return response()->download($pdfPath, $fileName, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ])->deleteFileAfterSend(false);
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

    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â
    // API
    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â

    // Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ Teknisi List (untuk dropdown) Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬
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

    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â
    // HELPERS PRIVAT
    // ÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚ÂÃƒÂ¢Ã¢â‚¬Â¢Ã‚Â

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
            'catatan_revisi'  => $rfc->revisi?->sortByDesc('id')->first()?->catatan_revisi,
            'nama_verifikator'=> $rfc->revisi?->sortByDesc('id')->first()?->verifikator?->nama_lengkap ?? $rfc->revisi?->sortByDesc('id')->first()?->verifikator?->name,
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
            // Lampiran & Tanda Tangan
            'lampiran_urls'          => collect($rfc->path_lampiran ?? [])->map(fn ($p) => Storage::url($p))->values()->all(),
            'tanda_tangan_url'       => $rfc->tanda_tangan_pemohon ? Storage::url($rfc->tanda_tangan_pemohon) : null,
            'nama_penanggung_jawab'  => $rfc->nama_penanggung_jawab,
            'nip_penanggung_jawab'   => $rfc->nip_penanggung_jawab,
            'tanda_tangan_penanggung_jawab_url' => $rfc->tanda_tangan_penanggung_jawab ? Storage::url($rfc->tanda_tangan_penanggung_jawab) : null,
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
                'keputusan'                 => $persetujuan->keputusan,
                'catatan_keputusan'         => $persetujuan->catatan_keputusan,
                'pimpinan'                  => $persetujuan->pimpinan?->nama_lengkap ?? $persetujuan->pimpinan?->name,
                'pimpinan_nip'              => $persetujuan->pimpinan?->nip ?? '-',
                'tanda_tangan_pimpinan_url' => $persetujuan->tanda_tangan_pimpinan ? Storage::url($persetujuan->tanda_tangan_pimpinan) : null,
                'diputuskan_pada'           => $persetujuan->diputuskan_pada?->format('d/m/Y H:i'),
                'diputuskan_pada_tanggal'   => $persetujuan->diputuskan_pada?->translatedFormat('d F Y') ?? $persetujuan->diputuskan_pada?->format('d/m/Y'),
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
                'nomor_dokumen'            => $bast->nomor_dokumen,
                'catatan'                  => $bast->catatan,
                'path_file_url'            => $bast->path_file ? (str_starts_with($bast->path_file, 'http') ? $bast->path_file : url('/storage/' . preg_replace('#^(public/)+#', '', $bast->path_file))) : null,
                'nama_teknisi'             => $eksekusiAktif?->teknisi?->nama_lengkap ?? $eksekusiAktif?->teknisi?->name ?? 'Teknisi Diskominfo',
                'nip_teknisi'              => $eksekusiAktif?->teknisi?->nip ?? '-',
                'tanda_tangan_teknisi_url' => $bast->tanda_tangan_teknisi ? Storage::url($bast->tanda_tangan_teknisi) : null,
                'dibuat_pada'              => $bast->dibuat_pada?->format('d/m/Y H:i'),
                'dibuat_pada_tanggal'      => $bast->dibuat_pada?->translatedFormat('d F Y') ?? $bast->dibuat_pada?->format('d/m/Y'),
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
        $namaFile  = uniqid('ttd_', true) . ".{$ext}";

        // Auto-crop ruang kosong transparan di sekitar goresan tanda tangan agar tampil besar & presisi
        if ($ext === 'png' && function_exists('imagecreatefromstring')) {
            $im = @imagecreatefromstring($imageData);
            if ($im) {
                $w = imagesx($im);
                $h = imagesy($im);
                $minX = $w;
                $minY = $h;
                $maxX = 0;
                $maxY = 0;
                $found = false;

                for ($y = 0; $y < $h; $y++) {
                    for ($x = 0; $x < $w; $x++) {
                        $rgba = imagecolorat($im, $x, $y);
                        $alpha = ($rgba >> 24) & 0x7F;
                        if ($alpha < 120) {
                            $found = true;
                            if ($x < $minX) $minX = $x;
                            if ($x > $maxX) $maxX = $x;
                            if ($y < $minY) $minY = $y;
                            if ($y > $maxY) $maxY = $y;
                        }
                    }
                }

                if ($found) {
                    $pad = 8;
                    $cropX = max(0, $minX - $pad);
                    $cropY = max(0, $minY - $pad);
                    $cropW = min($w - $cropX, ($maxX - $minX + 1) + $pad * 2);
                    $cropH = min($h - $cropY, ($maxY - $minY + 1) + $pad * 2);

                    $dst = imagecreatetruecolor($cropW, $cropH);
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                    $trans = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                    imagefill($dst, 0, 0, $trans);
                    imagecopy($dst, $im, 0, 0, $cropX, $cropY, $cropW, $cropH);

                    ob_start();
                    imagepng($dst);
                    $croppedData = ob_get_clean();
                    if ($croppedData) {
                        $imageData = $croppedData;
                    }
                    imagedestroy($dst);
                }
                imagedestroy($im);
            }
        }

        // Simpan di disk 'public' agar bisa diakses via /storage/
        Storage::disk('public')->put("{$folder}/{$namaFile}", $imageData);
        // Kembalikan path dengan prefix 'public/' agar Storage::url() menghasilkan URL yang benar
        return "public/{$folder}/{$namaFile}";
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
                        $service->kirim($pemohon->id, 'RFC Perlu Revisi', "RFC {$rfc->kode_pelacakan} dikembalikan untuk diperbaiki.", "/rfc/{$rfc->kode_pelacakan}/workspace");
                    }
                    break;
                case 'ditolak_verifikator':
                    if ($pemohon) {
                        $service->kirim($pemohon->id, 'RFC Ditolak', "RFC {$rfc->kode_pelacakan} ditolak pada tahap verifikasi.", "/rfc/{$rfc->kode_pelacakan}/workspace");
                    }
                    break;
                case 'menunggu_persetujuan':
                    $service->kirimKePeran('pimpinan', 'RFC Siap Diputuskan', "RFC {$rfc->kode_pelacakan} telah diverifikasi dan menunggu keputusan Anda.", "/rfc/{$rfc->kode_pelacakan}/workspace");
                    break;
                case 'disetujui':
                    if ($pemohon) {
                        $service->kirim($pemohon->id, 'RFC Disetujui', "RFC {$rfc->kode_pelacakan} telah disetujui oleh Pimpinan.", "/rfc/{$rfc->kode_pelacakan}/workspace");
                    }
                    $service->kirimKePeran('teknisi', 'Tugas Baru Tersedia', "RFC {$rfc->kode_pelacakan} siap untuk dikerjakan.", "/rfc/{$rfc->kode_pelacakan}/workspace");
                    break;
                case 'sedang_dikerjakan':
                    if ($pemohon) {
                        $service->kirim($pemohon->id, 'RFC Sedang Dikerjakan', "RFC {$rfc->kode_pelacakan} sedang dalam proses pengerjaan oleh tim teknisi.", "/rfc/{$rfc->kode_pelacakan}/workspace");
                    }
                    break;
                case 'selesai':
                    if ($pemohon) {
                        $service->kirim($pemohon->id, 'RFC Selesai', "RFC {$rfc->kode_pelacakan} telah selesai dikerjakan. BAST telah diterbitkan.", "/rfc/{$rfc->kode_pelacakan}/workspace");
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
