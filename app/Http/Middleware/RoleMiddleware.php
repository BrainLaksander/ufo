<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memeriksa role user menggunakan auth()->user()
 * 
 * Penggunaan di routes:
 * Route::group(['middleware' => 'role:admin,pengurus'], function () {
 *     // Routes yang hanya bisa diakses admin atau pengurus
 * });
 * 
 * Atau:
 * Route::get('/path', Controller@method)->middleware('role:admin');
 * Route::get('/path', Controller@method)->middleware('role:pengurus,admin');
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles Role-role yang diizinkan (comma-separated atau multiple parameters)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika user tidak authenticated, redirect ke login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Cek apakah user memiliki salah satu dari role yang diperlukan
        $hasRole = false;
        foreach ($roles as $roleStr) {
            // Support untuk comma-separated roles (role:admin,pengurus)
            $allowedRoles = array_map('trim', explode(',', $roleStr));
            
            foreach ($allowedRoles as $role) {
                if ($user->role === $role) {
                    $hasRole = true;
                    break 2;
                }

                // Support untuk hasRole() method jika ada
                if (method_exists($user, 'hasRole') && $user->hasRole($role)) {
                    $hasRole = true;
                    break 2;
                }
            }
        }

        // Jika user tidak memiliki role yang sesuai, kembalikan 403
        if (!$hasRole) {
            abort(403, 'Unauthorized. User role tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
