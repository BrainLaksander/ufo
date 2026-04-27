<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use App\Services\Auth\DemoAuthenticator;
use App\Services\Auth\PengurusAuthenticator;
use App\Services\Auth\SessionManager;
use App\Services\Auth\UserAuthenticator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthController extends Controller
{
    private SessionManager $sessionManager;
    private PengurusAuthenticator $pengurusAuth;
    private UserAuthenticator $userAuth;
    private DemoAuthenticator $demoAuth;

    public function __construct(
        SessionManager $sessionManager,
        PengurusAuthenticator $pengurusAuth,
        UserAuthenticator $userAuth,
        DemoAuthenticator $demoAuth,
    ) {
        $this->sessionManager = $sessionManager;
        $this->pengurusAuth = $pengurusAuth;
        $this->userAuth = $userAuth;
        $this->demoAuth = $demoAuth;
    }

    public function showLogin(Request $request): View
    {
        // Jika user sudah login tapi mengakses halaman login lagi, 
        // kita bersihkan sesinya agar tidak terjadi konflik role (mencegah 403).
        if ($this->sessionManager->hasUser($request)) {
            $this->sessionManager->clearSession($request);
        }

        $selectedRole = old('role', $request->query('role'));

        if (!in_array($selectedRole, User::INTERNAL_LOGIN_ROLES, true)) {
            $selectedRole = '';
        }

        $roleOptions = [];

        foreach (User::INTERNAL_LOGIN_ROLES as $role) {
            $roleOptions[] = [
                'value' => $role,
                'label' => User::INTERNAL_LOGIN_ROLE_LABELS[$role] ?? ucfirst($role),
            ];
        }

        return view('auth.login', [
            'roleOptions' => $roleOptions,
            'selectedRole' => $selectedRole,
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required', Rule::in(User::INTERNAL_LOGIN_ROLES)],
        ]);

        $email = strtolower($data['email']);
        $password = $data['password'];
        $selectedRole = $data['role'];

        if ($selectedRole === 'pengurus') {
            $pengurusData = $this->pengurusAuth->authenticate($email, $password);
            
            if ($pengurusData) {
                $this->sessionManager->setPengurusSession($request, $pengurusData);
                return $this->redirectByRole('pengurus');
            }

            // Try demo account
            $demoData = $this->demoAuth->authenticate($email, $password, 'pengurus');
            if ($demoData) {
                $this->sessionManager->setDemoSession($request, $demoData['name'], $demoData['email'], 'pengurus');
                return $this->redirectByRole('pengurus');
            }

            return back()
                ->withErrors(['email' => 'Akun pengurus UKM tidak ditemukan atau password salah'])
                ->onlyInput('email', 'role');
        }

        if ($selectedRole !== 'kemahasiswaan') {
            return back()
                ->withErrors(['role' => 'Role login tidak valid.'])
                ->onlyInput('email', 'role');
        }

        $userData = $this->userAuth->authenticate($email, $password, $selectedRole);
        
        if ($userData) {
            $this->sessionManager->setUserSession($request, $userData);
            return $this->redirectByRole($selectedRole);
        }

        // Try demo account
        $demoData = $this->demoAuth->authenticate($email, $password, $selectedRole);
        if ($demoData) {
            $this->sessionManager->setDemoSession($request, $demoData['name'], $demoData['email'], $selectedRole);
            return $this->redirectByRole($selectedRole);
        }

        return back()
            ->withErrors(['email' => 'Email atau password tidak valid untuk role yang dipilih'])
            ->onlyInput('email', 'role');
    }

    private function redirectByRole(string $role)
    {
        if ($role === 'kemahasiswaan') {
            return redirect()->route('dashboard.kemahasiswaan');
        }

        if ($role === 'pengurus') {
            return redirect()->route('dashboard.pengurus');
        }

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        $this->sessionManager->clearSession($request);
        $this->sessionManager->invalidate($request);
        return redirect()->route('home');
    }
}
