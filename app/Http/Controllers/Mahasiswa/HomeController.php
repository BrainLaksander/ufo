<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Services\Mahasiswa\MahasiswaDataProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    use MahasiswaControllerTrait;

    /**
     * Cache for loaded reference domains.
     * Declared to avoid dynamic property deprecation warnings.
     * @var array<string,mixed>
     */
    protected array $referenceCache = [];

    private MahasiswaDataProvider $dataProvider;

    public function __construct(MahasiswaDataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
        $this->referenceCache = [];
    }

    public function beranda(): View
    {
        $data = $this->dataProvider->loadAllData();

        return view('pages.mahasiswa.beranda', [
            'pageContent' => $this->loadMahasiswaHomeContent(),
            'carousel_images' => $data['carousel_images'],
            'organizations' => array_values($data['organizations']),
            'events' => array_slice($data['events'], 0, 6),
            'announcements' => array_slice($data['announcements'], 0, 4),
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
        return array_map(fn($item) => $item['payload'] ?? [], $references);
    }
}
