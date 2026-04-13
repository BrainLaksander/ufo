<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class IzinKegiatanWorkflowController extends Controller
{
    public function pengurusIndex(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = $context['organization_id'];

        return view('portal.pengurus.proposals', [
            'workflowPengajuan' => $organizationId ? $this->getPengajuan($organizationId) : [],
            'workflowLaporan' => $organizationId ? $this->getLaporan($organizationId) : [],
            'jadwalKegiatan' => $this->getJadwal(),
            'kontakPengurus' => $this->getKontakPengurus(),
            'eventOptions' => $organizationId ? $this->getEventOptions($organizationId) : [],
            'hasPengurusContext' => $context['organization_id'] !== null && $context['member_id'] !== null,
            'pengurusOrganizationName' => $context['organization_name'],
        ]);
    }

    public function pengurusSettings(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);

        $org = null;
        if ($context['organization_id']) {
            $org = DB::table('organizations')->where('id', $context['organization_id'])->first();
        }

        return view('pages.pengurus.settings', [
            'hasOrganizationContext' => $org !== null,
            'orgData' => [
                'id' => $org->id ?? null,
                'name' => $org->name ?? '-',
                'shortname' => $org->shortname ?? '-',
                'description' => $org->description ?? '',
                'vision' => $org->vision ?? '',
                'mission' => $org->mission ?? '',
                'email' => $org->email ?? '',
                'phone' => $org->phone ?? '',
                'instagram' => $org->instagram ?? '',
                'line' => $org->line ?? '',
            ],
        ]);
    }

    public function updateProfilUKM(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);

        if (!$context['organization_id']) {
            return back()->with('error', 'Konteks organisasi pengurus belum terhubung ke database.');
        }

        $validated = $request->validate([
            'description' => 'nullable|string|max:4000',
            'vision' => 'nullable|string|max:2000',
            'mission' => 'nullable|string|max:4000',
            'email' => 'nullable|email|max:120',
            'phone' => 'nullable|string|max:40',
            'instagram' => 'nullable|string|max:120',
            'line' => 'nullable|string|max:120',
        ]);

        DB::table('organizations')
            ->where('id', $context['organization_id'])
            ->update([
                'description' => $validated['description'] ?? null,
                'vision' => $validated['vision'] ?? null,
                'mission' => $validated['mission'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'instagram' => $validated['instagram'] ?? null,
                'line' => $validated['line'] ?? null,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Profil UKM berhasil diperbarui.');
    }

    public function kemahasiswaanIndex(): View
    {
        return view('pages.portal.kemahasiswaan.pengajuan', [
            'workflowPengajuan' => $this->getPengajuan(),
            'workflowLaporan' => $this->getLaporan(),
            'jadwalKegiatan' => $this->getJadwal(),
            'organizations' => $this->getOrganizations(),
        ]);
    }

    public function eventForm(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = $context['organization_id'];

        $hasApprovedIzin = false;
        if ($organizationId) {
            $hasApprovedIzin = DB::table('submissions')
                ->where('organization_id', $organizationId)
                ->where('status', 'approved')
                ->exists();
        }

        return view('pages.pengurus.events.form', [
            'hasApprovedIzin' => $hasApprovedIzin,
            'hasPengurusContext' => $context['organization_id'] !== null && $context['member_id'] !== null,
            'pengurusOrganizationName' => $context['organization_name'],
        ]);
    }

    public function storeEvent(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);

        if (!$context['organization_id'] || !$context['member_id']) {
            return back()->with('error', 'Data pengurus tidak lengkap. Hubungkan akun pengurus ke organization dan member.');
        }

        $hasApprovedIzin = DB::table('submissions')
            ->where('organization_id', $context['organization_id'])
            ->where('status', 'approved')
            ->exists();

        if (!$hasApprovedIzin) {
            return back()->with('error', 'Event belum bisa dibuat karena belum ada izin kegiatan yang disetujui.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'required|string|max:160',
            'quota' => 'required|integer|min:1',
            'description' => 'required|string|max:3000',
            'status' => 'required|in:draft,approved',
        ]);

        DB::table('events')->insert([
            'organization_id' => $context['organization_id'],
            'created_by' => $context['member_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => Carbon::parse($validated['start_date']),
            'end_date' => !empty($validated['end_date']) ? Carbon::parse($validated['end_date']) : Carbon::parse($validated['start_date']),
            'location' => $validated['location'],
            'quota' => (int) $validated['quota'],
            'current_participants' => 0,
            'banner' => null,
            'status' => $validated['status'],
            'internal_notes' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Event berhasil dibuat.');
    }

    public function storePengajuan(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);

        if (!$context['organization_id'] || !$context['member_id']) {
            return back()->with('error', 'Data pengurus tidak lengkap. Hubungkan akun pengurus ke organization dan member.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:180',
            'description' => 'required|string|max:3000',
            'type' => 'required|in:proposal,budget,activity_plan',
        ]);

        DB::table('submissions')->insert([
            'organization_id' => $context['organization_id'],
            'member_id' => $context['member_id'],
            'reviewed_by_department_user_id' => null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'status' => 'submitted',
            'feedback' => null,
            'department_review_note' => null,
            'revision_count' => 0,
            'submitted_date' => now()->toDateString(),
            'approved_date' => null,
            'reviewed_at' => null,
            'file_path' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan izin kegiatan berhasil dibuat dan dikirim untuk review.');
    }

    public function storeLaporan(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);

        if (!$context['organization_id'] || !$context['member_id']) {
            return back()->with('error', 'Data pengurus tidak lengkap. Hubungkan akun pengurus ke organization dan member.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:180',
            'content' => 'required|string|max:5000',
            'participants' => 'required|integer|min:0',
            'report_type' => 'required|in:activity,financial,semester',
            'event_id' => 'nullable|integer|exists:events,id',
        ]);

        if (!empty($validated['event_id'])) {
            $event = DB::table('events')
                ->where('id', $validated['event_id'])
                ->where('organization_id', $context['organization_id'])
                ->first();

            if (!$event) {
                return back()->with('error', 'Event laporan tidak valid untuk organisasi pengurus saat ini.');
            }
        }

        DB::table('reports')->insert([
            'organization_id' => $context['organization_id'],
            'event_id' => $validated['event_id'] ?? null,
            'member_id' => $context['member_id'],
            'reviewed_by_department_user_id' => null,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'participants' => (int) $validated['participants'],
            'report_type' => $validated['report_type'],
            'status' => 'draft',
            'reviewer_notes' => null,
            'department_review_note' => null,
            'submitted_date' => null,
            'approved_date' => null,
            'reviewed_at' => null,
            'attachment' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Draft laporan kegiatan berhasil dibuat.');
    }

    public function submit(Request $request, int $id): RedirectResponse
    {
        $submission = DB::table('submissions')->where('id', $id)->first();
        if (!$submission) {
            return back()->with('error', 'Data pengajuan tidak ditemukan.');
        }

        $context = $this->resolvePengurusContext($request);
        if ($context['organization_id'] && (int) $submission->organization_id !== $context['organization_id']) {
            return back()->with('error', 'Pengajuan ini bukan milik organisasi Anda.');
        }

        if (!in_array((string) $submission->status, ['draft', 'revised'], true)) {
            return back()->with('error', 'Pengajuan hanya bisa dikirim dari status Draft atau Revisi.');
        }

        DB::table('submissions')
            ->where('id', $id)
            ->update([
                'status' => 'submitted',
                'submitted_date' => now()->toDateString(),
                'department_review_note' => null,
                'reviewed_at' => null,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Pengajuan izin berhasil dikirim ke Departemen Kemahasiswaan.');
    }

    public function submitLaporan(Request $request, int $id): RedirectResponse
    {
        $report = DB::table('reports')->where('id', $id)->first();
        if (!$report) {
            return back()->with('error', 'Data laporan tidak ditemukan.');
        }

        $context = $this->resolvePengurusContext($request);
        if ($context['organization_id'] && (int) $report->organization_id !== $context['organization_id']) {
            return back()->with('error', 'Laporan ini bukan milik organisasi Anda.');
        }

        if (!in_array((string) $report->status, ['draft', 'revision_needed'], true)) {
            return back()->with('error', 'Laporan hanya bisa dikirim dari status Draft atau Revisi.');
        }

        DB::table('reports')
            ->where('id', $id)
            ->update([
                'status' => 'submitted',
                'submitted_date' => now()->toDateString(),
                'department_review_note' => null,
                'reviewed_at' => null,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Laporan kegiatan berhasil dikirim ke Departemen Kemahasiswaan.');
    }

    public function review(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'decision' => 'required|in:disetujui,ditolak,revisi',
            'catatan' => 'nullable|string|max:200',
        ]);

        if (in_array($validated['decision'], ['ditolak', 'revisi'], true) && empty(trim((string) ($validated['catatan'] ?? '')))) {
            return back()->with('error', 'Catatan wajib diisi saat keputusan ditolak atau revisi.');
        }

        $submission = DB::table('submissions')->where('id', $id)->first();
        if (!$submission) {
            return back()->with('error', 'Data pengajuan tidak ditemukan.');
        }

        if ((string) $submission->status === 'draft') {
            return back()->with('error', 'Pengajuan masih draft. Minta pengurus mengirim pengajuan terlebih dahulu.');
        }

        $statusMap = [
            'disetujui' => 'approved',
            'ditolak' => 'rejected',
            'revisi' => 'revised',
        ];

        DB::table('submissions')
            ->where('id', $id)
            ->update([
                'status' => $statusMap[$validated['decision']],
                'department_review_note' => trim((string) ($validated['catatan'] ?? '')) ?: null,
                'reviewed_by_department_user_id' => $this->resolveSessionUserId($request),
                'reviewed_at' => now(),
                'approved_date' => $validated['decision'] === 'disetujui' ? now()->toDateString() : null,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Review pengajuan berhasil disimpan.');
    }

    public function reviewLaporan(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'decision' => 'required|in:disetujui,ditolak,revisi',
            'catatan' => 'nullable|string|max:200',
        ]);

        if (in_array($validated['decision'], ['ditolak', 'revisi'], true) && empty(trim((string) ($validated['catatan'] ?? '')))) {
            return back()->with('error', 'Catatan wajib diisi saat keputusan laporan ditolak atau revisi.');
        }

        $report = DB::table('reports')->where('id', $id)->first();
        if (!$report) {
            return back()->with('error', 'Data laporan tidak ditemukan.');
        }

        if ((string) $report->status === 'draft') {
            return back()->with('error', 'Laporan masih draft. Minta pengurus mengirim laporan terlebih dahulu.');
        }

        $statusMap = [
            'disetujui' => 'approved',
            'ditolak' => 'rejected',
            'revisi' => 'revision_needed',
        ];

        $note = trim((string) ($validated['catatan'] ?? '')) ?: null;

        DB::table('reports')
            ->where('id', $id)
            ->update([
                'status' => $statusMap[$validated['decision']],
                'reviewer_notes' => $note,
                'department_review_note' => $note,
                'reviewed_by_department_user_id' => $this->resolveSessionUserId($request),
                'reviewed_at' => now(),
                'approved_date' => $validated['decision'] === 'disetujui' ? now()->toDateString() : null,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Review laporan kegiatan berhasil disimpan.');
    }

    public function storeJadwal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:120',
            'organization_id' => 'required|integer|exists:organizations,id',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:120',
        ]);

        DB::table('kemahasiswaan_schedules')->insert([
            'organization_id' => (int) $validated['organization_id'],
            'title' => $validated['judul'],
            'start_at' => Carbon::parse($validated['tanggal'])->startOfDay(),
            'end_at' => null,
            'location' => $validated['lokasi'],
            'status' => 'planned',
            'created_by' => $this->resolveSessionUserId($request),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Jadwal kegiatan berhasil ditambahkan.');
    }

    private function getPengajuan(?int $organizationId = null): array
    {
        $query = DB::table('submissions as sub')
            ->leftJoin('organizations as org', 'org.id', '=', 'sub.organization_id')
            ->select([
                'sub.id',
                'sub.title',
                'sub.status',
                'sub.submitted_date',
                'sub.created_at',
                'sub.department_review_note',
                'sub.feedback',
                'org.name as organization_name',
            ])
            ->orderByDesc('sub.id');

        if ($organizationId) {
            $query->where('sub.organization_id', $organizationId);
        }

        $rows = $query->get();

        return $rows->map(function ($row) {
            return [
                'id' => (int) $row->id,
                'judul' => $row->title,
                'organisasi' => $row->organization_name ?? '-',
                'tanggal_kegiatan' => $this->normalizeDateField($row->submitted_date, $row->created_at),
                'status' => $this->mapSubmissionStatus((string) $row->status),
                'catatan_departemen' => $row->department_review_note ?: $row->feedback,
            ];
        })->all();
    }

    private function getLaporan(?int $organizationId = null): array
    {
        $query = DB::table('reports as rep')
            ->leftJoin('organizations as org', 'org.id', '=', 'rep.organization_id')
            ->select([
                'rep.id',
                'rep.title',
                'rep.status',
                'rep.submitted_date',
                'rep.created_at',
                'rep.department_review_note',
                'rep.reviewer_notes',
                'org.name as organization_name',
            ])
            ->orderByDesc('rep.id');

        if ($organizationId) {
            $query->where('rep.organization_id', $organizationId);
        }

        $rows = $query->get();

        return $rows->map(function ($row) {
            return [
                'id' => (int) $row->id,
                'judul' => $row->title,
                'organisasi' => $row->organization_name ?? '-',
                'tanggal_laporan' => $this->normalizeDateField($row->submitted_date, $row->created_at),
                'status' => $this->mapReportStatus((string) $row->status),
                'catatan_departemen' => $row->department_review_note ?: $row->reviewer_notes,
            ];
        })->all();
    }

    private function getJadwal(): array
    {
        $rows = DB::table('kemahasiswaan_schedules as jadwal')
            ->leftJoin('organizations as org', 'org.id', '=', 'jadwal.organization_id')
            ->select([
                'jadwal.id',
                'jadwal.title',
                'jadwal.start_at',
                'jadwal.location',
                'org.name as organization_name',
            ])
            ->orderBy('jadwal.start_at')
            ->get();

        return $rows->map(function ($row) {
            return [
                'id' => (int) $row->id,
                'judul' => $row->title,
                'organisasi' => $row->organization_name ?? '-',
                'tanggal' => $this->normalizeDateField($row->start_at, $row->start_at),
                'lokasi' => $row->location,
            ];
        })->all();
    }

    private function getKontakPengurus(): array
    {
        $rows = DB::table('members as mem')
            ->leftJoin('organizations as org', 'org.id', '=', 'mem.organization_id')
            ->select([
                'mem.name',
                'mem.email',
                'mem.phone',
                'mem.position',
                'org.name as organization_name',
            ])
            ->where('mem.status', 'aktif')
            ->orderBy('org.name')
            ->orderBy('mem.name')
            ->limit(200)
            ->get();

        return $rows->map(function ($row) {
            return [
                'nama' => $row->name,
                'organisasi' => $row->organization_name ?? '-',
                'jabatan' => Str::title((string) $row->position),
                'kontak' => $row->phone ?: '-',
                'email' => $row->email ?: '-',
            ];
        })->all();
    }

    private function getEventOptions(int $organizationId): array
    {
        return DB::table('events')
            ->select(['id', 'name'])
            ->where('organization_id', $organizationId)
            ->orderByDesc('id')
            ->limit(200)
            ->get()
            ->map(fn ($row) => [
                'id' => (int) $row->id,
                'name' => $row->name,
            ])
            ->all();
    }

    private function getOrganizations(): array
    {
        return DB::table('organizations')
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get()
            ->map(fn ($row) => [
                'id' => (int) $row->id,
                'name' => $row->name,
            ])
            ->all();
    }

    private function mapSubmissionStatus(string $status): string
    {
        return [
            'draft' => 'Draft',
            'submitted' => 'Diajukan',
            'reviewing' => 'Sedang Direview',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revised' => 'Revisi',
        ][$status] ?? Str::title(str_replace('_', ' ', $status));
    }

    private function mapReportStatus(string $status): string
    {
        return [
            'draft' => 'Draft',
            'submitted' => 'Diajukan',
            'reviewing' => 'Sedang Direview',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revision_needed' => 'Revisi',
        ][$status] ?? Str::title(str_replace('_', ' ', $status));
    }

    private function normalizeDateField(mixed $primary, mixed $fallback): string
    {
        $value = $primary ?: $fallback;

        if (!$value) {
            return now()->toDateString();
        }

        return Carbon::parse((string) $value)->toDateString();
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

    private function resolvePengurusContext(Request $request): array
    {
        $email = (string) data_get($request->session()->get('user'), 'email', '');

        $organizationId = null;
        $memberId = null;

        if ($email !== '') {
            $user = DB::table('users')
                ->select(['id', 'organization_id'])
                ->where('email', $email)
                ->first();

            if ($user && $user->organization_id) {
                $organizationId = (int) $user->organization_id;
            }

            if (!$organizationId) {
                $ukmAccount = DB::table('kemahasiswaan_ukm_accounts')
                    ->select(['organization_id'])
                    ->where('email', $email)
                    ->first();

                if ($ukmAccount && $ukmAccount->organization_id) {
                    $organizationId = (int) $ukmAccount->organization_id;
                }
            }
        }

        $organizationName = null;
        if ($organizationId) {
            $organization = DB::table('organizations')->select(['id', 'name'])->where('id', $organizationId)->first();
            $organizationName = $organization->name ?? null;

            if ($email !== '') {
                $member = DB::table('members')
                    ->select('id')
                    ->where('organization_id', $organizationId)
                    ->where('email', $email)
                    ->first();

                if ($member) {
                    $memberId = (int) $member->id;
                }
            }

            if (!$memberId) {
                $fallbackMember = DB::table('members')
                    ->select('id')
                    ->where('organization_id', $organizationId)
                    ->where('status', 'aktif')
                    ->orderByRaw("CASE WHEN position = 'ketua' THEN 0 ELSE 1 END")
                    ->orderBy('id')
                    ->first();

                if ($fallbackMember) {
                    $memberId = (int) $fallbackMember->id;
                }
            }
        }

        return [
            'organization_id' => $organizationId,
            'organization_name' => $organizationName,
            'member_id' => $memberId,
        ];
    }
}
