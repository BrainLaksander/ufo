<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Login using static/dummy credentials.
     * This avoids database access and uses session storage only.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        // static users (email => [name, role, password])
        $users = [
            'admin@example.com' => ['name' => 'Admin Sistem', 'role' => 'admin', 'password' => 'password'],
            'kemahasiswaan@example.com' => ['name' => 'Kemahasiswaan', 'role' => 'kemahasiswaan', 'password' => 'password'],
            'pengurus@example.com' => ['name' => 'Pengurus Organisasi', 'role' => 'pengurus', 'password' => 'password'],
            'mahasiswa@example.com' => ['name' => 'Mahasiswa', 'role' => 'mahasiswa', 'password' => 'password'],
        ];

        $email = $data['email'];
        $password = $data['password'];

        if (!isset($users[$email]) || $users[$email]['password'] !== $password) {
            return back()->withErrors(['email' => 'Credentials not match'])->onlyInput('email');
        }

        $user = $users[$email];
        // store minimal user info in session
        $request->session()->put('user', [
            'name' => $user['name'],
            'email' => $email,
            'role' => $user['role'],
        ]);

        // regenerate session id
        $request->session()->migrate(true);

        // redirect by role
        if ($user['role'] === 'admin') return redirect()->route('dashboard.admin');
        if ($user['role'] === 'kemahasiswaan') return redirect()->route('dashboard.kemahasiswaan');
        if ($user['role'] === 'pengurus') return redirect()->route('dashboard.pengurus');

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
