<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memeriksa role user
 * 
 * Penggunaan di routes:
 * Route::group(['middleware' => 'role:admin,pengurus'], function () {
 *     // Routes yang hanya bisa diakses admin atau pengurus
 * });
 * 
 * Atau dengan method middleware():
 * Route::get('/path', Controller@method)->middleware('role:admin');
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
        // Jika user tidak authenticated, redirect ke login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Cek apakah user memiliki salah satu dari role yang diperlukan
        $hasRole = false;
        foreach ($roles as $role) {
            if ($user->role === $role) {
                $hasRole = true;
                break;
            }

            // Support untuk hasRole() method
            if (method_exists($user, 'hasRole') && $user->hasRole($role)) {
                $hasRole = true;
                break;
            }
        }

        // Jika user tidak memiliki role yang sesuai, kembalikan 403
        if (!$hasRole) {
            return response()->view('errors.403', [], 403);
        }

        return $next($request);
    }
}
