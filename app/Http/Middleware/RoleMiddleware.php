<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Expected usage: 'role:admin' or 'role:pengurus'
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = $request->session()->get('user');

        if (!$user) {
            return redirect()->route('login');
        }

        if (!isset($user['role']) || $user['role'] !== $role) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
