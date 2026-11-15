<?php

namespace App\Http\Controllers;

use App\Rules\EmailJabarprov;
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
            'email'        => ['required', 'email', new EmailJabarprov, 'unique:users,email,' . $user->id],
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
            'unit_kerja'   => $validated['unit_kerja'] ?? $user->unit_kerja,
        ]);

        // Sinkronkan juga unit kerja ke draf RFC pemohon yang belum diajukan
        if (!empty($validated['unit_kerja'])) {
            \App\Models\PermohonanRfc::where('pengguna_id', $user->id)
                ->where('status_terkini', 'draf')
                ->update(['unit_kerja' => $validated['unit_kerja']]);
        }

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user'    => [
                'nama_lengkap' => $user->nama_lengkap,
                'email'        => $user->email,
                'nip'          => $user->nip,
                'unit_kerja'   => $user->unit_kerja,
                'nomor_kontak' => $user->nomor_kontak,
            ],
        ]);
    }

    public function ubahKataSandi(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'password_lama'         => ['required'],
            'password'              => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',        // Minimal 1 huruf kapital
                'regex:/[0-9]/',        // Minimal 1 angka
                'regex:/[^A-Za-z0-9]/', // Minimal 1 simbol unik
                'confirmed',
            ],
            'password_confirmation' => ['required'],
        ], [
            'password_lama.required'         => 'Kata sandi saat ini wajib diisi.',
            'password.required'              => 'Kata sandi baru wajib diisi.',
            'password.min'                   => 'Kata sandi baru minimal 8 karakter.',
            'password.regex'                 => 'Kata sandi baru harus mengandung huruf kapital (A-Z), angka (0-9), dan simbol unik (@$!%*?&#).',
            'password.confirmed'             => 'Konfirmasi kata sandi baru tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi kata sandi baru wajib diisi.',
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
