<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )->withMiddleware(function (Middleware $middleware) {
        
        // Logika untuk memisahkan lemparan (redirect) tamu yang belum login
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