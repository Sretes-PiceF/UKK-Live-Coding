<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PelangganMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Gunakan guard 'pelanggan'
        if (!Auth::guard('pelanggan')->check()) {
            return redirect()->route('login.pelanggan')
                ->with('error', 'Silakan login sebagai pelanggan untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
