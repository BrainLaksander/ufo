<?php

namespace App\Http\Controllers\Kemahasiswaan;

use App\Services\ReferenceValueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait KemahasiswaanControllerTrait
{
    protected ReferenceValueService $referenceService;

    protected function uiText(string $code): string
    {
        // Try database first if service is available
        if (isset($this->referenceService)) {
            $dbText = $this->referenceService->getLabel('ui_text', $code);
            if (!empty($dbText)) {
                return $dbText;
            }
        }

        // Fallback to database with direct query
        try {
            $label = DB::table('workflow_reference_values')
                ->where('domain', 'ui_text')
                ->where('code', $code)
                ->where('is_active', true)
                ->value('label');

            if (!empty($label)) {
                return (string) $label;
            }
        } catch (\Throwable $e) {
            // Database error, continue to config fallback
        }

        // Final fallback to config/ui_text.php
        return config("ui_text.ui_text.{$code}", '');
    }

    /**
     * Fallback to config/ui_text.php when database is empty
     */
    protected function uiTextWithFallback(string $code): string
    {
        return $this->uiText($code);
    }

    /**
     * @param  array<string, string>  $keyCodeMap
     * @return array<string, string>
     */
    protected function uiTextMap(array $keyCodeMap): array
    {
        $labels = [];

        foreach ($keyCodeMap as $key => $code) {
            $labels[$key] = $this->uiText((string) $code);
        }

        return $labels;
    }

    // ============ Account Data Helpers ============

    protected function getAkunUKM(): array
    {
        $rows = DB::table('kemahasiswaan_ukm_accounts as akun')
            ->leftJoin('organizations as org', 'org.id', '=', 'akun.organization_id')
            ->select([
                'akun.id', 'akun.organization_id', 'akun.name', 'akun.email',
                'akun.status', 'akun.created_at', 'org.name as organization_name',
            ])
            ->orderByDesc('akun.id')
            ->get();

        return $rows->map(fn ($row) => [
            'id' => (int) $row->id,
            'name' => $row->name,
            'email' => $row->email,
            'organization_id' => (int) ($row->organization_id ?? 0),
            'organization_name' => $row->organization_name,
            'status' => Str::lower((string) $row->status),
            'status_label' => $this->statusLabel('account', Str::lower((string) $row->status)),
            'created_at' => $row->created_at,
        ])->all();
    }

    protected function getActivityLogs(): array
    {
        if (!Schema::hasTable('kemahasiswaan_activity_logs')) {
            return [];
        }

        $rows = DB::table('kemahasiswaan_activity_logs as log')
            ->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'log.ukm_account_id')
            ->leftJoin('organizations as org', 'org.id', '=', 'log.organization_id')
            ->select([
                'log.id', 'log.action', 'log.description', 'log.created_at',
                'akun.name as account_name', 'org.name as organization_name',
            ])
            ->orderByDesc('log.id')
            ->limit(200)
            ->get();

        return $rows->map(fn ($row) => [
            'id' => (int) $row->id,
            'action' => $row->action,
            'description' => $row->description,
            'account_name' => $row->account_name,
            'organization_name' => $row->organization_name,
            'created_at' => $row->created_at,
        ])->all();
    }

    protected function getOrganizations(): array
    {
        return DB::table('organizations')
            ->select(['id', 'name', 'shortname'])
            ->where('status', $this->organizationActiveStatus())
            ->orderBy('name')
            ->get()
            ->map(fn ($row) => [
                'id' => (int) $row->id,
                'name' => $row->name,
                'shortname' => $row->shortname,
            ])
            ->all();
    }

    protected function findAkunUKM(int $id): ?array
    {
        if (!Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return null;
        }

        $row = DB::table('kemahasiswaan_ukm_accounts as akun')
            ->leftJoin('organizations as org', 'org.id', '=', 'akun.organization_id')
            ->select([
                'akun.id', 'akun.organization_id', 'akun.name', 'akun.email',
                'org.name as organization_name',
            ])
            ->where('akun.id', $id)
            ->first();

        return $row ? [
            'id' => (int) $row->id,
            'name' => $row->name,
            'email' => $row->email,
            'organization_id' => (int) ($row->organization_id ?? 0),
            'organization_name' => $row->organization_name,
        ] : null;
    }

    protected function appendActivityLog(array $payload): void
    {
        if (!Schema::hasTable('kemahasiswaan_activity_logs')) {
            return;
        }

        DB::table('kemahasiswaan_activity_logs')->insert([
            'ukm_account_id' => $payload['ukm_account_id'] ?? null,
            'organization_id' => $payload['organization_id'] ?? null,
            'action' => $payload['action'] ?? '',
            'description' => $payload['description'] ?? '',
            'created_at' => now(),
        ]);
    }

    protected function resolveSessionUserId(Request $request): ?int
    {
        $userId = $request->user()?->id;
        if (is_numeric($userId)) {
            return (int) $userId;
        }

        $authId = auth()->id();
        if (is_numeric($authId)) {
            return (int) $authId;
        }

        foreach (['user_id', 'id', 'auth_user_id'] as $sessionKey) {
            $sessionValue = $request->session()->get($sessionKey);
            if (is_numeric($sessionValue)) {
                return (int) $sessionValue;
            }
        }

        return null;
    }

    protected function ensureDefaultBemUkmAccount(): void
    {
        if (!Schema::hasTable('organizations') || !Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return;
        }

        $organizationRow = DB::table('organizations')
            ->select(['id', 'name', 'shortname'])
            ->whereRaw('LOWER(name) = ?', ['bem unklab'])
            ->orWhereRaw('LOWER(shortname) = ?', ['bem'])
            ->first();

        if (!$organizationRow) {
            $organizationId = DB::table('organizations')->insertGetId([
                'name' => 'BEM UNKLAB',
                'shortname' => 'bem',
                'description' => 'Badan Eksekutif Mahasiswa UNKLAB',
                'status' => $this->organizationActiveStatus(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $organizationId = (int) $organizationRow->id;
        }

        $query = DB::table('kemahasiswaan_ukm_accounts')
            ->select(['id', 'organization_id', 'name', 'status'])
            ->whereRaw('LOWER(email) = ?', ['bem@unklab.ac.id']);

        if (Schema::hasColumn('kemahasiswaan_ukm_accounts', 'password_hash')) {
            $query->addSelect('password_hash');
        }

        $existingBemAccount = $query->first();

        if (!$existingBemAccount) {
            DB::table('kemahasiswaan_ukm_accounts')->insert([
                'organization_id' => $organizationId,
                'user_id' => null,
                'name' => 'Pengurus BEM UNKLAB',
                'email' => 'bem@unklab.ac.id',
                'status' => $this->defaultAccountStatus(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return;
        }

        $updates = [];
        if (empty($existingBemAccount->organization_id)) {
            $updates['organization_id'] = $organizationId;
        }
        if (empty($existingBemAccount->name)) {
            $updates['name'] = 'Pengurus BEM UNKLAB';
        }
        if (($existingBemAccount->status ?? '') !== $this->defaultAccountStatus()) {
            $updates['status'] = $this->defaultAccountStatus();
        }

        if (!empty($updates)) {
            $updates['updated_at'] = now();
            DB::table('kemahasiswaan_ukm_accounts')
                ->where('id', $existingBemAccount->id)
                ->update($updates);
        }
    }

    // ============ Status & Label Helpers ============

    protected function statusLabel(string $domain, string $code): string
    {
        $map = [
            'account' => ['active' => 'Aktif', 'inactive' => 'Nonaktif'],
            'publish' => [
                'draft' => 'Draft',
                'scheduled' => 'Dijadwalkan',
                'published' => 'Dipublikasikan',
                'archived' => 'Arsip',
            ],
            'review' => [
                'pending' => 'Menunggu Review',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                'revision' => 'Perlu Revisi',
            ],
        ];

        $normalizedCode = Str::lower($code);
        return $map[$domain][$normalizedCode] ?? Str::title(str_replace('_', ' ', $normalizedCode));
    }

    protected function accountStatusCodes(): array
    {
        $codes = $this->referenceService->getStatusOptions('ukm_account_status_map');
        return !empty($codes) ? $codes : ['active', 'inactive'];
    }

    protected function defaultAccountStatus(): string
    {
        $codes = $this->referenceService->getStatusOptions('ukm_account_status_map');
        return in_array('active', $codes, true) ? 'active' : ($codes[0] ?? 'active');
    }

    protected function inactiveAccountStatus(): string
    {
        $codes = $this->referenceService->getStatusOptions('ukm_account_status_map');
        return in_array('inactive', $codes, true) ? 'inactive' : ($codes[1] ?? 'inactive');
    }

    protected function organizationActiveStatus(): string
    {
        $payload = $this->referenceService->getPayload('organization_active_status', 'active');
        $value = trim((string) ($payload['value'] ?? ''));
        return $value !== '' ? $value : 'active';
    }

    protected function getNotificationCounter(): int
    {
        $total = 0;

        if (Schema::hasTable('submissions')) {
            $statuses = $this->referenceService->getStatusOptions('pending_submission_status') ?: ['submitted', 'reviewing', 'revised'];
            $total += (int) DB::table('submissions')->whereIn('status', $statuses)->count();
        }

        if (Schema::hasTable('reports')) {
            $statuses = $this->referenceService->getStatusOptions('pending_report_status') ?: ['submitted', 'reviewing', 'revision_needed'];
            $total += (int) DB::table('reports')->whereIn('status', $statuses)->count();
        }

        if (Schema::hasTable('kemahasiswaan_announcements')) {
            $statuses = $this->referenceService->getStatusOptions('pending_email_review_status') ?: ['pending', 'revision'];
            $total += (int) DB::table('kemahasiswaan_announcements')->whereIn('email_review_status', $statuses)->count();
        }

        return $total;
    }
}
