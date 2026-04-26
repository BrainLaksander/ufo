<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class IzinKegiatanWorkflowController extends Controller
{
    private array $referenceCache = [];

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
            ...$this->buildPengurusPlaceholderData($context['organization_name']),
            ...$this->buildPengurusShellData($organizationId),
        ]);
    }

    public function pengurusAnnouncementForm(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);

        return view('portal.pengurus.announcements.form', [
            ...$this->buildPengurusPlaceholderData($context['organization_name']),
            ...$this->buildPengurusShellData($context['organization_id']),
        ]);
    }

    public function pengurusSettings(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);

        $org = null;
        if ($context['organization_id']) {
            $org = DB::table('organizations')->where('id', $context['organization_id'])->first();
        }

        return view('portal.pengurus.settings', [
            'hasOrganizationContext' => $org !== null,
            'orgData' => [
                'id' => $org->id ?? null,
                'name' => $org->name ?? '',
                'shortname' => $org->shortname ?? '',
                'description' => $org->description ?? '',
                'vision' => $org->vision ?? '',
                'mission' => $org->mission ?? '',
                'email' => $org->email ?? '',
                'phone' => $org->phone ?? '',
                'instagram' => $org->instagram ?? '',
                'line' => $org->line ?? '',
            ],
            ...$this->buildPengurusShellData($context['organization_id']),
        ]);
    }

    public function updateProfilUKM(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);

        if (!$context['organization_id']) {
            return back()->with('error', $this->refLabel('flash_message', 'org_context_missing'));
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

        return back()->with('success', $this->refLabel('flash_message', 'profile_updated'));
    }

    public function updatePengurusMembersProfile(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);

        if (!$context['organization_id']) {
            return back()->with('error', $this->refLabel('flash_message', 'org_context_missing'));
        }

        $validated = $request->validate([
            'category' => 'nullable|string|max:80',
            'type' => 'nullable|string|max:20',
            'level' => 'nullable|string|max:40',
            'field' => 'nullable|string|max:120',
            'vision_text' => 'nullable|string|max:2000',
            'mission_text' => 'nullable|string|max:4000',
            'culture_text' => 'nullable|string|max:4000',
            'email' => 'nullable|email|max:120',
            'phone' => 'nullable|string|max:40',
            'instagram' => 'nullable|string|max:120',
            'line' => 'nullable|string|max:120',
            'values_text' => 'nullable|string|max:12000',
            'programs_text' => 'nullable|string|max:20000',
            'structure_text' => 'nullable|string|max:12000',
            'contacts_text' => 'nullable|string|max:12000',
            'logo_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $table = 'organizations';

        $payload = [
            'vision' => $validated['vision_text'] ?? null,
            'mission' => $validated['mission_text'] ?? null,
            'description' => $validated['culture_text'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'line' => $validated['line'] ?? null,
            'updated_at' => now(),
        ];

        if (Schema::hasColumn($table, 'category')) {
            $payload['category'] = $validated['category'] ?? null;
        }

        if (Schema::hasColumn($table, 'type')) {
            $payload['type'] = $validated['type'] ?? null;
        }

        if (Schema::hasColumn($table, 'level')) {
            $payload['level'] = $validated['level'] ?? null;
        }

        if (Schema::hasColumn($table, 'field')) {
            $payload['field'] = $validated['field'] ?? null;
        }

        if ($request->hasFile('logo_file')) {
            $payload['logo'] = $this->storeOrganizationMedia(
                $request->file('logo_file'),
                (int) $context['organization_id'],
                'logo'
            );
        }

        if ($request->hasFile('banner_file')) {
            $payload['banner'] = $this->storeOrganizationMedia(
                $request->file('banner_file'),
                (int) $context['organization_id'],
                'banner'
            );
        }

        if (Schema::hasColumn($table, 'profile_values_json')) {
            $payload['profile_values_json'] = json_encode($this->parseProfileRows($validated['values_text'] ?? '', ['name', 'desc'], 20), JSON_UNESCAPED_UNICODE);
        }

        if (Schema::hasColumn($table, 'profile_programs_json')) {
            $payload['profile_programs_json'] = json_encode($this->parseProfileRows($validated['programs_text'] ?? '', ['nama', 'periode', 'tujuan', 'kegiatan', 'output'], 30), JSON_UNESCAPED_UNICODE);
        }

        if (Schema::hasColumn($table, 'profile_structure_json')) {
            $payload['profile_structure_json'] = json_encode($this->parseProfileRows($validated['structure_text'] ?? '', ['jabatan', 'nama'], 40), JSON_UNESCAPED_UNICODE);
        }

        if (Schema::hasColumn($table, 'profile_contacts_json')) {
            $payload['profile_contacts_json'] = json_encode($this->parseProfileRows($validated['contacts_text'] ?? '', ['nama', 'jabatan', 'whatsapp'], 40), JSON_UNESCAPED_UNICODE);
        }

        DB::table($table)
            ->where('id', $context['organization_id'])
            ->update($payload);

        return back()->with('success', $this->refLabel('flash_message', 'profile_updated'));
    }

    public function kemahasiswaanIndex(): View
    {
        $workflowPengajuan = $this->getPengajuan();
        $workflowLaporan = $this->getLaporan();

        return view('portal.kemahasiswaan.pengajuan', [
            'workflowPengajuan' => $workflowPengajuan,
            'workflowLaporan' => $workflowLaporan,
            'jadwalKegiatan' => $this->getJadwal(),
            'organizations' => $this->getOrganizations(),
            'ui' => $this->buildKemahasiswaanPengajuanUiText(),
            'headerNotificationCount' => $this->countKemahasiswaanPendingNotifications($workflowPengajuan, $workflowLaporan),
        ]);
    }

    public function pengurusDashboard(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = $context['organization_id'];

        $month = now()->startOfMonth();
        $monthQuery = trim((string) $request->query('bulan', ''));

        if (preg_match('/^\d{4}-\d{2}$/', $monthQuery) === 1) {
            try {
                $month = Carbon::createFromFormat('Y-m', $monthQuery)->startOfMonth();
            } catch (\Throwable) {
                $month = now()->startOfMonth();
            }
        }

        $start = $month->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
        $end = $month->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

        $activities = $this->getDashboardActivities($organizationId, $start, $end);
        [$profileStatusValue, $profileStatusLabel] = $this->buildDashboardProfileStatus($organizationId);

        return view('portal.pengurus.dashboard', [
            'activities' => $activities,
            'summaryCards' => $this->buildDashboardSummaryCards($activities, $organizationId, $month),
            'legendItems' => $this->buildDashboardLegend($activities),
            'calendarDays' => $this->buildCalendarDays($activities, $month, $start, $end, now()),
            'pendingTasks' => $this->getPendingTasks($organizationId),
            'monthLabel' => $month->translatedFormat('F Y'),
            'prevMonth' => $month->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $month->copy()->addMonth()->format('Y-m'),
            'profileStatusValue' => $profileStatusValue,
            'profileStatusLabel' => $profileStatusLabel,
            ...$this->buildPengurusShellData($organizationId),
        ]);
    }

    public function pengurusEvents(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = $context['organization_id'];

        $rows = collect();
        if (Schema::hasTable('events')) {
            $query = DB::table('events as evt')
                ->leftJoin('organizations as org', 'org.id', '=', 'evt.organization_id')
                ->select([
                    'evt.id',
                    'evt.name',
                    'evt.description',
                    'evt.start_date',
                    'evt.end_date',
                    'evt.location',
                    'evt.current_participants',
                    'evt.quota',
                    'evt.status',
                    'evt.banner',
                    'org.name as organization_name',
                ])
                ->orderByDesc('evt.id')
                ->limit(200);

            if ($organizationId) {
                $query->where('evt.organization_id', $organizationId);
            }

            $rows = $query->get();
        }

        $announcementTitles = collect();
        if (Schema::hasTable('kemahasiswaan_announcements')) {
            $announcementQuery = DB::table('kemahasiswaan_announcements as ann')
                ->select('ann.title')
                ->where('ann.publish_status', 'published')
                ->limit(300);

            if ($organizationId && Schema::hasTable('kemahasiswaan_ukm_accounts')) {
                $announcementQuery
                    ->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'ann.ukm_account_id')
                    ->where('akun.organization_id', $organizationId);
            }

            $announcementTitles = $announcementQuery
                ->pluck('title')
                ->filter()
                ->map(fn ($title) => Str::lower((string) $title));
        }

        $mapped = $rows->map(function ($row) use ($announcementTitles) {
            $startDate = $row->start_date ? Carbon::parse($row->start_date) : null;
            $endDate = $row->end_date ? Carbon::parse($row->end_date) : $startDate;
            [$statusLabel, $pill] = $this->mapEventStatusToPortal((string) $row->status, $startDate, $endDate);

            $hasNews = $announcementTitles->contains(function ($title) use ($row) {
                $needle = Str::lower((string) $row->name);
                return $needle !== '' && Str::contains((string) $title, $needle);
            });

            return [
                'id' => (int) $row->id,
                'title' => (string) $row->name,
                'description' => (string) ($row->description ?? ''),
                'date' => $startDate ? $startDate->translatedFormat('d F Y') : '',
                'raw_date' => $startDate ? $startDate->toDateString() : '',
                'time' => $startDate
                    ? $startDate->format('H:i') . ' - ' . ($endDate ? $endDate->format('H:i') : $startDate->format('H:i'))
                    : '',
                'location' => (string) ($row->location ?? ''),
                'registrants' => (int) ($row->current_participants ?? 0),
                'participants' => (int) ($row->current_participants ?? 0),
                'quota' => (int) ($row->quota ?? 0),
                'status' => $statusLabel,
                'raw_status' => (string) $row->status,
                'pill' => $pill,
                'banner' => $row->banner ? Storage::url($row->banner) : null,
                'has_news' => $hasNews,
            ];
        });

        $completedLabels = array_filter([
            $this->mapEventStatusToPortal('completed', null, null)[0],
            $this->mapEventStatusToPortal('cancelled', null, null)[0],
        ]);

        $activeEvents = $mapped->filter(function ($event) use ($completedLabels) {
            return !in_array($event['status'], $completedLabels, true);
        })->values()->all();

        $completedEvents = $mapped->filter(function ($event) use ($completedLabels) {
            return in_array($event['status'], $completedLabels, true);
        })->values()->all();

        return view('portal.pengurus.events', [
            'activeEvents' => $activeEvents,
            'completedEvents' => $completedEvents,
            ...$this->buildPengurusPlaceholderData($context['organization_name']),
            ...$this->buildPengurusShellData($organizationId),
        ]);
    }

    public function pengurusAnnouncements(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = $context['organization_id'];

        $rows = collect();
        if (Schema::hasTable('kemahasiswaan_announcements')) {
            $query = DB::table('kemahasiswaan_announcements as ann');

            if (Schema::hasTable('kemahasiswaan_ukm_accounts')) {
                $query->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'ann.ukm_account_id');
            }

            if ($organizationId && Schema::hasTable('kemahasiswaan_ukm_accounts')) {
                $query->where('akun.organization_id', $organizationId);
            }

            $rows = $query
                ->select([
                    'ann.id',
                    'ann.title',
                    'ann.summary',
                    'ann.content',
                    'ann.publish_at',
                    'ann.created_at',
                    'ann.publish_status',
                ])
                ->orderByDesc('ann.publish_at')
                ->orderByDesc('ann.created_at')
                ->limit(250)
                ->get();
        }

        $allAnnouncements = $rows->map(function ($row) {
            $start = $row->publish_at ? Carbon::parse($row->publish_at) : Carbon::parse($row->created_at);
            [$statusLabel, $pill] = $this->mapAnnouncementStatus((string) $row->publish_status);

            return [
                'id' => (int) $row->id,
                'title' => (string) $row->title,
                'description' => (string) ($row->summary ?: Str::limit(strip_tags((string) $row->content), 180)),
                'full_content' => (string) $row->content,
                'start_date' => $start->translatedFormat('d M Y'),
                'raw_start_date' => $start->toDateString(),
                'end_date' => $start->copy()->addDays(7)->translatedFormat('d M Y'),
                'raw_end_date' => $start->copy()->addDays(7)->toDateString(),
                'status' => $statusLabel,
                'raw_status' => (string) $row->publish_status,
                'pill' => $pill,
            ];
        })->values()->all();

        $activeAnnouncements = collect($allAnnouncements)
            ->filter(fn ($item) => ($item['status'] ?? '') === 'Aktif')
            ->values()
            ->all();

        $eventOptions = [];
        if ($organizationId) {
            $eventOptions = $this->getEventOptions($organizationId);
        } elseif (Schema::hasTable('events')) {
            $eventOptions = DB::table('events')
                ->select(['id', 'name'])
                ->orderByDesc('id')
                ->limit(200)
                ->get()
                ->map(fn ($row) => [
                    'id' => (int) $row->id,
                    'name' => (string) $row->name,
                ])
                ->all();
        }

        return view('portal.pengurus.announcements', [
            'activeAnnouncements' => $activeAnnouncements,
            'allAnnouncements' => $allAnnouncements,
            'eventOptions' => $eventOptions,
            ...$this->buildPengurusPlaceholderData($context['organization_name']),
            ...$this->buildPengurusShellData($organizationId),
        ]);
    }

    public function pengurusLostAndFound(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = $context['organization_id'];

        $organization = null;
        if ($organizationId && Schema::hasTable('organizations')) {
            $organization = DB::table('organizations')
                ->select(['id', 'name', 'shortname'])
                ->where('id', $organizationId)
                ->first();
        }

        $sessionRole = Str::lower((string) data_get($request->session()->get('user'), 'role', ''));
        $isBem = Str::contains($sessionRole, 'bem')
            || Str::contains(Str::lower((string) ($organization->shortname ?? '')), 'bem')
            || Str::contains(Str::lower((string) ($organization->name ?? '')), 'bem');

        $rows = collect();
        if (Schema::hasTable('lost_found_items')) {
            $query = DB::table('lost_found_items as lf');

            if (Schema::hasTable('users')) {
                $query->leftJoin('users as reporter', 'reporter.id', '=', 'lf.reported_by');
            }

            if ($organizationId) {
                $query->where('lf.organization_id', $organizationId);
            }

            $query->select([
                'lf.id',
                'lf.type',
                'lf.item_name',
                'lf.description',
                'lf.location_found',
                'lf.status',
                'lf.created_at',
                'lf.claimed_at',
                'lf.resolved_at',
            ]);

            if (Schema::hasTable('users')) {
                $query->addSelect('reporter.name as reporter_name');
                $query->addSelect('reporter.email as reporter_email');
            } else {
                $query->selectRaw('NULL as reporter_name');
                $query->selectRaw('NULL as reporter_email');
            }

            $rows = $query
                ->orderByDesc('lf.created_at')
                ->limit(200)
                ->get();
        }

        $items = $rows->map(function ($row) {
            [$status, $itemStatus] = $this->mapLostFoundModerationStatus((string) $row->status);
            $createdAt = $row->created_at ? Carbon::parse($row->created_at) : now();

            return [
                'id' => (int) $row->id,
                'type' => (string) ($row->type ?? ''),
                'title' => (string) ($row->item_name ?? ''),
                'description' => (string) ($row->description ?? ''),
                'location' => (string) ($row->location_found ?? ''),
                'date' => $createdAt->toDateString(),
                'reporter' => (string) ($row->reporter_name ?? ''),
                'contact' => (string) ($row->reporter_email ?? ''),
                'status' => $status,
                'item_status' => $itemStatus,
                'priority' => ((string) $row->type === 'lost') && ((string) $row->status === 'active') && $createdAt->lte(now()->subDays(2)),
                'notes' => '',
            ];
        })->values();

        $priorityItems = $items
            ->filter(fn ($item) => ($item['priority'] ?? false) && ($item['status'] ?? '') === 'approved' && ($item['item_status'] ?? '') === 'belum_ditemukan')
            ->values()
            ->all();

        return view('portal.pengurus.lostandfound', [
            'isBem' => $isBem,
            'items' => $items->all(),
            'priorityItems' => $priorityItems,
            'statusLabel' => collect($this->getReferenceMap('lostfound_review_status'))
                ->mapWithKeys(fn ($entry, $code) => [$code => (string) ($entry['label'] ?? '')])
                ->all(),
            'statusPill' => collect($this->getReferenceMap('lostfound_review_status'))
                ->mapWithKeys(fn ($entry, $code) => [$code => (string) ($entry['payload']['pill'] ?? '')])
                ->all(),
            ...$this->buildPengurusShellData($organizationId),
        ]);
    }

    public function pengurusMembers(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = $context['organization_id'];

        $organization = null;
        if ($organizationId && Schema::hasTable('organizations')) {
            $organization = DB::table('organizations')
                ->where('id', $organizationId)
                ->first();
        }

        $logoUrl = $this->resolveOrganizationMediaUrl((string) ($organization->logo ?? ''));
        $bannerUrl = $this->resolveOrganizationMediaUrl((string) ($organization->banner ?? ''));

        $members = collect();
        if ($organizationId && Schema::hasTable('members')) {
            $members = DB::table('members')
                ->where('organization_id', $organizationId)
                ->where('status', 'aktif')
                ->orderByRaw("CASE position WHEN 'ketua' THEN 1 WHEN 'sekretaris' THEN 2 WHEN 'bendahara' THEN 3 ELSE 4 END")
                ->orderBy('name')
                ->get();
        }

        $storedValues = $this->decodeProfileList($organization, 'profile_values_json', ['name', 'desc']);
        $storedPrograms = $this->decodeProfileList($organization, 'profile_programs_json', ['nama', 'periode', 'tujuan', 'kegiatan', 'output']);
        $storedStructure = $this->decodeProfileList($organization, 'profile_structure_json', ['jabatan', 'nama']);
        $storedContacts = $this->decodeProfileList($organization, 'profile_contacts_json', ['nama', 'jabatan', 'whatsapp']);

        $programKegiatan = [];
        if ($organizationId && Schema::hasTable('events')) {
            $programKegiatan = DB::table('events')
                ->where('organization_id', $organizationId)
                ->whereIn('status', ['approved', 'ongoing', 'completed'])
                ->orderByDesc('start_date')
                ->limit(8)
                ->get()
                ->map(function ($row) {
                    $start = $row->start_date ? Carbon::parse($row->start_date) : null;
                    $end = $row->end_date ? Carbon::parse($row->end_date) : $start;

                    return [
                        'nama' => (string) $row->name,
                        'periode' => $start
                            ? $start->translatedFormat('d M Y') . ($end ? ' - ' . $end->translatedFormat('d M Y') : '')
                            : '',
                        'tujuan' => Str::limit((string) $row->description, 160),
                        'kegiatan' => (string) ($row->location ?? ''),
                        'output' => (string) ($row->quota ?? ''),
                    ];
                })
                ->all();
        }

        $struktur = $members->map(fn ($row) => [
            'jabatan' => Str::title((string) $row->position),
            'nama' => (string) $row->name,
        ])->values()->all();

        $kontakPengurus = $members
            ->filter(fn ($row) => !empty($row->phone))
            ->take(10)
            ->map(fn ($row) => [
                'nama' => (string) $row->name,
                'jabatan' => Str::title((string) $row->position),
                'whatsapp' => (string) $row->phone,
            ])
            ->values()
            ->all();

        if (empty($programKegiatan) && !empty($storedPrograms)) {
            $programKegiatan = $storedPrograms;
        }

        if (empty($struktur) && !empty($storedStructure)) {
            $struktur = $storedStructure;
        }

        if (empty($kontakPengurus) && !empty($storedContacts)) {
            $kontakPengurus = $storedContacts;
        }

        $socialMedia = [];
        if ($organization) {
            if (!empty($organization->instagram)) {
                $socialMedia[] = ['platform' => 'Instagram', 'value' => (string) $organization->instagram];
            }

            if (!empty($organization->line)) {
                $socialMedia[] = ['platform' => 'LINE', 'value' => (string) $organization->line];
            }

            if (!empty($organization->email)) {
                $socialMedia[] = ['platform' => 'Email', 'value' => (string) $organization->email];
            }

            if (!empty($organization->phone)) {
                $socialMedia[] = ['platform' => 'Telepon', 'value' => (string) $organization->phone];
            }
        }

        $kategoriOptions = [];
        if (Schema::hasTable('organizations')) {
            $kategoriOptions = collect($kategoriOptions)
                ->merge(
                    DB::table('organizations')
                        ->select('shortname')
                        ->whereNotNull('shortname')
                        ->orderBy('shortname')
                        ->limit(40)
                        ->pluck('shortname')
                        ->map(fn ($name) => (string) $name)
                )
                ->filter(fn ($value) => $value !== '')
                ->unique()
                ->values()
                ->all();
        }

            if ($organization && !empty($organization->shortname)) {
                $kategoriOptions = collect($kategoriOptions)
                ->prepend((string) $organization->shortname)
                ->filter(fn ($value) => $value !== '')
                ->unique()
                ->values()
                ->all();
            }

        $filled = 0;
        $total = 8;
        if ($organization) {
            foreach (['description', 'vision', 'mission', 'email', 'phone', 'instagram', 'line', 'logo'] as $field) {
                if (!empty($organization->{$field})) {
                    $filled++;
                }
            }
        }
        $profileCompletion = $organization ? (int) round(($filled / $total) * 100) : 0;

        return view('portal.pengurus.members', [
            'kategoriOptions' => $kategoriOptions,
            'activeKategori' => (string) ($organization->category ?? $this->inferOrganizationCategory($organization)),
            'programKegiatan' => $programKegiatan,
            'struktur' => $struktur,
            'kontakPengurus' => $kontakPengurus,
            'socialMedia' => $socialMedia,
            'values' => !empty($storedValues) ? $storedValues : $this->missionToValues($organization?->mission),
            'profileCompletion' => $profileCompletion,
            'visionText' => (string) ($organization?->vision ?? ''),
            'missionText' => (string) ($organization?->mission ?? ''),
            'cultureText' => (string) ($organization?->description ?? ''),
            'organizationType' => (string) ($organization->type ?? ''),
            'organizationLevel' => (string) ($organization->level ?? ''),
            'organizationField' => (string) ($organization->field ?? ''),
            'contactEmail' => (string) ($organization->email ?? ''),
            'contactPhone' => (string) ($organization->phone ?? ''),
            'contactInstagram' => (string) ($organization->instagram ?? ''),
            'contactLine' => (string) ($organization->line ?? ''),
            'valuesText' => $this->formatProfileRows(!empty($storedValues) ? $storedValues : $this->missionToValues($organization?->mission), ['name', 'desc']),
            'programsText' => $this->formatProfileRows($programKegiatan, ['nama', 'periode', 'tujuan', 'kegiatan', 'output']),
            'structureText' => $this->formatProfileRows($struktur, ['jabatan', 'nama']),
            'contactsText' => $this->formatProfileRows($kontakPengurus, ['nama', 'jabatan', 'whatsapp']),
            'logoUrl' => $logoUrl,
            'bannerUrl' => $bannerUrl,
            ...$this->buildPengurusShellData($organizationId),
        ]);
    }

    public function pengurusApplications(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = $context['organization_id'];

        $contacts = collect();
        if (Schema::hasTable('members')) {
            $query = DB::table('members as mem')
                ->leftJoin('organizations as org', 'org.id', '=', 'mem.organization_id')
                ->select([
                    'mem.name',
                    'mem.position',
                    'mem.phone',
                    'mem.email',
                    'org.name as organization_name',
                ])
                ->where('mem.status', 'aktif')
                ->orderByRaw("CASE mem.position WHEN 'ketua' THEN 1 WHEN 'sekretaris' THEN 2 WHEN 'bendahara' THEN 3 ELSE 4 END")
                ->orderBy('mem.name')
                ->limit(24);

            if ($organizationId) {
                $query->where('mem.organization_id', $organizationId);
            }

            $contacts = $query->get();
        }

        $mappedContacts = $contacts->map(function ($row) {
            $phone = (string) ($row->phone ?? '');

            return [
                'name' => (string) $row->name,
                'role' => Str::title((string) $row->position) . (!empty($row->organization_name) ? ' - ' . (string) $row->organization_name : ''),
                'phone' => $phone,
                'email' => (string) ($row->email ?? ''),
                'whatsapp' => $this->formatWhatsappLink($phone),
            ];
        })->values()->all();

        return view('portal.pengurus.applications', [
            'contacts' => $mappedContacts,
            'supportInfo' => $this->buildPengurusSupportInfo(),
            ...$this->buildPengurusShellData($organizationId),
        ]);
    }

    public function pengurusEventDetail(Request $request, int $id): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = $context['organization_id'];

        abort_unless(Schema::hasTable('events'), 404);

        $query = DB::table('events')->where('id', $id);
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        $row = $query->first();
        abort_unless($row, 404);

        $startDate = $row->start_date ? Carbon::parse($row->start_date) : null;
        $endDate = $row->end_date ? Carbon::parse($row->end_date) : $startDate;
        [$statusLabel] = $this->mapEventStatusToPortal((string) $row->status, $startDate, $endDate);

        $participants = [];
        if (Schema::hasTable('members')) {
            $participants = DB::table('members')
                ->where('organization_id', $row->organization_id)
                ->where('status', 'aktif')
                ->orderBy('name')
                ->limit(max((int) ($row->current_participants ?? 0), 20))
                ->get()
                ->map(fn ($member) => [
                    'name' => (string) $member->name,
                    'nim' => (string) $member->nim,
                    'status' => (string) ($member->status ?? ''),
                ])
                ->all();
        }

        return view('portal.pengurus.events.detail', [
            'event' => [
                'id' => (int) $row->id,
                'name' => (string) $row->name,
                'date' => $startDate ? $startDate->toDateString() : '',
                'time' => $startDate ? $startDate->format('H:i') : '',
                'location' => (string) ($row->location ?? ''),
                'quota' => (int) ($row->quota ?? 0),
                'description' => (string) ($row->description ?? ''),
                'status' => $statusLabel,
                'participants' => $participants,
            ],
            ...$this->buildPengurusShellData($organizationId),
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

        return view('portal.pengurus.events.form', [
            'hasApprovedIzin' => $hasApprovedIzin,
            'hasPengurusContext' => $context['organization_id'] !== null && $context['member_id'] !== null,
            'pengurusOrganizationName' => $context['organization_name'],
            ...$this->buildPengurusPlaceholderData($context['organization_name']),
            ...$this->buildPengurusShellData($organizationId),
        ]);
    }

    public function storeEvent(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);
        if (!$context['organization_id']) {
            return back()->with('error', 'Konteks organisasi tidak ditemukan.');
        }

        // Safety check: only allow publishing if there's at least one approved submission
        if ($request->input('status') === 'approved') {
            $hasApprovedIzin = DB::table('submissions')
                ->where('organization_id', $context['organization_id'])
                ->where('status', 'approved')
                ->exists();

            if (!$hasApprovedIzin) {
                return back()->with('error', 'Anda harus memiliki izin kegiatan yang sudah disetujui (Approved) sebelum dapat mempublikasikan event secara publik.');
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'location' => 'required|string|max:200',
            'quota' => 'required|integer|min:1',
            'status' => 'required|in:draft,approved',
            'banner' => 'nullable|image|max:5120',
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $this->storeOrganizationMedia($request->file('banner'), (int)$context['organization_id'], 'event');
        }

        DB::table('events')->insert([
            'organization_id' => $context['organization_id'],
            'created_by' => $this->resolveSessionUserId($request),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['start_date'],
            'location' => $validated['location'],
            'quota' => $validated['quota'],
            'status' => $validated['status'],
            'banner' => $bannerPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Event berhasil disimpan!');
    }

    public function storeNews(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);
        
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $ukmAccountId = DB::table('kemahasiswaan_ukm_accounts')
            ->where('organization_id', $context['organization_id'])
            ->value('id');

        DB::table('kemahasiswaan_announcements')->insert([
            'ukm_account_id' => $ukmAccountId,
            'title' => $validated['title'],
            'category' => 'event',
            'content' => $validated['content'],
            'summary' => Str::limit(strip_tags($validated['content']), 150),
            'publish_at' => now(),
            'publish_status' => 'published',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Berita event berhasil dipublikasikan!');
    }

    public function storeAnnouncement(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'event_id' => 'nullable|integer',
        ]);

        $ukmAccountId = DB::table('kemahasiswaan_ukm_accounts')
            ->where('organization_id', $context['organization_id'])
            ->value('id');

        DB::table('kemahasiswaan_announcements')->insert([
            'ukm_account_id' => $ukmAccountId,
            'title' => $validated['title'],
            'category' => 'announcement',
            'content' => $validated['description'],
            'summary' => Str::limit(strip_tags($validated['description']), 150),
            'publish_at' => $validated['start_date'],
            'publish_status' => 'published',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Pengumuman berhasil dipublikasikan!');
    }

    public function updateEvent(Request $request, int $id): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);
        if (!$context['organization_id']) {
            return back()->with('error', 'Konteks organisasi tidak ditemukan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'location' => 'required|string|max:200',
            'quota' => 'required|integer|min:1',
            'status' => 'required|in:draft,approved',
            'banner' => 'nullable|image|max:5120',
        ]);

        $payload = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['start_date'],
            'location' => $validated['location'],
            'quota' => $validated['quota'],
            'status' => $validated['status'],
            'updated_at' => now(),
        ];

        if ($request->hasFile('banner')) {
            $payload['banner'] = $this->storeOrganizationMedia($request->file('banner'), (int)$context['organization_id'], 'event');
        }

        DB::table('events')
            ->where('id', $id)
            ->where('organization_id', $context['organization_id'])
            ->update($payload);

        return back()->with('success', 'Event berhasil diperbarui!');
    }

    public function updateAnnouncement(Request $request, int $id): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);
        
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        DB::table('kemahasiswaan_announcements')
            ->where('id', $id)
            ->update([
                'title' => $validated['title'],
                'content' => $validated['description'],
                'summary' => Str::limit(strip_tags($validated['description']), 150),
                'publish_at' => $validated['start_date'],
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Pengumuman berhasil diperbarui!');
    }


    public function storeLostFound(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);
        if (!$context['organization_id']) {
            return back()->with('error', 'Konteks organisasi tidak ditemukan.');
        }

        $validated = $request->validate([
            'item_name' => 'required|string|max:200',
            'type' => 'required|in:lost,found',
            'location_found' => 'required|string|max:200',
            'description' => 'required|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = Storage::url($request->file('image')->store('lost-found', 'public'));
        }

        DB::table('lost_found_items')->insert([
            'organization_id' => $context['organization_id'],
            'reported_by' => $this->resolveSessionUserId($request),
            'item_name' => $validated['item_name'],
            'type' => $validated['type'],
            'location_found' => $validated['location_found'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'status' => 'active', // Use 'active' code from reference map
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Laporan Lost & Found berhasil disimpan!');
    }

    public function storePengajuan(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);

        if (!$context['organization_id'] || !$context['member_id']) {
            return back()->with('error', $this->refLabel('flash_message', 'pengurus_data_incomplete'));
        }

        $validated = $request->validate([
            'title' => 'required|string|max:180',
            'description' => 'required|string|max:3000',
            'type' => 'required|in:proposal,budget,activity_plan',
            'proposal_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('proposal_file')) {
            $filePath = $request->file('proposal_file')->store('proposals', 'public');
        }

        DB::table('submissions')->insert([
            'organization_id' => $context['organization_id'],
            'member_id' => $context['member_id'],
            'reviewed_by_department_user_id' => null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'status' => 'draft',
            'feedback' => null,
            'department_review_note' => null,
            'revision_count' => 0,
            'submitted_date' => null,
            'approved_date' => null,
            'reviewed_at' => null,
            'file_path' => $filePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', $this->refLabel('flash_message', 'proposal_created'));
    }

    public function storeLaporan(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);

        if (!$context['organization_id'] || !$context['member_id']) {
            return back()->with('error', $this->refLabel('flash_message', 'pengurus_data_incomplete'));
        }

        $validated = $request->validate([
            'title' => 'required|string|max:180',
            'content' => 'required|string|max:5000',
            'participants' => 'required|integer|min:0',
            'report_type' => 'required|in:activity,financial,semester',
            'event_id' => 'nullable|integer|exists:events,id',
            'report_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('report_file')) {
            $attachmentPath = $request->file('report_file')->store('reports', 'public');
        }

        if (!empty($validated['event_id'])) {
            $event = DB::table('events')
                ->where('id', $validated['event_id'])
                ->where('organization_id', $context['organization_id'])
                ->first();

            if (!$event) {
                return back()->with('error', $this->refLabel('flash_message', 'report_event_invalid'));
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
            'attachment' => $attachmentPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', $this->refLabel('flash_message', 'report_draft_created'));
    }

    public function submit(Request $request, int $id): RedirectResponse
    {
        $submission = DB::table('submissions')->where('id', $id)->first();
        if (!$submission) {
            return back()->with('error', $this->refLabel('flash_message', 'submission_not_found'));
        }

        $context = $this->resolvePengurusContext($request);
        if ($context['organization_id'] && (int) $submission->organization_id !== $context['organization_id']) {
            return back()->with('error', $this->refLabel('flash_message', 'submission_not_owned'));
        }

        if (!in_array((string) $submission->status, ['draft', 'revised'], true)) {
            return back()->with('error', $this->refLabel('flash_message', 'submission_status_invalid_for_submit'));
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

        return back()->with('success', $this->refLabel('flash_message', 'submission_submitted'));
    }

    public function submitLaporan(Request $request, int $id): RedirectResponse
    {
        $report = DB::table('reports')->where('id', $id)->first();
        if (!$report) {
            return back()->with('error', $this->refLabel('flash_message', 'report_not_found'));
        }

        $context = $this->resolvePengurusContext($request);
        if ($context['organization_id'] && (int) $report->organization_id !== $context['organization_id']) {
            return back()->with('error', $this->refLabel('flash_message', 'report_not_owned'));
        }

        if (!in_array((string) $report->status, ['draft', 'revision_needed'], true)) {
            return back()->with('error', $this->refLabel('flash_message', 'report_status_invalid_for_submit'));
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

        return back()->with('success', $this->refLabel('flash_message', 'report_submitted'));
    }

    public function review(Request $request, int $id): RedirectResponse
    {
        $decisionOptions = array_keys($this->getReferenceMap('review_submission_decision_map'));

        $validated = $request->validate([
            'decision' => 'required|in:' . implode(',', $decisionOptions),
            'catatan' => 'nullable|string|max:200',
        ]);

        $decisionConfig = $this->getReferencePayload('review_submission_decision_map', (string) $validated['decision']);
        if (($decisionConfig['requires_note'] ?? false) && empty(trim((string) ($validated['catatan'] ?? '')))) {
            return back()->with('error', $this->refLabel('flash_message', 'review_note_required_submission'));
        }

        $submission = DB::table('submissions')->where('id', $id)->first();
        if (!$submission) {
            return back()->with('error', $this->refLabel('flash_message', 'submission_not_found'));
        }

        if ((string) $submission->status === 'draft') {
            return back()->with('error', $this->refLabel('flash_message', 'submission_still_draft'));
        }

        $statusMap = collect($this->getReferenceMap('review_submission_decision_map'))
            ->mapWithKeys(fn ($entry, $code) => [$code => (string) ($entry['payload']['value'] ?? '')])
            ->all();

        $nextStatus = $statusMap[$validated['decision']] ?? '';
        if ($nextStatus === '') {
            return back()->with('error', $this->refLabel('flash_message', 'submission_review_config_missing'));
        }

        DB::table('submissions')
            ->where('id', $id)
            ->update([
                'status' => $nextStatus,
                'department_review_note' => trim((string) ($validated['catatan'] ?? '')) ?: null,
                'reviewed_by_department_user_id' => $this->resolveSessionUserId($request),
                'reviewed_at' => now(),
                'approved_date' => ($decisionConfig['approved'] ?? false) ? now()->toDateString() : null,
                'updated_at' => now(),
            ]);

        return back()->with('success', $this->refLabel('flash_message', 'submission_review_saved'));
    }

    public function reviewLaporan(Request $request, int $id): RedirectResponse
    {
        $decisionOptions = array_keys($this->getReferenceMap('review_report_decision_map'));

        $validated = $request->validate([
            'decision' => 'required|in:' . implode(',', $decisionOptions),
            'catatan' => 'nullable|string|max:200',
        ]);

        $decisionConfig = $this->getReferencePayload('review_report_decision_map', (string) $validated['decision']);
        if (($decisionConfig['requires_note'] ?? false) && empty(trim((string) ($validated['catatan'] ?? '')))) {
            return back()->with('error', $this->refLabel('flash_message', 'review_note_required_report'));
        }

        $report = DB::table('reports')->where('id', $id)->first();
        if (!$report) {
            return back()->with('error', $this->refLabel('flash_message', 'report_not_found'));
        }

        if ((string) $report->status === 'draft') {
            return back()->with('error', $this->refLabel('flash_message', 'report_still_draft'));
        }

        $statusMap = collect($this->getReferenceMap('review_report_decision_map'))
            ->mapWithKeys(fn ($entry, $code) => [$code => (string) ($entry['payload']['value'] ?? '')])
            ->all();

        $nextStatus = $statusMap[$validated['decision']] ?? '';
        if ($nextStatus === '') {
            return back()->with('error', $this->refLabel('flash_message', 'report_review_config_missing'));
        }

        $note = trim((string) ($validated['catatan'] ?? '')) ?: null;

        DB::table('reports')
            ->where('id', $id)
            ->update([
                'status' => $nextStatus,
                'reviewer_notes' => $note,
                'department_review_note' => $note,
                'reviewed_by_department_user_id' => $this->resolveSessionUserId($request),
                'reviewed_at' => now(),
                'approved_date' => ($decisionConfig['approved'] ?? false) ? now()->toDateString() : null,
                'updated_at' => now(),
            ]);

        return back()->with('success', $this->refLabel('flash_message', 'report_review_saved'));
    }

    public function storeJadwal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:120',
            'organization_id' => 'required|integer|exists:organizations,id',
            'tanggal_mulai' => 'nullable|date|required_without:tanggal',
            'tanggal' => 'nullable|date|required_without:tanggal_mulai',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'kategori' => 'nullable|string|in:acad,org,restricted,holiday,campus',
            'lokasi' => 'required|string|max:120',
            'deskripsi' => 'nullable|string|max:4000',
        ]);

        $startRaw = $validated['tanggal_mulai'] ?? $validated['tanggal'] ?? null;
        $endRaw = $validated['tanggal_selesai'] ?? $startRaw;
        $kategori = $validated['kategori'] ?? 'org';

        $insertPayload = [
            'organization_id' => (int) $validated['organization_id'],
            'title' => $validated['judul'],
            'start_at' => Carbon::parse((string) $startRaw)->startOfDay(),
            'end_at' => $endRaw ? Carbon::parse((string) $endRaw)->endOfDay() : null,
            'location' => $validated['lokasi'],
            'status' => 'planned',
            'created_by' => $this->resolveSessionUserId($request),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('kemahasiswaan_schedules', 'category')) {
            $insertPayload['category'] = $kategori;
        }

        if (Schema::hasColumn('kemahasiswaan_schedules', 'description')) {
            $insertPayload['description'] = $validated['deskripsi'] ?? null;
        }

        DB::table('kemahasiswaan_schedules')->insert($insertPayload);

        return back()->with('success', $this->refLabel('flash_message', 'schedule_created'));
    }

    private function buildPengurusShellData(?int $organizationId): array
    {
        $notifications = [];

        if (Schema::hasTable('kemahasiswaan_activity_logs')) {
            $query = DB::table('kemahasiswaan_activity_logs as log')
                ->select([
                    'log.action',
                    'log.description',
                    'log.created_at',
                ])
                ->orderByDesc('log.created_at')
                ->limit(10);

            if ($organizationId) {
                $query->where('log.organization_id', $organizationId);
            }

            $notifications = $query->get()->map(function ($row) {
                $action = Str::lower((string) $row->action);

                $rules = $this->getReferenceMap('notification_action_rule');
                $defaultRule = $rules['default']['payload'] ?? [];
                $icon = (string) ($defaultRule['icon'] ?? '');
                $tone = (string) ($defaultRule['tone'] ?? '');

                foreach ($rules as $code => $rule) {
                    if ($code === 'default') {
                        continue;
                    }

                    $keywords = $rule['payload']['keywords'] ?? [];
                    if (!is_array($keywords) || empty($keywords)) {
                        continue;
                    }

                    if (Str::contains($action, $keywords)) {
                        $icon = (string) ($rule['payload']['icon'] ?? $icon);
                        $tone = (string) ($rule['payload']['tone'] ?? $tone);
                        break;
                    }
                }

                return [
                    'title' => Str::title(str_replace('_', ' ', (string) $row->action)),
                    'description' => (string) ($row->description ?: $this->refLabel('ui_text', 'activity_default_description')),
                    'timestamp' => $row->created_at ? Carbon::parse($row->created_at)->diffForHumans() : '',
                    'icon' => $icon,
                    'tone' => $tone,
                ];
            })->take(5)->values()->all();
        }

        if (empty($notifications) && Schema::hasTable('activity_logs')) {
            $query = DB::table('activity_logs as log')
                ->select([
                    'log.activity_type as action',
                    'log.description',
                    'log.created_at',
                ])
                ->orderByDesc('log.created_at')
                ->limit(10);

            if ($organizationId) {
                $query->where('log.organization_id', $organizationId);
            }

            $notifications = $query->get()->map(function ($row) {
                $action = (string) $row->action;
                $defaultRule = $this->getReferencePayload('notification_action_rule', 'default');

                return [
                    'title' => Str::title(str_replace('_', ' ', $action)),
                    'description' => (string) ($row->description ?: $this->refLabel('ui_text', 'activity_default_description')),
                    'timestamp' => $row->created_at ? Carbon::parse($row->created_at)->diffForHumans() : '',
                    'icon' => (string) ($defaultRule['icon'] ?? ''),
                    'tone' => (string) ($defaultRule['tone'] ?? ''),
                ];
            })->take(5)->values()->all();
        }

        return [
            'notifications' => $notifications,
        ];
    }

    private function buildPengurusPlaceholderData(?string $organizationName): array
    {
        $organization = trim((string) $organizationName);
        $withOrganization = static function (string $text) use ($organization): string {
            if ($text === '') {
                return '';
            }

            return str_replace('{organization}', $organization, $text);
        };

        return [
            'eventNamePlaceholder' => $withOrganization($this->refLabel('ui_text', 'pengurus_event_name_placeholder')),
            'eventLocationPlaceholder' => $withOrganization($this->refLabel('ui_text', 'pengurus_event_location_placeholder')),
            'eventDescriptionPlaceholder' => $withOrganization($this->refLabel('ui_text', 'pengurus_event_description_placeholder')),
            'proposalTitlePlaceholder' => $withOrganization($this->refLabel('ui_text', 'pengurus_proposal_title_placeholder')),
            'proposalDescriptionPlaceholder' => $withOrganization($this->refLabel('ui_text', 'pengurus_proposal_description_placeholder')),
            'reportTitlePlaceholder' => $withOrganization($this->refLabel('ui_text', 'pengurus_report_title_placeholder')),
            'reportContentPlaceholder' => $withOrganization($this->refLabel('ui_text', 'pengurus_report_content_placeholder')),
            'announcementTitlePlaceholder' => $withOrganization($this->refLabel('ui_text', 'pengurus_announcement_title_placeholder')),
            'announcementDescriptionPlaceholder' => $withOrganization($this->refLabel('ui_text', 'pengurus_announcement_description_placeholder')),
            'eventNewsTitlePlaceholder' => $withOrganization($this->refLabel('ui_text', 'pengurus_event_news_title_placeholder')),
            'eventNewsDescriptionPlaceholder' => $withOrganization($this->refLabel('ui_text', 'pengurus_event_news_description_placeholder')),
            'eventNewsHighlightPlaceholder' => $withOrganization($this->refLabel('ui_text', 'pengurus_event_news_highlight_placeholder')),
        ];
    }

    private function buildDashboardProfileStatus(?int $organizationId): array
    {
        $label = $this->refLabel('ui_text', 'pengurus_profile_status_label');

        if (!$organizationId || !Schema::hasTable('organizations')) {
            return ['', $label];
        }

        $org = DB::table('organizations')
            ->select(['description', 'vision', 'mission', 'email', 'phone', 'instagram', 'line', 'logo'])
            ->where('id', $organizationId)
            ->first();

        if (!$org) {
            return ['', $label];
        }

        $fields = ['description', 'vision', 'mission', 'email', 'phone', 'instagram', 'line', 'logo'];
        $filled = 0;

        foreach ($fields as $field) {
            if (!empty($org->{$field})) {
                $filled++;
            }
        }

        $percentage = (int) round(($filled / count($fields)) * 100);

        return [$percentage . '%', $label];
    }

    private function buildPengurusSupportInfo(): array
    {
        $title = $this->refLabel('ui_text', 'pengurus_support_info_title');

        $items = [
            [
                'icon' => $this->refLabel('ui_text', 'pengurus_support_info_icon_1'),
                'text' => $this->refLabel('ui_text', 'pengurus_support_info_text_1'),
            ],
            [
                'icon' => $this->refLabel('ui_text', 'pengurus_support_info_icon_2'),
                'text' => $this->refLabel('ui_text', 'pengurus_support_info_text_2'),
            ],
            [
                'icon' => $this->refLabel('ui_text', 'pengurus_support_info_icon_3'),
                'text' => $this->refLabel('ui_text', 'pengurus_support_info_text_3'),
            ],
        ];

        $items = collect($items)
            ->filter(fn ($item) => trim((string) ($item['text'] ?? '')) !== '')
            ->values()
            ->all();

        if ($title === '' && empty($items)) {
            return [];
        }

        return [
            'title' => $title,
            'items' => $items,
        ];
    }

    private function getDashboardActivities(?int $organizationId, Carbon $start, Carbon $end): array
    {
        // Keep source in sync with campus calendar: prioritize kemahasiswaan_schedules;
        // fallback to events only when schedules table is unavailable.
        if (Schema::hasTable('kemahasiswaan_schedules')) {
            $hasCategory = Schema::hasColumn('kemahasiswaan_schedules', 'category');

            $query = DB::table('kemahasiswaan_schedules as sch')
                ->leftJoin('organizations as org', 'org.id', '=', 'sch.organization_id')
                ->select([
                    'sch.title',
                    'sch.start_at',
                    'sch.end_at',
                    'sch.status',
                    'org.name as organizer',
                ])
                ->whereDate('sch.start_at', '<=', $end->toDateString())
                ->whereDate(DB::raw('COALESCE(sch.end_at, sch.start_at)'), '>=', $start->toDateString())
                ->orderBy('sch.start_at')
                ->limit(300);

            if ($hasCategory) {
                $query->addSelect('sch.category');
            } else {
                $query->selectRaw('NULL as category');
            }

            if ($organizationId) {
                $query->where('sch.organization_id', $organizationId);
            }

            return $query->get()->map(function ($row) {
                $status = Str::lower((string) $row->status);
                $category = trim((string) ($row->category ?? ''));

                if ($category === '') {
                    $category = trim($this->refLabel('schedule_status_category_map', $status));
                }

                if ($category === '') {
                    $category = trim($this->refLabel('schedule_status_category_map', 'default'));
                }

                return [
                    'name' => (string) $row->title,
                    'category' => $category,
                    'start' => Carbon::parse($row->start_at)->toDateString(),
                    'end' => Carbon::parse($row->end_at ?: $row->start_at)->toDateString(),
                    'organizer' => (string) ($row->organizer ?? ''),
                ];
            })->all();
        }

        if (!Schema::hasTable('events')) {
            return [];
        }

        $query = DB::table('events as evt')
            ->leftJoin('organizations as org', 'org.id', '=', 'evt.organization_id')
            ->select([
                'evt.name',
                'evt.start_date',
                'evt.end_date',
                'evt.status',
                'org.name as organizer',
            ])
            ->whereDate('evt.start_date', '<=', $end->toDateString())
            ->whereDate('evt.end_date', '>=', $start->toDateString())
            ->orderBy('evt.start_date')
            ->limit(300);

        if ($organizationId) {
            $query->where('evt.organization_id', $organizationId);
        }

        return $query->get()->map(function ($row) {
            $status = Str::lower((string) $row->status);

            $category = trim($this->refLabel('event_status_category_map', $status));
            if ($category === '') {
                $category = trim($this->refLabel('event_status_category_map', 'default'));
            }

            return [
                'name' => (string) $row->name,
                'category' => $category,
                'start' => Carbon::parse($row->start_date)->toDateString(),
                'end' => Carbon::parse($row->end_date ?: $row->start_date)->toDateString(),
                'organizer' => (string) ($row->organizer ?? ''),
            ];
        })->all();
    }

    private function buildDashboardSummaryCards(array $activities, ?int $organizationId, Carbon $month): array
    {
        $collection = collect($activities);
        $currentMonth = $month->format('Y-m');

        $totalEvents = count($activities);

        return [
            [
                'label' => $this->refLabel('dashboard_summary_label', 'academic'),
                'value' => $collection->where('category', 'acad')->count(),
                'tone' => 'blue',
            ],
            [
                'label' => $this->refLabel('dashboard_summary_label', 'organization'),
                'value' => $collection->where('category', 'org')->count(),
                'tone' => 'green',
            ],
            [
                'label' => $this->refLabel('dashboard_summary_label', 'month'),
                'value' => $collection->filter(function ($item) use ($currentMonth) {
                    return str_starts_with((string) ($item['start'] ?? ''), $currentMonth)
                        || str_starts_with((string) ($item['end'] ?? ''), $currentMonth);
                })->count(),
                'tone' => 'purple',
            ],
            [
                'label' => $this->refLabel('dashboard_summary_label', 'total'),
                'value' => $totalEvents,
                'tone' => 'orange',
            ],
        ];
    }

    private function buildDashboardLegend(array $activities): array
    {
        $legendMap = $this->getReferenceMap('dashboard_legend');

        $categories = collect($activities)
            ->pluck('category')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($categories)) {
            $defaultCategory = trim($this->refLabel('dashboard_setting', 'default_category'));
            $categories = $defaultCategory !== '' ? [$defaultCategory] : [];
        }

        return collect($categories)
            ->map(function ($category) use ($legendMap) {
                $entry = $legendMap[$category] ?? null;

                if (is_array($entry)) {
                    return [
                        'label' => (string) ($entry['label'] ?? ''),
                    ];
                }

                return [
                    'label' => Str::title((string) $category),
                ];
            })
            ->values()
            ->all();
    }

    private function buildCalendarDays(array $activities, Carbon $month, Carbon $start, Carbon $end, Carbon $today): array
    {
        $calendarDays = [];

        for ($cursor = $start->copy(); $cursor->lte($end); $cursor->addDay()) {
            $matches = [];

            foreach ($activities as $activity) {
                $activityStart = Carbon::parse((string) $activity['start']);
                $activityEnd = Carbon::parse((string) ($activity['end'] ?: $activity['start']));

                if ($cursor->greaterThanOrEqualTo($activityStart) && $cursor->lessThanOrEqualTo($activityEnd)) {
                    $defaultCategory = trim($this->refLabel('dashboard_setting', 'default_category'));

                    $matches[] = [
                        'name' => (string) $activity['name'],
                        'badge' => (string) ($activity['category'] ?? $defaultCategory),
                    ];
                }
            }

            $calendarDays[] = [
                'day' => $cursor->day,
                'muted' => !$cursor->isSameMonth($month),
                'is_today' => $cursor->isSameDay($today),
                'events' => array_slice($matches, 0, 3),
                'overflow' => max(count($matches) - 3, 0),
            ];
        }

        return $calendarDays;
    }

    private function getPendingTasks(?int $organizationId): array
    {
        if (!Schema::hasTable('tasks')) {
            return [];
        }

        $query = DB::table('tasks')
            ->whereIn('status', ['pending', 'overdue'])
            ->orderByRaw("CASE priority WHEN 'urgent' THEN 1 WHEN 'normal' THEN 2 ELSE 3 END")
            ->orderBy('deadline')
            ->limit(8);

        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        return $query->get()->map(function ($row) {
            $deadline = $row->deadline ? Carbon::parse($row->deadline) : null;

            if (!$deadline) {
                $deadlineLabel = $this->refLabel('ui_text', 'task_deadline_none');
            } elseif ($deadline->isPast()) {
                $deadlineLabel = $this->refLabel('ui_text', 'task_deadline_overdue_prefix') . $deadline->diffForHumans();
            } else {
                $deadlineLabel = $deadline->diffForHumans();
            }

            return [
                'task' => (string) ($row->title ?? ''),
                'priority' => in_array($row->priority, ['urgent', 'normal', 'low'], true) ? (string) $row->priority : 'normal',
                'deadline' => $deadlineLabel,
            ];
        })->values()->all();
    }

    private function mapEventStatusToPortal(string $status, ?Carbon $startDate, ?Carbon $endDate): array
    {
        $normalized = Str::lower($status);

        if ($normalized === 'cancelled') {
            return $this->refStatusPair('event_status_map', 'cancelled');
        }

        if ($normalized === 'completed') {
            return $this->refStatusPair('event_status_map', 'completed');
        }

        if ($normalized === 'draft') {
            return $this->refStatusPair('event_status_map', 'draft');
        }

        if ($startDate && $startDate->isFuture()) {
            return $this->refStatusPair('event_status_map', 'future');
        }

        if ($startDate && $endDate && $startDate->lte(now()) && $endDate->gte(now())) {
            return $this->refStatusPair('event_status_map', 'ongoing');
        }

        return $this->refStatusPair('event_status_map', 'default');
    }

    private function mapAnnouncementStatus(string $status): array
    {
        return match (Str::lower($status)) {
            'published' => $this->refStatusPair('announcement_status_map', 'published'),
            'scheduled' => $this->refStatusPair('announcement_status_map', 'scheduled'),
            'archived' => $this->refStatusPair('announcement_status_map', 'archived'),
            default => $this->refStatusPair('announcement_status_map', 'default'),
        };
    }

    private function mapLostFoundModerationStatus(string $status): array
    {
        return match (Str::lower($status)) {
            'active' => $this->refStatusPair('lostfound_moderation_map', 'active'),
            'claimed' => $this->refStatusPair('lostfound_moderation_map', 'claimed'),
            'closed' => $this->refStatusPair('lostfound_moderation_map', 'closed'),
            default => $this->refStatusPair('lostfound_moderation_map', 'default'),
        };
    }

    private function inferOrganizationCategory(?object $organization): string
    {
        if (!$organization) {
            return '';
        }

        $shortname = trim((string) ($organization->shortname ?? ''));
        if ($shortname !== '') {
            return $shortname;
        }

        return trim((string) ($organization->name ?? ''));
    }

    private function missionToValues(?string $mission): array
    {
        $text = trim((string) $mission);
        if ($text === '') {
            return [];
        }

        $parts = preg_split('/\r\n|\r|\n|\./', $text) ?: [];
        $parts = collect($parts)
            ->map(fn ($part) => trim((string) $part))
            ->filter()
            ->take(6)
            ->values();

        return $parts->map(function ($part, $index) {
            return [
                'name' => Str::limit($part, 48, ''),
                'desc' => $part,
            ];
        })->all();
    }

    private function decodeProfileList(?object $organization, string $column, array $keys): array
    {
        if (!$organization || !Schema::hasColumn('organizations', $column)) {
            return [];
        }

        $raw = trim((string) ($organization->{$column} ?? ''));
        if ($raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return [];
        }

        return collect($decoded)
            ->filter(fn ($row) => is_array($row))
            ->map(function ($row) use ($keys) {
                $item = [];
                foreach ($keys as $key) {
                    $item[$key] = trim((string) ($row[$key] ?? ''));
                }

                return $item;
            })
            ->filter(function ($row) {
                foreach ($row as $value) {
                    if ($value !== '') {
                        return true;
                    }
                }

                return false;
            })
            ->values()
            ->all();
    }

    private function parseProfileRows(string $text, array $keys, int $maxItems = 20): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($text)) ?: [];

        return collect($lines)
            ->map(fn ($line) => trim((string) $line))
            ->filter(fn ($line) => $line !== '')
            ->take($maxItems)
            ->map(function ($line) use ($keys) {
                $parts = array_map('trim', explode('|', $line));
                $item = [];

                foreach ($keys as $index => $key) {
                    $item[$key] = (string) ($parts[$index] ?? '');
                }

                return $item;
            })
            ->filter(function ($row) {
                foreach ($row as $value) {
                    if ($value !== '') {
                        return true;
                    }
                }

                return false;
            })
            ->values()
            ->all();
    }

    private function formatProfileRows(array $rows, array $keys): string
    {
        return collect($rows)
            ->filter(fn ($row) => is_array($row))
            ->map(function ($row) use ($keys) {
                $values = [];

                foreach ($keys as $key) {
                    $values[] = trim((string) ($row[$key] ?? ''));
                }

                return implode('|', $values);
            })
            ->filter(fn ($line) => trim($line, '| ') !== '')
            ->values()
            ->implode("\n");
    }

    private function storeOrganizationMedia(UploadedFile $file, int $organizationId, string $type): string
    {
        $safeType = in_array($type, ['logo', 'banner'], true) ? $type : 'media';
        $relativeDirectory = 'uploads/organizations/' . $organizationId;
        $absoluteDirectory = public_path($relativeDirectory);

        File::ensureDirectoryExists($absoluteDirectory);

        $extension = strtolower((string) ($file->getClientOriginalExtension() ?: 'jpg'));
        $fileName = $safeType . '_' . now()->format('YmdHis') . '_' . Str::random(10) . '.' . $extension;

        $file->move($absoluteDirectory, $fileName);

        return $relativeDirectory . '/' . $fileName;
    }

    private function resolveOrganizationMediaUrl(string $path): string
    {
        $trimmed = trim($path);
        if ($trimmed === '') {
            return '';
        }

        if (Str::startsWith($trimmed, ['http://', 'https://', '//'])) {
            return $trimmed;
        }

        return asset(ltrim($trimmed, '/'));
    }

    private function formatWhatsappLink(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone) ?: '';
        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        return 'https://wa.me/' . $digits;
    }

    private function getPengajuan(?int $organizationId = null): array
    {
        $query = DB::table('submissions as sub')
            ->leftJoin('organizations as org', 'org.id', '=', 'sub.organization_id')
            ->select([
                'sub.id',
                'sub.title',
                'sub.description',
                'sub.type',
                'sub.status',
                'sub.file_path',
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
                'deskripsi' => $row->description,
                'tipe' => $row->type,
                'file_path' => $row->file_path,
                'organisasi' => (string) ($row->organization_name ?? ''),
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
                'rep.content',
                'rep.report_type',
                'rep.status',
                'rep.attachment',
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
                'konten' => $row->content,
                'tipe' => $row->report_type,
                'attachment' => $row->attachment,
                'organisasi' => (string) ($row->organization_name ?? ''),
                'tanggal_laporan' => $this->normalizeDateField($row->submitted_date, $row->created_at),
                'status' => $this->mapReportStatus((string) $row->status),
                'catatan_departemen' => $row->department_review_note ?: $row->reviewer_notes,
            ];
        })->all();
    }

    private function getJadwal(): array
    {
        $hasCategory = Schema::hasColumn('kemahasiswaan_schedules', 'category');
        $hasDescription = Schema::hasColumn('kemahasiswaan_schedules', 'description');

        $query = DB::table('kemahasiswaan_schedules as jadwal')
            ->leftJoin('organizations as org', 'org.id', '=', 'jadwal.organization_id')
            ->select([
                'jadwal.id',
                'jadwal.title',
                'jadwal.start_at',
                'jadwal.end_at',
                'jadwal.location',
                'org.name as organization_name',
            ])
            ->orderBy('jadwal.start_at');

        if ($hasCategory) {
            $query->addSelect('jadwal.category');
        } else {
            $query->selectRaw('NULL as category');
        }

        if ($hasDescription) {
            $query->addSelect('jadwal.description');
        } else {
            $query->selectRaw('NULL as description');
        }

        $rows = $query->get();

        return $rows->map(function ($row) {
            $startDate = $row->start_at ? Carbon::parse((string) $row->start_at) : null;
            $endDate = $row->end_at ? Carbon::parse((string) $row->end_at) : $startDate;

            return [
                'id' => (int) $row->id,
                'judul' => $row->title,
                'organisasi' => (string) ($row->organization_name ?? ''),
                'kategori' => (string) ($row->category ?? 'org'),
                'tanggal' => $this->normalizeDateField($row->start_at, $row->start_at),
                'tanggal_mulai' => $startDate?->toDateString(),
                'tanggal_selesai' => $endDate?->toDateString(),
                'tanggal_range_label' => $startDate
                    ? (($endDate && !$startDate->isSameDay($endDate))
                        ? $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y')
                        : $startDate->translatedFormat('d M Y'))
                    : '-',
                'lokasi' => $row->location,
                'deskripsi' => (string) ($row->description ?? ''),
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
                'organisasi' => (string) ($row->organization_name ?? ''),
                'jabatan' => Str::title((string) $row->position),
                'kontak' => (string) ($row->phone ?? ''),
                'email' => (string) ($row->email ?? ''),
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

    private function countKemahasiswaanPendingNotifications(array $workflowPengajuan, array $workflowLaporan): int
    {
        $pendingSubmissionCodes = array_keys($this->getReferenceMap('pending_submission_status'));
        $pendingSubmissionStatuses = collect($pendingSubmissionCodes)
            ->map(fn ($code) => $this->mapSubmissionStatus((string) $code))
            ->filter(fn ($value) => $value !== '')
            ->values()
            ->all();

        $pendingReportCodes = array_keys($this->getReferenceMap('pending_report_status'));
        $pendingReportStatuses = collect($pendingReportCodes)
            ->map(fn ($code) => $this->mapReportStatus((string) $code))
            ->filter(fn ($value) => $value !== '')
            ->values()
            ->all();

        if (empty($pendingReportStatuses)) {
            $pendingReportStatuses = $pendingSubmissionStatuses;
        }

        $count = collect($workflowPengajuan)
            ->whereIn('status', $pendingSubmissionStatuses)
            ->count();

        $count += collect($workflowLaporan)
            ->whereIn('status', $pendingReportStatuses)
            ->count();

        if (Schema::hasTable('kemahasiswaan_announcements')) {
            $pendingEmailReviewStatuses = array_keys($this->getReferenceMap('pending_email_review_status'));

            $count += (int) DB::table('kemahasiswaan_announcements')
                ->whereIn('email_review_status', $pendingEmailReviewStatuses)
                ->count();
        }

        return $count;
    }

    private function mapSubmissionStatus(string $status): string
    {
        $label = $this->refLabel('submission_status_map', $status);

        return $label !== '' ? $label : Str::title(str_replace('_', ' ', $status));
    }

    private function mapReportStatus(string $status): string
    {
        $label = $this->refLabel('report_status_map', $status);

        return $label !== '' ? $label : Str::title(str_replace('_', ' ', $status));
    }

    private function refStatusPair(string $domain, string $code, string $fallbackLabel = '', string $fallbackValue = ''): array
    {
        $label = $this->refLabel($domain, $code);
        $payload = $this->getReferencePayload($domain, $code);
        $value = trim((string) ($payload['value'] ?? ''));

        return [
            $label !== '' ? $label : $fallbackLabel,
            $value !== '' ? $value : $fallbackValue,
        ];
    }

    private function buildKemahasiswaanPengajuanUiText(): array
    {
        return $this->uiTextMap([
            'all_statuses' => 'kmh_common_all_statuses',
            'search_placeholder' => 'kmh_submission_search_placeholder',
            'search_aria' => 'kmh_submission_search_aria',
            'filter_scope_caption' => 'kmh_submission_filter_scope_caption',
            'table_pengajuan_empty' => 'kmh_submission_table_empty',
            'table_pengajuan_filter_empty' => 'kmh_submission_filter_empty',
            'table_laporan_empty' => 'kmh_report_table_empty',
            'table_laporan_filter_empty' => 'kmh_report_filter_empty',
            'review_save_button' => 'kmh_common_save_button',
            'review_note_placeholder' => 'kmh_common_review_note_placeholder',
            'no_followup_action' => 'kmh_common_no_followup_action',
            'schedule_form_warning' => 'kmh_schedule_form_warning',
            'schedule_org_placeholder' => 'kmh_schedule_org_placeholder',
            'schedule_save_button' => 'kmh_schedule_save_button',
            'schedule_empty' => 'kmh_schedule_empty',
        ]);
    }

    private function uiTextMap(array $keyCodeMap): array
    {
        $labels = [];

        foreach ($keyCodeMap as $key => $code) {
            $labels[$key] = $this->refLabel('ui_text', (string) $code);
        }

        return $labels;
    }

    private function refLabel(string $domain, string $code): string
    {
        $map = $this->getReferenceMap($domain);

        return (string) (($map[$code]['label'] ?? ''));
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
