<?php

namespace App\Http\Controllers;

use App\Models\LostFoundItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LostFoundController extends Controller
{
    /**
     * Daftar barang hilang & ditemukan
     */
    public function index(): View
    {
        $organization = auth()->user()->organization;

        $filter = request('filter', 'active'); // active, lost, found, claimed, closed
        $query = $organization->lostFoundItems();

        if ($filter === 'lost') {
            $query->where('type', 'lost');
        } elseif ($filter === 'found') {
            $query->where('type', 'found');
        } elseif ($filter === 'claimed') {
            $query->where('status', 'claimed');
        } elseif ($filter === 'closed') {
            $query->where('status', 'closed');
        } else {
            $query->where('status', 'active');
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(12);

        $stats = [
            'active' => $organization->lostFoundItems()->where('status', 'active')->count(),
            'claimed' => $organization->lostFoundItems()->where('status', 'claimed')->count(),
            'lost' => $organization->lostFoundItems()->where('type', 'lost')->count(),
            'found' => $organization->lostFoundItems()->where('type', 'found')->count(),
        ];

        return view('dashboard.lostfound.index', compact('organization', 'items', 'stats', 'filter'));
    }

    /**
     * Form lapor barang hilang/ditemukan
     */
    public function create()
    {
        return view('dashboard.lostfound.create');
    }

    /**
     * Simpan laporan barang
     */
    public function store(Request $request)
    {
        $organization = auth()->user()->organization;

        $validated = $request->validate([
            'type' => 'required|in:lost,found',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'report_date' => 'required|date|before_or_equal:today',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('lost-found', 'public');
        }

        $validated['organization_id'] = $organization->id;
        $validated['reporter_id'] = auth()->id();
        $validated['status'] = 'active';

        LostFoundItem::create($validated);

        $typeName = $validated['type'] === 'lost' ? 'Hilang' : 'Ditemukan';

        return redirect()->route('lostfound.index')
            ->with('success', "Laporan \"$typeName\" berhasil dibuat");
    }

    /**
     * Tampilkan detail barang
     */
    public function show(LostFoundItem $item)
    {
        $this->authorize('view', $item);

        return view('dashboard.lostfound.show', compact('item'));
    }

    /**
     * Form edit barang
     */
    public function edit(LostFoundItem $item)
    {
        $this->authorize('update', $item);

        if ($item->status !== 'active') {
            return back()->with('error', 'Barang tidak dapat diedit');
        }

        return view('dashboard.lostfound.edit', compact('item'));
    }

    /**
     * Update barang
     */
    public function update(Request $request, LostFoundItem $item)
    {
        $this->authorize('update', $item);

        if ($item->status !== 'active') {
            return back()->with('error', 'Barang tidak dapat diedit');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($item->photo) {
                \Storage::disk('public')->delete($item->photo);
            }
            $validated['photo'] = $request->file('photo')->store('lost-found', 'public');
        }

        $item->update($validated);

        return redirect()->route('lostfound.show', $item)
            ->with('success', 'Laporan berhasil diperbarui');
    }

    /**
     * Hapus laporan barang
     */
    public function destroy(LostFoundItem $item)
    {
        $this->authorize('delete', $item);

        if ($item->photo) {
            \Storage::disk('public')->delete($item->photo);
        }

        $item->delete();

        return redirect()->route('lostfound.index')
            ->with('success', 'Laporan berhasil dihapus');
    }

    /**
     * Claim barang yang ditemukan (sebagai pemilik barang yang hilang)
     * atau tandai barang hilang sebagai sudah ditemukan
     */
    public function claim(Request $request, LostFoundItem $item)
    {
        if ($item->status !== 'active') {
            return back()->with('error', 'Barang tidak dapat diklaim');
        }

        $item->claim(auth()->user());

        return back()->with('success', 'Barang berhasil diklaim. Hubungi pelapor untuk koordinasi lebih lanjut.');
    }

    /**
     * Tandai barang sebagai sudah ditemukan
     */
    public function markAsFound(Request $request, LostFoundItem $item)
    {
        $this->authorize('update', $item);

        if ($item->status !== 'active') {
            return back()->with('error', 'Barang tidak dapat ditandai');
        }

        $item->markAsFound();

        return back()->with('success', 'Barang ditandai sebagai sudah ditemukan');
    }

    /**
     * Tutup laporan barang
     */
    public function close(Request $request, LostFoundItem $item)
    {
        $this->authorize('update', $item);

        $item->close();

        return back()->with('success', 'Laporan ditutup');
    }

    /**
     * API endpoint untuk detail barang (untuk legacy view jika perlu)
     */
    public function detail($id)
    {
        $item = LostFoundItem::findOrFail($id);

        return response()->json([
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description,
            'type' => $item->type,
            'status' => $item->status,
            'location' => $item->location,
            'reporter' => $item->reporter->name,
            'report_date' => $item->report_date->format('d M Y'),
            'photo_url' => $item->photo_url,
        ]);
    }

    /**
     * API endpoint untuk pencarian barang (AJAX)
     */
    public function search(Request $request)
    {
        $organization = auth()->user()->organization;
        $query = $request->input('q');

        $items = $organization->lostFoundItems()
            ->where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%$query%")
                    ->orWhere('description', 'LIKE', "%$query%")
                    ->orWhere('location', 'LIKE', "%$query%");
            })
            ->limit(10)
            ->get(['id', 'title', 'type', 'location', 'report_date']);

        return response()->json($items);
    }
}

