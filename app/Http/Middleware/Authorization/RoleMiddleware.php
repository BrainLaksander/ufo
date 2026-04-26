<?php

namespace App\Http\Middleware\Authorization;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles Role-role yang diizinkan
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $sessionUser = $request->session()->get('user');

        // Jika user tidak authenticated, redirect ke login.
        if (!is_array($sessionUser)) {
            return redirect()->route('login');
        }

        $userRole = strtolower(trim((string) ($sessionUser['role'] ?? '')));

        // Jika middleware role tidak diberi argumen, cukup pastikan user sudah login.
        if ($roles === []) {
            return $next($request);
        }

        // Cek apakah role session ada di daftar role yang diizinkan.
        $allowedRoles = [];
        foreach ($roles as $roleStr) {
            $allowedRoles = array_merge($allowedRoles, array_map(
                static fn (string $role): string => strtolower(trim($role)),
                explode(',', $roleStr)
            ));
        }
        $allowedRoles = array_values(array_unique(array_filter($allowedRoles)));

        if ($userRole === '' || !in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Unauthorized. User role tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
