<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Tampilkan form registrasi
     */
    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Proses pendaftaran pemohon baru
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:50'],
            'nomor_kontak' => ['required', 'string', 'max:50'],
            'asal_opd' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'pernyataan' => ['accepted'],
        ], [
            'email.unique' => 'Email dinas ini sudah terdaftar. Silakan gunakan email lain atau hubungi admin.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'pernyataan.accepted' => 'Anda harus menyetujui pernyataan kebenaran data kedinasan.',
        ]);

        $user = User::create([
            'name' => $validated['nama_lengkap'],
            'nip' => $validated['nip'],
            'nomor_kontak' => $validated['nomor_kontak'],
            'asal_opd' => $validated['asal_opd'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'pemohon',
            'status_registrasi' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('registration.status', [
            'nama' => $user->name,
            'email' => $user->email,
            'opd' => $user->asal_opd,
        ]);
    }

    /**
     * Proses autentikasi login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember', false);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
            ]);
        }

        // Cek status registrasi khusus akun pemohon
        if ($user->role === 'pemohon' && $user->status_registrasi === 'menunggu_verifikasi') {
            return redirect()->route('registration.status', [
                'email' => $user->email,
                'nama' => $user->name,
                'opd' => $user->asal_opd ?? 'Organisasi Perangkat Daerah',
            ]);
        }

        if ($user->role === 'pemohon' && $user->status_registrasi === 'ditolak') {
            return back()->withErrors([
                'email' => 'Pendaftaran akun Anda ditolak oleh verifikator: ' . ($user->catatan_verifikasi ?? 'Data identitas kedinasan tidak valid.'),
            ]);
        }

        Auth::login($user, $remember);
        $request->session()->regenerate();

        // Redirect sesuai peran
        return match ($user->role) {
            'verifikator' => redirect()->route('dashboard.verifikator'),
            'pimpinan' => redirect()->route('dashboard.pimpinan'),
            'teknisi' => redirect()->route('dashboard.teknisi'),
            default => redirect()->route('dashboard.pemohon'),
        };
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

