<?php

namespace App\Services\Organization;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class OrganizationAccountSyncAction
{
    /**
     * Sync pengurus account for organization (create/update)
     */
    public function sync(
        int $organizationId,
        string $organizationName,
        string $organizationShortname,
        string $accountEmail,
        string $plainPassword,
        string $phone = '',
        string $leaderName = ''
    ): void {
        if (!Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return;
        }

        $accountDisplay = trim($organizationShortname) !== '' ? trim($organizationShortname) : trim($organizationName);
        if ($accountDisplay === '') {
            $accountDisplay = 'Organisasi';
        }

        $ketuaName = trim($leaderName);
        $accountName = $ketuaName !== '' ? $ketuaName : ('Ketua ' . $accountDisplay);
        $passwordToUse = trim($plainPassword) !== ''
            ? trim($plainPassword)
            : (trim((string) config('auth.default_ukm_password', '')) !== ''
                ? trim((string) config('auth.default_ukm_password', ''))
                : Str::random(10));

        $passwordHash = Hash::make($passwordToUse);
        $passwordHashColumnExists = Schema::hasColumn('kemahasiswaan_ukm_accounts', 'password_hash');
        $existingAccount = DB::table('kemahasiswaan_ukm_accounts')
            ->where('organization_id', $organizationId)
            ->orderBy('id')
            ->first();

        if ($existingAccount) {
            $updates = [
                'name' => $accountName,
                'email' => $accountEmail,
                'updated_at' => now(),
            ];

            if (empty($existingAccount->status)) {
                $updates['status'] = 'active';
            }

            if ($passwordHashColumnExists && ($plainPassword !== '' || empty($existingAccount->password_hash))) {
                $updates['password_hash'] = $passwordHash;
            }

            DB::table('kemahasiswaan_ukm_accounts')
                ->where('id', $existingAccount->id)
                ->update($updates);

            $accountId = (int) $existingAccount->id;
        } else {
            $insertPayload = [
                'organization_id' => $organizationId,
                'user_id' => null,
                'name' => $accountName,
                'email' => $accountEmail,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($passwordHashColumnExists) {
                $insertPayload['password_hash'] = $passwordHash;
            }

            $accountId = (int) DB::table('kemahasiswaan_ukm_accounts')->insertGetId($insertPayload);
        }

        $userId = $this->syncPengurusUserAccount($organizationId, $accountName, $accountEmail, $passwordHash);

        if ($userId !== null && Schema::hasColumn('kemahasiswaan_ukm_accounts', 'user_id')) {
            DB::table('kemahasiswaan_ukm_accounts')
                ->where('id', $accountId)
                ->update(['user_id' => $userId, 'updated_at' => now()]);
        }

        $this->syncKetuaMember($organizationId, $accountName, $accountEmail, $phone);
    }

    private function syncPengurusUserAccount(int $organizationId, string $name, string $email, string $passwordHash): ?int
    {
        if (!Schema::hasTable('users')) {
            return null;
        }

        $existing = DB::table('users')
            ->whereRaw('LOWER(email) = ?', [Str::lower($email)])
            ->first();

        $payload = [
            'name' => $name,
            'email' => $email,
            'role' => 'pengurus',
            'organization_id' => $organizationId,
            'updated_at' => now(),
        ];

        if ($existing) {
            if (($existing->role ?? null) !== 'pengurus') {
                return (int) $existing->id;
            }

            DB::table('users')
                ->where('id', $existing->id)
                ->update($payload);

            return (int) $existing->id;
        }

        $payload['password'] = $passwordHash;
        $payload['created_at'] = now();

        return (int) DB::table('users')->insertGetId($payload);
    }

    private function syncKetuaMember(int $organizationId, string $name, string $email, string $phone = ''): void
    {
        if (!Schema::hasTable('members')) {
            return;
        }

        $member = DB::table('members')
            ->where('organization_id', $organizationId)
            ->whereRaw('LOWER(position) = ?', ['ketua'])
            ->orderBy('id')
            ->first();

        $payload = [
            'name' => $name,
            'email' => $email,
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('members', 'phone')) {
            $payload['phone'] = $phone !== '' ? $phone : null;
        }

        if ($member) {
            DB::table('members')
                ->where('id', $member->id)
                ->update($payload);

            return;
        }

        $payload['organization_id'] = $organizationId;
        $payload['created_at'] = now();

        if (Schema::hasColumn('members', 'nim')) {
            $payload['nim'] = 'ADM-' . str_pad((string) $organizationId, 6, '0', STR_PAD_LEFT);
        }

        if (Schema::hasColumn('members', 'faculty')) {
            $payload['faculty'] = 'Universitas';
        }

        if (Schema::hasColumn('members', 'major')) {
            $payload['major'] = 'Staff UKM';
        }

        if (Schema::hasColumn('members', 'position')) {
            $payload['position'] = 'ketua';
        }

        if (Schema::hasColumn('members', 'status')) {
            $payload['status'] = 'aktif';
        }

        if (Schema::hasColumn('members', 'join_type')) {
            $payload['join_type'] = 'founder';
        }

        if (Schema::hasColumn('members', 'join_date')) {
            $payload['join_date'] = now()->toDateString();
        }

        DB::table('members')->insert($payload);
    }
}
