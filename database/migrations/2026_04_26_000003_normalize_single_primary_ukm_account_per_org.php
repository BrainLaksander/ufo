<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('organizations') || !Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return;
        }

        $organizations = DB::table('organizations')
            ->select(['id', 'name', 'shortname'])
            ->get();

        $organizationByKey = [];
        foreach ($organizations as $organization) {
            $shortnameKey = $this->normalizeKey((string) ($organization->shortname ?? ''));
            $nameKey = $this->normalizeKey((string) ($organization->name ?? ''));

            if ($shortnameKey !== '') {
                $organizationByKey[$shortnameKey] = (int) $organization->id;
            }

            if ($nameKey !== '' && !isset($organizationByKey[$nameKey])) {
                $organizationByKey[$nameKey] = (int) $organization->id;
            }
        }

        $accounts = DB::table('kemahasiswaan_ukm_accounts')
            ->select(['id', 'organization_id', 'email', 'name'])
            ->orderBy('id')
            ->get();

        foreach ($accounts as $account) {
            $accountId = (int) ($account->id ?? 0);
            $currentOrganizationId = (int) ($account->organization_id ?? 0);
            $emailLocal = strtolower((string) Str::before((string) ($account->email ?? ''), '@'));
            $emailKey = $this->normalizeKey($emailLocal);

            if ($emailKey === '' || !isset($organizationByKey[$emailKey])) {
                continue;
            }

            $targetOrganizationId = (int) $organizationByKey[$emailKey];
            if ($targetOrganizationId <= 0 || $targetOrganizationId === $currentOrganizationId) {
                continue;
            }

            DB::table('kemahasiswaan_ukm_accounts')
                ->where('id', $accountId)
                ->update([
                    'organization_id' => $targetOrganizationId,
                    'updated_at' => now(),
                ]);
        }

        $accountsByOrganization = DB::table('kemahasiswaan_ukm_accounts')
            ->whereNotNull('organization_id')
            ->select(['id', 'organization_id', 'email', 'name'])
            ->orderBy('id')
            ->get()
            ->groupBy('organization_id');

        foreach ($accountsByOrganization as $organizationId => $rows) {
            if ($rows->count() <= 1) {
                continue;
            }

            $organization = DB::table('organizations')
                ->where('id', (int) $organizationId)
                ->select(['id', 'name', 'shortname'])
                ->first();

            $organizationKey = $this->normalizeKey((string) ($organization->shortname ?? ''));
            if ($organizationKey === '') {
                $organizationKey = $this->normalizeKey((string) ($organization->name ?? ''));
            }

            $scored = $rows->map(function ($row) use ($organizationKey) {
                $emailLocal = strtolower((string) Str::before((string) ($row->email ?? ''), '@'));
                $emailKey = $this->normalizeKey($emailLocal);
                $nameKey = $this->normalizeKey((string) ($row->name ?? ''));

                $score = 3;
                if ($organizationKey !== '' && $emailKey === $organizationKey) {
                    $score = 0;
                } elseif ($organizationKey !== '' && Str::contains($nameKey, $organizationKey)) {
                    $score = 1;
                } elseif ($emailKey !== '') {
                    $score = 2;
                }

                return [
                    'id' => (int) $row->id,
                    'score' => $score,
                ];
            })->sortBy([
                ['score', 'asc'],
                ['id', 'asc'],
            ])->values();

            $keepId = (int) ($scored[0]['id'] ?? 0);
            if ($keepId <= 0) {
                continue;
            }

            $removeIds = $scored
                ->slice(1)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->filter(fn ($id) => $id > 0)
                ->values()
                ->all();

            if (empty($removeIds)) {
                continue;
            }

            if (Schema::hasTable('kemahasiswaan_announcements')) {
                DB::table('kemahasiswaan_announcements')
                    ->whereIn('ukm_account_id', $removeIds)
                    ->update([
                        'ukm_account_id' => $keepId,
                        'updated_at' => now(),
                    ]);
            }

            if (Schema::hasTable('kemahasiswaan_activity_logs')) {
                DB::table('kemahasiswaan_activity_logs')
                    ->whereIn('ukm_account_id', $removeIds)
                    ->update([
                        'ukm_account_id' => $keepId,
                        'updated_at' => now(),
                    ]);
            }

            DB::table('kemahasiswaan_ukm_accounts')
                ->whereIn('id', $removeIds)
                ->delete();
        }

        $indexExists = collect(DB::select("SHOW INDEX FROM kemahasiswaan_ukm_accounts WHERE Key_name = 'kmsa_account_org_unique'"))
            ->isNotEmpty();

        if (!$indexExists) {
            Schema::table('kemahasiswaan_ukm_accounts', function (Blueprint $table) {
                $table->unique('organization_id', 'kmsa_account_org_unique');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return;
        }

        $indexExists = collect(DB::select("SHOW INDEX FROM kemahasiswaan_ukm_accounts WHERE Key_name = 'kmsa_account_org_unique'"))
            ->isNotEmpty();

        if ($indexExists) {
            Schema::table('kemahasiswaan_ukm_accounts', function (Blueprint $table) {
                $table->dropUnique('kmsa_account_org_unique');
            });
        }
    }

    private function normalizeKey(string $value): string
    {
        $normalized = strtolower(trim($value));

        if ($normalized === '') {
            return '';
        }

        return preg_replace('/[^a-z0-9]+/', '', $normalized) ?? '';
    }
};
