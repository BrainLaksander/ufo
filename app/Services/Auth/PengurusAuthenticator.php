<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class PengurusAuthenticator
{
    public function authenticate(string $email, string $password): ?array
    {
        if (!Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return null;
        }

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

        $account = $query
            ->whereRaw('LOWER(akun.email) = ?', [$email])
            ->where('akun.status', 'active')
            ->first();

        if (!$account) {
            return null;
        }

        if (empty($account->password_hash) || !Hash::check($password, (string) $account->password_hash)) {
            return null;
        }

        // Update last login timestamp
        DB::table('kemahasiswaan_ukm_accounts')
            ->where('id', $account->id)
            ->update([
                'last_login_at' => now(),
                'updated_at' => now(),
            ]);

        return [
            'id' => (int) $account->id,
            'name' => $account->name,
            'email' => $account->email,
            'organization_id' => $account->organization_id,
            'organization_name' => $account->organization_name ?? null,
        ];
    }
}
