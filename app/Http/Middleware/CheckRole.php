<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Pastikan user sudah login
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // 🛡️ JIKA ADMIN MENCOBA MASUK KE AREA CUSTOMER:
        // Daripada 404, langsung alihkan (redirect) ke Dashboard Admin mereka
        if ($role === 'customer' && ($user->role === 'admin' || $user->hasRole(['admin', 'Super Admin']))) {
            return redirect()->route('admin.dashboard')->with('info', 'Anda berada di panel admin.');
        }

        // 2. Cek apakah role user COCOK dengan role yang diminta di routes/web.php
        if ($user->role !== $role) {
            // Jika customer masuk ke area admin, tetap block dengan error 404
            abort(404, 'Page Not Found.');
        }

        return $next($request);
    }
}
