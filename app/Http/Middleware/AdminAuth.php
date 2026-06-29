<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('admin_logged_in') && !Auth::check()) {
            return redirect()->route('login')->withErrors(['login_error' => 'Akses ditolak. Silakan login terlebih dahulu.']);
        }

        return $next($request);
    }
}
