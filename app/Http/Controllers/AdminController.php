<?php

namespace App\Http\Controllers;

use App\Models\KategoriRfc;
use App\Models\Opd;
use App\Models\Peran;
use App\Models\PermohonanRfc;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            'daftarPeran' => Peran::orderBy('id')->get(['id', 'nama_peran', 'deskripsi']),
            'daftarOpd'   => Opd::aktif()->orderBy('nama_opd')->get(['id', 'kode_opd', 'nama_opd']),
            'authUserId'  => auth()->id(),
        ]);
    }

    public function simpanAkun(Request $request)
    {
        $request->validate([
            'nama_lengkap'    => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users,email', new \App\Rules\EmailJabarprov],
            'password'        => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[^A-Za-z0-9]/'],
            'peran_id'        => ['required', 'exists:peran,id'],
            'opd_id'          => ['nullable', 'exists:opd,id'],
            'nip'             => ['nullable', 'string', 'max:30'],
            'nomor_kontak'    => ['nullable', 'string', 'max:20'],
            'unit_kerja'      => ['nullable', 'string', 'max:255'],
            'status_aktivasi' => ['required', 'in:aktif,tertunda,nonaktif'],
        ], [
            'nama_lengkap.required'    => 'Nama lengkap wajib diisi.',
            'email.required'           => 'Email dinas wajib diisi.',
            'email.email'              => 'Format email tidak valid.',
            'email.unique'             => 'Email sudah terdaftar.',
            'password.required'        => 'Kata sandi wajib diisi.',
            'password.min'             => 'Kata sandi harus minimal 8 karakter.',
            'password.regex'           => 'Kata sandi harus minimal 8 karakter dan mengandung huruf kapital, angka, serta simbol unik.',
            'peran_id.required'        => 'Peran akun wajib dipilih.',
            'status_aktivasi.required' => 'Status akun wajib dipilih.',
        ]);

        $peran = Peran::find($request->peran_id);
        $opd = $request->opd_id ? Opd::find($request->opd_id) : null;
        $isAktif = $request->status_aktivasi === 'aktif';

        $user = User::create([
            'name'                    => $request->nama_lengkap,
            'nama_lengkap'            => $request->nama_lengkap,
            'email'                   => $request->email,
            'password'                => Hash::make($request->password),
            'peran_id'                => $peran?->id,
            'role'                    => $peran?->nama_peran,
            'opd_id'                  => $opd?->id,
            'asal_opd'                => $opd?->nama_opd,
            'nip'                     => $request->nip,
            'nomor_kontak'            => $request->nomor_kontak,
            'unit_kerja'              => $request->unit_kerja,
            'status_aktivasi'         => $request->status_aktivasi,
            'status_registrasi'       => $isAktif ? 'terverifikasi' : ($request->status_aktivasi === 'tertunda' ? 'menunggu_verifikasi' : 'ditolak'),
            'email_diverifikasi_pada' => $isAktif ? now() : null,
            'diverifikasi_pada'       => $isAktif ? now() : null,
            'diverifikasi_oleh'       => $isAktif ? (auth()->user()->nama_lengkap ?: auth()->user()->name) : null,
        ]);

        return response()->json([
            'message' => 'Akun pengguna berhasil ditambahkan.',
            'user'    => $this->formatPengguna($user->fresh('peran', 'opd')),
        ]);
    }

    public function perbaruiAkun(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_lengkap'    => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id, new \App\Rules\EmailJabarprov],
            'password'        => ['nullable', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[^A-Za-z0-9]/'],
            'peran_id'        => ['required', 'exists:peran,id'],
            'opd_id'          => ['nullable', 'exists:opd,id'],
            'nip'             => ['nullable', 'string', 'max:30'],
            'nomor_kontak'    => ['nullable', 'string', 'max:20'],
            'unit_kerja'      => ['nullable', 'string', 'max:255'],
            'status_aktivasi' => ['required', 'in:aktif,tertunda,nonaktif'],
        ], [
            'nama_lengkap.required'    => 'Nama lengkap wajib diisi.',
            'email.required'           => 'Email dinas wajib diisi.',
            'email.email'              => 'Format email tidak valid.',
            'email.unique'             => 'Email sudah terdaftar oleh pengguna lain.',
            'password.min'             => 'Kata sandi harus minimal 8 karakter.',
            'password.regex'           => 'Kata sandi harus minimal 8 karakter dan mengandung huruf kapital, angka, serta simbol unik.',
            'peran_id.required'        => 'Peran akun wajib dipilih.',
            'status_aktivasi.required' => 'Status akun wajib dipilih.',
        ]);

        // Proteksi akun admin yang sedang login
        if ($user->id === auth()->id()) {
            if ($request->status_aktivasi !== 'aktif') {
                return response()->json(['message' => 'Tidak dapat menonaktifkan akun sendiri.'], 422);
            }
            $adminPeran = Peran::where('nama_peran', 'admin')->first();
            if ($adminPeran && (int) $request->peran_id !== $adminPeran->id) {
                return response()->json(['message' => 'Tidak dapat mengubah peran akun admin yang sedang aktif.'], 422);
            }
        }

        $peran = Peran::find($request->peran_id);
        $opd = $request->opd_id ? Opd::find($request->opd_id) : null;
        $isAktif = $request->status_aktivasi === 'aktif';

        $updateData = [
            'name'              => $request->nama_lengkap,
            'nama_lengkap'      => $request->nama_lengkap,
            'email'             => $request->email,
            'peran_id'          => $peran?->id,
            'role'              => $peran?->nama_peran,
            'opd_id'            => $opd?->id,
            'asal_opd'          => $opd?->nama_opd,
            'nip'               => $request->nip,
            'nomor_kontak'      => $request->nomor_kontak,
            'unit_kerja'        => $request->unit_kerja,
            'status_aktivasi'   => $request->status_aktivasi,
            'status_registrasi' => $isAktif ? 'terverifikasi' : ($request->status_aktivasi === 'tertunda' ? 'menunggu_verifikasi' : 'ditolak'),
        ];

        if ($isAktif && ! $user->email_diverifikasi_pada) {
            $updateData['email_diverifikasi_pada'] = now();
            $updateData['diverifikasi_pada'] = now();
            $updateData['diverifikasi_oleh'] = auth()->user()->nama_lengkap ?: auth()->user()->name;
        }

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'message' => 'Data akun berhasil diperbarui.',
            'user'    => $this->formatPengguna($user->fresh('peran', 'opd')),
        ]);
    }

    public function hapusAkun(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Tidak dapat menghapus akun Anda sendiri.'], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'Akun pengguna berhasil dihapus.',
        ]);
    }

    public function aktivasiAkun(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'status_aktivasi'             => 'aktif',
            'status_registrasi'           => 'terverifikasi',
            'email_diverifikasi_pada'     => now(),
            'diverifikasi_pada'           => now(),
            'diverifikasi_oleh'           => auth()->user()->nama_lengkap ?: auth()->user()->name,
            'modal_aktivasi_dilihat_pada' => null,
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
        $query = Opd::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_opd', 'like', "%{$search}%")
                  ->orWhere('kode_opd', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('aktif', true);
            } elseif ($request->status === 'nonaktif') {
                $query->where('aktif', false);
            }
        }

        $opd = $query->orderBy('nama_opd')->paginate(15)->withQueryString();

        $statistik = [
            'total'    => Opd::count(),
            'aktif'    => Opd::where('aktif', true)->count(),
            'nonaktif' => Opd::where('aktif', false)->count(),
        ];

        return Inertia::render('Admin/MasterOpd', [
            'opd'         => $opd,
            'filterAktif' => $request->only(['search', 'status']),
            'statistik'   => $statistik,
        ]);
    }

    public function simpanOpd(Request $request)
    {
        $validated = $request->validate([
            'kode_opd' => ['required', 'string', 'max:30', 'unique:opd,kode_opd'],
            'nama_opd' => ['required', 'string', 'max:255'],
            'alamat'   => ['nullable', 'string'],
            'telepon'  => ['nullable', 'string', 'max:30'],
            'aktif'    => ['nullable', 'boolean'],
        ]);

        $opd = Opd::create([
            'kode_opd' => strtoupper(trim($validated['kode_opd'])),
            'nama_opd' => trim($validated['nama_opd']),
            'alamat'   => $validated['alamat'] ?? null,
            'telepon'  => $validated['telepon'] ?? null,
            'aktif'    => $request->has('aktif') ? (bool) $request->aktif : true,
        ]);

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
            'aktif'    => ['nullable', 'boolean'],
        ]);

        $updateData = [
            'kode_opd' => strtoupper(trim($validated['kode_opd'])),
            'nama_opd' => trim($validated['nama_opd']),
            'alamat'   => $validated['alamat'] ?? null,
            'telepon'  => $validated['telepon'] ?? null,
        ];

        if ($request->has('aktif')) {
            $updateData['aktif'] = (bool) $request->aktif;
        }

        $opd->update($updateData);

        return response()->json(['message' => 'Data OPD berhasil diperbarui.', 'opd' => $opd]);
    }

    public function toggleAktifOpd(int $id)
    {
        $opd = Opd::findOrFail($id);
        $opd->update(['aktif' => ! $opd->aktif]);

        return response()->json([
            'message' => $opd->aktif ? 'OPD berhasil diaktifkan.' : 'OPD berhasil dinonaktifkan.',
            'opd'     => $opd,
        ]);
    }

    public function hapusOpd(int $id)
    {
        $opd = Opd::findOrFail($id);

        $terkaitUser = User::where('opd_id', $id)->exists();
        $terkaitRfc  = PermohonanRfc::where('opd_id', $id)->exists();

        if ($terkaitUser || $terkaitRfc) {
            return response()->json([
                'message' => 'OPD tidak dapat dihapus karena masih digunakan pada data pengguna atau permohonan RFC. Anda dapat menonaktifkannya.',
            ], 422);
        }

        $opd->delete();

        return response()->json([
            'message' => 'OPD berhasil dihapus.',
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // MASTER KATEGORI RFC
    // ═══════════════════════════════════════════════════════════════════════

    public function masterKategori(Request $request)
    {
        $query = KategoriRfc::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('aktif', true);
            } elseif ($request->status === 'nonaktif') {
                $query->where('aktif', false);
            }
        }

        $kategori = $query->orderBy('nama_kategori')->paginate(15)->withQueryString();

        $statistik = [
            'total'    => KategoriRfc::count(),
            'aktif'    => KategoriRfc::where('aktif', true)->count(),
            'nonaktif' => KategoriRfc::where('aktif', false)->count(),
        ];

        return Inertia::render('Admin/MasterKategori', [
            'kategori'    => $kategori,
            'filterAktif' => $request->only(['search', 'status']),
            'statistik'   => $statistik,
        ]);
    }

    public function simpanKategori(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori_rfc,nama_kategori'],
            'deskripsi'     => ['nullable', 'string'],
            'aktif'         => ['nullable', 'boolean'],
        ]);

        $kategori = KategoriRfc::create([
            'nama_kategori' => trim($validated['nama_kategori']),
            'deskripsi'     => $validated['deskripsi'] ?? null,
            'aktif'         => $request->has('aktif') ? (bool) $request->aktif : true,
        ]);

        return response()->json(['message' => 'Kategori RFC berhasil ditambahkan.', 'kategori' => $kategori]);
    }

    public function perbaruiKategori(Request $request, int $id)
    {
        $kategori  = KategoriRfc::findOrFail($id);
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', "unique:kategori_rfc,nama_kategori,{$id}"],
            'deskripsi'     => ['nullable', 'string'],
            'aktif'         => ['nullable', 'boolean'],
        ]);

        $updateData = [
            'nama_kategori' => trim($validated['nama_kategori']),
            'deskripsi'     => $validated['deskripsi'] ?? null,
        ];

        if ($request->has('aktif')) {
            $updateData['aktif'] = (bool) $request->aktif;
        }

        $kategori->update($updateData);

        return response()->json(['message' => 'Data kategori RFC berhasil diperbarui.', 'kategori' => $kategori]);
    }

    public function toggleAktifKategori(int $id)
    {
        $kategori = KategoriRfc::findOrFail($id);
        $kategori->update(['aktif' => ! $kategori->aktif]);

        return response()->json([
            'message'  => $kategori->aktif ? 'Kategori RFC berhasil diaktifkan.' : 'Kategori RFC berhasil dinonaktifkan.',
            'kategori' => $kategori,
        ]);
    }

    public function hapusKategori(int $id)
    {
        $kategori = KategoriRfc::findOrFail($id);

        $terkaitRfc = PermohonanRfc::where('kategori_id', $id)->exists();

        if ($terkaitRfc) {
            return response()->json([
                'message' => 'Kategori tidak dapat dihapus karena masih digunakan pada data permohonan RFC. Anda dapat menonaktifkannya.',
            ], 422);
        }

        $kategori->delete();

        return response()->json([
            'message' => 'Kategori RFC berhasil dihapus.',
        ]);
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
            'nip'             => $user->nip,
            'nomor_kontak'    => $user->nomor_kontak,
            'unit_kerja'      => $user->unit_kerja,
            'peran_id'        => $user->peran_id,
            'opd_id'          => $user->opd_id,
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
