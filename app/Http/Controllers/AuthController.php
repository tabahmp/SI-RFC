<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Auth/Login');
    }

    /**
     * Tampilkan halaman registrasi.
     */
    public function showRegister()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Auth/Register', [
            'opdList' => \App\Models\Opd::aktif()->orderBy('nama_opd')->get(['id', 'kode_opd', 'nama_opd']),
        ]);
    }

    /**
     * Proses pendaftaran pemohon baru.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip'          => ['required', 'string', 'max:50'],
            'nomor_kontak' => ['required', 'string', 'max:50'],
            'opd_id'       => ['required', 'exists:opd,id'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'pernyataan'   => ['accepted'],
        ], [
            'email.unique'       => 'Email dinas ini sudah terdaftar. Gunakan email lain atau hubungi admin.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'pernyataan.accepted'=> 'Anda harus menyetujui pernyataan kebenaran data kedinasan.',
            'opd_id.required'    => 'Silakan pilih Perangkat Daerah / OPD Anda.',
            'opd_id.exists'      => 'OPD yang dipilih tidak valid.',
        ]);

        $peranPemohon = Peran::where('nama_peran', 'pemohon')->first();
        $opd          = \App\Models\Opd::find($validated['opd_id']);

        $user = User::create([
            'name'               => $validated['nama_lengkap'],
            'nama_lengkap'       => $validated['nama_lengkap'],
            'email'              => $validated['email'],
            'password'           => Hash::make($validated['password']),
            'nip'                => $validated['nip'],
            'nomor_kontak'       => $validated['nomor_kontak'],
            'asal_opd'           => $opd?->nama_opd,
            'peran_id'           => $peranPemohon?->id,
            'opd_id'             => $validated['opd_id'],
            'role'               => 'pemohon',
            'status_aktivasi'    => 'tertunda',
            'status_registrasi'  => 'menunggu_verifikasi',
        ]);

        // Notifikasi ke admin
        try {
            app(\App\Services\NotifikasiService::class)->kirimKePeran(
                'admin',
                'Pendaftaran Akun Baru',
                "Pemohon baru {$user->nama_lengkap} dari {$opd?->nama_opd} menunggu aktivasi.",
                '/admin/akun'
            );
            \Mail::to($user->email)->queue(new \App\Mail\PendaftaranDiterima($user));
        } catch (\Exception $e) {
            \Log::warning('Notifikasi register gagal: ' . $e->getMessage());
        }

        return redirect()->route('registration.status', [
            'nama'  => $user->nama_lengkap,
            'email' => $user->email,
            'opd'   => $opd?->nama_opd,
        ]);
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember', false);
        $user     = User::with('peran', 'opd')->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
            ]);
        }

        $namaPeran     = $user->peran?->nama_peran ?? $user->role ?? 'pemohon';
        $statusEfektif = $user->status_aktivasi_efektif;

        // Pemohon: cek status aktivasi
        if ($namaPeran === 'pemohon') {
            if ($statusEfektif === 'tertunda') {
                return redirect()->route('registration.status', [
                    'email' => $user->email,
                    'nama'  => $user->nama_lengkap ?: $user->name,
                    'opd'   => $user->opd?->nama_opd ?? $user->asal_opd ?? '',
                ]);
            }

            if ($statusEfektif === 'nonaktif') {
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator SI-RFC.',
                ]);
            }
        }

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return match ($namaPeran) {
            'admin'       => redirect()->route('admin.dashboard'),
            'verifikator' => redirect()->route('dashboard.verifikator'),
            'pimpinan'    => redirect()->route('dashboard.pimpinan'),
            'teknisi'     => redirect()->route('dashboard.teknisi'),
            default       => redirect()->route('dashboard.pemohon'),
        };
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('sukses', 'Anda telah berhasil keluar dari sistem.');
    }
}
