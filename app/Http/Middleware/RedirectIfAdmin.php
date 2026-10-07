<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            // Jika yang login adalah admin/super admin, jangan biarkan akses halaman publik/customer, lempar ke dashboard admin
            if ($user->role === 'admin' || $user->hasRole(['admin', 'Super Admin'])) {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
