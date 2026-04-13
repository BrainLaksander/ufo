<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KemahasiswaanWorkflowController extends Controller
{
    public function organisasiIndex(): View
    {
        $this->ensureDefaultBemUkmAccount();

        return view('pages.portal.kemahasiswaan.organisasi', [
            'ukmAccounts' => $this->getAkunUKM(),
            'accountActivityLogs' => $this->getActivityLogs(),
            'organizations' => $this->getOrganizations(),
        ]);
    }

    public function pengumumanIndex(): View
    {
        $this->ensureDefaultBemUkmAccount();

        $pengumuman = $this->getPengumuman();

        $reviewQueue = array_values(array_filter(
            $pengumuman,
            fn (array $item) => in_array($item['email_review_code'], ['pending', 'revision'], true)
        ));

        return view('pages.portal.kemahasiswaan.pengumuman', [
            'workflowPengumuman' => $pengumuman,
            'emailReviewQueue' => $reviewQueue,
            'ukmAccounts' => $this->getAkunUKM(),
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

        $temporaryPassword = Str::upper(Str::random(10));

        $insertPayload = [
            'organization_id' => (int) $validated['organization_id'],
            'user_id' => null,
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'position' => $validated['jabatan'],
            'status' => 'active',
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
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:120|unique:kemahasiswaan_ukm_accounts,email,' . $id,
            'organization_id' => 'required|integer|exists:organizations,id',
            'jabatan' => 'required|string|max:80',
            'status' => 'required|in:active,inactive',
        ]);

        $exists = DB::table('kemahasiswaan_ukm_accounts')->where('id', $id)->exists();
        if (!$exists) {
            return back()->with('error', 'Akun UKM tidak ditemukan.');
        }

        DB::table('kemahasiswaan_ukm_accounts')
            ->where('id', $id)
            ->update([
                'organization_id' => (int) $validated['organization_id'],
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
                'status' => 'inactive',
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

    public function storePengumuman(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:140',
            'kategori' => 'required|string|max:60',
            'target' => 'required|string|max:100',
            'ringkasan' => 'required|string|max:240',
            'publish_at' => 'nullable|date',
            'ukm_account_id' => 'required|integer|exists:kemahasiswaan_ukm_accounts,id',
        ]);

        $publishAt = !empty($validated['publish_at'])
            ? Carbon::parse($validated['publish_at'])->startOfDay()
            : null;

        $publishStatus = $publishAt ? 'scheduled' : 'draft';

        $announcementId = DB::table('kemahasiswaan_announcements')->insertGetId([
            'ukm_account_id' => (int) $validated['ukm_account_id'],
            'title' => $validated['judul'],
            'category' => $validated['kategori'],
            'target_audience' => $validated['target'],
            'summary' => $validated['ringkasan'],
            'content' => null,
            'publish_at' => $publishAt,
            'publish_status' => $publishStatus,
            'email_review_status' => 'pending',
            'email_review_note' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $announcement = DB::table('kemahasiswaan_announcements')->where('id', $announcementId)->first();
        $account = $announcement ? $this->findAkunUKM((int) $announcement->ukm_account_id) : null;

        $this->appendActivityLog([
            'ukm_account_id' => $account['id'] ?? null,
            'organization_id' => $account['organization_id'] ?? null,
            'action' => 'Membuat Pengumuman',
            'description' => 'Draft pengumuman "' . $validated['judul'] . '" dibuat dan menunggu review email.',
        ]);

        return back()->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function reviewIzinPengumumanEmail(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'decision' => 'required|in:setujui,tolak,revisi',
            'catatan' => 'nullable|string|max:220',
        ]);

        if (in_array($validated['decision'], ['tolak', 'revisi'], true) && empty(trim((string) ($validated['catatan'] ?? '')))) {
            return back()->with('error', 'Catatan wajib diisi jika review email ditolak atau revisi.');
        }

        $announcement = DB::table('kemahasiswaan_announcements')->where('id', $id)->first();
        if (!$announcement) {
            return back()->with('error', 'Data pengumuman tidak ditemukan.');
        }

        $reviewStatus = [
            'setujui' => 'approved',
            'tolak' => 'rejected',
            'revisi' => 'revision',
        ][$validated['decision']];

        $publishStatus = $announcement->publish_status;
        if ($validated['decision'] === 'setujui') {
            $publishStatus = !empty($announcement->publish_at) ? 'scheduled' : 'published';
        }
        if (in_array($validated['decision'], ['tolak', 'revisi'], true)) {
            $publishStatus = 'draft';
        }

        DB::table('kemahasiswaan_announcements')
            ->where('id', $id)
            ->update([
                'publish_status' => $publishStatus,
                'email_review_status' => $reviewStatus,
                'email_review_note' => trim((string) ($validated['catatan'] ?? '')) ?: null,
                'reviewed_by' => $this->resolveSessionUserId($request),
                'reviewed_at' => now(),
                'updated_at' => now(),
            ]);

        $account = $this->findAkunUKM((int) $announcement->ukm_account_id);
        $this->appendActivityLog([
            'ukm_account_id' => $account['id'] ?? null,
            'organization_id' => $account['organization_id'] ?? null,
            'action' => 'Review Izin Pengumuman ke Email',
            'description' => 'Review email pengumuman "' . $announcement->title . '" disimpan oleh Departemen Kemahasiswaan.',
        ]);

        return back()->with('success', 'Review izin pengumuman ke email berhasil disimpan.');
    }

    private function ensureDefaultBemUkmAccount(): void
    {
        if (!Schema::hasTable('organizations') || !Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return;
        }

        $passwordHashColumnExists = Schema::hasColumn('kemahasiswaan_ukm_accounts', 'password_hash');
        $defaultBemPasswordHash = $this->resolveDefaultBemPasswordHash();

        $organizationRow = DB::table('organizations')
            ->select(['id', 'name', 'shortname'])
            ->whereRaw('LOWER(name) = ?', ['bem unklab'])
            ->orWhereRaw('LOWER(shortname) = ?', ['bem'])
            ->first();

        if (!$organizationRow) {
            $organizationId = DB::table('organizations')->insertGetId([
                'name' => 'BEM UNKLAB',
                'shortname' => 'BEM',
                'description' => 'Badan Eksekutif Mahasiswa UNKLAB.',
                'profile_status' => 'incomplete',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $organizationId = (int) $organizationRow->id;
        }

        $query = DB::table('kemahasiswaan_ukm_accounts')
            ->select(['id', 'organization_id', 'name', 'status'])
            ->whereRaw('LOWER(email) = ?', ['bem@example.com']);

        if ($passwordHashColumnExists) {
            $query->addSelect('password_hash');
        }

        $existingBemAccount = $query->first();

        if (!$existingBemAccount) {
            $insertPayload = [
                'organization_id' => $organizationId,
                'user_id' => null,
                'name' => 'Pengurus BEM UNKLAB',
                'email' => 'bem@example.com',
                'position' => 'Ketua BEM',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($passwordHashColumnExists && !empty($defaultBemPasswordHash)) {
                $insertPayload['password_hash'] = $defaultBemPasswordHash;
            }

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

        if (($existingBemAccount->status ?? '') !== 'active') {
            $updates['status'] = 'active';
        }

        if (
            $passwordHashColumnExists
            && empty($existingBemAccount->password_hash)
            && !empty($defaultBemPasswordHash)
        ) {
            $updates['password_hash'] = $defaultBemPasswordHash;
        }

        if (!empty($updates)) {
            $updates['updated_at'] = now();

            DB::table('kemahasiswaan_ukm_accounts')
                ->where('id', $existingBemAccount->id)
                ->update($updates);
        }
    }

    private function getAkunUKM(): array
    {
        $rows = DB::table('kemahasiswaan_ukm_accounts as akun')
            ->leftJoin('organizations as org', 'org.id', '=', 'akun.organization_id')
            ->select([
                'akun.id',
                'akun.organization_id',
                'akun.name',
                'akun.email',
                'akun.position',
                'akun.status',
                'akun.last_login_at',
                'akun.last_password_reset_at',
                'org.name as organization_name',
            ])
            ->orderByDesc('akun.id')
            ->get();

        return $rows->map(function ($row) {
            return [
                'id' => (int) $row->id,
                'organization_id' => $row->organization_id ? (int) $row->organization_id : null,
                'nama' => $row->name,
                'email' => $row->email,
                'organisasi' => $row->organization_name ?? '-',
                'jabatan' => $row->position ?? '-',
                'status_code' => $row->status,
                'status_label' => $row->status === 'active' ? 'Aktif' : 'Nonaktif',
                'last_login_at' => $row->last_login_at,
                'last_password_reset_at' => $row->last_password_reset_at,
            ];
        })->all();
    }

    private function getPengumuman(): array
    {
        $publishLabels = [
            'draft' => 'Draft',
            'scheduled' => 'Terjadwal',
            'published' => 'Terpublikasi',
            'archived' => 'Arsip',
        ];

        $reviewLabels = [
            'pending' => 'Menunggu Review',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revision' => 'Perlu Revisi',
        ];

        $rows = DB::table('kemahasiswaan_announcements as ann')
            ->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'ann.ukm_account_id')
            ->leftJoin('organizations as org', 'org.id', '=', 'akun.organization_id')
            ->select([
                'ann.id',
                'ann.ukm_account_id',
                'ann.title',
                'ann.category',
                'ann.target_audience',
                'ann.summary',
                'ann.publish_at',
                'ann.publish_status',
                'ann.email_review_status',
                'ann.email_review_note',
                'ann.created_at',
                'ann.reviewed_at',
                'akun.name as account_name',
                'org.name as organization_name',
            ])
            ->orderByDesc('ann.id')
            ->get();

        return $rows->map(function ($row) use ($publishLabels, $reviewLabels) {
            return [
                'id' => (int) $row->id,
                'ukm_account_id' => $row->ukm_account_id ? (int) $row->ukm_account_id : null,
                'ukm_account_name' => $row->account_name ?? 'Tidak diketahui',
                'organisasi' => $row->organization_name ?? '-',
                'judul' => $row->title,
                'kategori' => $row->category,
                'target' => $row->target_audience,
                'ringkasan' => $row->summary,
                'publish_at' => $row->publish_at,
                'status_code' => $row->publish_status,
                'status' => $publishLabels[$row->publish_status] ?? Str::title(str_replace('_', ' ', (string) $row->publish_status)),
                'email_review_code' => $row->email_review_status,
                'email_review_status' => $reviewLabels[$row->email_review_status] ?? Str::title(str_replace('_', ' ', (string) $row->email_review_status)),
                'email_review_note' => $row->email_review_note,
                'created_at' => $row->created_at,
                'reviewed_at' => $row->reviewed_at,
            ];
        })->all();
    }

    private function getActivityLogs(): array
    {
        $rows = DB::table('kemahasiswaan_activity_logs as log')
            ->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'log.ukm_account_id')
            ->leftJoin('organizations as org', 'org.id', '=', 'log.organization_id')
            ->select([
                'log.id',
                'log.action',
                'log.description',
                'log.created_at',
                'akun.name as account_name',
                'org.name as organization_name',
            ])
            ->orderByDesc('log.id')
            ->limit(200)
            ->get();

        return $rows->map(function ($row) {
            return [
                'id' => (int) $row->id,
                'account_name' => $row->account_name ?? 'Tidak diketahui',
                'organization' => $row->organization_name ?? '-',
                'action' => $row->action,
                'description' => $row->description,
                'created_at' => $row->created_at,
            ];
        })->all();
    }

    private function getOrganizations(): array
    {
        return DB::table('organizations')
            ->select(['id', 'name', 'shortname'])
            ->orderBy('name')
            ->get()
            ->map(fn ($row) => [
                'id' => (int) $row->id,
                'name' => $row->name,
                'shortname' => $row->shortname,
            ])
            ->all();
    }

    private function findAkunUKM(int $id): ?array
    {
        $row = DB::table('kemahasiswaan_ukm_accounts as akun')
            ->leftJoin('organizations as org', 'org.id', '=', 'akun.organization_id')
            ->select([
                'akun.id',
                'akun.organization_id',
                'akun.name',
                'org.name as organization_name',
            ])
            ->where('akun.id', $id)
            ->first();

        if (!$row) {
            return null;
        }

        return [
            'id' => (int) $row->id,
            'organization_id' => $row->organization_id ? (int) $row->organization_id : null,
            'name' => $row->name,
            'organization_name' => $row->organization_name,
        ];
    }

    private function appendActivityLog(array $payload): void
    {
        DB::table('kemahasiswaan_activity_logs')->insert([
            'ukm_account_id' => $payload['ukm_account_id'] ?? null,
            'organization_id' => $payload['organization_id'] ?? null,
            'action' => $payload['action'] ?? 'Aktivitas',
            'description' => $payload['description'] ?? '',
            'metadata' => null,
            'ip_address' => request()->ip(),
            'user_agent' => Str::limit((string) request()->userAgent(), 255, ''),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function resolveSessionUserId(Request $request): ?int
    {
        $email = (string) data_get($request->session()->get('user'), 'email', '');
        if ($email === '') {
            return null;
        }

        $user = DB::table('users')->select('id')->where('email', $email)->first();

        return $user ? (int) $user->id : null;
    }

    private function resolveDefaultBemPasswordHash(): ?string
    {
        $demoPasswordHash = (string) data_get(config('auth.demo_accounts'), 'pengurus.password_hash', '');
        if ($demoPasswordHash !== '') {
            return $demoPasswordHash;
        }

        $plainDefault = (string) config('auth.default_ukm_password', '');
        if ($plainDefault === '') {
            return null;
        }

        return Hash::make($plainDefault);
    }
}
