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

        return view('portal.pengurus.members', $viewData);
    }

    public function applications(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);

        return view('portal.pengurus.applications', [
            'kontakPengurus' => $this->loadContactMiniCards((int) ($context['organization_id'] ?? 0)),
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

        return view('portal.pengurus.lostandfound', [
            'isBem' => Str::contains(Str::lower($organizationName), 'bem'),
            'items' => $items,
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
            'item_type' => 'required|in:lost,found',
            'description' => 'nullable|string|max:2000',
            'location' => 'nullable|string|max:500',
        ]);

        return back()->with('success', 'Laporan barang hilang/ditemukan berhasil disimpan.');
    }

    public function storeJadwal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'location' => 'nullable|string|max:200',
            'category' => 'nullable|string|max:40',
        ]);

        return back()->with('success', 'Jadwal berhasil disimpan.');
    }

    public function destroyJadwal(int $id): RedirectResponse
    {
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

        $rows = DB::table('lost_found_items as item')
            ->leftJoin('users as reporter', 'reporter.id', '=', 'item.reported_by')
            ->where(function ($query) use ($organizationId) {
                $query->where('item.organization_id', $organizationId)
                    ->orWhereNull('item.organization_id');
            })
            ->orderByDesc('item.created_at')
            ->limit(50)
            ->get([
                'item.id',
                'item.item_name',
                'item.description',
                'item.location_found',
                'item.type',
                'item.status',
                'item.created_at',
                'reporter.name as reporter_name',
            ]);

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
                'reporter' => (string) ($row->reporter_name ?? 'Anonim'),
                'location' => (string) ($row->location_found ?? '-'),
                'description' => (string) ($row->description ?? ''),
                'notes' => '',
            ];
        })->all();
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
