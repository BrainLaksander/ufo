<?php

namespace App\Http\Middleware\Authorization;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memeriksa role user
 * 
 * Penggunaan di routes:
 * Route::group(['middleware' => 'role:kemahasiswaan,pengurus'], function () {
 *     // Routes yang hanya bisa diakses kemahasiswaan atau pengurus
 * });
 * 
 * Atau dengan method middleware():
 * Route::get('/path', Controller@method)->middleware('role:kemahasiswaan');
 */
class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $sessionUser = $request->session()->get('user');

        // Jika user tidak authenticated, redirect ke login.
        if (!is_array($sessionUser)) {
            return redirect()->route('login');
        }

        $userRole = strtolower((string) ($sessionUser['role'] ?? ''));

        if ($roles === []) {
            return $next($request);
        }

        $allowedRoles = array_map(
            static fn (string $role): string => strtolower(trim($role)),
            $roles
        );
        $allowedRoles = array_values(array_unique(array_filter($allowedRoles)));

        if (!in_array($userRole, $allowedRoles, true)) {
            return response()->view('errors.403', [], 403);
        }

        return $next($request);
    }
}
