<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Core\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(Request $request): View
    {
        // Jika user sudah login tapi mengakses halaman login lagi, 
        // kita bersihkan sesinya agar tidak terjadi konflik role (mencegah 403).
        if ($request->session()->has('user')) {
            $request->session()->forget('user');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
            'role' => ['required', 'in:kemahasiswaan,pengurus'],
        ]);

        $email = strtolower($data['email']);
        $password = $data['password'];
        $selectedRole = $data['role'];

        if ($selectedRole === 'pengurus') {
            return $this->attemptPengurusLogin($request, $email, $password);
        }

        if ($selectedRole !== 'kemahasiswaan') {
            return back()
                ->withErrors(['role' => 'Role login tidak valid.'])
                ->onlyInput('email', 'role');
        }

        return $this->attemptRoleLoginFromUsers($request, $email, $password, $selectedRole);
    }

    private function attemptPengurusLogin(Request $request, string $email, string $password)
    {
        $ukmAccount = null;

        if (Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            $query = DB::table('kemahasiswaan_ukm_accounts as akun')
                ->select([
                    'akun.id',
                    'akun.organization_id',
                    'akun.name',
                    'akun.email',
                    'akun.status',
                    'akun.password_hash',
                ]);

            if (Schema::hasTable('organizations')) {
                $query->leftJoin('organizations as org', 'org.id', '=', 'akun.organization_id')
                    ->addSelect('org.name as organization_name');
            }

            $ukmAccount = $query
                ->whereRaw('LOWER(akun.email) = ?', [$email])
                ->where('akun.status', 'active')
                ->first();
        }

        if ($ukmAccount && !empty($ukmAccount->password_hash) && Hash::check($password, (string) $ukmAccount->password_hash)) {
            DB::table('kemahasiswaan_ukm_accounts')
                ->where('id', $ukmAccount->id)
                ->update([
                    'last_login_at' => now(),
                    'updated_at' => now(),
                ]);

            $request->session()->put('user', [
                'name' => $ukmAccount->name,
                'email' => $ukmAccount->email,
                'role' => 'pengurus',
                'ukm_account_id' => (int) $ukmAccount->id,
                'organization_id' => $ukmAccount->organization_id ? (int) $ukmAccount->organization_id : null,
                'organization_name' => $ukmAccount->organization_name ?? null,
            ]);

            $request->session()->migrate(true);

            return $this->redirectByRole('pengurus');
        }

        if ($this->attemptLocalDemoLogin($request, $email, $password, 'pengurus')) {
            return $this->redirectByRole('pengurus');
        }

        return back()
            ->withErrors(['email' => 'Akun pengurus UKM tidak ditemukan atau password salah'])
            ->onlyInput('email', 'role');
    }

    private function attemptRoleLoginFromUsers(Request $request, string $email, string $password, string $role)
    {
        $user = User::query()
            ->whereRaw('LOWER(email) = ?', [$email])
            ->where('role', $role)
            ->first();

        if ($user && Hash::check($password, (string) $user->password)) {
            $user->forceFill(['last_login_at' => now()])->save();

            $request->session()->put('user', [
                'id' => (int) $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role,
                'organization_id' => $user->organization_id ? (int) $user->organization_id : null,
            ]);

            $request->session()->migrate(true);

            return $this->redirectByRole($role);
        }

        if ($this->attemptLocalDemoLogin($request, $email, $password, $role)) {
            return $this->redirectByRole($role);
        }

        return back()
            ->withErrors(['email' => 'Email atau password tidak valid untuk role yang dipilih'])
            ->onlyInput('email', 'role');
    }

    private function attemptLocalDemoLogin(Request $request, string $email, string $password, string $role): bool
    {
        if (!$this->isLocalDemoEnabled()) {
            return false;
        }

        $demoAccount = data_get(config('auth.demo_accounts'), $role);

        if (!is_array($demoAccount)) {
            return false;
        }

        $demoEmail = strtolower((string) ($demoAccount['email'] ?? ''));
        $demoName = (string) ($demoAccount['name'] ?? '');
        $passwordHash = (string) ($demoAccount['password_hash'] ?? '');

        if ($demoEmail === '' || $passwordHash === '') {
            return false;
        }

        if ($demoEmail !== $email || !Hash::check($password, $passwordHash)) {
            return false;
        }

        $request->session()->put('user', [
            'name' => $demoName !== '' ? $demoName : ucfirst($role),
            'email' => $demoEmail,
            'role' => $role,
        ]);

        $request->session()->migrate(true);

        return true;
    }

    private function isLocalDemoEnabled(): bool
    {
        return app()->environment('local') && (bool) config('auth.demo_mode', false);
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
        $request->session()->forget('user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
