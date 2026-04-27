<?php

namespace App\Http\Controllers\Kemahasiswaan;

use App\Http\Controllers\Controller;
use App\Services\Organization\OrganizationAccountSyncAction;
use App\Services\Organization\OrganizationProfileResolver;
use App\Services\ReferenceValueService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Validator;
use Illuminate\View\View;

class OrganizationAdminController extends Controller
{
    use KemahasiswaanControllerTrait;

    private OrganizationProfileResolver $profileResolver;
    private OrganizationAccountSyncAction $accountSync;

    public function __construct(
        OrganizationProfileResolver $profileResolver,
        OrganizationAccountSyncAction $accountSync,
        ReferenceValueService $referenceService,
    ) {
        $this->profileResolver = $profileResolver;
        $this->accountSync = $accountSync;
        $this->referenceService = $referenceService; // for trait usage
    }

    public function organisasiIndex(): View
    {
        $this->ensureDefaultBemUkmAccount();
        $this->ensureOrganizationPengurusAccounts();

        $organizationDirectory = $this->getOrganizationDirectory();
        $organizationCollection = collect($organizationDirectory);
        $organizationSummary = [
            'total' => $organizationCollection->count(),
            'ukm_umum' => $organizationCollection
                ->where('type', 'UKM')
                ->where('level', '!=', 'Fakultas')
                ->count(),
            'ukm_level_universitas' => $organizationCollection
                ->where('type', 'UKM')
                ->where('level', 'Universitas')
                ->count(),
            'ukm_level_fakultas' => $organizationCollection
                ->where('type', 'UKM')
                ->where('level', 'Fakultas')
                ->count(),
            'categories' => collect($organizationDirectory)->pluck('category')->filter()->unique()->count(),
        ];

        return view('portal.kemahasiswaan.organisasi', [
            'ukmAccounts' => $this->getAkunUKM(),
            'accountActivityLogs' => $this->getActivityLogs(),
            'organizations' => $this->getOrganizations(),
            'organizationDirectory' => $organizationDirectory,
            'organizationSummary' => $organizationSummary,
            'headerNotificationCount' => $this->getNotificationCounter(),
        ]);
    }

    public function storeOrganisasi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120|unique:organizations,name',
            'shortname' => 'nullable|string|max:40|unique:organizations,shortname',
            'category' => 'nullable|string|max:80',
            'type' => 'nullable|in:BEM,UKM',
            'level' => 'nullable|in:Universitas,Fakultas,Umum',
            'field' => 'nullable|string|max:120',
            'advisor' => 'required|string|max:255',
            'leader_name' => 'required|string|max:120',
            'description' => 'required|string|max:1000',
            'email' => 'nullable|email|max:120',
            'phone' => 'nullable|string|max:30',
            'account_email' => 'required|email|max:120|unique:kemahasiswaan_ukm_accounts,email|unique:users,email',
            'account_password' => 'required|string|min:6|max:40',
        ]);

        $resolvedProfile = $this->profileResolver->resolve($validated['name'], [
            'shortname' => $validated['shortname'] ?? null,
            'category' => $validated['category'] ?? null,
            'type' => $validated['type'] ?? null,
            'level' => $validated['level'] ?? null,
            'field' => $validated['field'] ?? null,
        ]);

        $shortname = trim((string) ($validated['shortname'] ?? ''));
        if ($shortname === '') {
            $shortname = $this->generateUniqueShortname((string) $validated['name']);
        }

        $insertPayload = [
            'name' => $validated['name'],
            'shortname' => $shortname,
            'description' => $validated['description'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'profile_status' => 'incomplete',
            'status' => $this->organizationActiveStatus(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('organizations', 'category')) {
            $insertPayload['category'] = $resolvedProfile['category'];
        }

        if (Schema::hasColumn('organizations', 'type')) {
            $insertPayload['type'] = $resolvedProfile['type'];
        }

        if (Schema::hasColumn('organizations', 'level')) {
            $insertPayload['level'] = $resolvedProfile['level'];
        }

        if (Schema::hasColumn('organizations', 'field')) {
            $insertPayload['field'] = $resolvedProfile['field'];
        }

        if (Schema::hasColumn('organizations', 'advisor')) {
            $insertPayload['advisor'] = trim((string) ($validated['advisor'] ?? ''));
        }

        if (Schema::hasColumn('organizations', 'leader_name')) {
            $insertPayload['leader_name'] = trim((string) ($validated['leader_name'] ?? ''));
        }

        $orgId = DB::table('organizations')->insertGetId($insertPayload);

        $this->accountSync->sync(
            $orgId,
            (string) $validated['name'],
            $shortname,
            (string) ($validated['account_email'] ?? ''),
            (string) ($validated['account_password'] ?? ''),
            (string) ($validated['phone'] ?? ''),
            (string) ($validated['leader_name'] ?? '')
        );

        return back()->with('success', 'Organisasi baru dan akun pengurus berhasil ditambahkan.');
    }

    public function updateOrganisasi(Request $request, int $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:120|unique:organizations,name,' . $id,
            'shortname' => 'required|string|max:40|unique:organizations,shortname,' . $id,
            'category' => 'nullable|string|max:80',
            'type' => 'nullable|in:BEM,UKM',
            'level' => 'nullable|in:Universitas,Fakultas,Umum',
            'field' => 'nullable|string|max:120',
            'advisor' => 'required|string|max:255',
            'leader_name' => 'required|string|max:120',
            'description' => 'nullable|string|max:1000',
            'email' => 'nullable|email|max:120',
            'phone' => 'nullable|string|max:30',
            'status' => 'required|in:active,inactive,suspended',
            'account_email' => 'nullable|email|max:120',
            'account_password' => 'nullable|string|min:6|max:40',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $exists = DB::table('organizations')->where('id', $id)->exists();
        if (!$exists) {
            return back()->with('error', 'Organisasi tidak ditemukan.');
        }

        $updatePayload = [
            'name' => $validated['name'],
            'shortname' => $validated['shortname'],
            'description' => $validated['description'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('organizations', 'category')) {
            $updatePayload['category'] = $validated['category'] ?? null;
        }

        if (Schema::hasColumn('organizations', 'type')) {
            $updatePayload['type'] = $validated['type'] ?? null;
        }

        if (Schema::hasColumn('organizations', 'level')) {
            $updatePayload['level'] = $validated['level'] ?? null;
        }

        if (Schema::hasColumn('organizations', 'field')) {
            $updatePayload['field'] = $validated['field'] ?? null;
        }

        if (Schema::hasColumn('organizations', 'advisor')) {
            $updatePayload['advisor'] = trim((string) ($validated['advisor'] ?? ''));
        }

        if (Schema::hasColumn('organizations', 'leader_name')) {
            $updatePayload['leader_name'] = trim((string) ($validated['leader_name'] ?? ''));
        }

        DB::table('organizations')
            ->where('id', $id)
            ->update($updatePayload);

        $accountEmail = trim((string) ($validated['account_email'] ?? ''));

        if ($accountEmail === '') {
            $currentAccountEmail = DB::table('kemahasiswaan_ukm_accounts')
                ->where('organization_id', $id)
                ->value('email');

            $accountEmail = trim((string) $currentAccountEmail);
        }

        if ($accountEmail !== '') {
            $this->accountSync->sync(
                $id,
                $validated['name'],
                $validated['shortname'],
                $accountEmail,
                (string) ($validated['account_password'] ?? ''),
                (string) ($validated['phone'] ?? ''),
                (string) ($validated['leader_name'] ?? '')
            );
        }

        return back()->with('success', 'Data organisasi dan akun pengurus berhasil diperbarui.');
    }

    public function deactivateOrganisasi(int $id): RedirectResponse
    {
        $organization = DB::table('organizations')->select(['id', 'name', 'status'])->where('id', $id)->first();

        if (!$organization) {
            return back()->with('error', 'Organisasi tidak ditemukan.');
        }

        if ((string) $organization->status === 'inactive') {
            return back()->with('success', 'Organisasi sudah nonaktif.');
        }

        DB::table('organizations')
            ->where('id', $id)
            ->update([
                'status' => 'inactive',
                'updated_at' => now(),
            ]);

        if (Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            DB::table('kemahasiswaan_ukm_accounts')
                ->where('organization_id', $id)
                ->update([
                    'status' => 'inactive',
                    'updated_at' => now(),
                ]);
        }

        return back()->with('success', 'Organisasi berhasil dinonaktifkan.');
    }

    // ============ Private Helpers ============

    private function ensureDefaultBemUkmAccount(): void
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
            $insertPayload = [
                'organization_id' => $organizationId,
                'user_id' => null,
                'name' => 'Pengurus BEM UNKLAB',
                'email' => 'bem@unklab.ac.id',
                'status' => $this->defaultAccountStatus(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('kemahasiswaan_ukm_accounts')->insert($insertPayload);

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

    private function ensureOrganizationPengurusAccounts(): void
    {
        if (!Schema::hasTable('organizations') || !Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return;
        }

        $organizationQuery = DB::table('organizations')
            ->select(['id', 'name', 'shortname', 'phone'])
            ->where('status', $this->organizationActiveStatus())
            ->orderBy('id');

        if (Schema::hasColumn('organizations', 'leader_name')) {
            $organizationQuery->addSelect('leader_name');
        }

        $organizations = $organizationQuery->get();

        foreach ($organizations as $organization) {
            $organizationId = (int) ($organization->id ?? 0);

            if ($organizationId <= 0) {
                continue;
            }

            $currentEmail = DB::table('kemahasiswaan_ukm_accounts')
                ->where('organization_id', $organizationId)
                ->value('email');

            $email = trim((string) $currentEmail);
            if ($email === '') {
                $email = (string) ($organization->shortname ?? '') . '@unklab.ac.id';
                if ($email === '@unklab.ac.id' || $email === '') {
                    $email = 'org' . $organizationId . '@unklab.ac.id';
                }
            }

            $this->accountSync->sync(
                $organizationId,
                (string) $organization->name,
                (string) $organization->shortname,
                $email,
                '',
                (string) ($organization->phone ?? ''),
                (string) ($organization->leader_name ?? '')
            );
        }
    }







    private function getOrganizationDirectory(): array
    {
        if (!Schema::hasTable('organizations')) {
            return [];
        }

        $organizationQuery = DB::table('organizations')
            ->select(['id', 'name', 'shortname', 'description', 'email', 'phone', 'status'])
            ->where('status', $this->organizationActiveStatus())
            ->orderBy('name');

        if (Schema::hasColumn('organizations', 'category')) {
            $organizationQuery->addSelect('category');
        }

        if (Schema::hasColumn('organizations', 'type')) {
            $organizationQuery->addSelect('type');
        }

        if (Schema::hasColumn('organizations', 'level')) {
            $organizationQuery->addSelect('level');
        }

        if (Schema::hasColumn('organizations', 'field')) {
            $organizationQuery->addSelect('field');
        }

        if (Schema::hasColumn('organizations', 'advisor')) {
            $organizationQuery->addSelect('advisor');
        }

        if (Schema::hasColumn('organizations', 'leader_name')) {
            $organizationQuery->addSelect('leader_name');
        }

        $organizationRows = $organizationQuery->get();

        if ($organizationRows->isEmpty()) {
            return [];
        }

        $organizationIds = $organizationRows
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $accountsByOrganization = [];
        if (Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            $accountRows = DB::table('kemahasiswaan_ukm_accounts')
                ->whereIn('organization_id', $organizationIds)
                ->select(['id', 'organization_id', 'name', 'email', 'status'])
                ->get();

            foreach ($accountRows as $accountRow) {
                $accountsByOrganization[(int) $accountRow->organization_id] = [
                    'account_id' => (int) $accountRow->id,
                    'account_name' => $accountRow->name,
                    'account_email' => $accountRow->email,
                    'account_status' => $accountRow->status,
                ];
            }
        }

        return $organizationRows
            ->map(function ($organizationRow) use ($accountsByOrganization) {
                $organizationId = (int) $organizationRow->id;
                $account = $accountsByOrganization[$organizationId] ?? null;

                return [
                    'id' => $organizationId,
                    'name' => $organizationRow->name,
                    'shortname' => $organizationRow->shortname,
                    'description' => $organizationRow->description,
                    'email' => $organizationRow->email,
                    'phone' => $organizationRow->phone,
                    'category' => $organizationRow->category ?? null,
                    'type' => $organizationRow->type ?? null,
                    'level' => $organizationRow->level ?? null,
                    'field' => $organizationRow->field ?? null,
                    'advisor' => $organizationRow->advisor ?? null,
                    'leader_name' => $organizationRow->leader_name ?? null,
                    'account' => $account,
                ];
            })
            ->values()
            ->all();
    }

    private function generateUniqueShortname(string $organizationName): string
    {
        $words = preg_split('/\s+/', trim($organizationName)) ?: [];
        $base = collect($words)
            ->map(fn ($word) => substr($word, 0, 1))
            ->join('');

        $base = Str::upper(substr((string) $base, 0, 8));
        if ($base === '') {
            $base = 'ORG';
        }

        $candidate = $base;
        $counter = 1;
        while (DB::table('organizations')->whereRaw('LOWER(shortname) = ?', [Str::lower($candidate)])->exists()) {
            $candidate = $base . $counter;
            $counter++;
        }

        return $candidate;
    }

}
