<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Services\Mahasiswa\MahasiswaDataProvider;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    use MahasiswaControllerTrait;

    private MahasiswaDataProvider $dataProvider;

    public function __construct(MahasiswaDataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
        $this->referenceCache = [];
    }

    public function index(): View
    {
        $data = $this->dataProvider->loadAllData();

        return view('pages.mahasiswa.pengumuman', [
            'pageContent' => $this->loadMahasiswaPublicUiContent('pengumuman'),
            'categories' => $data['announcement_categories'],
            'announcements' => $data['announcements'],
            'notifications' => $data['notifications'],
        ]);
    }

    public function show(int $id): View
    {
        $data = $this->dataProvider->loadAllData();
        $announcement = collect($data['announcements'])->firstWhere('id', $id);

        abort_if($announcement === null, 404);

        return view('pages.mahasiswa.pengumuman-detail', [
            'pageContent' => $this->loadMahasiswaPublicUiContent('pengumuman_detail'),
            'announcement' => $announcement,
            'notifications' => $data['notifications'],
        ]);
    }

    private function loadMahasiswaPublicUiContent(string $code): array
    {
        $references = $this->loadReferenceDomain('mahasiswa_public_ui');
        return data_get($references, $code . '.payload', []);
    }
}
