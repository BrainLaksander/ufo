<?php

namespace App\Services\Mahasiswa;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class MahasiswaDataProvider
{
    private array $referenceCache = [];

    /**
     * @return array<string, mixed>
     */
    public function loadAllData(): array
    {
        try {
            $organizations = $this->loadOrganizations();
            $events = $this->loadEvents($organizations);
            $announcements = $this->loadAnnouncements();
            $lostFound = $this->loadLostFound();
            $notifications = $this->loadNotifications($events, $announcements, $lostFound['items']);

            $carouselImages = collect($organizations)
                ->pluck('banner')
                ->filter()
                ->unique()
                ->take(5)
                ->values()
                ->all();

            return [
                'carousel_images' => $carouselImages,
                'organization_categories' => $this->withAllCategory(
                    collect($organizations)->pluck('category')->filter()->unique()->values()->all()
                ),
                'organizations' => collect($organizations)->keyBy('id')->all(),
                'events' => $events,
                'event_categories' => $this->withAllCategory(
                    collect($events)->pluck('category')->filter()->unique()->values()->all()
                ),
                'announcements' => $announcements,
                'announcement_categories' => $this->withAllCategory(
                    collect($announcements)->pluck('category')->filter()->unique()->values()->all()
                ),
                'lost_found' => $lostFound,
                'lost_found_categories' => $this->withAllCategory(
                    collect($lostFound['items'])->pluck('category')->filter()->unique()->values()->all()
                ),
                'notifications' => $notifications,
            ];
        } catch (Throwable $exception) {
            report($exception);

            return [
                'carousel_images' => [],
                'organization_categories' => $this->withAllCategory([]),
                'organizations' => [],
                'events' => [],
                'event_categories' => $this->withAllCategory([]),
                'announcements' => [],
                'announcement_categories' => $this->withAllCategory([]),
                'lost_found' => ['urgent' => [], 'items' => []],
                'lost_found_categories' => $this->withAllCategory([]),
                'notifications' => [],
            ];
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadOrganizations(): array
    {
        if (!Schema::hasTable('organizations')) {
            return [];
        }

        $rows = DB::table('organizations')
            ->select([
                'id', 'name', 'shortname', 'description', 'vision', 'mission',
                'logo', 'email', 'phone', 'banner', 'instagram', 'line', 'status',
            ])
            ->where('status', 'active')
            ->orderBy('name')
            ->when(Schema::hasColumn('organizations', 'profile_values_json'), fn ($query) => $query->addSelect('profile_values_json'))
            ->when(Schema::hasColumn('organizations', 'profile_programs_json'), fn ($query) => $query->addSelect('profile_programs_json'))
            ->when(Schema::hasColumn('organizations', 'profile_structure_json'), fn ($query) => $query->addSelect('profile_structure_json'))
            ->when(Schema::hasColumn('organizations', 'profile_contacts_json'), fn ($query) => $query->addSelect('profile_contacts_json'))
            ->when(Schema::hasColumn('organizations', 'profile_registration_json'), fn ($query) => $query->addSelect('profile_registration_json'))
            ->get();

        if ($rows->isEmpty()) {
            return [];
        }

        $organizationIds = $rows->pluck('id')->map(fn ($id) => (int) $id)->all();

        $activeMembersByOrganization = [];
        $structureByOrganization = [];

        if (Schema::hasTable('members')) {
            $activeMembersByOrganization = DB::table('members')
                ->selectRaw('organization_id, COUNT(*) as total')
                ->whereIn('organization_id', $organizationIds)
                ->where('status', 'aktif')
                ->groupBy('organization_id')
                ->pluck('total', 'organization_id')
                ->map(fn ($total) => (int) $total)
                ->all();

            $structureRows = DB::table('members')
                ->select(['organization_id', 'position', 'name'])
                ->whereIn('organization_id', $organizationIds)
                ->where('status', 'aktif')
                ->orderBy('organization_id')
                ->orderByRaw("CASE position WHEN 'ketua' THEN 0 WHEN 'sekretaris' THEN 1 WHEN 'bendahara' THEN 2 ELSE 3 END")
                ->orderBy('id')
                ->get();

            foreach ($structureRows as $member) {
                $orgId = (int) $member->organization_id;
                if (!isset($structureByOrganization[$orgId])) {
                    $structureByOrganization[$orgId] = [];
                }
                if (count($structureByOrganization[$orgId]) >= 12) {
                    continue;
                }
                $structureByOrganization[$orgId][] = [
                    'position' => Str::title((string) $member->position),
                    'name' => $member->name,
                ];
            }
        }

        $eventsByOrganization = [];
        $registrationReferences = $this->loadReferenceDomain('mahasiswa_org_registration');

        if (Schema::hasTable('events')) {
            $eventRows = DB::table('events')
                ->select(['id', 'organization_id', 'name', 'description', 'start_date', 'end_date', 'banner'])
                ->whereIn('organization_id', $organizationIds)
                ->orderByDesc('start_date')
                ->limit(500)
                ->get();

            foreach ($eventRows as $row) {
                $orgId = (int) $row->organization_id;
                $eventsByOrganization[$orgId][] = $this->mapOrganizationEvent($row);
            }
        }

        $organizations = [];

        foreach ($rows as $row) {
            $orgId = (int) $row->id;
            $defaultRegistrationPayload = data_get($registrationReferences, 'org_' . $orgId . '.payload', data_get($registrationReferences, 'default.payload', []));
            $registrationPayload = $defaultRegistrationPayload;

            $rawRegistrationProfile = trim((string) ($row->profile_registration_json ?? ''));
            if ($rawRegistrationProfile !== '') {
                $decodedRegistrationProfile = json_decode($rawRegistrationProfile, true);
                if (is_array($decodedRegistrationProfile)) {
                    $registrationPayload = array_merge($defaultRegistrationPayload, $decodedRegistrationProfile);
                }
            }

            $organizations[] = $this->buildOrganizationData(
                $row,
                $orgId,
                $eventsByOrganization[$orgId] ?? [],
                $structureByOrganization[$orgId] ?? [],
                (int) ($activeMembersByOrganization[$orgId] ?? 0),
                $registrationPayload
            );
        }

        return $organizations;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildOrganizationData(object $row, int $orgId, array $orgEvents, array $structure, int $activeMembers, array $registrationPayload): array
    {
        $mission = $this->splitListText($row->mission);
        $category = $this->inferOrganizationCategory($row->name, $row->shortname);
        $logoUrl = $this->resolveMediaUrl($row->logo);
        $bannerUrl = $this->resolveMediaUrl($row->banner);

        $values = $this->decodeOrganizationProfileRows($row, 'profile_values_json', ['name', 'desc']);
        if (empty($values)) {
            $values = collect($this->splitListText((string) ($row->mission ?? '')))
                ->map(fn (string $value): array => [
                    'name' => Str::limit($value, 48, ''),
                    'desc' => $value,
                ])
                ->values()
                ->all();
        }

        $programs = $this->decodeOrganizationProfileRows($row, 'profile_programs_json', ['name', 'description', 'goal']);
        if (empty($programs)) {
            $programs = collect($orgEvents)
            ->take(3)
            ->map(fn ($event) => [
                'name' => $event['name'],
                'description' => $event['description'],
                'goal' => $event['highlights'],
            ])
            ->values()
            ->all();
        }

        $profileStructure = $this->decodeOrganizationProfileRows($row, 'profile_structure_json', ['jabatan', 'nama']);
        $displayStructure = !empty($profileStructure)
            ? array_map(static fn (array $item): array => [
                'position' => (string) ($item['jabatan'] ?? ''),
                'name' => (string) ($item['nama'] ?? ''),
            ], $profileStructure)
            : $structure;

        $contactRows = $this->decodeOrganizationProfileRows($row, 'profile_contacts_json', ['platform', 'value']);
        $contactValue = static function (array $rows, string $platform): string {
            foreach ($rows as $item) {
                if (Str::lower(trim((string) ($item['platform'] ?? ''))) === $platform) {
                    return trim((string) ($item['value'] ?? ''));
                }
            }

            return '';
        };

        $contactInstagram = $contactValue($contactRows, 'instagram') ?: (string) ($row->instagram ?? '');
        $contactEmail = $contactValue($contactRows, 'email') ?: (string) ($row->email ?? '');
        $contactFacebook = $contactValue($contactRows, 'facebook');
        $contactTiktok = $contactValue($contactRows, 'tiktok');
        $contactYoutube = $contactValue($contactRows, 'youtube');

        $registrationDivisions = $this->extractRegistrationDivisions($registrationPayload, $structure);

        return [
            'id' => $orgId,
            'name' => $row->name,
            'category' => $category,
            'logo_text' => $this->acronym($row->shortname, $row->name),
            'logo' => $logoUrl,
            'tagline' => $row->description ? Str::limit((string) $row->description, 110, '...') : ($row->shortname ?: null),
            'banner' => $bannerUrl ?: $this->placeholderImage((string) $row->name),
            'visi' => $row->vision ?: $row->description,
            'misi' => $mission,
            'culture' => $row->description,
            'values' => $values,
            'active_members' => $activeMembers,
            'programs' => $programs,
            'events' => $orgEvents,
            'structure' => $displayStructure,
            'social_media' => [
                'instagram' => $this->normalizeSocialLink($contactInstagram, 'instagram'),
                'email' => $contactEmail !== '' ? 'mailto:' . $contactEmail : null,
                'facebook' => $this->normalizeSocialLink($contactFacebook, 'facebook'),
                'tiktok' => $this->normalizeSocialLink($contactTiktok, 'tiktok'),
                'youtube' => $this->normalizeSocialLink($contactYoutube, 'youtube'),
            ],
            'registration' => [
                'open' => (bool) data_get($registrationPayload, 'open', false),
                'period' => data_get($registrationPayload, 'period'),
                'open_date' => data_get($registrationPayload, 'open_date'),
                'form_link' => data_get($registrationPayload, 'form_link'),
                'guidebook_url' => data_get($registrationPayload, 'guidebook_url'),
                'divisions' => $registrationDivisions,
            ],
        ];
    }

    /**
     * @param array<int, array<string, mixed>> $organizations
     * @return array<int, array<string, mixed>>
     */
    public function loadEvents(array $organizations): array
    {
        $organizationLookup = collect($organizations)->keyBy('id');
        $allEvents = collect();

        if (Schema::hasTable('kemahasiswaan_schedules')) {
            $hasCategory = Schema::hasColumn('kemahasiswaan_schedules', 'category');
            $hasDescription = Schema::hasColumn('kemahasiswaan_schedules', 'description');

            $query = DB::table('kemahasiswaan_schedules as sch')
                ->leftJoin('organizations as org', 'org.id', '=', 'sch.organization_id')
                ->select([
                    'sch.id', 'sch.organization_id', 'sch.title as name', 'sch.start_at as start_date',
                    'sch.end_at as end_date', 'sch.location', 'sch.status', 'org.name as organization_name',
                    'org.banner as banner',
                ])
                ->selectRaw('0 as current_participants');

            if ($hasCategory) {
                $query->addSelect('sch.category as schedule_category');
            } else {
                $query->selectRaw('NULL as schedule_category');
            }

            if ($hasDescription) {
                $query->addSelect('sch.description');
            } else {
                $query->selectRaw('NULL as description');
            }

            $legendMap = $this->loadReferenceDomain('dashboard_legend');
            
            $schRows = $query->get()->map(function ($row) use ($organizationLookup, $legendMap) {
                $organization = $organizationLookup->get((int) $row->organization_id, []);
                $rawCategory = Str::lower((string) ($row->schedule_category ?? ''));
                $categoryLabel = (string) data_get($legendMap, $rawCategory . '.label', '');

                if ($categoryLabel === '') {
                    $categoryLabel = (string) ($organization['category'] ?? 'Umum');
                }

                return $this->mapCampusEvent($row, $categoryLabel);
            });
            
            $allEvents = $allEvents->concat($schRows);
        }

        if (Schema::hasTable('events')) {
            $evRows = DB::table('events as ev')
                ->leftJoin('organizations as org', 'org.id', '=', 'ev.organization_id')
                ->select([
                    'ev.id', 'ev.organization_id', 'ev.name', 'ev.description', 'ev.start_date', 'ev.end_date',
                    'ev.location', 'ev.current_participants', 'ev.banner', 'ev.status', 'org.name as organization_name',
                ])
                ->whereIn('ev.status', ['approved', 'ongoing', 'completed'])
                ->get()
                ->map(function ($row) use ($organizationLookup) {
                    $organization = $organizationLookup->get((int) $row->organization_id, []);
                    $category = (string) ($organization['category'] ?? 'Umum');
                    return $this->mapCampusEvent($row, $category);
                });
            
            $allEvents = $allEvents->concat($evRows);
        }

        return $allEvents
            ->unique(function (array $item): string {
                return mb_strtolower(implode('|', [
                    (string) ($item['title'] ?? ''),
                    (string) ($item['date'] ?? ''),
                    (string) ($item['organizer'] ?? ''),
                    (string) ($item['time'] ?? ''),
                    (string) ($item['location'] ?? ''),
                ]));
            })
            ->sortByDesc('date')
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function decodeOrganizationProfileRows(object $row, string $column, array $keys): array
    {
        $raw = trim((string) ($row->{$column} ?? ''));
        if ($raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return [];
        }

        return collect($decoded)
            ->filter(fn ($item) => is_array($item))
            ->map(function (array $item) use ($keys): array {
                $mapped = [];
                foreach ($keys as $key) {
                    $mapped[$key] = trim((string) ($item[$key] ?? ''));
                }

                return $mapped;
            })
            ->filter(function (array $item): bool {
                foreach ($item as $value) {
                    if (trim((string) $value) !== '') {
                        return true;
                    }
                }

                return false;
            })
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadAnnouncements(): array
    {
        if (!Schema::hasTable('kemahasiswaan_announcements')) {
            return [];
        }

        $query = DB::table('kemahasiswaan_announcements as ann');

        if (Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            $query->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'ann.ukm_account_id');
        }

        if (Schema::hasTable('kemahasiswaan_ukm_accounts') && Schema::hasTable('organizations')) {
            $query->leftJoin('organizations as org', 'org.id', '=', 'akun.organization_id');
        }

        $query->select([
            'ann.id', 'ann.title', 'ann.category', 'ann.summary', 'ann.content', 'ann.publish_at',
            'ann.created_at', 'ann.publish_status',
        ]);

        if (Schema::hasTable('kemahasiswaan_ukm_accounts')) {
            $query->addSelect('akun.name as account_name');
        } else {
            $query->selectRaw('NULL as account_name');
        }

        if (Schema::hasTable('kemahasiswaan_ukm_accounts') && Schema::hasTable('organizations')) {
            $query->addSelect('org.name as organization_name');
            $query->addSelect('org.banner as organization_banner');
        } else {
            $query->selectRaw('NULL as organization_name');
            $query->selectRaw('NULL as organization_banner');
        }

        $query->whereIn('ann.publish_status', ['published', 'scheduled', 'archived']);
        $query->orderByDesc(DB::raw('COALESCE(ann.publish_at, ann.created_at)'));

        return $query->limit(300)->get()->map(function ($row) {
            $date = $this->formatDate($row->publish_at ?: $row->created_at);
            $summary = $row->summary ?: Str::limit(trim(strip_tags((string) $row->content)), 160, '...');
            $content = trim((string) $row->content);
            $contentHtml = $content !== '' ? nl2br(e($content)) : '<p>' . e((string) $summary) . '</p>';
            $source = $row->organization_name ?: ($row->account_name ?: 'Kemahasiswaan');
            $category = $row->category ?: 'Umum';
            $title = (string) $row->title;
            $isHighPriority = Str::contains(Str::lower($title . ' ' . $category), ['penting', 'urgent', 'darurat']);

            return [
                'id' => (int) $row->id,
                'title' => $title,
                'category' => $category,
                'source' => $source,
                'date' => $date,
                'image' => $this->resolveMediaUrl($row->organization_banner) ?: $this->placeholderImage($title),
                'summary' => $summary,
                'priority' => $isHighPriority ? 'high' : 'normal',
                'content_html' => $contentHtml,
            ];
        })->values()->all();
    }

    /**
     * @return array{urgent: array<int, array<string, mixed>>, items: array<int, array<string, mixed>>}
     */
    public function loadLostFound(): array
    {
        if (!Schema::hasTable('lost_found_items')) {
            return ['urgent' => [], 'items' => []];
        }

        $columns = Schema::getColumnListing('lost_found_items');
        $hasColumn = fn (string $column): bool => in_array($column, $columns, true);

        $nameColumn = $hasColumn('item_name') ? 'item_name' : ($hasColumn('title') ? 'title' : ($hasColumn('name') ? 'name' : null));
        $descriptionColumn = $hasColumn('description') ? 'description' : null;
        $imageColumn = $hasColumn('image') ? 'image' : ($hasColumn('photo') ? 'photo' : null);
        $locationColumn = $hasColumn('location_found') ? 'location_found' : ($hasColumn('location') ? 'location' : null);
        $typeColumn = $hasColumn('type') ? 'type' : null;
        $statusColumn = $hasColumn('status') ? 'status' : null;
        $dateColumn = $hasColumn('created_at') ? 'created_at' : ($hasColumn('report_date') ? 'report_date' : null);
        $reporterColumn = $hasColumn('reported_by') ? 'reported_by' : ($hasColumn('reporter_id') ? 'reporter_id' : null);

        $query = DB::table('lost_found_items as lf')->select('lf.id');

        $query->addSelect($nameColumn ? 'lf.' . $nameColumn . ' as item_name' : DB::raw('NULL as item_name'));
        $query->addSelect($descriptionColumn ? 'lf.' . $descriptionColumn . ' as item_description' : DB::raw('NULL as item_description'));
        $query->addSelect($imageColumn ? 'lf.' . $imageColumn . ' as item_image' : DB::raw('NULL as item_image'));
        $query->addSelect($locationColumn ? 'lf.' . $locationColumn . ' as item_location' : DB::raw('NULL as item_location'));
        $query->addSelect($typeColumn ? 'lf.' . $typeColumn . ' as item_type' : DB::raw("'lost' as item_type"));
        $query->addSelect($statusColumn ? 'lf.' . $statusColumn . ' as item_status' : DB::raw("'active' as item_status"));
        $query->addSelect($dateColumn ? 'lf.' . $dateColumn . ' as reported_at' : DB::raw('NULL as reported_at'));

        if ($reporterColumn && Schema::hasTable('users')) {
            $query->leftJoin('users as reporter', 'reporter.id', '=', 'lf.' . $reporterColumn);
            $query->addSelect('reporter.name as reporter_name');
        } else {
            $query->selectRaw('NULL as reporter_name');
        }

        $rows = $query->orderByDesc('lf.id')->limit(300)->get();

        $items = $rows->map(function ($row) {
            $name = (string) ($row->item_name ?: '');
            $type = in_array((string) $row->item_type, ['lost', 'found'], true) ? (string) $row->item_type : 'lost';
            $description = (string) ($row->item_description ?: '');
            $meta = $this->extractLostFoundMeta($description);
            $reviewStatus = Str::lower((string) ($meta['ReviewStatus'] ?? 'approved'));
            $isReviewApproved = !in_array($reviewStatus, ['pending_bem', 'pending', 'draft', 'under_review'], true);

            return [
                'id' => (int) $row->id,
                'type' => $type,
                'name' => $name,
                'category' => $this->inferLostFoundCategory($name),
                'location' => $row->item_location ?: '',
                'date' => $this->formatDate($row->reported_at),
                'reporter' => $row->reporter_name ?: (string) ($meta['Pelapor'] ?? ''),
                'status' => $this->mapLostFoundStatus((string) $row->item_status),
                'image' => $row->item_image ?: $this->placeholderImage($name),
                'description' => $description,
                'contact' => (string) ($meta['Kontak'] ?? ''),
                'is_review_approved' => $isReviewApproved,
            ];
        })->filter(fn ($item) => $item['is_review_approved'])->values();

        $urgent = $items
            ->filter(fn ($item) => $item['type'] === 'lost' && $item['status'] === 'Belum ditemukan')
            ->take(5)
            ->map(fn ($item) => ['name' => $item['name'], 'image' => $item['image']])
            ->values()
            ->all();

        return ['urgent' => $urgent, 'items' => $items->all()];
    }

    /**
     * @param array<int, array<string, mixed>> $events
     * @param array<int, array<string, mixed>> $announcements
     * @param array<int, array<string, mixed>> $lostFoundItems
     * @return array<int, array<string, mixed>>
     */
    private function loadNotifications(array $events, array $announcements, array $lostFoundItems): array
    {
        if (Schema::hasTable('kemahasiswaan_activity_logs')) {
            $rows = DB::table('kemahasiswaan_activity_logs as log')
                ->leftJoin('organizations as org', 'org.id', '=', 'log.organization_id')
                ->select(['log.action', 'log.description', 'log.created_at', 'org.name as organization_name'])
                ->orderByDesc('log.id')
                ->limit(8)
                ->get();

            return $rows->map(function ($row) {
                return [
                    'title' => Str::limit((string) ($row->description ?: $row->action), 90, '...'),
                    'category' => $row->organization_name ?: 'Sistem',
                    'timestamp' => $this->parseDate($row->created_at)?->diffForHumans() ?? '-',
                    'icon' => $this->inferNotificationIcon((string) $row->action),
                ];
            })->values()->all();
        }

        $fallback = [];
        foreach (array_slice($announcements, 0, 3) as $item) {
            $fallback[] = [
                'title' => (string) ($item['title'] ?? 'Pengumuman'),
                'category' => (string) ($item['source'] ?? 'Kemahasiswaan'),
                'timestamp' => (string) ($item['date'] ?? '-'),
                'icon' => 'megaphone',
            ];
        }

        foreach (array_slice($events, 0, 3) as $item) {
            $fallback[] = [
                'title' => (string) ($item['title'] ?? 'Kegiatan'),
                'category' => (string) ($item['organizer'] ?? 'Organisasi'),
                'timestamp' => (string) ($item['date'] ?? '-'),
                'icon' => 'calendar-event',
            ];
        }

        if (!empty($lostFoundItems)) {
            $latestItem = $lostFoundItems[0];
            $fallback[] = [
                'title' => 'Barang Hilang: ' . ($latestItem['name'] ?? ''),
                'category' => 'Kehilangan & Penemuan',
                'timestamp' => (string) ($latestItem['date'] ?? '-'),
                'icon' => 'search',
            ];
        }

        return array_slice($fallback, 0, 8);
    }

    /**
     * @return array<string, mixed>
     */
    private function mapOrganizationEvent(object $row): array
    {
        $startDate = $this->parseDate($row->start_date);
        $endDate = $this->parseDate($row->end_date);
        $fullDate = $startDate
            ? ($endDate && !$startDate->isSameDay($endDate)
                ? $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')
                : $startDate->format('d M Y'))
            : '-';

        return [
            'id' => (int) $row->id,
            'name' => (string) $row->name,
            'date' => $startDate ? $startDate->format('d M Y') : '-',
            'full_date' => $fullDate,
            'description' => (string) ($row->description ?: ''),
            'activities' => array_filter([
                $startDate ? 'Pembukaan: ' . $startDate->format('H:i') : null,
                $endDate ? 'Penutupan: ' . $endDate->format('H:i') : null,
            ]),
            'highlights' => Str::limit((string) ($row->description ?: ''), 140, '...'),
            'images' => [$row->banner ?: $this->placeholderImage((string) $row->name)],
            'organizer' => null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function mapCampusEvent(object $row, string $category): array
    {
        $startDate = $this->parseDate($row->start_date);
        $endDate = $this->parseDate($row->end_date);
        $status = (string) ($row->status ?? '');
        $organizationId = (int) ($row->organization_id ?? 0);
        $effectiveEndDate = $endDate ?? $startDate;

        return [
            'id' => (int) $row->id,
            'title' => (string) $row->name,
            'organizer' => $row->organization_name ?: null,
            'organizer_id' => $organizationId,
            'date' => $this->formatDate($row->start_date),
            'start_date_iso' => $startDate?->toDateString() ?? '',
            'end_date_iso' => $effectiveEndDate?->toDateString() ?? '',
            'time' => $this->formatTimeRange($startDate, $endDate),
            'location' => (string) ($row->location ?: ''),
            'category' => $category,
            'poster' => $row->banner ?: $this->placeholderImage((string) $row->name),
            'participants' => (int) ($row->current_participants ?? 0),
            'registration_status' => $this->mapEventStatusLabel($status),
            'registration_open' => $this->isEventRegistrationOpen($status),
            'register_url' => $organizationId > 0 ? route('mahasiswa.organisasi.daftar', ['id' => $organizationId]) : null,
            'description' => (string) ($row->description ?: ''),
            'benefits' => [],
            'speakers' => [],
            'schedule' => array_values(array_filter([
                $startDate ? ['time' => $startDate->format('H:i'), 'activity' => 'Kegiatan dimulai'] : null,
                $endDate ? ['time' => $endDate->format('H:i'), 'activity' => 'Kegiatan selesai'] : null,
            ])),
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function extractRegistrationDivisions(array $registrationPayload, array $structure): array
    {
        if (is_array(data_get($registrationPayload, 'divisions'))) {
            $registrationDivisions = collect((array) $registrationPayload['divisions'])
                ->map(function ($division) {
                    if (!is_array($division)) {
                        return null;
                    }
                    return [
                        'name' => (string) ($division['name'] ?? ''),
                        'description' => (string) ($division['description'] ?? ''),
                    ];
                })
                ->filter(fn ($item) => !empty($item['name']))
                ->values()
                ->all();

            if (!empty($registrationDivisions)) {
                return $registrationDivisions;
            }
        }

        return collect($structure)
            ->take(6)
            ->map(fn ($member) => [
                'name' => $member['position'],
                'description' => '',
            ])
            ->values()
            ->all();
    }

    // ============ Helper Methods (Use Trait Methods) ============

    private function parseDate(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Carbon::parse((string) $value);
        } catch (Throwable) {
            return null;
        }
    }

    private function formatDate(mixed $value): string
    {
        return $this->parseDate($value)?->format('d M Y') ?? '-';
    }

    private function formatTimeRange(?Carbon $startDate, ?Carbon $endDate): string
    {
        if (!$startDate && !$endDate) {
            return '-';
        }

        if ($startDate && $endDate) {
            return $startDate->format('H:i') . ' - ' . $endDate->format('H:i');
        }

        return ($startDate ?: $endDate)->format('H:i');
    }

    private function splitListText(?string $text): array
    {
        if (!$text) {
            return [];
        }

        $rows = preg_split('/\r\n|\r|\n/', $text) ?: [];
        return collect($rows)
            ->map(fn ($row) => trim(preg_replace('/^\d+[\.)]\s*/', '', (string) $row)))
            ->filter()
            ->values()
            ->all();
    }

    private function normalizeSocialLink(?string $value, string $platform): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        return match (Str::lower($platform)) {
            'instagram' => 'https://instagram.com/' . ltrim($value, '@'),
            'facebook' => 'https://facebook.com/' . ltrim($value, '@'),
            'tiktok' => 'https://tiktok.com/@' . ltrim($value, '@'),
            'youtube' => 'https://youtube.com/@' . ltrim($value, '@'),
            default => $value,
        };
    }

    private function extractLostFoundMeta(string $description): array
    {
        if ($description === '') {
            return [];
        }

        $meta = [];
        $lines = preg_split('/\r\n|\r|\n/', $description) ?: [];

        foreach ($lines as $line) {
            $line = trim((string) $line);
            if ($line === '' || !str_contains($line, ':')) {
                continue;
            }

            [$rawKey, $rawValue] = explode(':', $line, 2);
            $key = trim((string) $rawKey);
            $value = trim((string) $rawValue);

            if ($key !== '' && $value !== '') {
                $meta[$key] = $value;
            }
        }

        return $meta;
    }

    private function normalizeInstagramUrl(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }
        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }
        return 'https://instagram.com/' . ltrim($value, '@');
    }

    private function normalizeWhatsappUrl(?string $value): ?string
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }
        if (Str::startsWith($raw, ['http://', 'https://'])) {
            return $raw;
        }
        $number = preg_replace('/[^0-9]/', '', $raw);
        if ($number === '') {
            return null;
        }
        if (Str::startsWith($number, '0')) {
            $number = '62' . substr($number, 1);
        }
        return 'https://wa.me/' . $number;
    }

    private function resolveMediaUrl(mixed $value): ?string
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }
        if (Str::startsWith($raw, ['data:image/', 'http://', 'https://', '/storage/'])) {
            return $raw;
        }
        if (Str::startsWith($raw, 'storage/')) {
            return '/' . ltrim($raw, '/');
        }
        $publicRelativePath = ltrim($raw, '/');
        if (Str::startsWith($publicRelativePath, 'public/')) {
            $storageRelativePath = Str::after($publicRelativePath, 'public/');
            if ($storageRelativePath !== '' && \Illuminate\Support\Facades\Storage::disk('public')->exists($storageRelativePath)) {
                return \Illuminate\Support\Facades\Storage::url($storageRelativePath);
            }
        }
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($publicRelativePath)) {
            return \Illuminate\Support\Facades\Storage::url($publicRelativePath);
        }
        if (file_exists(public_path($publicRelativePath))) {
            return '/' . $publicRelativePath;
        }
        if (!Str::contains($raw, ['/', '.'])) {
            return null;
        }
        return null;
    }

    private function acronym(?string $shortname, ?string $name): string
    {
        $shortname = trim((string) $shortname);
        if ($shortname !== '') {
            return Str::upper($shortname);
        }
        $words = preg_split('/\s+/', trim((string) $name)) ?: [];
        $acronym = collect($words)
            ->filter()
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
        $acronym = Str::upper(Str::limit($acronym, 4, ''));
        return $acronym !== '' ? $acronym : 'UFO';
    }

    private function inferOrganizationCategory(?string $name, ?string $shortname): string
    {
        $text = Str::lower(trim((string) $name . ' ' . (string) $shortname));
        if ($text === '') {
            return 'Umum';
        }
        if (Str::contains($text, ['bem'])) {
            return 'BEM';
        }
        if (Str::contains($text, ['choir', 'paduan suara', 'vocal', 'echo'])) {
            return 'Paduan Suara';
        }
        if (Str::contains($text, ['creative', 'cinema', 'sinema', 'media', 'event', 'organizer', 'computer', 'science', 'cssa', 'uvics'])) {
            return 'Kreatif & Teknologi';
        }
        if (Str::contains($text, ['ikatan', 'daerah', 'papua', 'minahasa', 'maluku', 'ikmapap', 'ikmamalut'])) {
            return 'Kedaerahan';
        }
        if (Str::contains($text, ['ministry', 'rohis', 'kerohanian', 'pilgrims', 'penginjilan', 'mission'])) {
            return 'Kerohanian';
        }
        return 'Umum';
    }

    private function inferLostFoundCategory(string $name): string
    {
        $text = Str::lower($name);
        if (Str::contains($text, ['dompet', 'wallet', 'uang'])) {
            return 'Dompet';
        }
        if (Str::contains($text, ['kunci', 'key'])) {
            return 'Kunci';
        }
        if (Str::contains($text, ['ktm', 'id', 'kartu', 'card'])) {
            return 'Kartu Identitas';
        }
        if (Str::contains($text, ['laptop', 'hp', 'handphone', 'headset', 'earphone'])) {
            return 'Elektronik';
        }
        return 'Lainnya';
    }

    private function mapLostFoundStatus(string $status): string
    {
        return match (Str::lower($status)) {
            'active', 'pending', 'approved' => 'Belum ditemukan',
            'claimed', 'closed', 'resolved', 'found' => 'Selesai',
            default => 'Menunggu',
        };
    }

    private function mapEventStatusLabel(string $status): string
    {
        $normalized = Str::lower($status);
        $references = $this->loadReferenceDomain('event_status_map');
        $label = (string) data_get($references, $normalized . '.label', '');
        return $label !== '' ? $label : Str::title(str_replace('_', ' ', $status));
    }

    private function inferNotificationIcon(string $action): string
    {
        $action = Str::lower($action);
        if (Str::contains($action, ['event', 'jadwal'])) {
            return 'calendar-event';
        }
        if (Str::contains($action, ['lost', 'found', 'barang'])) {
            return 'search';
        }
        if (Str::contains($action, ['pengumuman', 'announcement'])) {
            return 'megaphone';
        }
        return 'info-circle';
    }

    private function isEventRegistrationOpen(string $status): bool
    {
        return in_array(Str::lower(trim($status)), [
            'approved', 'ongoing', 'open', 'scheduled', 'published', 'active',
        ], true);
    }

    private function withAllCategory(array $categories): array
    {
        return array_values(array_merge(
            ['Semua'],
            collect($categories)->filter(fn ($item) => trim((string) $item) !== '')->unique()->values()->all()
        ));
    }

    private function loadReferenceDomain(string $domain): array
    {
        if (array_key_exists($domain, $this->referenceCache)) {
            return $this->referenceCache[$domain];
        }

        if (!Schema::hasTable('workflow_reference_values')) {
            $this->referenceCache[$domain] = [];
            return [];
        }

        $map = DB::table('workflow_reference_values')
            ->select(['code', 'label', 'payload'])
            ->where('domain', $domain)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->mapWithKeys(function ($row) {
                $payload = [];
                if (is_string($row->payload) && trim($row->payload) !== '') {
                    $decoded = json_decode($row->payload, true);
                    $payload = is_array($decoded) ? $decoded : [];
                }
                return [(string) $row->code => ['label' => $row->label, 'payload' => $payload]];
            })
            ->all();

        $this->referenceCache[$domain] = $map;
        return $map;
    }

    private function placeholderImage(string $label): string
    {
        $text = trim($label) !== '' ? Str::limit(trim($label), 30, '') : 'UFO';
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="675" viewBox="0 0 1200 675"><rect width="1200" height="675" fill="#E6EEF4"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#44576D" font-size="44" font-family="Arial, sans-serif">' . e($text) . '</text></svg>';
        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }
}
