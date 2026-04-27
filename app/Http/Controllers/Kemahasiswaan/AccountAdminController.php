<?php

namespace App\Http\Controllers\Kemahasiswaan;

use App\Http\Controllers\Controller;
use App\Services\ReferenceValueService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AccountAdminController extends Controller
{
    use KemahasiswaanControllerTrait;

    public function __construct(ReferenceValueService $referenceService)
    {
        $this->referenceService = $referenceService;
    }

    public function akunIndex(): View
    {
        $this->ensureDefaultBemUkmAccount();

        return view('portal.kemahasiswaan.akun', [
            'ukmAccounts' => $this->getAkunUKM(),
            'accountActivityLogs' => $this->getActivityLogs(),
            'organizations' => $this->getOrganizations(),
            'headerNotificationCount' => $this->getNotificationCounter(),
        ]);
    }

    public function storeAkunUKM(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:120|unique:kemahasiswaan_ukm_accounts,email',
            'organization_id' => 'required|integer|exists:organizations,id',
            'jabatan' => 'required|string|max:80',
        ]);

        $organizationId = (int) $validated['organization_id'];
        $organizationAlreadyHasAccount = DB::table('kemahasiswaan_ukm_accounts')
            ->where('organization_id', $organizationId)
            ->exists();

        if ($organizationAlreadyHasAccount) {
            return back()
                ->withErrors(['organization_id' => 'Setiap organisasi hanya boleh memiliki satu akun utama.'])
                ->withInput();
        }

        $temporaryPassword = Str::upper(Str::random(10));

        $insertPayload = [
            'organization_id' => $organizationId,
            'user_id' => null,
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'position' => $validated['jabatan'],
            'status' => $this->defaultAccountStatus(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('kemahasiswaan_ukm_accounts', 'password_hash')) {
            $insertPayload['password_hash'] = Hash::make($temporaryPassword);
        }

        $accountId = DB::table('kemahasiswaan_ukm_accounts')->insertGetId($insertPayload);

        $account = $this->findAkunUKM($accountId);
        $this->appendActivityLog([
            'ukm_account_id' => $account['id'] ?? null,
            'organization_id' => $account['organization_id'] ?? null,
            'action' => 'Tambah Akun UKM',
            'description' => 'Akun UKM baru dibuat oleh Departemen Kemahasiswaan.',
        ]);

        return back()->with('success', 'Akun UKM berhasil ditambahkan. Password sementara: ' . $temporaryPassword);
    }

    public function updateAkunUKM(Request $request, int $id): RedirectResponse
    {
        $statusOptions = $this->accountStatusCodes();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:120|unique:kemahasiswaan_ukm_accounts,email,' . $id,
            'organization_id' => 'required|integer|exists:organizations,id',
            'jabatan' => 'required|string|max:80',
            'status' => 'required|in:' . implode(',', $statusOptions),
        ]);

        $exists = DB::table('kemahasiswaan_ukm_accounts')->where('id', $id)->exists();
        if (!$exists) {
            return back()->with('error', 'Akun UKM tidak ditemukan.');
        }

        $organizationId = (int) $validated['organization_id'];
        $organizationAlreadyHasAnotherAccount = DB::table('kemahasiswaan_ukm_accounts')
            ->where('organization_id', $organizationId)
            ->where('id', '!=', $id)
            ->exists();

        if ($organizationAlreadyHasAnotherAccount) {
            return back()
                ->withErrors(['organization_id' => 'Setiap organisasi hanya boleh memiliki satu akun utama.'])
                ->withInput();
        }

        DB::table('kemahasiswaan_ukm_accounts')
            ->where('id', $id)
            ->update([
                'organization_id' => $organizationId,
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'position' => $validated['jabatan'],
                'status' => $validated['status'],
                'updated_at' => now(),
            ]);

        $account = $this->findAkunUKM($id);
        $this->appendActivityLog([
            'ukm_account_id' => $account['id'] ?? null,
            'organization_id' => $account['organization_id'] ?? null,
            'action' => 'Ubah Data Akun UKM',
            'description' => 'Data akun UKM diperbarui oleh Departemen Kemahasiswaan.',
        ]);

        return back()->with('success', 'Data akun UKM berhasil diperbarui.');
    }

    public function resetPasswordAkunUKM(int $id): RedirectResponse
    {
        $exists = DB::table('kemahasiswaan_ukm_accounts')->where('id', $id)->exists();
        if (!$exists) {
            return back()->with('error', 'Akun UKM tidak ditemukan.');
        }

        $temporaryPassword = Str::upper(Str::random(10));

        $updatePayload = [
            'last_password_reset_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('kemahasiswaan_ukm_accounts', 'password_hash')) {
            $updatePayload['password_hash'] = Hash::make($temporaryPassword);
        }

        DB::table('kemahasiswaan_ukm_accounts')
            ->where('id', $id)
            ->update($updatePayload);

        $account = $this->findAkunUKM($id);
        $this->appendActivityLog([
            'ukm_account_id' => $account['id'] ?? null,
            'organization_id' => $account['organization_id'] ?? null,
            'action' => 'Reset Password Akun UKM',
            'description' => 'Password akun UKM di-reset oleh Departemen Kemahasiswaan.',
        ]);

        return back()->with('success', 'Password sementara akun: ' . $temporaryPassword);
    }

    public function deactivateAkunUKM(int $id): RedirectResponse
    {
        $exists = DB::table('kemahasiswaan_ukm_accounts')->where('id', $id)->exists();
        if (!$exists) {
            return back()->with('error', 'Akun UKM tidak ditemukan.');
        }

        DB::table('kemahasiswaan_ukm_accounts')
            ->where('id', $id)
            ->update([
                'status' => $this->inactiveAccountStatus(),
                'updated_at' => now(),
            ]);

        $account = $this->findAkunUKM($id);
        $this->appendActivityLog([
            'ukm_account_id' => $account['id'] ?? null,
            'organization_id' => $account['organization_id'] ?? null,
            'action' => 'Non-aktifkan Akun UKM',
            'description' => 'Akun UKM dinonaktifkan oleh Departemen Kemahasiswaan.',
        ]);

        return back()->with('success', 'Akun UKM berhasil dinonaktifkan.');
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











}
