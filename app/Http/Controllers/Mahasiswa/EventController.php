<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Services\Mahasiswa\MahasiswaDataProvider;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    use MahasiswaControllerTrait;

    private MahasiswaDataProvider $dataProvider;

    public function __construct(MahasiswaDataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
        $this->referenceCache = [];
    }

    public function index(Request $request): View
    {
        $data = $this->dataProvider->loadAllData();
        $orgFilter = trim((string) $request->query('org', ''));
        $orgFilter = ctype_digit($orgFilter) ? (int) $orgFilter : null;
        $organizations = collect((array) ($data['organizations'] ?? []))
            ->values()
            ->map(fn (array $org): array => [
                'id' => (int) ($org['id'] ?? 0),
                'name' => (string) ($org['name'] ?? ''),
            ])
            ->filter(fn (array $org): bool => $org['id'] > 0 && $org['name'] !== '')
            ->values()
            ->all();

        return view('mahasiswa.event', [
            'pageContent' => $this->loadMahasiswaPublicUiContent('event'),
            'categories' => $data['event_categories'],
            'events' => $data['events'],
            'orgFilter' => $orgFilter,
            'organizations' => $organizations,
            'notifications' => $data['notifications'],
        ]);
    }

    public function show(int $id): View
    {
        $data = $this->dataProvider->loadAllData();
        $event = collect($data['events'])->firstWhere('id', $id);

        abort_if($event === null, 404);

        return view('mahasiswa.event-detail', [
            'pageContent' => $this->loadMahasiswaPublicUiContent('event_detail'),
            'event' => $event,
            'notifications' => $data['notifications'],
        ]);
    }

    public function calendar(): View
    {
        $data = $this->dataProvider->loadAllData();

        $calendarEvents = collect($data['events'])
            ->flatMap(function (array $event): array {
                $startDate = $this->parseDate($event['start_date_iso'] ?? ($event['date'] ?? null));
                $endDate = $this->parseDate($event['end_date_iso'] ?? ($event['start_date_iso'] ?? ($event['date'] ?? null)));

                if (!$startDate) {
                    return [];
                }

                if (!$endDate || $endDate->lt($startDate)) {
                    $endDate = $startDate->copy();
                }

                $items = [];
                $cursor = $startDate->copy()->startOfDay();
                $lastDay = $endDate->copy()->startOfDay();
                $safetyCounter = 0;

                while ($cursor->lte($lastDay) && $safetyCounter < 370) {
                    $items[] = [
                        'id' => (int) ($event['id'] ?? 0),
                        'title' => (string) ($event['title'] ?? ''),
                        'organizer' => (string) ($event['organizer'] ?? ''),
                        'location' => (string) ($event['location'] ?? ''),
                        'time' => (string) ($event['time'] ?? ''),
                        'category' => (string) ($event['category'] ?? ''),
                        'date_label' => (string) ($event['date'] ?? '-'),
                        'date_iso' => $cursor->toDateString(),
                        'detail_url' => route('mahasiswa.event.show', ['id' => (int) ($event['id'] ?? 0)]),
                    ];

                    $cursor->addDay();
                    $safetyCounter++;
                }

                return $items;
            })
            ->filter(fn (array $event): bool => $event['date_iso'] !== '')
            ->sortBy('date_iso')
            ->values()
            ->all();

        return view('mahasiswa.kalendar-kegiatan', [
            'calendarEvents' => $calendarEvents,
            'notifications' => $data['notifications'],
        ]);
    }

    private function loadMahasiswaPublicUiContent(string $code): array
    {
        $references = $this->loadReferenceDomain('mahasiswa_public_ui');
        return data_get($references, $code . '.payload', []);
    }
}
