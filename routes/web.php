<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PermohonanRfcController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ═══════════════════════════════════════════════════════════════════════════════
// RUTE PUBLIK (Tanpa autentikasi)
// ═══════════════════════════════════════════════════════════════════════════════

Route::get('/', fn () => redirect()->route('login'));

// Autentikasi
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.post')->middleware('throttle:login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.post');

// Status registrasi
Route::get('/status-registrasi', fn () => Inertia::render('Auth/RegistrationStatus'))->name('registration.status');
Route::get('/registration-status', fn () => redirect()->route('registration.status'));

// Lupa & reset kata sandi
Route::get('/lupa-kata-sandi',          [PasswordResetController::class, 'tampilkanForm'])->name('password.request');
Route::post('/lupa-kata-sandi',         [PasswordResetController::class, 'kirimLink'])->name('password.email');
Route::get('/reset-kata-sandi/{token}', [PasswordResetController::class, 'tampilkanFormReset'])->name('password.reset');
Route::post('/reset-kata-sandi',        [PasswordResetController::class, 'prosesReset'])->name('password.update');

// Alias backward-compat untuk halaman lupa password lama
Route::get('/forgot-password', fn () => redirect()->route('password.request'));

// ═══════════════════════════════════════════════════════════════════════════════
// RUTE TERPROTEKSI (Semua butuh login + akun aktif)
// ═══════════════════════════════════════════════════════════════════════════════

Route::middleware(['auth.user', 'active.user'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Redirect dashboard cerdas
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');

    // Inside middleware('peran:pemohon') group
    Route::middleware('peran:pemohon')->group(function () {
        Route::get('/dashboard/pemohon',          [PermohonanRfcController::class, 'dashboardPemohon'])->name('dashboard.pemohon');
        Route::get('/rfc/baru',                   [PermohonanRfcController::class, 'create'])->name('rfc.create');
        Route::post('/rfc',                       [PermohonanRfcController::class, 'store'])->name('rfc.store');
        Route::get('/rfc/{id}/edit',              [PermohonanRfcController::class, 'edit'])->name('rfc.edit');
        Route::put('/rfc/{id}',                   [PermohonanRfcController::class, 'update'])->name('rfc.update');
        Route::patch('/rfc/{id}/ajukan',          [PermohonanRfcController::class, 'ajukanDraf'])->name('rfc.ajukan');
        Route::delete('/rfc/{id}/draf',           [PermohonanRfcController::class, 'hapusDraf'])->name('rfc.hapus.draf');
        Route::patch('/rfc/{id}/revisi-pemohon',  [PermohonanRfcController::class, 'revisiPemohon'])->name('rfc.revisi');
    });

    // ─── VERIFIKATOR ────────────────────────────────────────────────────────
    Route::middleware('peran:verifikator')->group(function () {
        Route::get('/dashboard/verifikator',  [PermohonanRfcController::class, 'dashboardVerifikator'])->name('dashboard.verifikator');
        Route::post('/rfc/{id}/telaah',       [PermohonanRfcController::class, 'telaahVerifikator'])->name('rfc.telaah');
    });

    // ─── PIMPINAN ───────────────────────────────────────────────────────────
    Route::middleware('peran:pimpinan')->group(function () {
        Route::get('/dashboard/pimpinan',  [PermohonanRfcController::class, 'dashboardPimpinan'])->name('dashboard.pimpinan');
        Route::post('/rfc/{id}/keputusan', [PermohonanRfcController::class, 'keputusanPimpinan'])->name('rfc.keputusan');
    });

    // ─── TEKNISI ────────────────────────────────────────────────────────────
    Route::middleware('peran:teknisi')->group(function () {
        Route::get('/dashboard/teknisi',       [PermohonanRfcController::class, 'dashboardTeknisi'])->name('dashboard.teknisi');
        Route::post('/rfc/{id}/mulai-eksekusi',[PermohonanRfcController::class, 'mulaiEksekusi'])->name('rfc.mulai');
        Route::post('/rfc/{id}/bast',          [PermohonanRfcController::class, 'unggahBast'])->name('rfc.bast');
    });

    // ─── ADMIN ──────────────────────────────────────────────────────────────
    Route::middleware('peran:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard',          [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/akun',               [AdminController::class, 'kelolaAkun'])->name('akun');
        Route::patch('/akun/{id}/aktivasi',[AdminController::class, 'aktivasiAkun'])->name('akun.aktivasi');
        Route::patch('/akun/{id}/nonaktif',[AdminController::class, 'nonaktifkanAkun'])->name('akun.nonaktif');
        Route::patch('/akun/{id}/tolak',  [AdminController::class, 'tolakAkun'])->name('akun.tolak');
        Route::get('/opd',                [AdminController::class, 'masterOpd'])->name('opd');
        Route::post('/opd',               [AdminController::class, 'simpanOpd'])->name('opd.simpan');
        Route::patch('/opd/{id}',         [AdminController::class, 'perbaruiOpd'])->name('opd.perbarui');
        Route::patch('/opd/{id}/toggle',  [AdminController::class, 'toggleAktifOpd'])->name('opd.toggle');
        Route::get('/kategori',           [AdminController::class, 'masterKategori'])->name('kategori');
        Route::post('/kategori',          [AdminController::class, 'simpanKategori'])->name('kategori.simpan');
        Route::patch('/kategori/{id}',    [AdminController::class, 'perbaruiKategori'])->name('kategori.perbarui');
        Route::get('/laporan',            [LaporanController::class, 'index'])->name('laporan');
        Route::get('/laporan/ekspor',     [LaporanController::class, 'ekspor'])->name('laporan.ekspor');
    });

    // ─── SHARED (semua peran bisa akses) ────────────────────────────────────
    Route::get('/rfc',               [PermohonanRfcController::class, 'index'])->name('rfc.index');
    Route::get('/rfc/{kode}',        [PermohonanRfcController::class, 'show'])->name('rfc.show');
    Route::get('/rfc/{kode}/dokumen',[PermohonanRfcController::class, 'dokumen'])->name('rfc.dokumen');
    Route::get('/rfc/{kode}/workspace',[PermohonanRfcController::class, 'workspace'])->name('rfc.workspace');

    // ─── API NOTIFIKASI ─────────────────────────────────────────────────────
    Route::prefix('api')->group(function () {
        Route::get('/notifikasi',                     [NotifikasiController::class, 'index']);
        Route::patch('/notifikasi/{id}/baca',         [NotifikasiController::class, 'tandaiBaca']);
        Route::patch('/notifikasi/baca-semua',        [NotifikasiController::class, 'tandaiBacaSemua']);
        Route::get('/rfc',                            [PermohonanRfcController::class, 'apiIndex']);
        Route::get('/opd',                            [AdminController::class, 'apiOpd']);
        Route::get('/kategori',                       [AdminController::class, 'apiKategori']);
        
        // Analytics endpoints
        Route::prefix('analytics')->group(function () {
            Route::get('/rfc-by-status',          [AnalyticsController::class, 'rfcByStatus']);
            Route::get('/rfc-by-urgency',         [AnalyticsController::class, 'rfcByUrgency']);
            Route::get('/rfc-trend',              [AnalyticsController::class, 'rfcTrend']);
            Route::get('/rfc-by-category',        [AnalyticsController::class, 'rfcByCategory']);
            Route::get('/rfc-by-opd',             [AnalyticsController::class, 'rfcByOpd']);
            Route::get('/completion-rate',        [AnalyticsController::class, 'completionRate']);
            Route::get('/sla-compliance',         [AnalyticsController::class, 'slaCompliance']);
            Route::get('/dashboard-summary',      [AnalyticsController::class, 'dashboardSummary']);
            Route::post('/clear-cache',           [AnalyticsController::class, 'clearCache'])->middleware('peran:admin');
        });
    });

    // ─── AKUN PENGGUNA ────────────────────────────────────────────────────────
    Route::get('/akun/profil',     [AkunController::class, 'profil'])->name('akun.profil');
    Route::patch('/akun/profil',   [AkunController::class, 'perbaruiProfil'])->name('akun.profil.update');
    Route::get('/akun/keamanan',   [AkunController::class, 'keamanan'])->name('akun.keamanan');
    Route::patch('/akun/kata-sandi', [AkunController::class, 'ubahKataSandi'])->name('akun.kata-sandi');

    // ─── Alias backward-compat untuk URL lama ───────────────────────────────
    Route::get('/form-perubahan-it',                      fn () => redirect()->route('rfc.index'));
    Route::get('/form-perubahan-it/baru',                 fn () => redirect()->route('rfc.create'));
    Route::get('/form-perubahan-it/ticket/{kode}',        fn ($kode) => redirect()->route('rfc.show', $kode));
    Route::get('/form-perubahan-it/dokumen/{kode}',       fn ($kode) => redirect()->route('rfc.dokumen', $kode));
    Route::get('/form-perubahan-it/pengerjaan/{kode}',    fn ($kode) => redirect()->route('rfc.workspace', $kode));
});
