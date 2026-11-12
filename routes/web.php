<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermohonanTiController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Halaman Publik & Autentikasi
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/registration-status', fn () => Inertia::render('Auth/RegistrationStatus'))->name('registration.status');
Route::get('/status-registrasi', fn () => Inertia::render('Auth/RegistrationStatus'));
Route::get('/forgot-password', fn () => Inertia::render('Auth/ForgotPassword'))->name('password.request');
Route::get('/welcome', fn () => Inertia::render('Welcome'));

// Dashboard per Peran (Role-based Dashboards)
Route::get('/dashboard', [PermohonanTiController::class, 'index'])->name('dashboard');
Route::get('/dashboard/pemohon', [PermohonanTiController::class, 'dashboardPemohon'])->name('dashboard.pemohon');
Route::get('/permohonan-saya', [PermohonanTiController::class, 'permohonanSaya'])->name('pemohon.permohonan');
Route::get('/dashboard/verifikator', [PermohonanTiController::class, 'dashboardVerifikator'])->name('dashboard.verifikator');
Route::get('/dashboard/pimpinan', [PermohonanTiController::class, 'dashboardPimpinan'])->name('dashboard.pimpinan');
Route::get('/dashboard/teknisi', [PermohonanTiController::class, 'dashboardTeknisi'])->name('dashboard.teknisi');

// Portal Pemohon
Route::get('/form-perubahan-it', [PermohonanTiController::class, 'index'])->name('rfc.index');
Route::get('/form-perubahan-it/baru', [PermohonanTiController::class, 'create'])->name('rfc.create');
Route::get('/form-perubahan-it/ticket/{rfc}', [PermohonanTiController::class, 'show'])->name('rfc.show');
Route::get('/form-perubahan-it/dokumen/{rfc}', [PermohonanTiController::class, 'dokumen'])->name('rfc.doc');

// Workspace (semua peran)
Route::get('/form-perubahan-it/pengerjaan/{rfc}', [PermohonanTiController::class, 'workspace'])->name('rfc.workspace');

// API Routes
Route::prefix('api')->group(function () {
    Route::get('/form-perubahan-it', [PermohonanTiController::class, 'apiIndex']);
    Route::get('/form-perubahan-it/opd', [PermohonanTiController::class, 'opd']);
    Route::get('/form-perubahan-it/ticket/{rfc}', [PermohonanTiController::class, 'apiShow']);
    Route::post('/form-perubahan-it', [PermohonanTiController::class, 'store']);
    Route::post('/form-perubahan-it/{id}/revisi', [PermohonanTiController::class, 'revisi']);
    Route::post('/form-perubahan-it/{id}/verifikasi', [PermohonanTiController::class, 'verifikasi']);
    Route::post('/form-perubahan-it/{id}/keputusan-pimpinan', [PermohonanTiController::class, 'keputusanPimpinan']);
    Route::post('/form-perubahan-it/{id}/telaah-teknis', [PermohonanTiController::class, 'telaahTeknis']);
    Route::post('/form-perubahan-it/{id}/bast', [PermohonanTiController::class, 'bast']);
    Route::patch('/form-perubahan-it/{id}/status', [PermohonanTiController::class, 'updateStatus']);
    Route::patch('/form-perubahan-it/{id}/assign', [PermohonanTiController::class, 'assign']);
    Route::post('/users/{id}/verifikasi-registrasi', [PermohonanTiController::class, 'verifyUser']);
});
