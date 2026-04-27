<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Services\Mahasiswa\MahasiswaDataProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    use MahasiswaControllerTrait;

    private MahasiswaDataProvider $dataProvider;

    public function __construct(MahasiswaDataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
        $this->referenceCache = [];
    }

    public function beranda(): View
    {
        $data = $this->dataProvider->loadAllData();

        return view('mahasiswa.beranda', [
            'pageContent' => $this->loadMahasiswaHomeContent(),
            'carousel_images' => $data['carousel_images'],
            'organizations' => array_values($data['organizations']),
            'events' => array_slice($data['events'], 0, 6),
            'announcements' => array_slice($data['announcements'], 0, 4),
            'notifications' => array_slice($data['notifications'], 0, 5),
        ]);
    }

    public function tentang(): View
    {
        $data = $this->dataProvider->loadAllData();

        return view('mahasiswa.tentang', [
            'pageContent' => $this->loadMahasiswaAboutContent(),
            'organizations' => array_values($data['organizations']),
            'organization_categories' => $data['organization_categories'],
            'notifications' => array_slice($data['notifications'], 0, 5),
        ]);
    }

    public function indexRedirect(): RedirectResponse
    {
        return redirect()->route('mahasiswa.organisasi.index');
    }

    private function loadMahasiswaHomeContent(): array
    {
        $references = $this->loadReferenceDomain('mahasiswa_home');
        return data_get($references, 'home.payload', []);
    }

    private function loadMahasiswaAboutContent(): array
    {
        $references = $this->loadReferenceDomain('mahasiswa_about');
        return data_get($references, 'about.payload', []);
    }
}
