<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        // Use session-based 'user' to determine authentication in this legacy flow.
        if (!$request->session()->has('user')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
