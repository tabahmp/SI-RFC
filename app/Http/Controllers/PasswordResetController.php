<?php

namespace App\Http\Controllers;

use App\Mail\ResetKataSandi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PasswordResetController extends Controller
{
    /**
     * Tampilkan form input email lupa kata sandi.
     */
    public function tampilkanForm()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    /**
     * Kirim tautan reset kata sandi via email.
     */
    public function kirimLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Selalu tampilkan pesan sukses (security: jangan bocorkan apakah email terdaftar)
        if ($user) {
            // Hapus token lama
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // Buat token baru
            $token = Str::random(64);
            DB::table('password_reset_tokens')->insert([
                'email'      => $request->email,
                'token'      => Hash::make($token),
                'created_at' => now(),
            ]);

            // Kirim email
            try {
                Mail::to($user->email)->queue(new ResetKataSandi($token, $user->email, $user->nama_lengkap ?: $user->name));
            } catch (\Exception $e) {
                // Log error tapi jangan gagalkan request
                \Log::error('Gagal mengirim email reset: ' . $e->getMessage());
            }
        }

        return back()->with('sukses', 'Jika email Anda terdaftar, tautan reset kata sandi telah dikirimkan. Periksa kotak masuk Anda.');
    }

    /**
     * Tampilkan form reset kata sandi baru.
     */
    public function tampilkanFormReset(Request $request, string $token)
    {
        return Inertia::render('Auth/ResetPassword', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Proses reset kata sandi.
     */
    public function prosesReset(Request $request)
    {
        $request->validate([
            'token'                 => ['required'],
            'email'                 => ['required', 'email'],
            'password'              => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Cari token
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record) {
            return back()->withErrors(['email' => 'Token reset tidak valid atau sudah kedaluwarsa.']);
        }

        // Cek kedaluwarsa (60 menit)
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Token reset sudah kedaluwarsa. Silakan minta tautan baru.']);
        }

        // Verifikasi token
        if (! Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Token reset tidak valid.']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withErrors(['email' => 'Akun dengan email ini tidak ditemukan.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Hapus token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('sukses', 'Kata sandi berhasil diperbarui. Silakan masuk dengan kata sandi baru Anda.');
    }
}
