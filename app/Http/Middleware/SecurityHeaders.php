<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Menambahkan pelindung dari Clickjacking (Temuan Nikto)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Opsional: Tambahkan header keamanan ekstra agar web Anda makin kokoh
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        return $response;
    }
}
