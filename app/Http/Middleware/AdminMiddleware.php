<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Gunakan guard 'admin'
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('login.admin')
                ->with('error', 'Silakan login sebagai admin untuk mengakses halaman ini.');
        }
        return $next($request);
    }
}
