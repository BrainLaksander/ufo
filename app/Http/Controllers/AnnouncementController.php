<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Daftar pengumuman
     */
    public function index()
    {
        $organization = auth()->user()->organization;

        $announcements = $organization->announcements()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total' => $organization->announcements()->count(),
            'published' => $organization->announcements()->published()->count(),
            'pending' => $organization->announcements()->where('status', 'pending')->count(),
            'draft' => $organization->announcements()->where('status', 'draft')->count(),
        ];

        return view('dashboard.announcements.index', compact('organization', 'announcements', 'stats'));
    }

    /**
     * Form buat pengumuman baru
     */
    public function create()
    {
        return view('dashboard.announcements.create');
    }

    /**
     * Simpan pengumuman baru
     */
    public function store(Request $request)
    {
        $organization = auth()->user()->organization;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,pending',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $validated['organization_id'] = $organization->id;
        $validated['creator_id'] = auth()->id();

        // Jika status pending, kirim untuk approval
        if ($validated['status'] === 'pending') {
            $validated['submitted_at'] = now();
        }

        Announcement::create($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat. Menunggu persetujuan admin.');
    }

    /**
     * Tampilkan detail pengumuman
     */
    public function show(Announcement $announcement)
    {
        $this->authorize('view', $announcement);

        return view('dashboard.announcements.show', compact('announcement'));
    }

    /**
     * Form edit pengumuman
     */
    public function edit(Announcement $announcement)
    {
        $this->authorize('update', $announcement);

        if (!in_array($announcement->status, ['draft', 'rejected'])) {
            return back()->with('error', 'Pengumuman tidak dapat diedit');
        }

        return view('dashboard.announcements.edit', compact('announcement'));
    }

    /**
     * Update pengumuman
     */
    public function update(Request $request, Announcement $announcement)
    {
        $this->authorize('update', $announcement);

        if (!in_array($announcement->status, ['draft', 'rejected'])) {
            return back()->with('error', 'Pengumuman tidak dapat diedit');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,pending',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $announcement->update($validated);

        return redirect()->route('announcements.show', $announcement)
            ->with('success', 'Pengumuman berhasil diperbarui');
    }

    /**
     * Hapus pengumuman
     */
    public function destroy(Announcement $announcement)
    {
        $this->authorize('delete', $announcement);

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus');
    }

    /**
     * Submit pengumuman untuk approval (ubah status draft -> pending)
     */
    public function submit(Request $request, Announcement $announcement)
    {
        $this->authorize('update', $announcement);

        if ($announcement->status !== 'draft') {
            return back()->with('error', 'Pengumuman sudah diajukan');
        }

        $announcement->update([
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Pengumuman berhasil diajukan untuk persetujuan');
    }

    /**
     * [ADMIN ONLY] Setujui dan publikasikan pengumuman
     */
    public function approve(Request $request, Announcement $announcement)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($announcement->status !== 'pending') {
            return back()->with('error', 'Hanya pengumuman yang tertunda yang dapat disetujui');
        }

        $announcement->approve(auth()->user());

        return back()->with('success', 'Pengumuman berhasil disetujui dan dipublikasikan');
    }

    /**
     * [ADMIN ONLY] Tolak pengumuman
     */
    public function reject(Request $request, Announcement $announcement)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($announcement->status !== 'pending') {
            return back()->with('error', 'Hanya pengumuman yang tertunda yang dapat ditolak');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $announcement->reject($validated['reason']);

        return back()->with('success', 'Pengumuman berhasil ditolak');
    }

    /**
     * Publish pengumuman yang belum dipublikasikan (jika sudah di-approve)
     */
    public function publish(Request $request, Announcement $announcement)
    {
        $this->authorize('update', $announcement);

        if ($announcement->status !== 'approved') {
            return back()->with('error', 'Hanya pengumuman yang disetujui yang dapat dipublikasikan');
        }

        $announcement->update([
            'published_at' => now(),
        ]);

        return back()->with('success', 'Pengumuman berhasil dipublikasikan');
    }
}
