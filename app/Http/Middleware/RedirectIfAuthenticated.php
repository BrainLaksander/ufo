<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk halaman publik (mahasiswa).
 * Jika user sudah login sebagai kemahasiswaan atau pengurus_ukm,
 * arahkan langsung ke dashboard masing-masing.
 */
class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $role = Auth::user()->role;

            if ($role === 'kemahasiswaan') {
                return redirect()->route('kemahasiswaan.dashboard');
            }

            if ($role === 'pengurus_ukm') {
                return redirect()->route('pengurus-ukm.dashboard');
            }
        }

        return $next($request);
    }
}
