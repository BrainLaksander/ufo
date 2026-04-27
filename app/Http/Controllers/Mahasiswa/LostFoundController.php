<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Services\Mahasiswa\MahasiswaDataProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class LostFoundController extends Controller
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
        $urgentItems = (array) data_get($data, 'lost_found.urgent', []);
        $items = (array) data_get($data, 'lost_found.items', []);
        $categories = (array) data_get($data, 'lost_found_categories', []);

        return view('mahasiswa.lost-found', [
            'pageContent' => $this->loadMahasiswaPublicUiContent('lost_found'),
            'categories' => $categories,
            'urgent_items' => $urgentItems,
            'urgentItems' => $urgentItems,
            'items' => $items,
            'notifications' => $data['notifications'],
        ]);
    }

    public function report(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_type' => 'required|in:lost,found',
            'description' => 'nullable|string|max:2000',
            'location' => 'nullable|string|max:500',
        ]);

        if (!Schema::hasTable('lost_found_items')) {
            return back()->with('error', 'Tabel laporan tidak ditemukan.');
        }

        DB::table('lost_found_items')->insert([
            'item_name' => $validated['item_name'],
            'type' => $validated['item_type'],
            'description' => $validated['description'] ?? null,
            'location_found' => $validated['location'] ?? null,
            'status' => 'active',
            'created_at' => now(),
        ]);

        return redirect()->route('mahasiswa.lost-found')
            ->with('success', 'Laporan barang hilang/ditemukan berhasil dikirim.');
    }

    private function loadMahasiswaPublicUiContent(string $code): array
    {
        $references = $this->loadReferenceDomain('mahasiswa_public_ui');
        return data_get($references, $code . '.payload', []);
    }
}
