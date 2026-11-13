<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Rate limiter untuk login (maks 5 percobaan per menit per IP)
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                return back()->withErrors([
                    'email' => 'Terlalu banyak percobaan masuk. Silakan coba lagi dalam 1 menit.',
                ]);
            });
        });
    }
}
