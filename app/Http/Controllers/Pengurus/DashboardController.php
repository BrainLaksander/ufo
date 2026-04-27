<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Services\Mahasiswa\MahasiswaDataProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use PengurusControllerTrait;

    private MahasiswaDataProvider $mahasiswaDataProvider;

    public function __construct(MahasiswaDataProvider $mahasiswaDataProvider)
    {
        $this->mahasiswaDataProvider = $mahasiswaDataProvider;
    }

    public function dashboard(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = (int) ($context['organization_id'] ?? 0);

        $month = now()->startOfMonth();
        $monthQuery = trim((string) $request->query('bulan', ''));

        if (preg_match('/^\d{4}-\d{2}$/', $monthQuery) === 1) {
            try {
                $month = \Carbon\Carbon::createFromFormat('Y-m', $monthQuery)->startOfMonth();
            } catch (\Throwable) {
                $month = now()->startOfMonth();
            }
        }

        $monthStart = $month->copy()->startOfMonth()->startOfDay();
        $monthEnd = $month->copy()->endOfMonth()->endOfDay();
        $calendarStart = $monthStart->copy()->startOfWeek(Carbon::SUNDAY);
        $calendarEnd = $monthEnd->copy()->endOfWeek(Carbon::SATURDAY);

        $events = $this->getDashboardEvents($organizationId, $calendarStart, $calendarEnd);
        $eventsInMonth = collect($events)
            ->filter(function (array $event) use ($monthStart, $monthEnd) {
                return $event['start']->lte($monthEnd) && $event['end']->gte($monthStart);
            })
            ->count();

        $pendingTasks = $this->getPendingTasks($organizationId);
        $summaryCards = [
            [
                'tone' => 'tone-primary',
                'label' => 'Agenda Bulan Ini',
                'value' => (string) $eventsInMonth,
            ],
            [
                'tone' => 'tone-secondary',
                'label' => 'Total Agenda Tercatat',
                'value' => (string) count($events),
            ],
        ];

        $legendItems = [
            ['label' => 'Agenda organisasi'],
            ['label' => 'Tenggat pengajuan'],
            ['label' => 'Tenggat laporan'],
        ];

        $calendarDays = $this->buildCalendarDays($calendarStart, $calendarEnd, $monthStart, $events);

        [$profileStatusValue, $profileStatusLabel] = $this->getProfileStatus($organizationId);

        return view('portal.pengurus.dashboard', [
            'activities' => $events,
            'summaryCards' => $summaryCards,
            'legendItems' => $legendItems,
            'calendarDays' => $calendarDays,
            'pendingTasks' => $pendingTasks,
            'monthLabel' => $month->translatedFormat('F Y'),
            'prevMonth' => $month->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $month->copy()->addMonth()->format('Y-m'),
            'profileStatusValue' => $profileStatusValue,
            'profileStatusLabel' => $profileStatusLabel,
        ]);
    }

    private function getDashboardEvents(int $organizationId, Carbon $rangeStart, Carbon $rangeEnd): array
    {
        if ($organizationId <= 0) {
            return [];
        }

        $data = $this->mahasiswaDataProvider->loadAllData();

        return collect((array) ($data['events'] ?? []))
            ->map(function (array $event): ?array {
                $rawStartDate = trim((string) ($event['start_date_iso'] ?? ($event['date'] ?? '')));
                if ($rawStartDate === '' || $rawStartDate === '-') {
                    return null;
                }

                try {
                    $start = Carbon::parse($rawStartDate)->startOfDay();
                } catch (\Throwable) {
                    return null;
                }

                $rawEndDate = trim((string) ($event['end_date_iso'] ?? $rawStartDate));
                try {
                    $end = Carbon::parse($rawEndDate)->endOfDay();
                } catch (\Throwable) {
                    $end = $start->copy()->endOfDay();
                }

                if ($end->lt($start)) {
                    $end = $start->copy()->endOfDay();
                }

                return [
                    'id' => (int) ($event['id'] ?? 0),
                    'name' => (string) ($event['title'] ?? ''),
                    'start' => $start,
                    'end' => $end,
                    'badge' => $this->mapDashboardEventBadge((string) ($event['category'] ?? '')),
                ];
            })
            ->filter(fn (?array $event): bool => is_array($event)
                && $event['name'] !== ''
                && $event['start']->lte($rangeEnd)
                && $event['end']->gte($rangeStart))
            ->sortBy(fn (array $event) => $event['start']->getTimestamp())
            ->values()
            ->all();
    }

    private function mapDashboardEventBadge(string $category): string
    {
        $value = mb_strtolower(trim($category));

        if ($value === '') {
            return 'org';
        }

        if (str_contains($value, 'libur') || str_contains($value, 'holiday')) {
            return 'holiday';
        }

        if (str_contains($value, 'akademik') || str_contains($value, 'acad')) {
            return 'acad';
        }

        if (str_contains($value, 'besar') || str_contains($value, 'utama') || str_contains($value, 'major')) {
            return 'major';
        }

        return 'org';
    }

    private function buildCalendarDays(Carbon $calendarStart, Carbon $calendarEnd, Carbon $monthStart, array $events): array
    {
        $eventsByDate = [];

        foreach ($events as $event) {
            $cursor = $event['start']->copy()->startOfDay();
            $end = $event['end']->copy()->startOfDay();

            if ($cursor->lt($calendarStart)) {
                $cursor = $calendarStart->copy();
            }
            if ($end->gt($calendarEnd)) {
                $end = $calendarEnd->copy();
            }

            while ($cursor->lte($end)) {
                $eventsByDate[$cursor->toDateString()][] = [
                    'name' => $event['name'],
                    'badge' => $event['badge'],
                ];
                $cursor->addDay();
            }
        }

        $days = [];
        for ($cursor = $calendarStart->copy(); $cursor->lte($calendarEnd); $cursor->addDay()) {
            $dateKey = $cursor->toDateString();
            $dailyEvents = $eventsByDate[$dateKey] ?? [];

            $days[] = [
                'day' => $cursor->day,
                'muted' => $cursor->month !== $monthStart->month,
                'is_today' => $cursor->isToday(),
                'events' => array_slice($dailyEvents, 0, 2),
                'overflow' => max(0, count($dailyEvents) - 2),
            ];
        }

        return $days;
    }

    private function getPendingTasks(int $organizationId): array
    {
        if ($organizationId <= 0) {
            return [];
        }

        $items = collect();

        if (Schema::hasTable('submissions')) {
            $submissionRows = DB::table('submissions')
                ->select(['id', 'title', 'status', 'updated_at'])
                ->where('organization_id', $organizationId)
                ->whereIn('status', ['draft', 'submitted', 'reviewing', 'revised'])
                ->orderByDesc('updated_at')
                ->limit(6)
                ->get();

            foreach ($submissionRows as $row) {
                $items->push([
                    'task' => 'Pengajuan: ' . (string) ($row->title ?? 'Tanpa Judul'),
                    'priority' => 'warning',
                    'deadline' => 'Update terakhir: ' . Carbon::parse((string) $row->updated_at)->translatedFormat('d M Y H:i'),
                ]);
            }
        }

        if (Schema::hasTable('reports')) {
            $reportRows = DB::table('reports')
                ->select(['id', 'title', 'status', 'updated_at'])
                ->where('organization_id', $organizationId)
                ->whereIn('status', ['draft', 'submitted', 'reviewing', 'revision_needed'])
                ->orderByDesc('updated_at')
                ->limit(6)
                ->get();

            foreach ($reportRows as $row) {
                $items->push([
                    'task' => 'Laporan: ' . (string) ($row->title ?? 'Tanpa Judul'),
                    'priority' => 'danger',
                    'deadline' => 'Update terakhir: ' . Carbon::parse((string) $row->updated_at)->translatedFormat('d M Y H:i'),
                ]);
            }
        }

        return $items->take(8)->values()->all();
    }

    private function getProfileStatus(int $organizationId): array
    {
        if ($organizationId <= 0 || !Schema::hasTable('organizations')) {
            return ['-', 'Profil organisasi tidak ditemukan'];
        }

        $query = DB::table('organizations')->where('id', $organizationId);
        $profileStatus = Schema::hasColumn('organizations', 'profile_status')
            ? (string) ($query->value('profile_status') ?? '')
            : '';

        if ($profileStatus !== '') {
            return [strtoupper($profileStatus), 'Status profil organisasi'];
        }

        return ['BELUM LENGKAP', 'Lengkapi profil organisasi'];
    }

    public function settings(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $org = null;

        if ($context['organization_id']) {
            $org = \Illuminate\Support\Facades\DB::table('organizations')
                ->where('id', $context['organization_id'])
                ->first();
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
        ]);
    }

    public function updateProfile(Request $request): \Illuminate\Http\RedirectResponse
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

        \Illuminate\Support\Facades\DB::table('organizations')
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

    public function updateMembers(Request $request): \Illuminate\Http\RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);

        if (!$context['organization_id']) {
            return back()->with('error', $this->refLabel('flash_message', 'org_context_missing'));
        }

        $validated = $request->validate([
            'vision_text' => 'nullable|string|max:2000',
            'mission_text' => 'nullable|string|max:4000',
            'culture_text' => 'nullable|string|max:4000',
            'values_text' => 'nullable|string|max:8000',
            'programs_text' => 'nullable|string|max:12000',
            'structure_text' => 'nullable|string|max:8000',
            'registration_period' => 'nullable|string|max:255',
            'registration_open_date' => 'nullable|string|max:255',
            'registration_form_link' => 'nullable|string|max:500',
            'registration_guidebook_url' => 'nullable|string|max:500',
            'registration_divisions_text' => 'nullable|string|max:8000',
            'email' => 'nullable|email|max:120',
            'instagram' => 'nullable|string|max:120',
            'facebook' => 'nullable|string|max:120',
            'tiktok' => 'nullable|string|max:120',
            'youtube' => 'nullable|string|max:120',
            'logo_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $valuesRows = $this->parseProfileRows((string) ($validated['values_text'] ?? ''), ['name', 'desc'], 20);
        $programRows = $this->parseProfileRows((string) ($validated['programs_text'] ?? ''), ['name', 'description', 'goal'], 20);
        $structureRows = $this->parseProfileRows((string) ($validated['structure_text'] ?? ''), ['jabatan', 'nama'], 20);
        $registrationDivisionRows = $this->parseProfileRows((string) ($validated['registration_divisions_text'] ?? ''), ['name', 'description'], 20);
        $registrationPayload = [
            'open' => $request->boolean('registration_open'),
            'period' => trim((string) ($validated['registration_period'] ?? '')),
            'open_date' => trim((string) ($validated['registration_open_date'] ?? '')),
            'form_link' => trim((string) ($validated['registration_form_link'] ?? '')),
            'guidebook_url' => trim((string) ($validated['registration_guidebook_url'] ?? '')),
            'divisions' => $registrationDivisionRows,
        ];
        $contactRows = array_values(array_filter([
            ['platform' => 'Instagram', 'value' => trim((string) ($validated['instagram'] ?? ''))],
            ['platform' => 'Email', 'value' => trim((string) ($validated['email'] ?? ''))],
            ['platform' => 'Facebook', 'value' => trim((string) ($validated['facebook'] ?? ''))],
            ['platform' => 'TikTok', 'value' => trim((string) ($validated['tiktok'] ?? ''))],
            ['platform' => 'YouTube', 'value' => trim((string) ($validated['youtube'] ?? ''))],
        ], static fn (array $row): bool => trim((string) ($row['value'] ?? '')) !== ''));

        $payload = [
            'vision' => $validated['vision_text'] ?? null,
            'mission' => $validated['mission_text'] ?? null,
            'description' => $validated['culture_text'] ?? null,
            'email' => $validated['email'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('organizations', 'profile_values_json')) {
            $payload['profile_values_json'] = json_encode($valuesRows, JSON_UNESCAPED_UNICODE);
        }

        if (Schema::hasColumn('organizations', 'profile_programs_json')) {
            $payload['profile_programs_json'] = json_encode($programRows, JSON_UNESCAPED_UNICODE);
        }

        if (Schema::hasColumn('organizations', 'profile_structure_json')) {
            $payload['profile_structure_json'] = json_encode($structureRows, JSON_UNESCAPED_UNICODE);
        }

        if (Schema::hasColumn('organizations', 'profile_contacts_json')) {
            $payload['profile_contacts_json'] = json_encode($contactRows, JSON_UNESCAPED_UNICODE);
        }

        if (Schema::hasColumn('organizations', 'profile_registration_json')) {
            $payload['profile_registration_json'] = json_encode($registrationPayload, JSON_UNESCAPED_UNICODE);
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

        \Illuminate\Support\Facades\DB::table('organizations')
            ->where('id', $context['organization_id'])
            ->update($payload);

        if (Schema::hasTable('workflow_reference_values')) {
            DB::table('workflow_reference_values')->updateOrInsert(
                [
                    'domain' => 'mahasiswa_org_registration',
                    'code' => 'org_' . (int) $context['organization_id'],
                ],
                [
                    'label' => 'Konfigurasi pendaftaran organisasi',
                    'payload' => json_encode($registrationPayload, JSON_UNESCAPED_UNICODE),
                ]
            );
        }

        return back()->with('success', $this->refLabel('flash_message', 'profile_updated'));
    }
}
