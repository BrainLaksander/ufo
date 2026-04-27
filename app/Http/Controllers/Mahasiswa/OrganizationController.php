<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Services\Mahasiswa\MahasiswaDataProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    use MahasiswaControllerTrait;

    private MahasiswaDataProvider $dataProvider;

    public function __construct(MahasiswaDataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
        $this->referenceCache = [];
    }

    public function indexRedirect(): RedirectResponse
    {
        return redirect()->route('mahasiswa.organisasi.index');
    }

    public function index(): View
    {
        $data = $this->dataProvider->loadAllData();

        return view('mahasiswa.organisasi', [
            'pageContent' => $this->loadMahasiswaPublicUiContent('organisasi'),
            'carouselImages' => $data['carousel_images'],
            'categories' => $data['organization_categories'],
            'organizations' => array_values($data['organizations']),
            'notifications' => $data['notifications'],
        ]);
    }

    public function show(int $id): View
    {
        $data = $this->dataProvider->loadAllData();
        $org = $data['organizations'][$id] ?? null;

        abort_if($org === null, 404);

        return view('mahasiswa.organisasi-detail', [
            'pageContent' => $this->loadMahasiswaPublicUiContent('organisasi_detail'),
            'org' => $org,
            'notifications' => $data['notifications'],
        ]);
    }

    public function daftar(int $id): View
    {
        $data = $this->dataProvider->loadAllData();
        $org = $data['organizations'][$id] ?? null;

        abort_if($org === null, 404);

        return view('mahasiswa.organisasi-daftar', [
            'pageContent' => $this->loadMahasiswaPublicUiContent('organisasi_daftar'),
            'org' => $org,
            'registration' => $org['registration'] ?? [],
            'notifications' => $data['notifications'],
        ]);
    }

    public function eventShow(int $orgId, string $eventId): View
    {
        $data = $this->dataProvider->loadAllData();
        $org = $data['organizations'][$orgId] ?? null;

        abort_if($org === null, 404);

        $event = collect($org['events'] ?? [])->firstWhere('id', $eventId);
        abort_if($event === null, 404);

        return view('mahasiswa.organisasi-event-detail', [
            'pageContent' => $this->loadMahasiswaPublicUiContent('organisasi_event_detail'),
            'org' => $org,
            'event' => $event,
            'notifications' => $data['notifications'],
        ]);
    }

    private function loadMahasiswaPublicUiContent(string $code): array
    {
        $references = $this->loadReferenceDomain('mahasiswa_public_ui');
        return data_get($references, $code . '.payload', []);
    }
}
