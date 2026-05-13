<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->role === 'pengurus_ukm'
                ? redirect()->route('pengurus-ukm.dashboard')
                : redirect()->route('kemahasiswaan.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'role' => ['required', 'in:kemahasiswaan,pengurus_ukm'],
        ]);

        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => $credentials['role'],
        ])) {
            return back()
                ->withErrors(['email' => 'Email, password, atau role tidak sesuai.'])
                ->withInput($request->only('email', 'role'));
        }

        $request->session()->regenerate();
        $request->session()->put('actor_role', Auth::user()->role);

        if (Auth::user()->role === 'pengurus_ukm') {
            $org = \App\Models\Organization::where('account_user_id', Auth::id())->first();
            if ($org && $org->status === 'Nonaktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()
                    ->withErrors(['email' => 'Akun organisasi ini telah dinonaktifkan oleh Kemahasiswaan.'])
                    ->withInput($request->only('email', 'role'));
            }
            return redirect()->route('pengurus-ukm.dashboard');
        }

        return redirect()->route('kemahasiswaan.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
