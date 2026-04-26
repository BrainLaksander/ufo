<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('organizations')) {
            return;
        }

        DB::transaction(function () {
            $now = Carbon::now();
            $currentYear = (int) $now->year;
            $organizations = DB::table('organizations')->orderBy('id')->get();

            if ($organizations->isEmpty()) {
                return;
            }

            $users = DB::table('users')->select(['id', 'role', 'name', 'email'])->orderBy('id')->get();
            $departmentUser = $users->firstWhere('role', 'kemahasiswaan') ?: $users->first();
            $reporterUser = $users->first();
            $claimerUser = $users->count() > 1 ? $users->get(1) : $users->first();
            $departmentUserId = $departmentUser?->id ? (int) $departmentUser->id : null;
            $reporterUserId = $reporterUser?->id ? (int) $reporterUser->id : null;
            $claimerUserId = $claimerUser?->id ? (int) $claimerUser->id : null;

            $ukmAccounts = [];
            if (Schema::hasTable('kemahasiswaan_ukm_accounts')) {
                $ukmAccounts = DB::table('kemahasiswaan_ukm_accounts')
                    ->select(['id', 'organization_id'])
                    ->whereNotNull('organization_id')
                    ->get()
                    ->keyBy('organization_id')
                    ->map(fn ($row) => (int) $row->id)
                    ->all();
            }

            $memberIdsByOrganization = [];
            $eventIdsByOrganization = [];
            $submissionIdsByOrganization = [];
            $reportIdsByOrganization = [];

            foreach ($organizations as $index => $organization) {
                $organizationId = (int) $organization->id;
                $shortname = $this->organizationShortname($organization);
                $slug = Str::slug($shortname !== '' ? $shortname : (string) $organization->name, '');

                $this->backfillOrganizationProfile($organizationId, $organization, $index, $now);

                $memberIdsByOrganization[$organizationId] = $this->seedMembers(
                    $organizationId,
                    $organization,
                    $shortname,
                    $slug,
                    $index,
                    $now
                );

                $eventIdsByOrganization[$organizationId] = $this->seedEvents(
                    $organizationId,
                    $shortname,
                    $slug,
                    $index,
                    $now,
                    $memberIdsByOrganization[$organizationId]['ketua'] ?? null
                );

                $submissionIdsByOrganization[$organizationId] = $this->seedSubmissions(
                    $organizationId,
                    $shortname,
                    $slug,
                    $currentYear,
                    $index,
                    $now,
                    $memberIdsByOrganization[$organizationId]['sekretaris'] ?? null,
                    $departmentUserId
                );

                $reportIdsByOrganization[$organizationId] = $this->seedReports(
                    $organizationId,
                    $shortname,
                    $slug,
                    $currentYear,
                    $index,
                    $now,
                    $memberIdsByOrganization[$organizationId]['bendahara'] ?? null,
                    $departmentUserId,
                    $eventIdsByOrganization[$organizationId]['completed'] ?? ($eventIdsByOrganization[$organizationId]['approved'] ?? null)
                );

                $this->seedAnnouncements(
                    $organizationId,
                    $shortname,
                    $slug,
                    $index,
                    $now,
                    $departmentUserId,
                    $ukmAccounts[$organizationId] ?? null
                );

                $this->seedSchedules(
                    $organizationId,
                    $shortname,
                    $slug,
                    $index,
                    $now,
                    $departmentUserId
                );

                $this->seedTasks(
                    $organizationId,
                    $shortname,
                    $slug,
                    $index,
                    $now,
                    $memberIdsByOrganization[$organizationId],
                    $submissionIdsByOrganization[$organizationId],
                    $reportIdsByOrganization[$organizationId]
                );

                $this->seedActivityLogs(
                    $organizationId,
                    $shortname,
                    $slug,
                    $index,
                    $now,
                    $memberIdsByOrganization[$organizationId],
                    $submissionIdsByOrganization[$organizationId],
                    $reportIdsByOrganization[$organizationId],
                    $eventIdsByOrganization[$organizationId]
                );

                $this->seedLostFound(
                    $organizationId,
                    $shortname,
                    $slug,
                    $index,
                    $now,
                    $reporterUserId,
                    $claimerUserId
                );
            }
        });
    }

    private function backfillOrganizationProfile(int $organizationId, object $organization, int $index, Carbon $now): void
    {
        $shortname = $this->organizationShortname($organization);
        $slug = Str::slug($shortname !== '' ? $shortname : (string) $organization->name, '');
        $displayName = trim((string) $organization->name);
        $readableShortname = $shortname !== '' ? $shortname : $displayName;

        $payload = [];

        foreach ([
            'description' => 'Organisasi mahasiswa ' . $readableShortname . ' berfokus pada pengembangan minat, kepemimpinan, dan kolaborasi di lingkungan kampus.',
            'vision' => 'Menjadi wadah organisasi ' . $readableShortname . ' yang aktif, kreatif, dan berdampak bagi mahasiswa.',
            'mission' => 'Menyelenggarakan program kerja yang bermanfaat. Menguatkan komunikasi internal. Mendorong partisipasi mahasiswa dalam kegiatan kampus.',
            'email' => $slug . '@unklab.ac.id',
            'phone' => '+62 8' . str_pad((string) (($index + 1) % 90 + 10), 2, '0', STR_PAD_LEFT) . str_pad((string) ($organizationId % 100000000), 8, '0', STR_PAD_LEFT),
            'instagram' => '@' . $slug,
            'line' => 'https://line.me/ti/p/~' . $slug,
        ] as $column => $value) {
            if (Schema::hasColumn('organizations', $column)) {
                $currentValue = trim((string) ($organization->{$column} ?? ''));
                if ($currentValue === '') {
                    $payload[$column] = $value;
                }
            }
        }

        foreach ([
            'category' => $this->resolveCategory($displayName, $shortname, $index),
            'type' => Str::contains(Str::lower($displayName . ' ' . $shortname), 'bem') ? 'BEM' : 'UKM',
            'level' => Str::contains(Str::lower($displayName . ' ' . $shortname), ['fakultas', 'hima', 'himaf', 'himti', 'himsi']) ? 'Fakultas' : 'Universitas',
            'field' => $this->resolveField($displayName, $shortname, $index),
        ] as $column => $value) {
            if (Schema::hasColumn('organizations', $column)) {
                $currentValue = trim((string) ($organization->{$column} ?? ''));
                if ($currentValue === '') {
                    $payload[$column] = $value;
                }
            }
        }

        if (Schema::hasColumn('organizations', 'profile_status') && trim((string) ($organization->profile_status ?? '')) !== 'complete') {
            $payload['profile_status'] = 'complete';
        }

        if (!empty($payload)) {
            $payload['updated_at'] = $now;

            DB::table('organizations')
                ->where('id', $organizationId)
                ->update($payload);
        }
    }

    private function seedMembers(int $organizationId, object $organization, string $shortname, string $slug, int $index, Carbon $now): array
    {
        if (!Schema::hasTable('members')) {
            return [];
        }

        $facultyOptions = [
            'Fakultas Ekonomi',
            'Fakultas Ilmu Komputer',
            'Fakultas Keperawatan',
            'Fakultas Filsafat',
            'Fakultas Pendidikan',
            'Fakultas Teknik',
        ];

        $majorOptions = [
            'Manajemen',
            'Informatika',
            'Keperawatan',
            'Teologi',
            'Pendidikan Bahasa Inggris',
            'Sistem Informasi',
        ];

        $positions = [
            'ketua' => 'Ketua',
            'sekretaris' => 'Sekretaris',
            'bendahara' => 'Bendahara',
            'staff' => 'Koordinator Program',
        ];

        $memberIds = [];
        $memberCount = 0;

        foreach ($positions as $position => $roleLabel) {
            if ($position === 'staff' && ($index % 2) !== 0) {
                continue;
            }

            $memberCount++;
            $nim = sprintf('NIM-%d-%02d', $organizationId, $memberCount);
            $email = sprintf('%s.%s@unklab.ac.id', $slug !== '' ? $slug : 'org' . $organizationId, $position);
            $existing = DB::table('members')
                ->where('organization_id', $organizationId)
                ->where('nim', $nim)
                ->first();

            if ($existing) {
                $memberIds[$position] = (int) $existing->id;
                continue;
            }

            $memberIds[$position] = (int) DB::table('members')->insertGetId([
                'organization_id' => $organizationId,
                'name' => $roleLabel . ' ' . ($shortname !== '' ? $shortname : $organization->name),
                'nim' => $nim,
                'email' => $email,
                'phone' => '+62 8' . str_pad((string) (($organizationId + $memberCount) % 90 + 10), 2, '0', STR_PAD_LEFT) . str_pad((string) (($organizationId * 17 + $memberCount) % 100000000), 8, '0', STR_PAD_LEFT),
                'faculty' => $facultyOptions[($organizationId + $memberCount) % count($facultyOptions)],
                'major' => $majorOptions[($organizationId + $memberCount) % count($majorOptions)],
                'position' => $position,
                'status' => 'aktif',
                'join_type' => $position === 'ketua' ? 'founder' : 'invited',
                'join_date' => $now->copy()->subMonths(6 + $memberCount)->toDateString(),
                'notes' => 'Data dummy untuk melengkapi struktur organisasi ' . ($shortname !== '' ? $shortname : $organization->name) . '.',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return $memberIds;
    }

    private function seedEvents(int $organizationId, string $shortname, string $slug, int $index, Carbon $now, ?int $creatorMemberId): array
    {
        if (!Schema::hasTable('events') || !$creatorMemberId) {
            return [];
        }

        $events = [
            'approved' => [
                'name' => 'Seminar Inspiratif ' . ($shortname !== '' ? $shortname : 'Organisasi ' . $organizationId),
                'description' => 'Kegiatan pembuka untuk melatih kepemimpinan, komunikasi, dan partisipasi anggota.',
                'start_date' => $now->copy()->addDays(7 + $index)->setTime(9, 0),
                'end_date' => $now->copy()->addDays(7 + $index)->setTime(16, 0),
                'location' => 'Aula Kampus Utama',
                'quota' => 120,
                'current_participants' => 42 + $index,
                'status' => 'approved',
            ],
            'completed' => [
                'name' => 'Pekan Aksi ' . ($shortname !== '' ? $shortname : 'Organisasi ' . $organizationId),
                'description' => 'Kegiatan yang sudah selesai dan menghasilkan dokumentasi kegiatan serta evaluasi akhir.',
                'start_date' => $now->copy()->subDays(14 + $index)->setTime(8, 0),
                'end_date' => $now->copy()->subDays(13 + $index)->setTime(15, 0),
                'location' => 'Gedung Serbaguna',
                'quota' => 80,
                'current_participants' => 38 + $index,
                'status' => 'completed',
            ],
        ];

        $createdIds = [];

        foreach ($events as $key => $event) {
            $existing = DB::table('events')
                ->where('organization_id', $organizationId)
                ->where('name', $event['name'])
                ->first();

            if ($existing) {
                $createdIds[$key] = (int) $existing->id;
                continue;
            }

            $createdIds[$key] = (int) DB::table('events')->insertGetId([
                'organization_id' => $organizationId,
                'created_by' => $creatorMemberId,
                'name' => $event['name'],
                'description' => $event['description'],
                'start_date' => $event['start_date'],
                'end_date' => $event['end_date'],
                'location' => $event['location'],
                'quota' => $event['quota'],
                'current_participants' => $event['current_participants'],
                'banner' => null,
                'status' => $event['status'],
                'internal_notes' => 'Data dummy untuk organisasi ' . $slug . '.',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return $createdIds;
    }

    private function seedSubmissions(int $organizationId, string $shortname, string $slug, int $currentYear, int $index, Carbon $now, ?int $memberId, ?int $departmentUserId): array
    {
        if (!Schema::hasTable('submissions') || !$memberId) {
            return [];
        }

        $rows = [
            'draft' => [
                'title' => 'Draft Pengajuan Kegiatan ' . ($shortname !== '' ? $shortname : 'Organisasi ' . $organizationId) . ' ' . $currentYear,
                'description' => 'Draft pengajuan awal untuk menampilkan tombol kirim di portal pengurus.',
                'type' => 'activity_plan',
                'status' => 'draft',
                'submitted_date' => null,
                'approved_date' => null,
                'reviewed_at' => null,
                'reviewed_by_department_user_id' => null,
            ],
            'submitted' => [
                'title' => 'Pengajuan Dana ' . ($shortname !== '' ? $shortname : 'Organisasi ' . $organizationId) . ' ' . $currentYear,
                'description' => 'Pengajuan yang sudah dikirim dan menunggu review departemen kemahasiswaan.',
                'type' => 'budget',
                'status' => 'submitted',
                'submitted_date' => $now->copy()->subDays(3 + $index)->toDateString(),
                'approved_date' => null,
                'reviewed_at' => null,
                'reviewed_by_department_user_id' => null,
            ],
            'approved' => [
                'title' => 'Proposal Program Kerja ' . ($shortname !== '' ? $shortname : 'Organisasi ' . $organizationId) . ' ' . $currentYear,
                'description' => 'Pengajuan yang sudah disetujui sehingga event dapat dijadwalkan.',
                'type' => 'proposal',
                'status' => 'approved',
                'submitted_date' => $now->copy()->subDays(10 + $index)->toDateString(),
                'approved_date' => $now->copy()->subDays(7 + $index)->toDateString(),
                'reviewed_at' => $now->copy()->subDays(7 + $index),
                'reviewed_by_department_user_id' => $departmentUserId,
            ],
        ];

        $createdIds = [];

        foreach ($rows as $key => $submission) {
            $existing = DB::table('submissions')
                ->where('organization_id', $organizationId)
                ->where('title', $submission['title'])
                ->first();

            if ($existing) {
                $createdIds[$key] = (int) $existing->id;
                continue;
            }

            $createdIds[$key] = (int) DB::table('submissions')->insertGetId([
                'organization_id' => $organizationId,
                'member_id' => $memberId,
                'reviewed_by_department_user_id' => $submission['reviewed_by_department_user_id'],
                'title' => $submission['title'],
                'description' => $submission['description'],
                'type' => $submission['type'],
                'status' => $submission['status'],
                'feedback' => $submission['status'] === 'rejected' ? 'Belum ada.' : null,
                'department_review_note' => $submission['status'] === 'approved' ? 'Disetujui untuk demo data.' : null,
                'revision_count' => $submission['status'] === 'revised' ? 1 : 0,
                'submitted_date' => $submission['submitted_date'],
                'approved_date' => $submission['approved_date'],
                'reviewed_at' => $submission['reviewed_at'],
                'file_path' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return $createdIds;
    }

    private function seedReports(int $organizationId, string $shortname, string $slug, int $currentYear, int $index, Carbon $now, ?int $memberId, ?int $departmentUserId, ?int $eventId): array
    {
        if (!Schema::hasTable('reports') || !$memberId) {
            return [];
        }

        $rows = [
            'draft' => [
                'title' => 'Draft LPJ ' . ($shortname !== '' ? $shortname : 'Organisasi ' . $organizationId) . ' ' . $currentYear,
                'content' => 'Draft laporan kegiatan yang masih menunggu pengiriman dari pengurus.',
                'participants' => 20 + $index,
                'report_type' => 'activity',
                'status' => 'draft',
                'submitted_date' => null,
                'approved_date' => null,
                'reviewed_at' => null,
                'reviewed_by_department_user_id' => null,
                'department_review_note' => null,
            ],
            'submitted' => [
                'title' => 'LPJ Kegiatan ' . ($shortname !== '' ? $shortname : 'Organisasi ' . $organizationId) . ' ' . $currentYear,
                'content' => 'Laporan kegiatan yang sudah dikirim dan menunggu review departemen kemahasiswaan.',
                'participants' => 35 + $index,
                'report_type' => 'financial',
                'status' => 'submitted',
                'submitted_date' => $now->copy()->subDays(2 + $index)->toDateString(),
                'approved_date' => null,
                'reviewed_at' => null,
                'reviewed_by_department_user_id' => null,
                'department_review_note' => null,
            ],
            'revision_needed' => [
                'title' => 'LPJ Revisi ' . ($shortname !== '' ? $shortname : 'Organisasi ' . $organizationId) . ' ' . $currentYear,
                'content' => 'Laporan kegiatan yang perlu revisi agar tombol kirim di portal pengurus terlihat.',
                'participants' => 12 + $index,
                'report_type' => 'semester',
                'status' => 'revision_needed',
                'submitted_date' => $now->copy()->subDays(8 + $index)->toDateString(),
                'approved_date' => null,
                'reviewed_at' => $now->copy()->subDays(5 + $index),
                'reviewed_by_department_user_id' => $departmentUserId,
                'department_review_note' => 'Mohon lengkapi lampiran dan ringkasan hasil kegiatan.',
            ],
        ];

        $createdIds = [];

        foreach ($rows as $key => $report) {
            $existing = DB::table('reports')
                ->where('organization_id', $organizationId)
                ->where('title', $report['title'])
                ->first();

            if ($existing) {
                $createdIds[$key] = (int) $existing->id;
                continue;
            }

            $createdIds[$key] = (int) DB::table('reports')->insertGetId([
                'organization_id' => $organizationId,
                'event_id' => $eventId,
                'member_id' => $memberId,
                'reviewed_by_department_user_id' => $report['reviewed_by_department_user_id'],
                'title' => $report['title'],
                'content' => $report['content'],
                'participants' => $report['participants'],
                'report_type' => $report['report_type'],
                'status' => $report['status'],
                'reviewer_notes' => $report['department_review_note'],
                'department_review_note' => $report['department_review_note'],
                'submitted_date' => $report['submitted_date'],
                'approved_date' => $report['approved_date'],
                'reviewed_at' => $report['reviewed_at'],
                'attachment' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return $createdIds;
    }

    private function seedAnnouncements(int $organizationId, string $shortname, string $slug, int $index, Carbon $now, ?int $departmentUserId, ?int $ukmAccountId): void
    {
        if (!Schema::hasTable('kemahasiswaan_announcements') || !$ukmAccountId) {
            return;
        }

        $rows = [
            [
                'title' => 'Pengumuman ' . ($shortname !== '' ? $shortname : 'Organisasi ' . $organizationId) . ' Terpublikasi',
                'category' => 'Informasi Organisasi',
                'target_audience' => 'Seluruh anggota dan mahasiswa',
                'summary' => 'Pengumuman publik untuk menampilkan isi halaman mahasiswa dan portal internal.',
                'content' => 'Halo mahasiswa. Ini adalah pengumuman dummy untuk organisasi ' . ($shortname !== '' ? $shortname : $slug) . '. Gunakan data ini untuk menguji tampilan publik dan panel review departemen.',
                'publish_at' => $now->copy()->subDays(2 + $index),
                'publish_status' => 'published',
                'email_review_status' => 'approved',
                'email_review_note' => null,
                'reviewed_by' => $departmentUserId,
                'reviewed_at' => $now->copy()->subDays(2 + $index),
            ],
            [
                'title' => 'Pengumuman ' . ($shortname !== '' ? $shortname : 'Organisasi ' . $organizationId) . ' Terjadwal',
                'category' => 'Agenda Internal',
                'target_audience' => 'Anggota aktif',
                'summary' => 'Pengumuman terjadwal untuk antrean review email departemen.',
                'content' => 'Pengumuman ini dijadwalkan untuk organisasi ' . ($shortname !== '' ? $shortname : $slug) . '. Ini dibuat sebagai dummy data agar queue review tidak kosong.',
                'publish_at' => $now->copy()->addDays(3 + $index),
                'publish_status' => 'scheduled',
                'email_review_status' => 'pending',
                'email_review_note' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ],
        ];

        foreach ($rows as $row) {
            $existing = DB::table('kemahasiswaan_announcements')
                ->where('ukm_account_id', $ukmAccountId)
                ->where('title', $row['title'])
                ->first();

            if ($existing) {
                continue;
            }

            DB::table('kemahasiswaan_announcements')->insert([
                'ukm_account_id' => $ukmAccountId,
                'title' => $row['title'],
                'category' => $row['category'],
                'target_audience' => $row['target_audience'],
                'summary' => $row['summary'],
                'content' => $row['content'],
                'publish_at' => $row['publish_at'],
                'publish_status' => $row['publish_status'],
                'email_review_status' => $row['email_review_status'],
                'email_review_note' => $row['email_review_note'],
                'reviewed_by' => $row['reviewed_by'],
                'reviewed_at' => $row['reviewed_at'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function seedSchedules(int $organizationId, string $shortname, string $slug, int $index, Carbon $now, ?int $departmentUserId): void
    {
        if (!Schema::hasTable('kemahasiswaan_schedules')) {
            return;
        }

        $scheduleTitle = 'Jadwal Koordinasi ' . ($shortname !== '' ? $shortname : 'Organisasi ' . $organizationId);
        $existing = DB::table('kemahasiswaan_schedules')
            ->where('organization_id', $organizationId)
            ->where('title', $scheduleTitle)
            ->first();

        if ($existing) {
            return;
        }

        $startAt = $now->copy()->addDays(1 + $index)->setTime(10, 0);
        $endAt = $startAt->copy()->addHours(2);

        $payload = [
            'organization_id' => $organizationId,
            'title' => $scheduleTitle,
            'start_at' => $startAt,
            'end_at' => $endAt,
            'location' => 'Ruang Rapat ' . ($index + 1),
            'status' => $index % 3 === 0 ? 'ongoing' : ($index % 2 === 0 ? 'planned' : 'completed'),
            'created_by' => $departmentUserId,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        if (Schema::hasColumn('kemahasiswaan_schedules', 'category')) {
            $payload['category'] = $index % 3 === 0 ? 'org' : ($index % 3 === 1 ? 'acad' : 'campus');
        }

        if (Schema::hasColumn('kemahasiswaan_schedules', 'description')) {
            $payload['description'] = 'Agenda dummy untuk menampilkan kalender kegiatan organisasi ' . ($shortname !== '' ? $shortname : $slug) . '.';
        }

        DB::table('kemahasiswaan_schedules')->insert($payload);
    }

    private function seedTasks(int $organizationId, string $shortname, string $slug, int $index, Carbon $now, array $memberIds, array $submissionIds, array $reportIds): void
    {
        if (!Schema::hasTable('tasks')) {
            return;
        }

        $title = 'Finalisasi LPJ ' . ($shortname !== '' ? $shortname : 'Organisasi ' . $organizationId);
        $existing = DB::table('tasks')
            ->where('organization_id', $organizationId)
            ->where('title', $title)
            ->first();

        if ($existing) {
            return;
        }

        $assignedTo = $memberIds['sekretaris'] ?? ($memberIds['ketua'] ?? null);
        $deadline = $index % 2 === 0 ? $now->copy()->subDays(2 + $index) : $now->copy()->addDays(3 + $index);

        DB::table('tasks')->insert([
            'organization_id' => $organizationId,
            'assigned_to' => $assignedTo,
            'title' => $title,
            'description' => 'Tugas dummy untuk menampilkan daftar reminder pada dashboard pengurus.',
            'priority' => $index % 3 === 0 ? 'urgent' : 'normal',
            'status' => $index % 2 === 0 ? 'overdue' : 'pending',
            'task_type' => $index % 2 === 0 ? 'report_submission' : 'revision',
            'deadline' => $deadline,
            'completed_at' => null,
            'related_submission_id' => $submissionIds['submitted'] ?? ($submissionIds['draft'] ?? null),
            'related_report_id' => $reportIds['submitted'] ?? ($reportIds['draft'] ?? null),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function seedActivityLogs(int $organizationId, string $shortname, string $slug, int $index, Carbon $now, array $memberIds, array $submissionIds, array $reportIds, array $eventIds): void
    {
        if (!Schema::hasTable('kemahasiswaan_activity_logs')) {
            return;
        }

        $rows = [
            [
                'action' => 'submission_created',
                'description' => 'Pengajuan kegiatan baru dibuat untuk ' . ($shortname !== '' ? $shortname : $slug) . '.',
                'metadata' => ['submission_id' => $submissionIds['draft'] ?? null],
            ],
            [
                'action' => 'report_received',
                'description' => 'Laporan kegiatan diterima untuk ' . ($shortname !== '' ? $shortname : $slug) . '.',
                'metadata' => ['report_id' => $reportIds['submitted'] ?? null],
            ],
            [
                'action' => 'event_published',
                'description' => 'Event organisasi dipublikasikan untuk ' . ($shortname !== '' ? $shortname : $slug) . '.',
                'metadata' => ['event_id' => $eventIds['approved'] ?? null],
            ],
        ];

        foreach ($rows as $offset => $row) {
            $existing = DB::table('kemahasiswaan_activity_logs')
                ->where('organization_id', $organizationId)
                ->where('action', $row['action'])
                ->where('description', $row['description'])
                ->first();

            if ($existing) {
                continue;
            }

            DB::table('kemahasiswaan_activity_logs')->insert([
                'ukm_account_id' => null,
                'organization_id' => $organizationId,
                'action' => $row['action'],
                'description' => $row['description'],
                'metadata' => json_encode($row['metadata'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Demo Seeder',
                'created_at' => $now->copy()->subHours(($index * 3) + $offset),
                'updated_at' => $now->copy()->subHours(($index * 3) + $offset),
            ]);
        }
    }

    private function seedLostFound(int $organizationId, string $shortname, string $slug, int $index, Carbon $now, ?int $reporterUserId, ?int $claimerUserId): void
    {
        if (!Schema::hasTable('lost_found_items') || !$reporterUserId) {
            return;
        }

        $rows = [
            [
                'item_name' => 'Laptop Organisasi ' . ($shortname !== '' ? $shortname : $slug),
                'description' => 'Barang hilang untuk menampilkan daftar Lost & Found di halaman mahasiswa.',
                'location_found' => 'Perpustakaan Kampus',
                'type' => 'lost',
                'status' => 'active',
                'claimed_by' => null,
                'claimed_at' => null,
                'resolved_at' => null,
            ],
            [
                'item_name' => 'Microphone ' . ($shortname !== '' ? $shortname : $slug),
                'description' => 'Barang ditemukan untuk menampilkan status klaim dan penyelesaian.',
                'location_found' => 'Gedung Serbaguna',
                'type' => 'found',
                'status' => $index % 2 === 0 ? 'claimed' : 'closed',
                'claimed_by' => $claimerUserId,
                'claimed_at' => $now->copy()->subDays(1 + $index),
                'resolved_at' => $now->copy()->subHours(6 + $index),
            ],
        ];

        foreach ($rows as $row) {
            $existing = DB::table('lost_found_items')
                ->where('organization_id', $organizationId)
                ->where('item_name', $row['item_name'])
                ->first();

            if ($existing) {
                continue;
            }

            DB::table('lost_found_items')->insert([
                'organization_id' => $organizationId,
                'reported_by' => $reporterUserId,
                'item_name' => $row['item_name'],
                'description' => $row['description'],
                'image' => null,
                'location_found' => $row['location_found'],
                'type' => $row['type'],
                'status' => $row['status'],
                'claimed_by' => $row['claimed_by'],
                'claimed_at' => $row['claimed_at'],
                'resolved_at' => $row['resolved_at'],
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]);
        }
    }

    private function organizationShortname(object $organization): string
    {
        return trim((string) ($organization->shortname ?? ''));
    }

    private function resolveCategory(string $name, string $shortname, int $index): string
    {
        $blob = Str::lower(trim($name . ' ' . $shortname));

        if (Str::contains($blob, ['bem', 'senat', 'dpm'])) {
            return 'BEM';
        }

        if (Str::contains($blob, ['teknologi', 'it', 'coding', 'robot', 'ai'])) {
            return 'Akademik & Teknologi';
        }

        if (Str::contains($blob, ['seni', 'musik', 'choir', 'paduan suara'])) {
            return 'Minat & Bakat';
        }

        if (Str::contains($blob, ['rohani', 'kerohanian', 'ministry', 'pelayanan'])) {
            return 'Kerohanian';
        }

        if (Str::contains($blob, ['daerah', 'kedaerahan'])) {
            return 'Kedaerahan';
        }

        return match ($index % 3) {
            0 => 'Akademik & Teknologi',
            1 => 'Minat & Bakat',
            default => 'UKM Umum',
        };
    }

    private function resolveField(string $name, string $shortname, int $index): string
    {
        $blob = Str::lower(trim($name . ' ' . $shortname));

        if (Str::contains($blob, ['bem', 'senat', 'dpm'])) {
            return 'Pemerintahan Mahasiswa';
        }

        if (Str::contains($blob, ['teknologi', 'it', 'coding', 'robot', 'ai'])) {
            return 'Inovasi Digital';
        }

        if (Str::contains($blob, ['seni', 'musik', 'choir', 'paduan suara'])) {
            return 'Seni & Kreativitas';
        }

        if (Str::contains($blob, ['rohani', 'kerohanian', 'ministry', 'pelayanan'])) {
            return 'Kerohanian';
        }

        if (Str::contains($blob, ['daerah', 'kedaerahan'])) {
            return 'Organisasi Kedaerahan';
        }

        return match ($index % 4) {
            0 => 'Akademik & Teknologi',
            1 => 'Kewirausahaan',
            2 => 'Kepemimpinan',
            default => 'Bidang Umum',
        };
    }
}