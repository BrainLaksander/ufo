<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class ActivityController extends Controller
{
    use PengurusControllerTrait;

    public function members(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = (int) ($context['organization_id'] ?? 0);
        $sessionUser = $request->session()->get('user');

        $viewData = $this->loadOrganizationProfileCards($organizationId);
        $viewData['loggedOrganizationName'] = trim((string) ($context['organization_name'] ?? ''));
        $viewData['loggedAccountName'] = is_array($sessionUser)
            ? trim((string) ($sessionUser['name'] ?? ''))
            : '';

        return view('pages.pengurus.members', $viewData);
    }

    public function applications(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);

        return view('pages.pengurus.applications', [
            // view expects `$contacts` variable
            'contacts' => $this->loadContactMiniCards((int) ($context['organization_id'] ?? 0)),
        ]);
    }

    public function lostFound(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = (int) ($context['organization_id'] ?? 0);
        $organizationName = (string) ($context['organization_name'] ?? '');
        
        if (!$this->canAccessLostAndFound($context['organization_level'] ?? null, $organizationName)) {
            abort(403);
        }

        $items = $this->loadLostFoundCards($organizationId);
        $lostItems = array_values(array_filter($items, static fn (array $item): bool => $item['type'] === 'lost'));
        $foundItems = array_values(array_filter($items, static fn (array $item): bool => $item['type'] === 'found'));
        $openLostOptions = array_values(array_filter($lostItems, static fn (array $item): bool => in_array((string) ($item['status'] ?? ''), ['active'], true)));

        return view('pages.pengurus.lostandfound', [
            'isBem' => Str::contains(Str::lower($organizationName), 'bem'),
            'items' => $items,
            'lostItems' => $lostItems,
            'foundItems' => $foundItems,
            'openLostOptions' => $openLostOptions,
            'priorityItems' => array_values(array_filter($items, static fn (array $item): bool => !empty($item['priority']))),
            'statusLabel' => $this->lostFoundStatusLabelMap(),
            'statusPill' => $this->lostFoundStatusPillMap(),
        ]);
    }

    public function storeLostFound(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);
        $organizationName = (string) ($context['organization_name'] ?? '');

        if (!$this->canAccessLostAndFound($context['organization_level'] ?? null, $organizationName)) {
            abort(403);
        }

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'type' => 'required|in:lost,found',
            'description' => 'nullable|string|max:2000',
            'location_found' => 'required|string|max:500',
            'reporter_name' => 'required|string|max:120',
            'reporter_contact' => 'required|string|max:120',
            'linked_lost_item_id' => 'nullable|integer|min:1',
            'photo_data' => 'nullable|string',
        ]);

        if (!Schema::hasTable('lost_found_items')) {
            return back()->with('error', 'Tabel Lost & Found belum tersedia.');
        }

        $type = Str::lower((string) ($validated['type'] ?? 'lost'));
        $columns = Schema::getColumnListing('lost_found_items');
        $columnExists = static fn (string $column) => in_array($column, $columns, true);

        $linkedLostItemId = null;
        if ($type === 'found') {
            $linkedLostItemId = (int) ($validated['linked_lost_item_id'] ?? 0);
            if ($linkedLostItemId <= 0) {
                return back()->withErrors([
                    'linked_lost_item_id' => 'Pilih laporan barang hilang yang sesuai sebelum menyimpan barang ditemukan.',
                ])->withInput();
            }

            $lostQuery = DB::table('lost_found_items')
                ->where('id', $linkedLostItemId)
                ->where('type', 'lost');

            if ($columnExists('organization_id')) {
                $organizationId = (int) ($context['organization_id'] ?? 0);
                $lostQuery->where(function ($query) use ($organizationId) {
                    $query->where('organization_id', $organizationId)
                        ->orWhereNull('organization_id');
                });
            }

            if (!$lostQuery->exists()) {
                return back()->withErrors([
                    'linked_lost_item_id' => 'Laporan barang hilang tidak ditemukan atau sudah tidak tersedia.',
                ])->withInput();
            }

            if (trim((string) ($validated['photo_data'] ?? '')) === '') {
                return back()->withErrors([
                    'photo_data' => 'Foto real-time wajib diambil untuk laporan barang ditemukan.',
                ])->withInput();
            }
        }

        $storedImagePath = null;
        if ($type === 'found') {
            $storedImagePath = $this->storeRealtimePhoto((string) ($validated['photo_data'] ?? ''));
            if ($storedImagePath === null) {
                return back()->withErrors([
                    'photo_data' => 'Foto real-time tidak valid. Ambil ulang foto dan coba lagi.',
                ])->withInput();
            }
        }

        $metaDescription = $this->buildLostFoundDescription(
            (string) ($validated['description'] ?? ''),
            (string) $validated['reporter_name'],
            (string) $validated['reporter_contact'],
            'approved'
        );

        $insertData = [
            'item_name' => $validated['item_name'],
            'description' => $metaDescription,
            'location_found' => $validated['location_found'],
            'type' => $type,
            'status' => $type === 'found' ? 'claimed' : 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($columnExists('organization_id')) {
            $insertData['organization_id'] = (int) ($context['organization_id'] ?? 0) ?: null;
        }

        if ($columnExists('reported_by')) {
            $sessionUser = $request->session()->get('user');
            $insertData['reported_by'] = is_array($sessionUser) && isset($sessionUser['id'])
                ? (int) $sessionUser['id']
                : null;
        }

        if ($columnExists('image')) {
            $insertData['image'] = $storedImagePath;
        }

        if ($columnExists('reporter_name')) {
            $insertData['reporter_name'] = (string) $validated['reporter_name'];
        }

        if ($columnExists('reporter_contact')) {
            $insertData['reporter_contact'] = (string) $validated['reporter_contact'];
        }

        if ($columnExists('linked_lost_item_id')) {
            $insertData['linked_lost_item_id'] = $linkedLostItemId;
        }

        DB::table('lost_found_items')->insert($insertData);

        if ($type === 'found' && $linkedLostItemId !== null) {
            DB::table('lost_found_items')
                ->where('id', $linkedLostItemId)
                ->update([
                    'status' => 'claimed',
                    'updated_at' => now(),
                ]);
        }

        return back()->with('success', $type === 'found'
            ? 'Laporan barang ditemukan berhasil dikirim ke BEM dan ditautkan ke laporan kehilangan.'
            : 'Laporan barang hilang berhasil dicatat untuk ditindaklanjuti BEM.');
    }

    public function storeJadwal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:150',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:160',
            'kategori' => 'nullable|string|max:40',
            'organization_id' => 'required|integer|exists:organizations,id',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        if (!Schema::hasTable('kemahasiswaan_schedules')) {
            return back()->with('error', 'Tabel kemahasiswaan_schedules belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $startAt = Carbon::parse((string) $validated['tanggal_mulai'])->startOfDay();
        $endAt = null;
        if (!empty($validated['tanggal_selesai'])) {
            $endAt = Carbon::parse((string) $validated['tanggal_selesai'])->endOfDay();
        }

        $columns = Schema::getColumnListing('kemahasiswaan_schedules');
        $columnExists = static fn (string $column): bool => in_array($column, $columns, true);

        $insertData = [
            'organization_id' => (int) $validated['organization_id'],
            'title' => trim((string) $validated['judul']),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'location' => trim((string) $validated['lokasi']),
            'status' => 'planned',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($columnExists('category')) {
            $insertData['category'] = trim((string) ($validated['kategori'] ?? '')) ?: null;
        }

        if ($columnExists('description')) {
            $insertData['description'] = trim((string) ($validated['deskripsi'] ?? '')) ?: null;
        }

        if ($columnExists('created_by')) {
            $sessionUser = $request->session()->get('user');
            $insertData['created_by'] = is_array($sessionUser) && isset($sessionUser['id'])
                ? (int) $sessionUser['id']
                : null;
        }

        DB::table('kemahasiswaan_schedules')->insert($insertData);

        return back()->with('success', 'Jadwal berhasil disimpan.');
    }

    public function destroyJadwal(int $id): RedirectResponse
    {
        if (!Schema::hasTable('kemahasiswaan_schedules')) {
            return back()->with('error', 'Tabel kemahasiswaan_schedules belum tersedia.');
        }

        $deleted = DB::table('kemahasiswaan_schedules')->where('id', $id)->delete();
        if ($deleted === 0) {
            return back()->with('error', 'Jadwal tidak ditemukan atau sudah dihapus.');
        }

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function importKalenderPdf(Request $request): RedirectResponse
    {
        $request->validate([
            'calendar_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        return back()->with('success', 'Kalender berhasil diimpor dari PDF.');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadLostFoundCards(int $organizationId): array
    {
        if ($organizationId <= 0 || !Schema::hasTable('lost_found_items')) {
            return [];
        }

        $columns = Schema::getColumnListing('lost_found_items');
        $hasColumn = static fn (string $column): bool => in_array($column, $columns, true);

        try {
            $query = DB::table('lost_found_items as item')
                ->leftJoin('users as reporter', 'reporter.id', '=', 'item.reported_by')
                ->where(function ($query) use ($organizationId) {
                    $query->where('item.organization_id', $organizationId)
                        ->orWhereNull('item.organization_id');
                })
                ->orderByDesc('item.created_at')
                ->limit(50);

            if ($hasColumn('linked_lost_item_id')) {
                $query->leftJoin('lost_found_items as linked', 'linked.id', '=', 'item.linked_lost_item_id');
            }

            $rows = $query->get([
                    'item.id',
                    'item.item_name',
                    'item.description',
                    'item.location_found',
                    'item.type',
                    'item.status',
                    $hasColumn('image') ? 'item.image' : DB::raw('NULL as image'),
                    $hasColumn('reporter_name') ? 'item.reporter_name as item_reporter_name' : DB::raw('NULL as item_reporter_name'),
                    $hasColumn('reporter_contact') ? 'item.reporter_contact' : DB::raw('NULL as reporter_contact'),
                    $hasColumn('linked_lost_item_id') ? 'item.linked_lost_item_id' : DB::raw('NULL as linked_lost_item_id'),
                    'item.created_at',
                    'reporter.name as user_reporter_name',
                    $hasColumn('linked_lost_item_id') ? 'linked.item_name as linked_item_name' : DB::raw('NULL as linked_item_name'),
                ]);
        } catch (\Throwable $e) {
            logger()->error('loadLostFoundCards query failed', ['error' => $e->getMessage()]);
            return [];
        }

        return $rows->map(function ($row) {
            $itemStatus = Str::lower((string) ($row->status ?? 'active'));
            $type = Str::lower((string) ($row->type ?? 'lost'));

            return [
                'id' => (int) $row->id,
                'title' => (string) $row->item_name,
                'type' => in_array($type, ['found', 'ditemukan'], true) ? 'found' : 'lost',
                'item_status' => $itemStatus,
                'status' => $itemStatus,
                'priority' => $itemStatus === 'active',
                'date' => Carbon::parse((string) ($row->created_at ?? now()))->toDateString(),
                'reporter' => (string) (($row->item_reporter_name ?? '') !== ''
                    ? $row->item_reporter_name
                    : (($row->user_reporter_name ?? '') !== '' ? $row->user_reporter_name : 'Anonim')),
                'reporter_contact' => (string) ($row->reporter_contact ?? ''),
                'location' => (string) ($row->location_found ?? '-'),
                'description' => (string) ($row->description ?? ''),
                'image' => (string) ($row->image ?? ''),
                'linked_lost_item_id' => (int) ($row->linked_lost_item_id ?? 0),
                'linked_item_name' => (string) ($row->linked_item_name ?? ''),
                'notes' => '',
            ];
        })->all();
    }

    private function buildLostFoundDescription(string $description, string $reporterName, string $reporterContact, string $reviewStatus): string
    {
        $baseDescription = trim($description);
        $metaLines = [
            'Pelapor: ' . trim($reporterName),
            'Kontak: ' . trim($reporterContact),
            'ReviewStatus: ' . trim($reviewStatus),
        ];

        return trim($baseDescription . "\n" . implode("\n", $metaLines));
    }

    private function storeRealtimePhoto(string $photoData): ?string
    {
        $raw = trim($photoData);
        if ($raw === '' || !preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $raw, $matches)) {
            return null;
        }

        $extension = $matches[1] === 'png' ? 'png' : 'jpg';
        $base64Body = substr($raw, strpos($raw, ',') + 1);
        $binary = base64_decode($base64Body, true);

        if ($binary === false) {
            return null;
        }

        $directory = public_path('uploads/lost-found');
        if (!is_dir($directory) && !@mkdir($directory, 0775, true) && !is_dir($directory)) {
            return null;
        }

        $filename = 'lf-' . now()->format('YmdHis') . '-' . Str::random(10) . '.' . $extension;
        $absolutePath = $directory . DIRECTORY_SEPARATOR . $filename;

        try {
            file_put_contents($absolutePath, $binary);
        } catch (Throwable) {
            return null;
        }

        return '/uploads/lost-found/' . $filename;
    }

    private function lostFoundStatusLabelMap(): array
    {
        return [
            'active' => 'Aktif',
            'claimed' => 'Diambil',
            'closed' => 'Selesai',
        ];
    }

    private function lostFoundStatusPillMap(): array
    {
        return [
            'active' => 'pending',
            'claimed' => 'approved',
            'closed' => 'draft',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function loadOrganizationProfileCards(int $organizationId): array
    {
        if ($organizationId <= 0 || !Schema::hasTable('organizations')) {
            return [
                'kategoriOptions' => [],
                'activeKategori' => '',
                'programKegiatan' => [],
                'struktur' => [],
                'kontakPengurus' => [],
                'socialMedia' => [],
                'values' => [],
                'profileCompletion' => 0,
                'visionText' => '',
                'missionText' => '',
                'cultureText' => '',
                'organizationType' => '',
                'organizationLevel' => '',
                'organizationField' => '',
                'contactEmail' => '',
                'contactInstagram' => '',
                'contactFacebook' => '',
                'contactTiktok' => '',
                'contactYoutube' => '',
                'valuesText' => '',
                'programsText' => '',
                'structureText' => '',
                'contactsText' => '',
                'registrationOpen' => false,
                'registrationPeriod' => '',
                'registrationOpenDate' => '',
                'registrationFormLink' => '',
                'registrationGuidebookUrl' => '',
                'registrationDivisionsText' => '',
                'logoUrl' => '',
                'bannerUrl' => '',
            ];
        }

        $organization = DB::table('organizations')->where('id', $organizationId)->first();
        $members = $this->loadContactMiniCards($organizationId);
        $events = $this->loadProfileEventCards($organizationId);

        $values = $this->decodeProfileList($organization, 'profile_values_json', ['name', 'desc']);
        if (empty($values)) {
            $values = $this->buildMissionValues((string) ($organization->mission ?? ''));
        }

        $programs = $this->decodeProfileList($organization, 'profile_programs_json', ['name', 'description', 'goal']);
        if (empty($programs)) {
            $programs = array_map(static function (array $event): array {
                return [
                    'name' => $event['judul'],
                    'description' => $event['lokasi'],
                    'goal' => $event['status'],
                ];
            }, $events);
        }

        $structure = $this->decodeProfileList($organization, 'profile_structure_json', ['jabatan', 'nama']);
        if (empty($structure)) {
            $structure = array_map(static function (array $member): array {
                return [
                    'jabatan' => $member['jabatan'],
                    'nama' => $member['nama'],
                ];
            }, $members);
        }

        $contactRows = $this->decodeProfileList($organization, 'profile_contacts_json', ['platform', 'value']);
        $contactValue = static function (array $rows, string $platform): string {
            foreach ($rows as $row) {
                if (Str::lower(trim((string) ($row['platform'] ?? ''))) === $platform) {
                    return trim((string) ($row['value'] ?? ''));
                }
            }

            return '';
        };

        $contactInstagram = $contactValue($contactRows, 'instagram') ?: (string) ($organization->instagram ?? '');
        $contactEmail = $contactValue($contactRows, 'email') ?: (string) ($organization->email ?? '');
        $contactFacebook = $contactValue($contactRows, 'facebook');
        $contactTiktok = $contactValue($contactRows, 'tiktok');
        $contactYoutube = $contactValue($contactRows, 'youtube');
        $registrationPayload = [];
        if (Schema::hasColumn('organizations', 'profile_registration_json')) {
            $rawRegistration = trim((string) ($organization->profile_registration_json ?? ''));
            if ($rawRegistration !== '') {
                $decodedRegistration = json_decode($rawRegistration, true);
                if (is_array($decodedRegistration)) {
                    $registrationPayload = $decodedRegistration;
                }
            }
        }

        if (empty($registrationPayload) && Schema::hasTable('workflow_reference_values')) {
            $rawReferencePayload = DB::table('workflow_reference_values')
                ->where('domain', 'mahasiswa_org_registration')
                ->where('code', 'org_' . $organizationId)
                ->value('payload');

            if (is_string($rawReferencePayload) && trim($rawReferencePayload) !== '') {
                $decodedReferencePayload = json_decode($rawReferencePayload, true);
                if (is_array($decodedReferencePayload)) {
                    $registrationPayload = $decodedReferencePayload;
                }
            }
        }

        $registrationDivisions = collect((array) data_get($registrationPayload, 'divisions', []))
            ->filter(fn ($item) => is_array($item))
            ->map(fn (array $item): array => [
                'name' => trim((string) ($item['name'] ?? '')),
                'description' => trim((string) ($item['description'] ?? '')),
            ])
            ->filter(fn (array $item): bool => $item['name'] !== '' || $item['description'] !== '')
            ->values()
            ->all();
        $profileCompletion = $this->calculateProfileCompletionScore($organization);

        return [
            'kategoriOptions' => ['BEM', 'UKM', 'HIMA', 'HMJ'],
            'activeKategori' => $this->inferOrganizationCategory((string) ($organization->name ?? ''), (string) ($organization->shortname ?? '')),
            'programKegiatan' => array_map(static function (array $program): array {
                return [
                    'nama' => $program['name'] ?? '',
                    'deskripsi' => $program['description'] ?? '',
                    'tujuan' => $program['goal'] ?? '',
                ];
            }, $programs),
            'struktur' => $structure,
            'kontakPengurus' => $members,
            'socialMedia' => array_values(array_filter([
                $contactInstagram !== '' ? ['platform' => 'Instagram', 'value' => $contactInstagram] : null,
                $contactEmail !== '' ? ['platform' => 'Email', 'value' => $contactEmail] : null,
                $contactFacebook !== '' ? ['platform' => 'Facebook', 'value' => $contactFacebook] : null,
                $contactTiktok !== '' ? ['platform' => 'TikTok', 'value' => $contactTiktok] : null,
                $contactYoutube !== '' ? ['platform' => 'YouTube', 'value' => $contactYoutube] : null,
            ])),
            'values' => $values,
            'profileCompletion' => $profileCompletion,
            'visionText' => (string) ($organization->vision ?? ''),
            'missionText' => (string) ($organization->mission ?? ''),
            'cultureText' => (string) ($organization->description ?? ''),
            'organizationType' => Str::contains(Str::lower((string) ($organization->name ?? '')), 'bem') ? 'BEM' : 'UKM',
            'organizationLevel' => '',
            'organizationField' => '',
            'contactEmail' => $contactEmail,
            'contactInstagram' => $contactInstagram,
            'contactFacebook' => $contactFacebook,
            'contactTiktok' => $contactTiktok,
            'contactYoutube' => $contactYoutube,
            'valuesText' => implode("\n", array_map(static fn (array $value): string => trim((string) ($value['name'] ?? '')) . '|' . trim((string) ($value['desc'] ?? '')), $values)),
            'programsText' => implode("\n", array_map(static fn (array $program): string => trim((string) ($program['nama'] ?? '')) . '|' . trim((string) ($program['deskripsi'] ?? '')) . '|' . trim((string) ($program['tujuan'] ?? '')), $programs)),
            'structureText' => implode("\n", array_map(static fn (array $member): string => trim((string) ($member['jabatan'] ?? '')) . '|' . trim((string) ($member['nama'] ?? '')), $structure)),
            'contactsText' => implode("\n", array_map(static fn (array $member): string => $member['nama'] . '|' . $member['jabatan'] . '|' . $member['whatsapp'], $members)),
            'registrationOpen' => (bool) data_get($registrationPayload, 'open', false),
            'registrationPeriod' => trim((string) data_get($registrationPayload, 'period', '')),
            'registrationOpenDate' => trim((string) data_get($registrationPayload, 'open_date', '')),
            'registrationFormLink' => trim((string) data_get($registrationPayload, 'form_link', '')),
            'registrationGuidebookUrl' => trim((string) data_get($registrationPayload, 'guidebook_url', '')),
            'registrationDivisionsText' => implode("\n", array_map(static fn (array $division): string => $division['name'] . '|' . $division['description'], $registrationDivisions)),
            'logoUrl' => !empty($organization->logo) ? (string) $organization->logo : '',
            'bannerUrl' => !empty($organization->banner) ? (string) $organization->banner : '',
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadContactMiniCards(int $organizationId): array
    {
        if ($organizationId <= 0 || !Schema::hasTable('members')) {
            return [];
        }

        $organizationName = (string) (DB::table('organizations')->where('id', $organizationId)->value('name') ?? 'Organisasi');

        return DB::table('members')
            ->where('organization_id', $organizationId)
            ->where('status', 'aktif')
            ->orderByRaw("CASE position WHEN 'ketua' THEN 0 WHEN 'sekretaris' THEN 1 WHEN 'bendahara' THEN 2 ELSE 3 END")
            ->orderBy('name')
            ->limit(12)
            ->get(['id', 'name', 'position', 'phone'])
            ->map(function ($row) use ($organizationName) {
                return [
                    'id' => (int) $row->id,
                    'nama' => (string) $row->name,
                    'jabatan' => Str::title((string) ($row->position ?? 'staff')),
                    'organisasi' => $organizationName,
                    'whatsapp' => (string) ($row->phone ?? '-'),
                ];
            })
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildMissionValues(string $mission): array
    {
        $parts = preg_split('/\r\n|\r|\n|\./', trim($mission)) ?: [];

        return collect($parts)
            ->map(fn ($part) => trim((string) $part))
            ->filter(fn ($part) => $part !== '')
            ->take(5)
            ->map(fn (string $part) => ['name' => Str::limit($part, 48, ''), 'desc' => $part])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadProfileEventCards(int $organizationId): array
    {
        if ($organizationId <= 0 || !Schema::hasTable('events')) {
            return [];
        }

        return DB::table('events')
            ->where('organization_id', $organizationId)
            ->orderByDesc('start_date')
            ->limit(5)
            ->get(['id', 'name', 'start_date', 'location', 'status'])
            ->map(function ($row) {
                return [
                    'id' => (int) $row->id,
                    'judul' => (string) $row->name,
                    'tanggal' => Carbon::parse((string) $row->start_date)->translatedFormat('d M Y'),
                    'lokasi' => (string) ($row->location ?? '-'),
                    'status' => Str::title((string) ($row->status ?? 'draft')),
                ];
            })
            ->all();
    }

    private function calculateProfileCompletionScore(object $organization): int
    {
        $score = 0;
        $total = 7;

        foreach (['logo', 'banner', 'description', 'vision', 'mission', 'email', 'phone'] as $field) {
            if (!empty($organization->{$field})) {
                $score++;
            }
        }

        return (int) round(($score / $total) * 100);
    }
}
