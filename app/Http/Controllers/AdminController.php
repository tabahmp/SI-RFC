<?php

namespace App\Http\Controllers;

use App\Models\KategoriRfc;
use App\Models\Opd;
use App\Models\PermohonanRfc;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    // ═══════════════════════════════════════════════════════════════════════
    // DASHBOARD ADMIN
    // ═══════════════════════════════════════════════════════════════════════

    public function dashboard()
    {
        // Statistik RFC per status
        $statistikRfc = PermohonanRfc::selectRaw('status_terkini, COUNT(*) as jumlah')
            ->groupBy('status_terkini')
            ->pluck('jumlah', 'status_terkini')
            ->toArray();

        // Statistik pengguna per peran
        $statistikPengguna = User::with('peran')
            ->get()
            ->groupBy(fn ($u) => $u->peran?->nama_peran ?? $u->role ?? 'lainnya')
            ->map->count()
            ->toArray();

        // RFC terbaru 5 item
        $rfcTerbaru = PermohonanRfc::with(['pengguna', 'opd', 'kategori'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(fn ($rfc) => $this->formatRfcRingkas($rfc));

        return Inertia::render('Admin/Dashboard', [
            'statistikRfc'      => $statistikRfc,
            'statistikPengguna' => $statistikPengguna,
            'rfcTerbaru'        => $rfcTerbaru,
            'totalRfc'          => PermohonanRfc::count(),
            'rfcBulanIni'       => PermohonanRfc::whereMonth('created_at', now()->month)
                                                 ->whereYear('created_at', now()->year)
                                                 ->count(),
            'akunTertunda'      => User::where('status_aktivasi', 'tertunda')->count(),
            'totalPengguna'     => User::count(),
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // KELOLA AKUN PENGGUNA
    // ═══════════════════════════════════════════════════════════════════════

    public function kelolaAkun(Request $request)
    {
        $query = User::with('peran', 'opd')->orderByDesc('created_at');

        if ($request->filled('peran')) {
            $query->whereHas('peran', fn ($q) => $q->where('nama_peran', $request->peran));
        }

        if ($request->filled('status')) {
            $query->where('status_aktivasi', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$s}%")
                                       ->orWhere('nama_lengkap', 'like', "%{$s}%")
                                       ->orWhere('email', 'like', "%{$s}%")
                                       ->orWhere('nip', 'like', "%{$s}%"));
        }

        $pengguna = $query->paginate(20)->through(fn ($u) => $this->formatPengguna($u));

        return Inertia::render('Admin/KelolaAkun', [
            'pengguna'    => $pengguna,
            'filterAktif' => $request->only(['peran', 'status', 'search']),
        ]);
    }

    public function aktivasiAkun(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'status_aktivasi'        => 'aktif',
            'status_registrasi'      => 'terverifikasi',
            'email_diverifikasi_pada'=> now(),
            'diverifikasi_pada'      => now(),
            'diverifikasi_oleh'      => auth()->user()->nama_lengkap ?: auth()->user()->name,
        ]);

        // Kirim notifikasi in-app ke user
        try {
            if (class_exists(\App\Services\NotifikasiService::class)) {
                app(\App\Services\NotifikasiService::class)->kirim(
                    $user->id,
                    'Akun Anda Telah Diaktifkan',
                    'Selamat! Akun SI-RFC Anda telah diaktifkan. Anda sekarang dapat masuk dan mengajukan RFC.',
                    '/login'
                );
            }

            if (class_exists(\App\Mail\AkunDiaktifkan::class)) {
                \Mail::to($user->email)->queue(new \App\Mail\AkunDiaktifkan($user));
            }
        } catch (\Exception $e) {
            \Log::warning('Gagal kirim notifikasi aktivasi: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Akun berhasil diaktifkan.',
            'user'    => $this->formatPengguna($user->fresh('peran', 'opd')),
        ]);
    }

    public function nonaktifkanAkun(int $id)
    {
        $user = User::findOrFail($id);

        // Jangan nonaktifkan diri sendiri
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Tidak dapat menonaktifkan akun sendiri.'], 422);
        }

        $user->update(['status_aktivasi' => 'nonaktif']);

        return response()->json([
            'message' => 'Akun berhasil dinonaktifkan.',
            'user'    => $this->formatPengguna($user->fresh('peran', 'opd')),
        ]);
    }

    public function tolakAkun(Request $request, int $id)
    {
        $request->validate(['catatan' => ['nullable', 'string', 'max:500']]);

        $user = User::findOrFail($id);
        $user->update([
            'status_aktivasi'   => 'nonaktif',
            'status_registrasi' => 'ditolak',
            'catatan_verifikasi'=> $request->catatan,
        ]);

        try {
            if (class_exists(\App\Mail\AkunDitolak::class)) {
                \Mail::to($user->email)->queue(new \App\Mail\AkunDitolak($user, $request->catatan ?? ''));
            }
        } catch (\Exception $e) {
            \Log::warning('Gagal kirim email penolakan: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Akun berhasil ditolak.',
            'user'    => $this->formatPengguna($user->fresh('peran', 'opd')),
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // MASTER OPD
    // ═══════════════════════════════════════════════════════════════════════

    public function masterOpd(Request $request)
    {
        $opd = Opd::orderBy('nama_opd')->paginate(25);

        return Inertia::render('Admin/MasterOpd', [
            'opd' => $opd,
        ]);
    }

    public function simpanOpd(Request $request)
    {
        $validated = $request->validate([
            'kode_opd' => ['required', 'string', 'max:30', 'unique:opd,kode_opd'],
            'nama_opd' => ['required', 'string', 'max:255'],
            'alamat'   => ['nullable', 'string'],
            'telepon'  => ['nullable', 'string', 'max:30'],
        ]);

        $opd = Opd::create(array_merge($validated, ['aktif' => true]));

        return response()->json(['message' => 'OPD berhasil ditambahkan.', 'opd' => $opd]);
    }

    public function perbaruiOpd(Request $request, int $id)
    {
        $opd       = Opd::findOrFail($id);
        $validated = $request->validate([
            'kode_opd' => ['required', 'string', 'max:30', "unique:opd,kode_opd,{$id}"],
            'nama_opd' => ['required', 'string', 'max:255'],
            'alamat'   => ['nullable', 'string'],
            'telepon'  => ['nullable', 'string', 'max:30'],
        ]);

        $opd->update($validated);

        return response()->json(['message' => 'OPD berhasil diperbarui.', 'opd' => $opd]);
    }

    public function toggleAktifOpd(int $id)
    {
        $opd = Opd::findOrFail($id);
        $opd->update(['aktif' => ! $opd->aktif]);

        return response()->json([
            'message' => $opd->aktif ? 'OPD diaktifkan.' : 'OPD dinonaktifkan.',
            'opd'     => $opd,
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // MASTER KATEGORI RFC
    // ═══════════════════════════════════════════════════════════════════════

    public function masterKategori(Request $request)
    {
        $kategori = KategoriRfc::orderBy('nama_kategori')->paginate(25);

        return Inertia::render('Admin/MasterKategori', [
            'kategori' => $kategori,
        ]);
    }

    public function simpanKategori(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori_rfc,nama_kategori'],
            'deskripsi'     => ['nullable', 'string'],
        ]);

        $kategori = KategoriRfc::create(array_merge($validated, ['aktif' => true]));

        return response()->json(['message' => 'Kategori berhasil ditambahkan.', 'kategori' => $kategori]);
    }

    public function perbaruiKategori(Request $request, int $id)
    {
        $kategori  = KategoriRfc::findOrFail($id);
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', "unique:kategori_rfc,nama_kategori,{$id}"],
            'deskripsi'     => ['nullable', 'string'],
            'aktif'         => ['nullable', 'boolean'],
        ]);

        $kategori->update($validated);

        return response()->json(['message' => 'Kategori berhasil diperbarui.', 'kategori' => $kategori]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // API ENDPOINTS
    // ═══════════════════════════════════════════════════════════════════════

    public function apiOpd()
    {
        return response()->json([
            'opd' => Opd::aktif()->orderBy('nama_opd')->get(['id', 'kode_opd', 'nama_opd']),
        ]);
    }

    public function apiKategori()
    {
        return response()->json([
            'kategori' => KategoriRfc::aktif()->orderBy('nama_kategori')->get(['id', 'nama_kategori', 'deskripsi']),
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // HELPERS
    // ═══════════════════════════════════════════════════════════════════════

    private function formatPengguna(User $user): array
    {
        return [
            'id'              => $user->id,
            'nama_lengkap'    => $user->nama_lengkap ?: $user->name,
            'email'           => $user->email,
            'nip'             => $user->nip ?? '-',
            'nomor_kontak'    => $user->nomor_kontak ?? '-',
            'peran'           => $user->peran?->nama_peran ?? $user->role ?? '-',
            'opd'             => $user->opd?->nama_opd ?? $user->asal_opd ?? '-',
            'status_aktivasi' => $user->status_aktivasi_efektif,
            'created_at'      => $user->created_at?->format('d/m/Y H:i'),
            'email_diverifikasi_pada' => $user->email_diverifikasi_pada?->format('d/m/Y H:i'),
        ];
    }

    private function formatRfcRingkas(PermohonanRfc $rfc): array
    {
        return [
            'id'              => $rfc->id,
            'kode_pelacakan'  => $rfc->kode_pelacakan,
            'nama_pemohon'    => $rfc->nama_pemohon,
            'opd'             => $rfc->opd?->nama_opd ?? '-',
            'kategori'        => $rfc->kategori?->nama_kategori ?? '-',
            'status_terkini'  => $rfc->status_terkini,
            'status_label'    => $rfc->status_terkini_label,
            'warna_badge'     => $rfc->warna_badge,
            'urgensi'         => $rfc->urgensi,
            'created_at'      => $rfc->created_at->format('d/m/Y H:i'),
        ];
    }
}
