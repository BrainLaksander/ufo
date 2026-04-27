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
            'item_name' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'item_type' => 'nullable|in:lost,found',
            'type' => 'nullable|in:lost,found',
            'description' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:2000',
            'location' => 'nullable|string|max:500',
            'location_found' => 'nullable|string|max:500',
            'reporter_name' => 'nullable|string|max:120',
            'contact' => 'nullable|string|max:120',
        ]);

        $itemName = trim((string) ($validated['item_name'] ?? $validated['name'] ?? ''));
        if ($itemName === '') {
            return back()->withErrors(['name' => 'Nama barang wajib diisi.'])->withInput();
        }

        $location = trim((string) ($validated['location'] ?? $validated['location_found'] ?? ''));
        if ($location === '') {
            return back()->withErrors(['location' => 'Lokasi wajib diisi.'])->withInput();
        }

        if (!Schema::hasTable('lost_found_items')) {
            return back()->with('error', 'Tabel laporan tidak ditemukan.');
        }

        $columns = Schema::getColumnListing('lost_found_items');
        $hasColumn = static fn (string $column): bool => in_array($column, $columns, true);

        $reporterName = trim((string) ($validated['reporter_name'] ?? ''));
        $reporterContact = trim((string) ($validated['contact'] ?? ''));
        $baseDescription = trim((string) ($validated['description'] ?? $validated['notes'] ?? ''));
        $description = trim($baseDescription . "\n" . implode("\n", array_filter([
            $reporterName !== '' ? 'Pelapor: ' . $reporterName : null,
            $reporterContact !== '' ? 'Kontak: ' . $reporterContact : null,
            'ReviewStatus: approved',
        ])));

        $insertData = [
            'item_name' => $itemName,
            'type' => 'lost',
            'description' => $description,
            'location_found' => $location,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($hasColumn('reporter_name')) {
            $insertData['reporter_name'] = $reporterName !== '' ? $reporterName : null;
        }

        if ($hasColumn('reporter_contact')) {
            $insertData['reporter_contact'] = $reporterContact !== '' ? $reporterContact : null;
        }

        DB::table('lost_found_items')->insert($insertData);

        return redirect()->route('mahasiswa.lost-found')
            ->with('success', 'Laporan barang hilang berhasil dikirim ke BEM. Untuk barang ditemukan, silakan lapor langsung ke BEM agar difoto real-time.');
    }

    private function loadMahasiswaPublicUiContent(string $code): array
    {
        $references = $this->loadReferenceDomain('mahasiswa_public_ui');
        return data_get($references, $code . '.payload', []);
    }
}
