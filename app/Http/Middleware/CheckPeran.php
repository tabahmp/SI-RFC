<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPeran
{
    public function handle(Request $request, Closure $next, string ...$peranDiizinkan): Response
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $namaPeran = $user->peran?->nama_peran ?? $user->role ?? '';

        if (! in_array($namaPeran, $peranDiizinkan)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda tidak memiliki izin untuk mengakses sumber daya ini.',
                ], 403);
            }

            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
