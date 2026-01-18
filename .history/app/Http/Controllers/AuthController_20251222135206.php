<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // redirect to role dashboard
            $user = Auth::user();
            if ($user->role === 'admin') return redirect()->route('dashboard.admin');
            if ($user->role === 'kemahasiswaan') return redirect()->route('dashboard.kemahasiswaan');
            if ($user->role === 'pengurus') return redirect()->route('dashboard.pengurus');
            return redirect()->route('home');
        }

        return back()->withErrors(['email' => 'Credentials not match'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
