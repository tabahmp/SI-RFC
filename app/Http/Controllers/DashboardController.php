<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    /**
     * Redirect ke dashboard sesuai peran user yang sedang login.
     */
    public function redirect(Request $request)
    {
        $user      = auth()->user();
        $namaPeran = $user->peran?->nama_peran ?? $user->role ?? 'pemohon';

        return match ($namaPeran) {
            'admin'       => redirect()->route('admin.dashboard'),
            'verifikator' => redirect()->route('dashboard.verifikator'),
            'pimpinan'    => redirect()->route('dashboard.pimpinan'),
            'teknisi'     => redirect()->route('dashboard.teknisi'),
            default       => redirect()->route('dashboard.pemohon'),
        };
    }
}
