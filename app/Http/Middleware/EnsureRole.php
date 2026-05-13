<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Belum login → redirect ke halaman login
        if (! $request->user()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        $userRole = $request->user()->role;

        // Role tidak sesuai → redirect ke dashboard yang sesuai dengan role user
        if ($userRole !== $role) {
            if ($userRole === 'kemahasiswaan') {
                return redirect()->route('kemahasiswaan.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            if ($userRole === 'pengurus_ukm') {
                return redirect()->route('pengurus-ukm.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

        // Role tidak dikenal → logout dan redirect ke login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->with('error', 'Role akun tidak valid. Silakan login ulang.');
        }

        // Jika user adalah pengurus_ukm, pastikan organisasi mereka masih aktif
        if ($userRole === 'pengurus_ukm') {
            $org = \App\Models\Organization::where('account_user_id', $request->user()->id)->first();
            if ($org && $org->status === 'Nonaktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')
                    ->withErrors(['email' => 'Akun organisasi ini telah dinonaktifkan oleh Kemahasiswaan.']);
            }
        }

        return $next($request);
    }
}
