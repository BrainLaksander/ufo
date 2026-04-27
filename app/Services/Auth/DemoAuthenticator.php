<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Hash;

class DemoAuthenticator
{
    public function authenticate(string $email, string $password, string $role): ?array
    {
        if (!$this->isDemoEnabled()) {
            return null;
        }

        $demoAccount = data_get(config('auth.demo_accounts'), $role);

        if (!is_array($demoAccount)) {
            return null;
        }

        $demoEmail = strtolower((string) ($demoAccount['email'] ?? ''));
        $demoName = (string) ($demoAccount['name'] ?? '');
        $passwordHash = (string) ($demoAccount['password_hash'] ?? '');

        if ($demoEmail === '' || $passwordHash === '') {
            return null;
        }

        if ($demoEmail !== $email || !Hash::check($password, $passwordHash)) {
            return null;
        }

        return [
            'name' => $demoName !== '' ? $demoName : ucfirst($role),
            'email' => $demoEmail,
            'role' => $role,
        ];
    }

    private function isDemoEnabled(): bool
    {
        return app()->environment('local') && (bool) config('auth.demo_mode', false);
    }
}
