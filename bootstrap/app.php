<?php

use App\Http\Middleware\CheckPeran;
use App\Http\Middleware\EnsureUserActive;
use App\Http\Middleware\EnsureUserAuthenticated;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias middleware
        $middleware->alias([
            'auth.user'   => EnsureUserAuthenticated::class,
            'active.user' => EnsureUserActive::class,
            'peran'       => CheckPeran::class,
        ]);

        // Tambahkan Inertia ke web middleware group
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

        // Kecualikan CSRF untuk API routes
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
