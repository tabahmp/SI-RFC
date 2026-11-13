<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AkunController extends Controller
{
    public function profil()
    {
        return Inertia::render('Akun/Profil');
    }

    public function keamanan()
    {
        return Inertia::render('Akun/Keamanan');
    }

    public function perbaruiProfil(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email,' . $user->id],
            'nip'          => ['nullable', 'string', 'max:50'],
            'nomor_kontak' => ['nullable', 'string', 'max:50'],
            'unit_kerja'   => ['nullable', 'string', 'max:255'],
        ], [
            'email.unique' => 'Email ini sudah digunakan akun lain.',
        ]);

        $user->update([
            'nama_lengkap' => $validated['nama_lengkap'],
            'name'         => $validated['nama_lengkap'],
            'email'        => $validated['email'],
            'nip'          => $validated['nip'] ?? $user->nip,
            'nomor_kontak' => $validated['nomor_kontak'] ?? $user->nomor_kontak,
        ]);

        return response()->json(['message' => 'Profil berhasil diperbarui.']);
    }

    public function ubahKataSandi(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'password_lama'        => ['required'],
            'password'             => ['required', 'min:8', 'confirmed'],
            'password_confirmation'=> ['required'],
        ], [
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        if (! Hash::check($request->password_lama, $user->password)) {
            return response()->json([
                'message' => 'Kata sandi saat ini tidak sesuai.',
                'errors'  => ['password_lama' => 'Kata sandi saat ini tidak sesuai.'],
            ], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Kata sandi berhasil diperbarui.']);
    }
}
