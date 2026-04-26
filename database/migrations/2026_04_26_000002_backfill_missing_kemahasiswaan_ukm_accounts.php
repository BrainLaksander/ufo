<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('organizations') || !Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return;
        }

        $organizations = DB::table('organizations')
            ->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.organization_id', '=', 'organizations.id')
            ->where('organizations.status', 'active')
            ->whereNull('akun.id')
            ->select(['organizations.id', 'organizations.name', 'organizations.shortname'])
            ->orderBy('organizations.id')
            ->get();

        if ($organizations->isEmpty()) {
            return;
        }

        $emailSet = DB::table('kemahasiswaan_ukm_accounts')
            ->pluck('email')
            ->filter()
            ->map(fn ($email) => strtolower((string) $email))
            ->values()
            ->all();

        $usedEmails = array_fill_keys($emailSet, true);
        $now = now();

        foreach ($organizations as $organization) {
            $orgId = (int) ($organization->id ?? 0);
            if ($orgId <= 0) {
                continue;
            }

            $preferredKey = trim((string) ($organization->shortname ?? ''));
            if ($preferredKey === '') {
                $preferredKey = trim((string) ($organization->name ?? ''));
            }

            $baseLocalPart = strtolower(preg_replace('/[^a-z0-9]+/i', '', $preferredKey) ?? '');
            if ($baseLocalPart === '') {
                $baseLocalPart = 'org' . $orgId;
            }

            $email = $baseLocalPart . '@unklab.ac.id';
            $attempt = 1;
            while (isset($usedEmails[$email])) {
                $email = $baseLocalPart . $attempt . '@unklab.ac.id';
                $attempt++;
            }
            $usedEmails[$email] = true;

            $displayName = trim((string) ($organization->shortname ?? ''));
            if ($displayName === '') {
                $displayName = trim((string) ($organization->name ?? ''));
            }

            DB::table('kemahasiswaan_ukm_accounts')->insert([
                'organization_id' => $orgId,
                'user_id' => null,
                'name' => 'Pengurus ' . $displayName,
                'email' => $email,
                'position' => 'Ketua',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        // No-op to avoid deleting accounts that may have been updated after backfill.
    }
};
