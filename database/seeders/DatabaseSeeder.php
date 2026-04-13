<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!app()->environment('local') || !config('auth.demo_mode')) {
            return;
        }

        $demoAccounts = (array) config('auth.demo_accounts', []);
        $userRoleMap = ['kemahasiswaan'];

        foreach ($userRoleMap as $role) {
            $account = $demoAccounts[$role] ?? null;
            if (!is_array($account)) {
                continue;
            }

            $email = strtolower((string) ($account['email'] ?? ''));
            $passwordHash = (string) ($account['password_hash'] ?? '');
            $name = (string) ($account['name'] ?? ucfirst($role));

            if ($email === '' || $passwordHash === '') {
                continue;
            }

            User::query()->updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => $passwordHash,
                    'role' => $role,
                ]
            );
        }

        if (!Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return;
        }

        $pengurusAccount = $demoAccounts['pengurus'] ?? null;
        if (!is_array($pengurusAccount)) {
            return;
        }

        $pengurusEmail = strtolower((string) ($pengurusAccount['email'] ?? ''));
        $pengurusName = (string) ($pengurusAccount['name'] ?? 'Pengurus UKM/BEM');
        $pengurusPasswordHash = (string) ($pengurusAccount['password_hash'] ?? '');

        if ($pengurusEmail === '') {
            return;
        }

        $record = [
            'name' => $pengurusName,
            'status' => 'active',
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('kemahasiswaan_ukm_accounts', 'password_hash') && $pengurusPasswordHash !== '') {
            $record['password_hash'] = $pengurusPasswordHash;
        }

        DB::table('kemahasiswaan_ukm_accounts')->updateOrInsert(
            ['email' => $pengurusEmail],
            $record + ['created_at' => now()]
        );
    }
}
