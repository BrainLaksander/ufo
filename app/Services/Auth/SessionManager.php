<?php

namespace App\Services\Auth;

use Illuminate\Http\Request;

class SessionManager
{
    public function setPengurusSession(Request $request, array $data): void
    {
        $ukmAccountId = $data['ukm_account_id'] ?? $data['id'] ?? null;

        $request->session()->put('user', [
            'name' => (string) ($data['name'] ?? ''),
            'email' => (string) ($data['email'] ?? ''),
            'role' => 'pengurus',
            'ukm_account_id' => $ukmAccountId !== null ? (int) $ukmAccountId : null,
            'organization_id' => !empty($data['organization_id']) ? (int) $data['organization_id'] : null,
            'organization_name' => $data['organization_name'] ?? null,
        ]);

        $request->session()->migrate(true);
    }

    public function setUserSession(Request $request, array $data): void
    {
        $request->session()->put('user', [
            'id' => (int) $data['id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'organization_id' => $data['organization_id'] ? (int) $data['organization_id'] : null,
        ]);

        $request->session()->migrate(true);
    }

    public function setDemoSession(Request $request, string $name, string $email, string $role): void
    {
        $request->session()->put('user', [
            'name' => $name !== '' ? $name : ucfirst($role),
            'email' => $email,
            'role' => $role,
        ]);

        $request->session()->migrate(true);
    }

    public function clearSession(Request $request): void
    {
        $request->session()->forget('user');
    }

    public function invalidate(Request $request): void
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function hasUser(Request $request): bool
    {
        return $request->session()->has('user');
    }
}
