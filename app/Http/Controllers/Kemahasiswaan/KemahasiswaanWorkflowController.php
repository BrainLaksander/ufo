<?php

namespace App\Http\Controllers\Kemahasiswaan;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KemahasiswaanWorkflowController extends Controller
{
    private array $referenceCache = [];

    public function organisasiIndex(): View
    {
        $this->ensureDefaultBemUkmAccount();
        $this->ensureOrganizationPengurusAccounts();

        $organizationDirectory = $this->getOrganizationDirectory();
        $organizationSummary = [
            'total' => count($organizationDirectory),
            'bem' => collect($organizationDirectory)->where('type', 'BEM')->count(),
            'ukm' => collect($organizationDirectory)->where('type', 'UKM')->count(),
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
            'level' => 'nullable|in:Universitas,Fakultas',
            'field' => 'nullable|string|max:120',
            'description' => 'required|string|max:1000',
            'email' => 'nullable|email|max:120',
            'phone' => 'nullable|string|max:30',
            'account_email' => 'required|email|max:120|unique:kemahasiswaan_ukm_accounts,email|unique:users,email',
            'account_password' => 'required|string|min:6|max:40',
        ]);

        $resolvedProfile = $this->resolveOrganizationProfile($validated['name'], [
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

        $orgId = DB::table('organizations')->insertGetId($insertPayload);

        $this->syncOrganizationPengurusAccount(
            $orgId,
            (string) $validated['name'],
            $shortname,
            (string) ($validated['account_email'] ?? ''),
            (string) ($validated['account_password'] ?? ''),
            (string) ($validated['phone'] ?? '')
        );

        return back()->with('success', 'Organisasi baru dan akun pengurus berhasil ditambahkan.');
    }

    private function resolveOrganizationProfile(string $name, array $input = []): array
    {
        $normalizedName = Str::lower(trim($name));
        $normalizedShortname = Str::lower(trim((string) ($input['shortname'] ?? '')));
        $nameBlob = trim($normalizedName . ' ' . $normalizedShortname);

        $hasBemSignals = Str::contains($nameBlob, [
            'bem',
            'badan eksekutif mahasiswa',
            'senat mahasiswa',
            'dpm',
            'dewan perwakilan mahasiswa',
        ]);

        $hasFacultySignals = Str::contains($nameBlob, [
            'fakultas',
            'himpunan mahasiswa',
            'hima',
            'himaf',
            'himatika',
            'himti',
            'himsi',
            'fkep',
            'fkip',
            'filsafat',
            'fakultas kedokteran',
        ]);

        $resolvedType = Str::upper(trim((string) ($input['type'] ?? '')));
        if (!in_array($resolvedType, ['BEM', 'UKM'], true)) {
            $resolvedType = $hasBemSignals ? 'BEM' : 'UKM';
        }

        $resolvedLevel = trim((string) ($input['level'] ?? ''));
        if (!in_array($resolvedLevel, ['Universitas', 'Fakultas'], true)) {
            $resolvedLevel = $hasFacultySignals ? 'Fakultas' : 'Universitas';
        }

        $resolvedCategory = trim((string) ($input['category'] ?? ''));
        if ($resolvedCategory === '') {
            if ($resolvedType === 'BEM') {
                $resolvedCategory = 'BEM';
            } elseif (Str::contains($nameBlob, ['kerohanian', 'rohani', 'ministry', 'pelayanan'])) {
                $resolvedCategory = 'Kerohanian';
            } elseif (Str::contains($nameBlob, ['paduan suara', 'choir', 'musik', 'seni'])) {
                $resolvedCategory = 'Minat & Bakat';
            } elseif (Str::contains($nameBlob, ['kedaerahan', 'ikm', 'ikatan daerah', 'maluku', 'minahasa'])) {
                $resolvedCategory = 'Kedaerahan';
            } elseif (Str::contains($nameBlob, ['teknologi', 'tech', 'it', 'developer', 'coding', 'ai', 'robot', 'pasar modal', 'kspm'])) {
                $resolvedCategory = 'Akademik & Teknologi';
            } elseif ($resolvedLevel === 'Fakultas') {
                $resolvedCategory = 'UKM Umum';
            } else {
                $resolvedCategory = 'UKM Umum';
            }
        }

        $resolvedField = trim((string) ($input['field'] ?? ''));
        if ($resolvedField === '') {
            if ($resolvedType === 'BEM') {
                $resolvedField = 'Pemerintahan Mahasiswa';
            } elseif ($resolvedCategory === 'Kerohanian') {
                $resolvedField = 'Kerohanian';
            } elseif ($resolvedCategory === 'Minat & Bakat') {
                $resolvedField = Str::contains($nameBlob, ['musik', 'choir', 'paduan suara'])
                    ? 'Paduan Suara'
                    : (Str::contains($nameBlob, ['seni', 'art', 'creative']) ? 'Seni & Kreativitas' : 'Minat dan Bakat');
            } elseif ($resolvedCategory === 'Kedaerahan') {
                $resolvedField = 'Organisasi Kedaerahan';
            } elseif ($resolvedCategory === 'Akademik & Teknologi') {
                if (Str::contains($nameBlob, ['pasar modal', 'kspm'])) {
                    $resolvedField = 'Pasar Modal';
                } elseif (Str::contains($nameBlob, ['ai', 'robot', 'coding', 'developer'])) {
                    $resolvedField = 'Inovasi Digital';
                } else {
                    $resolvedField = 'Akademik & Teknologi';
                }
            } else {
                $resolvedField = 'Bidang Umum';
            }
        }

        return [
            'category' => $resolvedCategory,
            'type' => $resolvedType,
            'level' => $resolvedLevel,
            'field' => Str::limit($resolvedField, 120, ''),
        ];
    }

    public function updateOrganisasi(Request $request, int $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:120|unique:organizations,name,' . $id,
            'shortname' => 'required|string|max:40|unique:organizations,shortname,' . $id,
            'category' => 'nullable|string|max:80',
            'type' => 'nullable|in:BEM,UKM',
            'level' => 'nullable|in:Universitas,Fakultas',
            'field' => 'nullable|string|max:120',
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
                ->withInput()
                ->with('organization_edit_id', $id);
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
            $currentAccountId = DB::table('kemahasiswaan_ukm_accounts')
                ->where('organization_id', $id)
                ->value('id');

            $duplicateAccountEmail = DB::table('kemahasiswaan_ukm_accounts')
                ->whereRaw('LOWER(email) = ?', [Str::lower($accountEmail)])
                ->when($currentAccountId, fn ($query) => $query->where('id', '!=', (int) $currentAccountId))
                ->exists();

            if ($duplicateAccountEmail) {
                return back()->with('error', 'Email akun sudah digunakan oleh organisasi lain.');
            }

            $duplicateUserEmail = DB::table('users')
                ->whereRaw('LOWER(email) = ?', [Str::lower($accountEmail)])
                ->when($currentAccountId, function ($query) use ($currentAccountId) {
                    $linkedUserId = DB::table('kemahasiswaan_ukm_accounts')->where('id', (int) $currentAccountId)->value('user_id');

                    if ($linkedUserId) {
                        $query->where('id', '!=', (int) $linkedUserId);
                    }
                })
                ->exists();

            if ($duplicateUserEmail) {
                return back()->with('error', 'Email akun sudah digunakan pada tabel users.');
            }

            $this->syncOrganizationPengurusAccount(
                $id,
                (string) $validated['name'],
                (string) ($validated['shortname'] ?? ''),
                $accountEmail,
                (string) ($validated['account_password'] ?? ''),
                (string) ($validated['phone'] ?? '')
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
                    'status' => $this->inactiveAccountStatus(),
                    'updated_at' => now(),
                ]);
        }

        return back()->with('success', 'Organisasi berhasil dinonaktifkan.');
    }

    public function pengumumanIndex(): View
    {
        $this->ensureDefaultBemUkmAccount();

        $pengumuman = $this->getPengumuman();
        $pendingEmailReviewStatuses = $this->pendingEmailReviewStatuses();

        $reviewQueue = array_values(array_filter(
            $pengumuman,
            fn (array $item) => in_array($item['email_review_code'], $pendingEmailReviewStatuses, true)
        ));

        return view('portal.kemahasiswaan.pengumuman', [
            'workflowPengumuman' => $pengumuman,
            'emailReviewQueue' => $reviewQueue,
            'ukmAccounts' => $this->getAkunUKM(),
            'ui' => $this->buildPengumumanUiText(),
            'headerNotificationCount' => $this->getNotificationCounter(),
        ]);
    }

    public function dashboard(): View
    {
        $organizationActiveStatus = $this->organizationActiveStatus();
        $ongoingEventStatuses = $this->ongoingEventStatuses();
        $pendingSubmissionStatuses = $this->pendingSubmissionStatuses();
        $pendingReportStatuses = $this->pendingReportStatuses();

        $totalOrganizations = 0;
        if (Schema::hasTable('organizations')) {
            $totalOrganizations = (int) DB::table('organizations')
                ->where('status', $organizationActiveStatus)
                ->count();
        }

        $totalOngoingEvents = 0;
        if (Schema::hasTable('events')) {
            $totalOngoingEvents = (int) DB::table('events')
                ->whereIn('status', $ongoingEventStatuses)
                ->count();
        }

        $pendingSubmissions = 0;
        if (Schema::hasTable('submissions')) {
            $pendingSubmissions = (int) DB::table('submissions')
                ->whereIn('status', $pendingSubmissionStatuses)
                ->count();
        }

        $pendingReports = 0;
        if (Schema::hasTable('reports')) {
            $pendingReports = (int) DB::table('reports')
                ->whereIn('status', $pendingReportStatuses)
                ->count();
        }

        $monthlyActivity = $this->buildMonthlyActivity();
        $chartMax = max(1, (int) collect($monthlyActivity)->max('value'));

        $stats = [
            [
                'label' => 'Total Organisasi Aktif',
                'value' => (string) $totalOrganizations,
                'icon' => 'bi-buildings',
                'tone' => 'primary',
            ],
            [
                'label' => 'Total Kegiatan Berjalan',
                'value' => (string) $totalOngoingEvents,
                'icon' => 'bi-card-list',
                'tone' => 'primary',
            ],
            [
                'label' => 'Total Kegiatan Menunggu Persetujuan',
                'value' => (string) $pendingSubmissions,
                'icon' => 'bi-clock-history',
                'tone' => 'warning',
            ],
            [
                'label' => 'Total Laporan Belum Direview',
                'value' => (string) $pendingReports,
                'icon' => 'bi-clipboard-check',
                'tone' => 'primary',
            ],
        ];

        $recentAnnouncements = collect($this->getPengumuman())
            ->take(6)
            ->map(function (array $item) {
                $dateSource = $item['publish_at'] ?? $item['created_at'] ?? null;

                return [
                    'judul' => (string) ($item['judul'] ?? '-'),
                    'tanggal' => $dateSource ? Carbon::parse((string) $dateSource)->translatedFormat('d M Y') : '-',
                    'status' => (string) ($item['status'] ?? '-'),
                ];
            })
            ->values()
            ->all();

        return view('portal.kemahasiswaan.dashboard', [
            'stats' => $stats,
            'monthlyActivity' => $monthlyActivity,
            'upcomingEvents' => $this->getUpcomingEvents(),
            'chartMax' => $chartMax,
            'recentAnnouncements' => $recentAnnouncements,
            'ui' => $this->buildDashboardUiText(),
            'headerNotificationCount' => $this->getNotificationCounter(),
        ]);
    }

    public function notifikasiIndex(Request $request): View
    {
        $selectedFilter = Str::lower((string) $request->query('jenis', 'semua'));

        $allNotifications = $this->getSystemNotifications();
        $types = $this->notificationTypeOptions();
        $allowedFilters = collect($types)
            ->pluck('value')
            ->filter(fn ($value) => trim((string) $value) !== '')
            ->values()
            ->all();

        if (!in_array($selectedFilter, $allowedFilters, true)) {
            $selectedFilter = 'semua';
        }

        $filteredNotifications = $selectedFilter === 'semua'
            ? $allNotifications
            : array_values(array_filter(
                $allNotifications,
                fn (array $item) => ($item['jenis'] ?? '') === $selectedFilter
            ));

        $counts = collect($allNotifications)
            ->countBy('jenis')
            ->all();

        $types = collect($types)
            ->map(function (array $type) use ($counts, $allNotifications) {
                return [
                    'value' => $type['value'],
                    'label' => $type['label'],
                    'count' => $type['value'] === 'semua'
                        ? count($allNotifications)
                        : (int) ($counts[$type['value']] ?? 0),
                ];
            })
            ->values()
            ->all();

        return view('portal.kemahasiswaan.notifikasi', [
            'notifikasiItems' => $filteredNotifications,
            'notifikasiFilter' => $selectedFilter,
            'notifikasiTypes' => $types,
            'notifikasiSummary' => [
                'total' => count($allNotifications),
                'belum_dibaca' => collect($allNotifications)
                    ->where('status', 'belum_dibaca')
                    ->count(),
            ],
            'ui' => $this->buildNotifikasiUiText(),
            'headerNotificationCount' => $this->getNotificationCounter(),
        ]);
    }

    public function kontakPengurusIndex(): View
    {
        $kontakPengurus = $this->getKontakPengurusUkm();

        return view('portal.kemahasiswaan.kontak', [
            'kontakPengurus' => $kontakPengurus,
            'contactSummary' => [
                'total_kontak' => count($kontakPengurus),
                'dengan_email' => collect($kontakPengurus)
                    ->filter(fn (array $item) => trim((string) ($item['email'] ?? '')) !== '')
                    ->count(),
                'dengan_kontak' => collect($kontakPengurus)
                    ->filter(fn (array $item) => trim((string) ($item['kontak'] ?? '')) !== '')
                    ->count(),
                'total_organisasi' => collect($kontakPengurus)
                    ->pluck('organisasi')
                    ->filter(fn ($name) => trim((string) $name) !== '')
                    ->unique()
                    ->count(),
            ],
            'ui' => $this->buildKontakUiText(),
            'headerNotificationCount' => $this->getNotificationCounter(),
        ]);
    }

    public function kalenderKegiatanIndex(): View
    {
        $kalenderKegiatan = $this->getKalenderKegiatanKampus();
        $now = now();

        return view('portal.kemahasiswaan.kalender', [
            'kalenderKegiatan' => $kalenderKegiatan,
            'organizations' => $this->getOrganizations(),
            'kalenderSummary' => [
                'total' => count($kalenderKegiatan),
                'bulan_ini' => collect($kalenderKegiatan)
                    ->filter(function (array $item) use ($now) {
                        if (empty($item['tanggal_raw'])) {
                            return false;
                        }

                        $start = Carbon::parse((string) $item['tanggal_raw'])->startOfDay();
                        $end = Carbon::parse((string) ($item['tanggal_selesai_raw'] ?? $item['tanggal_raw']))->endOfDay();
                        $monthStart = $now->copy()->startOfMonth()->startOfDay();
                        $monthEnd = $now->copy()->endOfMonth()->endOfDay();

                        return $start->lte($monthEnd) && $end->gte($monthStart);
                    })
                    ->count(),
                '7_hari' => collect($kalenderKegiatan)
                    ->filter(function (array $item) use ($now) {
                        if (empty($item['tanggal_raw'])) {
                            return false;
                        }

                        $start = Carbon::parse((string) $item['tanggal_raw'])->startOfDay();
                        $end = Carbon::parse((string) ($item['tanggal_selesai_raw'] ?? $item['tanggal_raw']))->endOfDay();
                        $rangeStart = $now->copy()->startOfDay();
                        $rangeEnd = $now->copy()->addDays(7)->endOfDay();

                        return $start->lte($rangeEnd) && $end->gte($rangeStart);
                    })
                    ->count(),
            ],
            'ui' => $this->buildKalenderUiText(),
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

    public function storePengumuman(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:140',
            'kategori' => 'required|string|max:60',
            'target' => 'required|string|max:100',
            'konten' => 'required|string|max:10000',
            'publish_at' => 'nullable|date',
            'ukm_account_id' => 'nullable|integer|exists:kemahasiswaan_ukm_accounts,id',
            'submit_action' => 'required|in:draft,publish_now',
        ]);

        $publishAt = !empty($validated['publish_at'])
            ? Carbon::parse($validated['publish_at'])
            : null;

        $submitAction = (string) ($validated['submit_action'] ?? 'draft');
        $publishStatus = 'draft';
        $emailReviewStatus = $this->defaultPendingEmailReviewStatus();
        $reviewedBy = null;
        $reviewedAt = null;

        if ($submitAction === 'publish_now') {
            $publishStatus = ($publishAt && $publishAt->isFuture()) ? 'scheduled' : 'published';
            $emailReviewStatus = 'approved';
            $reviewedBy = $this->resolveSessionUserId($request);
            $reviewedAt = now();
        }

        $rawContent = trim((string) ($validated['konten'] ?? ''));
        $normalizedContent = preg_replace('/\s+/u', ' ', strip_tags($rawContent)) ?? $rawContent;
        $summary = Str::limit(trim($normalizedContent), 240, '...');

        $accountId = !empty($validated['ukm_account_id'])
            ? (int) $validated['ukm_account_id']
            : (int) (DB::table('kemahasiswaan_ukm_accounts')
                ->where('status', $this->defaultAccountStatus())
                ->orderBy('id')
                ->value('id') ?? 0);

        $announcementId = DB::table('kemahasiswaan_announcements')->insertGetId([
            'ukm_account_id' => $accountId > 0 ? $accountId : null,
            'title' => $validated['judul'],
            'category' => $validated['kategori'],
            'target_audience' => $validated['target'],
            'summary' => $summary,
            'content' => $rawContent,
            'publish_at' => $publishAt,
            'publish_status' => $publishStatus,
            'submit_action' => $submitAction,
            'email_review_status' => $emailReviewStatus,
            'email_review_note' => null,
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => $reviewedAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $announcement = DB::table('kemahasiswaan_announcements')->where('id', $announcementId)->first();
        $account = $announcement ? $this->findAkunUKM((int) $announcement->ukm_account_id) : null;

        $this->appendActivityLog([
            'ukm_account_id' => $account['id'] ?? null,
            'organization_id' => $account['organization_id'] ?? null,
            'action' => 'Membuat Pengumuman',
            'description' => $submitAction === 'publish_now'
                ? 'Pengumuman "' . $validated['judul'] . '" dipublikasikan dari modal Kemahasiswaan.'
                : 'Draft pengumuman "' . $validated['judul'] . '" disimpan dari modal Kemahasiswaan.',
        ]);

        return back()->with('success', 'Pengumuman berhasil disimpan.');
    }

    public function reviewIzinPengumumanEmail(Request $request, int $id): RedirectResponse
    {
        $decisionMap = $this->reviewAnnouncementDecisionMap();

        $validated = $request->validate([
            'decision' => 'required|in:' . implode(',', array_keys($decisionMap)),
            'catatan' => 'nullable|string|max:220',
        ]);

        $decisionConfig = $decisionMap[$validated['decision']] ?? [];
        if (($decisionConfig['requires_note'] ?? false) && empty(trim((string) ($validated['catatan'] ?? '')))) {
            return back()->with('error', 'Catatan wajib diisi jika review email ditolak atau revisi.');
        }

        $announcement = DB::table('kemahasiswaan_announcements')->where('id', $id)->first();
        if (!$announcement) {
            return back()->with('error', 'Data pengumuman tidak ditemukan.');
        }

        $reviewStatus = (string) ($decisionConfig['review_status'] ?? $this->defaultPendingEmailReviewStatus());

        $publishStatus = $announcement->publish_status;
        if ($validated['decision'] === 'setujui') {
            if (!empty($announcement->publish_at)) {
                $publishStatus = (string) ($decisionConfig['publish_status_scheduled'] ?? 'scheduled');
            } else {
                $publishStatus = (string) ($decisionConfig['publish_status'] ?? 'published');
            }
        } else {
            $publishStatus = (string) ($decisionConfig['publish_status'] ?? 'draft');
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

    private function buildMonthlyActivity(): array
    {
        $months = collect(range(0, 11))
            ->map(fn ($offset) => now()->startOfYear()->addMonths($offset));

        $counts = collect();
        if (Schema::hasTable('events')) {
            $isSqlite = DB::getDriverName() === 'sqlite';
            $monthFunction = $isSqlite ? "strftime('%m', start_date)" : "MONTH(start_date)";
            $castType = $isSqlite ? 'INTEGER' : 'UNSIGNED';
            $monthExpression = "CAST($monthFunction AS $castType)";
            
            $counts = DB::table('events')
                ->selectRaw("$monthExpression as month_number, COUNT(*) as total")
                ->whereYear('start_date', now()->year)
                ->groupByRaw($monthExpression)
                ->pluck('total', 'month_number');
        }

        return $months->map(function (Carbon $month) use ($counts) {
            $monthNumber = (int) $month->format('n');

            return [
                'month' => $month->translatedFormat('M'),
                'value' => (int) ($counts[$monthNumber] ?? 0),
            ];
        })->all();
    }

    private function getUpcomingEvents(): array
    {
        if (!Schema::hasTable('events')) {
            return [];
        }

        $rows = DB::table('events as evt')
            ->leftJoin('organizations as org', 'org.id', '=', 'evt.organization_id')
            ->select([
                'evt.name',
                'evt.start_date',
                'evt.status',
                'org.shortname as organization_shortname',
                'org.name as organization_name',
            ])
            ->whereDate('evt.start_date', '>=', now()->toDateString())
            ->orderBy('evt.start_date')
            ->limit(6)
            ->get();

        return $rows->map(function ($row) {
            $status = Str::lower((string) $row->status);
            [$label, $tone] = $this->eventDashboardStatus($status);

            $title = (string) $row->name;
            if (!empty($row->organization_shortname)) {
                $title .= ' - ' . (string) $row->organization_shortname;
            } elseif (!empty($row->organization_name)) {
                $title .= ' - ' . (string) $row->organization_name;
            }

            return [
                'title' => $title,
                'date' => Carbon::parse($row->start_date)->translatedFormat('d F Y'),
                'status' => $label,
                'tone' => $tone,
            ];
        })->all();
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

        if ($passwordHashColumnExists) {
            $query->addSelect('password_hash');
        }

        $existingBemAccount = $query->first();

        if (!$existingBemAccount) {
            $insertPayload = [
                'organization_id' => $organizationId,
                'user_id' => null,
                'name' => 'Pengurus BEM UNKLAB',
                'email' => 'bem@unklab.ac.id',
                'position' => 'Ketua BEM',
                'status' => $this->defaultAccountStatus(),
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

        if (($existingBemAccount->status ?? '') !== $this->defaultAccountStatus()) {
            $updates['status'] = $this->defaultAccountStatus();
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

    private function ensureOrganizationPengurusAccounts(): void
    {
        if (!Schema::hasTable('organizations') || !Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return;
        }

        $organizations = DB::table('organizations')
            ->select(['id', 'name', 'shortname', 'phone'])
            ->where('status', $this->organizationActiveStatus())
            ->orderBy('id')
            ->get();

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
                $email = $this->generateUniqueOrganizationAccountEmail(
                    (string) ($organization->shortname ?? ''),
                    (string) ($organization->name ?? ''),
                    $organizationId
                );
            }

            $this->syncOrganizationPengurusAccount(
                $organizationId,
                (string) ($organization->name ?? ''),
                (string) ($organization->shortname ?? ''),
                $email,
                '',
                (string) ($organization->phone ?? '')
            );
        }
    }

    private function syncOrganizationPengurusAccount(
        int $organizationId,
        string $organizationName,
        string $organizationShortname,
        string $accountEmail,
        string $plainPassword,
        string $phone = ''
    ): void {
        if (!Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            return;
        }

        $accountDisplay = trim($organizationShortname) !== '' ? trim($organizationShortname) : trim($organizationName);
        if ($accountDisplay === '') {
            $accountDisplay = 'Organisasi';
        }

        $accountName = 'Ketua ' . $accountDisplay;
        $passwordToUse = trim($plainPassword) !== ''
            ? trim($plainPassword)
            : (trim((string) config('auth.default_ukm_password', '')) !== ''
                ? trim((string) config('auth.default_ukm_password', ''))
                : 'Pengurus12345');

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
                'position' => 'Ketua / Pengurus',
                'updated_at' => now(),
            ];

            if (empty($existingAccount->status)) {
                $updates['status'] = $this->defaultAccountStatus();
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
                'position' => 'Ketua / Pengurus',
                'status' => $this->defaultAccountStatus(),
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
                ->update([
                    'user_id' => $userId,
                    'updated_at' => now(),
                ]);
        }

        $this->syncKetuaMember($organizationId, $accountName, $accountEmail, $phone);
    }

    private function generateUniqueOrganizationAccountEmail(string $shortname, string $name, int $organizationId): string
    {
        $source = trim($shortname) !== '' ? $shortname : $name;
        $baseLocalPart = strtolower((string) preg_replace('/[^a-z0-9]+/i', '', $source));

        if ($baseLocalPart === '') {
            $baseLocalPart = 'org' . $organizationId;
        }

        $email = $baseLocalPart . '@unklab.ac.id';
        $attempt = 1;

        while (
            DB::table('kemahasiswaan_ukm_accounts')->whereRaw('LOWER(email) = ?', [Str::lower($email)])->exists()
            || (Schema::hasTable('users') && DB::table('users')->whereRaw('LOWER(email) = ?', [Str::lower($email)])->exists())
        ) {
            $email = $baseLocalPart . $attempt . '@unklab.ac.id';
            $attempt++;
        }

        return $email;
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
                return null;
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

    private function getAkunUKM(): array
    {
        $statusLabels = $this->statusLabelMap('ukm_account_status_map', [
            'active' => 'Aktif',
            'inactive' => 'Nonaktif',
        ]);

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

        return $rows->map(function ($row) use ($statusLabels) {
            $statusCode = Str::lower((string) $row->status);

            return [
                'id' => (int) $row->id,
                'organization_id' => $row->organization_id ? (int) $row->organization_id : null,
                'nama' => $row->name,
                'email' => $row->email,
                'organisasi' => $row->organization_name ?? '-',
                'jabatan' => $row->position ?? '-',
                'status_code' => $row->status,
                'status_label' => $statusLabels[$statusCode] ?? Str::title(str_replace('_', ' ', $statusCode)),
                'last_login_at' => $row->last_login_at,
                'last_password_reset_at' => $row->last_password_reset_at,
            ];
        })->all();
    }

    private function getPengumuman(): array
    {
        $publishLabels = $this->statusLabelMap('announcement_publish_status_map', [
            'draft' => 'Draft',
            'scheduled' => 'Terjadwal',
            'published' => 'Terpublikasi',
            'archived' => 'Arsip',
        ]);

        $reviewLabels = $this->statusLabelMap('announcement_email_review_status_map', [
            'pending' => 'Menunggu Review',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revision' => 'Perlu Revisi',
        ]);

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

        $organizationRows = $organizationQuery->get();

        if ($organizationRows->isEmpty()) {
            return [];
        }

        $organizationIds = $organizationRows
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $leadersByOrganization = [];

        if (Schema::hasTable('members')) {
            $memberRows = DB::table('members')
                ->select(['organization_id', 'name', 'position', 'status'])
                ->whereIn('organization_id', $organizationIds)
                ->where('status', 'aktif')
                ->orderByRaw("CASE WHEN LOWER(position) = 'ketua' THEN 0 ELSE 1 END")
                ->orderBy('id')
                ->get();

            foreach ($memberRows as $memberRow) {
                $organizationId = (int) ($memberRow->organization_id ?? 0);

                if ($organizationId <= 0 || isset($leadersByOrganization[$organizationId])) {
                    continue;
                }

                $leadersByOrganization[$organizationId] = (string) ($memberRow->name ?? '-');
            }
        }

        $accountsByOrganization = [];
        $accountNamesByOrganization = [];
        if (Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            $accountRows = DB::table('kemahasiswaan_ukm_accounts')
                ->select(['organization_id', 'name', 'email'])
                ->whereIn('organization_id', $organizationIds)
                ->get();

            foreach ($accountRows as $accountRow) {
                $organizationId = (int) ($accountRow->organization_id ?? 0);
                if ($organizationId > 0) {
                    $accountsByOrganization[$organizationId] = (string) ($accountRow->email ?? '');
                    $accountNamesByOrganization[$organizationId] = (string) ($accountRow->name ?? '');
                }
            }
        }

        return $organizationRows
            ->map(function ($organizationRow) use ($leadersByOrganization, $accountsByOrganization) {
                $name = (string) ($organizationRow->name ?? '');
                $shortname = (string) ($organizationRow->shortname ?? '');
                $description = (string) ($organizationRow->description ?? '');
                $textBlob = Str::lower(trim($name . ' ' . $shortname . ' ' . $description));

                $storedCategory = '';
                if (property_exists($organizationRow, 'category')) {
                    $storedCategory = trim((string) ($organizationRow->category ?? ''));
                }

                $storedType = '';
                if (property_exists($organizationRow, 'type')) {
                    $storedType = trim((string) ($organizationRow->type ?? ''));
                }

                $storedLevel = '';
                if (property_exists($organizationRow, 'level')) {
                    $storedLevel = trim((string) ($organizationRow->level ?? ''));
                }

                $storedField = '';
                if (property_exists($organizationRow, 'field')) {
                    $storedField = trim((string) ($organizationRow->field ?? ''));
                }

                $type = Str::contains($textBlob, 'bem') ? 'BEM' : 'UKM';

                $facultyKeywords = [
                    'fakultas',
                    'fkip',
                    'fik',
                    'fkep',
                    'faperta',
                    'ubs',
                    'filsafat',
                ];

                $scope = Str::contains($textBlob, $facultyKeywords) ? 'Fakultas' : 'Universitas';

                if ($storedType !== '') {
                    $type = Str::upper($storedType);
                }

                if ($storedLevel !== '') {
                    $scope = Str::title(Str::lower($storedLevel));
                }

                $category = 'UKM Umum';
                if ($type === 'BEM') {
                    $category = 'BEM';
                } elseif (Str::contains($textBlob, ['choir', 'vocs', 'suara', 'musik'])) {
                    $category = 'Musik & Paduan Suara';
                } elseif (Str::contains($textBlob, ['ministry', 'pilgrims', 'kerohanian'])) {
                    $category = 'Kerohanian';
                } elseif (Str::contains($textBlob, ['mapala', 'creative', 'event organizer', 'eo '])) {
                    $category = 'Minat & Bakat';
                } elseif (Str::contains($textBlob, ['ikm', 'ikmapap', 'malut', 'minahasa', 'kedaerahan', 'tou '])) {
                    $category = 'Kedaerahan';
                } elseif (Str::contains($textBlob, ['uvics', 'gdsc', 'kspm', 'developer', 'pasar modal', 'ai ', 'teknologi'])) {
                    $category = 'Akademik & Teknologi';
                }

                if ($storedCategory !== '') {
                    $category = $storedCategory;
                }

                $field = 'Bidang Belum Ditentukan';
                if ($type === 'BEM') {
                    $field = 'Pemerintahan Mahasiswa';
                } elseif ($category === 'Musik & Paduan Suara') {
                    $field = 'Paduan Suara';
                } elseif ($category === 'Kerohanian') {
                    $field = 'Kerohanian';
                } elseif ($category === 'Kedaerahan') {
                    $field = 'Organisasi Kedaerahan';
                } elseif ($category === 'Akademik & Teknologi') {
                    if (Str::contains($textBlob, ['pasar modal', 'kspm'])) {
                        $field = 'Pasar Modal';
                    } elseif (Str::contains($textBlob, ['gdsc', 'developer'])) {
                        $field = 'Developer Community';
                    } elseif (Str::contains($textBlob, ['uvics', 'ai'])) {
                        $field = 'AI & Inovasi Digital';
                    } else {
                        $field = 'Akademik & Teknologi';
                    }
                } elseif ($category === 'Minat & Bakat') {
                    if (Str::contains($textBlob, ['mapala'])) {
                        $field = 'Pecinta Alam';
                    } elseif (Str::contains($textBlob, ['creative'])) {
                        $field = 'Desain & Kreativitas';
                    } elseif (Str::contains($textBlob, ['event organizer', 'eo '])) {
                        $field = 'Event Organizer';
                    } else {
                        $field = 'Minat & Bakat';
                    }
                } elseif (trim($description) !== '') {
                    $field = Str::limit(trim($description), 48, '...');
                }

                if ($storedField !== '') {
                    $field = $storedField;
                }

                $organizationId = (int) ($organizationRow->id ?? 0);
                $leader = $leadersByOrganization[$organizationId]
                    ?? ($accountNamesByOrganization[$organizationId] ?? '-');

                return [
                    'id' => $organizationId,
                    'name' => trim($name) !== '' ? $name : '-',
                    'shortname' => trim($shortname) !== '' ? $shortname : '-',
                    'scope' => $scope,
                    'category' => $category,
                    'type' => $type,
                    'field' => $field,
                    'leader' => $leader,
                    'description' => $description,
                    'email' => (string) ($organizationRow->email ?? ''),
                    'phone' => (string) ($organizationRow->phone ?? ''),
                    'status' => (string) ($organizationRow->status ?? 'active'),
                    'account_email' => $accountsByOrganization[$organizationId] ?? '',
                ];
            })
            ->values()
            ->all();
    }

    private function getKontakPengurusUkm(): array
    {
        return collect($this->getAkunUKM())
            ->map(function (array $item) {
                return [
                    'nama' => (string) ($item['nama'] ?? '-'),
                    'organisasi' => (string) ($item['organisasi'] ?? '-'),
                    'jabatan' => (string) ($item['jabatan'] ?? '-'),
                    'kontak' => '-',
                    'email' => (string) ($item['email'] ?? '-'),
                    'status_code' => (string) ($item['status_code'] ?? 'inactive'),
                    'status_label' => (string) ($item['status_label'] ?? '-'),
                ];
            })
            ->values()
            ->all();
    }

    private function getKalenderKegiatanKampus(): array
    {
        if (Schema::hasTable('kemahasiswaan_schedules')) {
            $hasCategory = Schema::hasColumn('kemahasiswaan_schedules', 'category');
            $hasDescription = Schema::hasColumn('kemahasiswaan_schedules', 'description');

            $query = DB::table('kemahasiswaan_schedules as sch')
                ->leftJoin('organizations as org', 'org.id', '=', 'sch.organization_id')
                ->select([
                    'sch.id',
                    'sch.title',
                    'sch.start_at',
                    'sch.end_at',
                    'sch.location',
                    'sch.status',
                    'org.name as organization_name',
                ])
                ->orderBy('sch.start_at')
                ->limit(200);

            if ($hasCategory) {
                $query->addSelect('sch.category');
            } else {
                $query->selectRaw('NULL as category');
            }

            if ($hasDescription) {
                $query->addSelect('sch.description');
            } else {
                $query->selectRaw('NULL as description');
            }

            $rows = $query->get();

            return $rows->map(function ($row) {
                $statusCode = Str::lower((string) ($row->status ?? 'planned'));
                $statusLabel = match ($statusCode) {
                    'planned' => 'Terjadwal',
                    'ongoing' => 'Berlangsung',
                    'completed' => 'Selesai',
                    default => Str::title(str_replace('_', ' ', $statusCode)),
                };

                $startDate = $row->start_at ? Carbon::parse((string) $row->start_at) : null;
                $endDate = $row->end_at ? Carbon::parse((string) $row->end_at) : $startDate;
                $category = trim((string) ($row->category ?? ''));

                if ($category === '') {
                    $category = 'org';
                }

                return [
                    'id' => (int) $row->id,
                    'judul' => (string) ($row->title ?? '-'),
                    'kategori' => $category,
                    'deskripsi' => (string) ($row->description ?? ''),
                    'organisasi' => (string) ($row->organization_name ?? '-'),
                    'tanggal' => $startDate ? $startDate->translatedFormat('d F Y') : '-',
                    'tanggal_raw' => $startDate?->toDateString(),
                    'tanggal_selesai_raw' => $endDate?->toDateString(),
                    'lokasi' => (string) ($row->location ?? '-'),
                    'status_code' => $statusCode,
                    'status_label' => $statusLabel,
                ];
            })->all();
        }

        if (!Schema::hasTable('events')) {
            return [];
        }

        $rows = DB::table('events as evt')
            ->leftJoin('organizations as org', 'org.id', '=', 'evt.organization_id')
            ->select([
                'evt.id',
                'evt.name',
                'evt.start_date',
                'evt.end_date',
                'evt.location',
                'evt.status',
                'org.name as organization_name',
            ])
            ->orderBy('evt.start_date')
            ->limit(200)
            ->get();

        return $rows->map(function ($row) {
            $statusCode = Str::lower((string) ($row->status ?? 'planned'));
            [$statusLabel] = $this->eventDashboardStatus($statusCode);
            $startDate = $row->start_date ? Carbon::parse((string) $row->start_date) : null;
            $endDate = $row->end_date ? Carbon::parse((string) $row->end_date) : $startDate;

            return [
                'id' => (int) $row->id,
                'judul' => (string) ($row->name ?? '-'),
                'kategori' => 'org',
                'organisasi' => (string) ($row->organization_name ?? '-'),
                'tanggal' => $startDate ? $startDate->translatedFormat('d F Y') : '-',
                'tanggal_raw' => $startDate?->toDateString(),
                'tanggal_selesai_raw' => $endDate?->toDateString(),
                'lokasi' => (string) ($row->location ?? '-'),
                'status_code' => $statusCode,
                'status_label' => $statusLabel,
            ];
        })->all();
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

    private function getNotificationCounter(): int
    {
        $total = 0;
        $pendingSubmissionStatuses = $this->pendingSubmissionStatuses();
        $pendingReportStatuses = $this->pendingReportStatuses();
        $pendingEmailReviewStatuses = $this->pendingEmailReviewStatuses();

        if (Schema::hasTable('submissions')) {
            $total += (int) DB::table('submissions')
                ->whereIn('status', $pendingSubmissionStatuses)
                ->count();
        }

        if (Schema::hasTable('reports')) {
            $total += (int) DB::table('reports')
                ->whereIn('status', $pendingReportStatuses)
                ->count();
        }

        if (Schema::hasTable('kemahasiswaan_announcements')) {
            $total += (int) DB::table('kemahasiswaan_announcements')
                ->whereIn('email_review_status', $pendingEmailReviewStatuses)
                ->count();
        }

        return $total;
    }

    private function getSystemNotifications(): array
    {
        $items = [];

        $submissionStatus = $this->statusLabelMap('submission_status_map', [
            'draft' => 'Draft',
            'submitted' => 'Diajukan',
            'reviewing' => 'Sedang Direview',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revised' => 'Revisi',
        ]);

        $reportStatus = $this->statusLabelMap('report_status_map', [
            'draft' => 'Draft',
            'submitted' => 'Diajukan',
            'reviewing' => 'Sedang Direview',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revision_needed' => 'Revisi',
        ]);

        $emailReviewStatus = $this->statusLabelMap('announcement_email_review_status_map', [
            'pending' => 'Menunggu Review',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revision' => 'Perlu Revisi',
        ]);

        $pendingSubmissionStatuses = $this->pendingSubmissionStatuses();
        $pendingReportStatuses = $this->pendingReportStatuses();
        $pendingEmailReviewStatuses = $this->pendingEmailReviewStatuses();

        if (Schema::hasTable('submissions')) {
            $rows = DB::table('submissions as sub')
                ->leftJoin('organizations as org', 'org.id', '=', 'sub.organization_id')
                ->select([
                    'sub.id',
                    'sub.title',
                    'sub.status',
                    'sub.created_at',
                    'sub.updated_at',
                    'org.name as organization_name',
                ])
                ->orderByDesc('sub.updated_at')
                ->limit(30)
                ->get();

            foreach ($rows as $row) {
                $statusCode = Str::lower((string) $row->status);
                $statusLabel = $submissionStatus[$statusCode] ?? Str::title(str_replace('_', ' ', $statusCode));
                $timestamp = $this->toTimestamp($row->updated_at ?: $row->created_at);

                $items[] = [
                    'id' => 'submission-' . (int) $row->id,
                    'jenis' => 'pengajuan',
                    'judul' => 'Update Pengajuan Kegiatan',
                    'pesan' => ($row->organization_name ?: 'Organisasi')
                        . ' mengajukan "' . Str::limit((string) $row->title, 80) . '".',
                    'status_label' => $statusLabel,
                    'status' => in_array($statusCode, $pendingSubmissionStatuses, true)
                        ? 'belum_dibaca'
                        : 'sudah_dibaca',
                    'waktu' => Carbon::createFromTimestamp($timestamp)->diffForHumans(),
                    'sort_time' => $timestamp,
                ];
            }
        }

        if (Schema::hasTable('reports')) {
            $rows = DB::table('reports as rep')
                ->leftJoin('organizations as org', 'org.id', '=', 'rep.organization_id')
                ->select([
                    'rep.id',
                    'rep.title',
                    'rep.status',
                    'rep.created_at',
                    'rep.updated_at',
                    'org.name as organization_name',
                ])
                ->orderByDesc('rep.updated_at')
                ->limit(30)
                ->get();

            foreach ($rows as $row) {
                $statusCode = Str::lower((string) $row->status);
                $statusLabel = $reportStatus[$statusCode] ?? Str::title(str_replace('_', ' ', $statusCode));
                $timestamp = $this->toTimestamp($row->updated_at ?: $row->created_at);

                $items[] = [
                    'id' => 'report-' . (int) $row->id,
                    'jenis' => 'laporan',
                    'judul' => 'Update Laporan Kegiatan',
                    'pesan' => ($row->organization_name ?: 'Organisasi')
                        . ' mengirim laporan "' . Str::limit((string) $row->title, 80) . '".',
                    'status_label' => $statusLabel,
                    'status' => in_array($statusCode, $pendingReportStatuses, true)
                        ? 'belum_dibaca'
                        : 'sudah_dibaca',
                    'waktu' => Carbon::createFromTimestamp($timestamp)->diffForHumans(),
                    'sort_time' => $timestamp,
                ];
            }
        }

        if (Schema::hasTable('kemahasiswaan_announcements')) {
            $rows = DB::table('kemahasiswaan_announcements as ann')
                ->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'ann.ukm_account_id')
                ->leftJoin('organizations as org', 'org.id', '=', 'akun.organization_id')
                ->select([
                    'ann.id',
                    'ann.title',
                    'ann.email_review_status',
                    'ann.updated_at',
                    'ann.created_at',
                    'org.name as organization_name',
                ])
                ->orderByDesc('ann.updated_at')
                ->limit(30)
                ->get();

            foreach ($rows as $row) {
                $statusCode = Str::lower((string) $row->email_review_status);
                $statusLabel = $emailReviewStatus[$statusCode] ?? Str::title(str_replace('_', ' ', $statusCode));
                $timestamp = $this->toTimestamp($row->updated_at ?: $row->created_at);

                $items[] = [
                    'id' => 'announcement-' . (int) $row->id,
                    'jenis' => 'pengumuman',
                    'judul' => 'Review Distribusi Pengumuman',
                    'pesan' => 'Pengumuman "' . Str::limit((string) $row->title, 80)
                        . '" dari ' . ($row->organization_name ?: 'Organisasi') . ' diperbarui.',
                    'status_label' => $statusLabel,
                    'status' => in_array($statusCode, $pendingEmailReviewStatuses, true)
                        ? 'belum_dibaca'
                        : 'sudah_dibaca',
                    'waktu' => Carbon::createFromTimestamp($timestamp)->diffForHumans(),
                    'sort_time' => $timestamp,
                ];
            }
        }

        if (Schema::hasTable('kemahasiswaan_activity_logs')) {
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
                ->orderByDesc('log.created_at')
                ->limit(30)
                ->get();

            foreach ($rows as $row) {
                $timestamp = $this->toTimestamp($row->created_at);
                $actor = $row->organization_name ?: ($row->account_name ?: 'Akun UKM');

                $items[] = [
                    'id' => 'activity-' . (int) $row->id,
                    'jenis' => 'akun',
                    'judul' => Str::title((string) $row->action),
                    'pesan' => $actor . ': ' . (string) ($row->description ?: 'Aktivitas baru tercatat.'),
                    'status_label' => 'Aktivitas',
                    'status' => 'sudah_dibaca',
                    'waktu' => Carbon::createFromTimestamp($timestamp)->diffForHumans(),
                    'sort_time' => $timestamp,
                ];
            }
        }

        if (Schema::hasTable('kemahasiswaan_schedules')) {
            $rows = DB::table('kemahasiswaan_schedules as sch')
                ->leftJoin('organizations as org', 'org.id', '=', 'sch.organization_id')
                ->select([
                    'sch.id',
                    'sch.title',
                    'sch.start_at',
                    'sch.location',
                    'org.name as organization_name',
                ])
                ->whereDate('sch.start_at', '>=', now()->subDays(7)->toDateString())
                ->orderByDesc('sch.start_at')
                ->limit(20)
                ->get();

            foreach ($rows as $row) {
                $timestamp = $this->toTimestamp($row->start_at);

                $items[] = [
                    'id' => 'schedule-' . (int) $row->id,
                    'jenis' => 'jadwal',
                    'judul' => 'Jadwal Kegiatan',
                    'pesan' => ($row->organization_name ?: 'Organisasi')
                        . ' menjadwalkan "' . Str::limit((string) $row->title, 80)
                        . '" di ' . (string) ($row->location ?: 'lokasi belum ditentukan') . '.',
                    'status_label' => 'Terjadwal',
                    'status' => 'sudah_dibaca',
                    'waktu' => Carbon::createFromTimestamp($timestamp)->diffForHumans(),
                    'sort_time' => $timestamp,
                ];
            }
        }

        usort($items, fn (array $a, array $b) => ($b['sort_time'] ?? 0) <=> ($a['sort_time'] ?? 0));

        return collect($items)
            ->take(80)
            ->map(function (array $item) {
                unset($item['sort_time']);

                return $item;
            })
            ->values()
            ->all();
    }

    private function toTimestamp(mixed $dateTime): int
    {
        if (empty($dateTime)) {
            return now()->timestamp;
        }

        return Carbon::parse((string) $dateTime)->timestamp;
    }

    private function notificationTypeOptions(): array
    {
        $default = [
            ['value' => 'semua', 'label' => 'Semua Notifikasi'],
            ['value' => 'pengajuan', 'label' => 'Pengajuan Kegiatan'],
            ['value' => 'laporan', 'label' => 'Laporan Masuk'],
            ['value' => 'pengumuman', 'label' => 'Review Pengumuman'],
            ['value' => 'akun', 'label' => 'Aktivitas Akun UKM'],
            ['value' => 'jadwal', 'label' => 'Jadwal Kegiatan'],
        ];

        $map = $this->getReferenceMap('kmh_notification_type_map');
        if (empty($map)) {
            return $default;
        }

        $items = [];
        foreach ($map as $code => $entry) {
            $value = trim((string) ($entry['payload']['value'] ?? $code));
            if ($value === '') {
                continue;
            }

            $items[] = [
                'value' => $value,
                'label' => trim((string) ($entry['label'] ?? '')) ?: Str::title(str_replace('_', ' ', $value)),
            ];
        }

        if (empty($items)) {
            return $default;
        }

        if (!collect($items)->contains(fn ($item) => ($item['value'] ?? '') === 'semua')) {
            array_unshift($items, ['value' => 'semua', 'label' => 'Semua Notifikasi']);
        }

        return $items;
    }

    private function statusLabelMap(string $domain, array $fallback): array
    {
        $labels = [];
        foreach ($this->getReferenceMap($domain) as $code => $entry) {
            $normalizedCode = Str::lower((string) $code);
            $label = trim((string) ($entry['label'] ?? ''));

            if ($normalizedCode !== '' && $label !== '') {
                $labels[$normalizedCode] = $label;
            }
        }

        foreach ($fallback as $code => $label) {
            $normalizedCode = Str::lower((string) $code);
            if (!isset($labels[$normalizedCode]) || $labels[$normalizedCode] === '') {
                $labels[$normalizedCode] = (string) $label;
            }
        }

        return $labels;
    }

    private function accountStatusCodes(): array
    {
        $codes = array_keys($this->getReferenceMap('ukm_account_status_map'));
        $codes = array_values(array_filter(array_map(fn ($code) => Str::lower((string) $code), $codes)));

        if (empty($codes)) {
            return ['active', 'inactive'];
        }

        return $codes;
    }

    private function defaultAccountStatus(): string
    {
        $codes = $this->accountStatusCodes();

        return in_array('active', $codes, true) ? 'active' : $codes[0];
    }

    private function inactiveAccountStatus(): string
    {
        $codes = $this->accountStatusCodes();

        if (in_array('inactive', $codes, true)) {
            return 'inactive';
        }

        return $codes[count($codes) > 1 ? 1 : 0];
    }

    private function organizationActiveStatus(): string
    {
        $payload = $this->getReferencePayload('organization_active_status', 'active');
        $value = trim((string) ($payload['value'] ?? ''));

        return $value !== '' ? $value : 'active';
    }

    private function generateUniqueShortname(string $organizationName): string
    {
        $words = preg_split('/\s+/', trim($organizationName)) ?: [];
        $base = collect($words)
            ->filter()
            ->map(fn ($word) => Str::substr((string) $word, 0, 1))
            ->implode('');

        $base = Str::upper(substr((string) $base, 0, 8));
        if ($base === '') {
            $base = 'ORG';
        }

        $candidate = $base;
        $counter = 1;
        while (DB::table('organizations')->whereRaw('LOWER(shortname) = ?', [Str::lower($candidate)])->exists()) {
            $counter++;
            $suffix = (string) $counter;
            $candidate = substr($base, 0, max(1, 8 - strlen($suffix))) . $suffix;
        }

        return $candidate;
    }

    private function ongoingEventStatuses(): array
    {
        $codes = array_keys($this->getReferenceMap('ongoing_event_status'));
        $codes = array_values(array_filter(array_map(fn ($code) => Str::lower((string) $code), $codes)));

        return !empty($codes) ? $codes : ['approved', 'ongoing'];
    }

    private function pendingSubmissionStatuses(): array
    {
        $codes = array_keys($this->getReferenceMap('pending_submission_status'));
        $codes = array_values(array_filter(array_map(fn ($code) => Str::lower((string) $code), $codes)));

        return !empty($codes) ? $codes : ['submitted', 'reviewing', 'revised'];
    }

    private function pendingReportStatuses(): array
    {
        $codes = array_keys($this->getReferenceMap('pending_report_status'));
        $codes = array_values(array_filter(array_map(fn ($code) => Str::lower((string) $code), $codes)));

        return !empty($codes) ? $codes : ['submitted', 'reviewing', 'revision_needed'];
    }

    private function pendingEmailReviewStatuses(): array
    {
        $codes = array_keys($this->getReferenceMap('pending_email_review_status'));
        $codes = array_values(array_filter(array_map(fn ($code) => Str::lower((string) $code), $codes)));

        return !empty($codes) ? $codes : ['pending', 'revision'];
    }

    private function defaultPendingEmailReviewStatus(): string
    {
        $statuses = $this->pendingEmailReviewStatuses();

        return in_array('pending', $statuses, true) ? 'pending' : $statuses[0];
    }

    private function reviewAnnouncementDecisionMap(): array
    {
        $default = [
            'setujui' => [
                'review_status' => 'approved',
                'publish_status' => 'published',
                'publish_status_scheduled' => 'scheduled',
                'requires_note' => false,
            ],
            'tolak' => [
                'review_status' => 'rejected',
                'publish_status' => 'draft',
                'requires_note' => true,
            ],
            'revisi' => [
                'review_status' => 'revision',
                'publish_status' => 'draft',
                'requires_note' => true,
            ],
        ];

        $map = [];
        foreach ($this->getReferenceMap('review_announcement_decision_map') as $decision => $entry) {
            $payload = is_array($entry['payload'] ?? null) ? $entry['payload'] : [];
            if (empty($payload)) {
                continue;
            }

            $map[(string) $decision] = [
                'review_status' => (string) ($payload['review_status'] ?? ''),
                'publish_status' => (string) ($payload['publish_status'] ?? ''),
                'publish_status_scheduled' => (string) ($payload['publish_status_scheduled'] ?? ''),
                'requires_note' => (bool) ($payload['requires_note'] ?? false),
            ];
        }

        if (empty($map)) {
            return $default;
        }

        foreach ($default as $key => $fallback) {
            if (!isset($map[$key])) {
                $map[$key] = $fallback;
                continue;
            }

            foreach ($fallback as $attr => $fallbackValue) {
                if (empty($map[$key][$attr]) && $map[$key][$attr] !== false) {
                    $map[$key][$attr] = $fallbackValue;
                }
            }
        }

        return $map;
    }

    private function eventDashboardStatus(string $status): array
    {
        $status = Str::lower($status);
        $map = $this->getReferenceMap('event_dashboard_status_map');

        $entry = $map[$status] ?? $map['default'] ?? null;
        if (!$entry) {
            $default = [
                'label' => 'Aktif',
                'tone' => 'active',
            ];

            return match ($status) {
                'draft' => ['Draft', 'pending'],
                'completed' => ['Selesai', 'completed'],
                'cancelled' => ['Batal', 'pending'],
                default => [$default['label'], $default['tone']],
            };
        }

        $label = trim((string) ($entry['label'] ?? ''));
        $tone = trim((string) ($entry['payload']['tone'] ?? ''));

        return [
            $label !== '' ? $label : Str::title(str_replace('_', ' ', $status)),
            $tone !== '' ? $tone : 'active',
        ];
    }

    private function refLabel(string $domain, string $code): string
    {
        $map = $this->getReferenceMap($domain);

        return (string) (($map[$code]['label'] ?? ''));
    }

    private function uiText(string $code): string
    {
        return $this->refLabel('ui_text', $code);
    }

    private function uiTextMap(array $keyCodeMap): array
    {
        $labels = [];

        foreach ($keyCodeMap as $key => $code) {
            $labels[$key] = $this->uiText((string) $code);
        }

        return $labels;
    }

    private function buildDashboardUiText(): array
    {
        return $this->uiTextMap([
            'chart_title' => 'kmh_dashboard_chart_title',
            'quick_actions_title' => 'kmh_dashboard_quick_actions_title',
            'quick_action_review' => 'kmh_dashboard_quick_action_review',
            'quick_action_announcement' => 'kmh_dashboard_quick_action_announcement',
            'upcoming_events_title' => 'kmh_dashboard_upcoming_events_title',
            'upcoming_empty_title' => 'kmh_dashboard_upcoming_empty_title',
            'upcoming_empty_message' => 'kmh_dashboard_upcoming_empty_message',
            'recent_announcements_title' => 'kmh_dashboard_recent_announcements_title',
            'view_all_label' => 'kmh_common_view_all',
            'table_col_title' => 'kmh_dashboard_table_col_title',
            'table_col_date' => 'kmh_dashboard_table_col_date',
            'table_col_status' => 'kmh_dashboard_table_col_status',
            'recent_empty' => 'kmh_dashboard_recent_empty',
        ]);
    }

    private function buildNotifikasiUiText(): array
    {
        return $this->uiTextMap([
            'total_notifications' => 'kmh_notification_total',
            'unread_notifications' => 'kmh_notification_unread',
            'filter_title' => 'kmh_notification_filter_title',
            'filter_showing_items' => 'kmh_notification_filter_showing_items',
            'summary_prefix' => 'kmh_notification_summary_prefix',
            'summary_suffix' => 'kmh_notification_summary_suffix',
            'reset_filter' => 'kmh_notification_reset_filter',
            'open_submission_review' => 'kmh_notification_open_submission_review',
            'detail_button' => 'kmh_notification_detail_button',
            'empty_state' => 'kmh_notification_empty_state',
        ]);
    }

    private function buildKontakUiText(): array
    {
        return $this->uiTextMap([
            'hero_title' => 'kmh_contact_hero_title',
            'hero_subtitle' => 'kmh_contact_hero_subtitle',
            'search_placeholder' => 'kmh_contact_search_placeholder',
            'search_aria' => 'kmh_contact_search_aria',
            'total_org_label' => 'kmh_contact_total_org_label',
            'bem_label' => 'kmh_contact_bem_label',
            'ukm_label' => 'kmh_contact_ukm_label',
            'contact_unavailable' => 'kmh_contact_unavailable',
            'email_unavailable' => 'kmh_email_unavailable',
            'empty_state' => 'kmh_contact_empty_state',
            'search_empty_state' => 'kmh_contact_search_empty_state',
        ]);
    }

    private function buildKalenderUiText(): array
    {
        return $this->uiTextMap([
            'calendar_title' => 'kmh_calendar_title',
            'calendar_subtitle' => 'kmh_calendar_subtitle',
            'add_activity' => 'kmh_calendar_add_activity',
            'modal_title' => 'kmh_calendar_modal_title',
            'modal_subtitle' => 'kmh_calendar_modal_subtitle',
            'field_title' => 'kmh_calendar_field_title',
            'field_start_date' => 'kmh_calendar_field_start_date',
            'field_end_date' => 'kmh_calendar_field_end_date',
            'field_category' => 'kmh_calendar_field_category',
            'field_organization' => 'kmh_calendar_field_organization',
            'field_location' => 'kmh_calendar_field_location',
            'field_description' => 'kmh_calendar_field_description',
            'field_title_placeholder' => 'kmh_calendar_field_title_placeholder',
            'field_location_placeholder' => 'kmh_calendar_field_location_placeholder',
            'field_description_placeholder' => 'kmh_calendar_field_description_placeholder',
            'schedule_form_warning' => 'kmh_schedule_form_warning',
            'schedule_org_placeholder' => 'kmh_schedule_org_placeholder',
            'save_button' => 'kmh_calendar_save_button',
            'cancel_button' => 'kmh_calendar_cancel_button',
            'filter_category' => 'kmh_calendar_filter_category',
            'search_label' => 'kmh_calendar_search_label',
            'search_placeholder' => 'kmh_calendar_search_placeholder',
            'legend_label' => 'kmh_calendar_legend_label',
            'all_activities' => 'kmh_calendar_all_activities',
            'month_view' => 'kmh_calendar_month_view',
            'list_view' => 'kmh_calendar_list_view',
            'more_suffix' => 'kmh_calendar_more_suffix',
            'table_col_title' => 'kmh_calendar_table_col_title',
            'table_col_org' => 'kmh_calendar_table_col_org',
            'table_col_date' => 'kmh_calendar_table_col_date',
            'table_col_location' => 'kmh_calendar_table_col_location',
            'table_col_category' => 'kmh_calendar_table_col_category',
            'empty_state' => 'kmh_calendar_empty_state',
            'category_akademik' => 'kmh_calendar_category_akademik',
            'category_organisasi' => 'kmh_calendar_category_organisasi',
            'category_masa_tenang' => 'kmh_calendar_category_masa_tenang',
            'category_libur' => 'kmh_calendar_category_libur',
            'category_event_besar' => 'kmh_calendar_category_event_besar',
        ]);
    }

    private function buildPengumumanUiText(): array
    {
        return $this->uiTextMap([
            'total_label' => 'kmh_announcement_total_label',
            'published_label' => 'kmh_announcement_published_label',
            'scheduled_label' => 'kmh_announcement_scheduled_label',
            'draft_label' => 'kmh_announcement_draft_label',
            'search_placeholder' => 'kmh_announcement_search_placeholder',
            'search_aria' => 'kmh_announcement_search_aria',
            'all_statuses' => 'kmh_common_all_statuses',
            'create_new_button' => 'kmh_announcement_create_new_button',
            'create_new_title' => 'kmh_announcement_create_new_title',
            'create_new_subtitle' => 'kmh_announcement_create_new_subtitle',
            'modal_title' => 'kmh_announcement_modal_title',
            'modal_subtitle' => 'kmh_announcement_modal_subtitle',
            'account_missing_warning' => 'kmh_announcement_account_missing_warning',
            'field_title' => 'kmh_announcement_field_title',
            'field_category' => 'kmh_announcement_field_category',
            'field_category_placeholder' => 'kmh_announcement_field_category_placeholder',
            'field_target' => 'kmh_announcement_field_target',
            'field_target_placeholder' => 'kmh_announcement_field_target_placeholder',
            'field_content' => 'kmh_announcement_field_content',
            'field_content_placeholder' => 'kmh_announcement_field_content_placeholder',
            'field_publish_date' => 'kmh_announcement_field_publish_datetime',
            'field_publish_placeholder' => 'kmh_announcement_field_publish_datetime_placeholder',
            'field_account' => 'kmh_announcement_field_account',
            'field_account_placeholder' => 'kmh_announcement_field_account_placeholder',
            'field_summary' => 'kmh_announcement_field_summary',
            'save_button' => 'kmh_common_save_button',
            'cancel_button' => 'kmh_common_cancel_button',
            'save_draft_button' => 'kmh_announcement_save_draft_button',
            'publish_now_button' => 'kmh_announcement_publish_now_button',
            'distribution_info_title' => 'kmh_announcement_distribution_info_title',
            'distribution_info_body' => 'kmh_announcement_distribution_info_body',
            'review_email_title' => 'kmh_announcement_review_email_title',
            'review_email_count_suffix' => 'kmh_announcement_review_email_count_suffix',
            'list_title' => 'kmh_announcement_list_title',
            'list_count_suffix' => 'kmh_announcement_list_count_suffix',
            'review_queue_empty' => 'kmh_announcement_review_queue_empty',
            'list_empty' => 'kmh_announcement_list_empty',
            'list_filter_empty' => 'kmh_announcement_list_filter_empty',
            'note_placeholder' => 'kmh_common_review_note_placeholder',
        ]);
    }

    private function getReferencePayload(string $domain, string $code): array
    {
        $map = $this->getReferenceMap($domain);
        $payload = $map[$code]['payload'] ?? [];

        return is_array($payload) ? $payload : [];
    }

    private function getReferenceMap(string $domain): array
    {
        if (array_key_exists($domain, $this->referenceCache)) {
            return $this->referenceCache[$domain];
        }

        if (!Schema::hasTable('workflow_reference_values')) {
            $this->referenceCache[$domain] = [];
            return [];
        }

        $rows = DB::table('workflow_reference_values')
            ->select(['code', 'label', 'payload'])
            ->where('domain', $domain)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $map = [];
        foreach ($rows as $row) {
            $payload = [];
            if (!empty($row->payload)) {
                $decoded = json_decode((string) $row->payload, true);
                if (is_array($decoded)) {
                    $payload = $decoded;
                }
            }

            $map[(string) $row->code] = [
                'label' => (string) ($row->label ?? ''),
                'payload' => $payload,
            ];
        }

        $this->referenceCache[$domain] = $map;

        return $map;
    }
}
