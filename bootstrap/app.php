<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\RedirectIfAdmin;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // 🪄 1. TAMBAHKAN PENDAFTARAN ALIAS MIDDLEWARE DI SINI
        $middleware->alias([
            'role' => CheckRole::class,
            'spatie_role' => RoleMiddleware::class,
            'redirect.admin' => RedirectIfAdmin::class,
        ]);

        $middleware->web(append: [
            SecurityHeaders::class,
        ]);

        // 2. Logika untuk memisahkan lemparan (redirect) tamu yang belum login
        $middleware->redirectGuestsTo(function (Request $request) {
            // Jika URL-nya mengandung kata 'admin', lempar ke halaman login admin
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            // Selain itu, lempar ke halaman login customer biasa
            return route('login');
        });

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
