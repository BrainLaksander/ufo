<?php

namespace App\Services\Auth;

use App\Models\Core\User;
use Illuminate\Support\Facades\Hash;

class UserAuthenticator
{
    public function authenticate(string $email, string $password, string $role): ?array
    {
        $user = User::query()
            ->whereRaw('LOWER(email) = ?', [$email])
            ->where('role', $role)
            ->first();

        if (!$user) {
            return null;
        }

        if (!Hash::check($password, (string) $user->password)) {
            return null;
        }

        $user->forceFill(['last_login_at' => now()])->save();

        return [
            'id' => (int) $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $role,
            'organization_id' => $user->organization_id,
        ];
    }
}
