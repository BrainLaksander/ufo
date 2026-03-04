<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;

/**
 * ProposalController - Kelola pengajuan (dana, acara, fasilitas, dll)
 * 
 * Workflow:
 * draft -> submitted -> under_review -> approved/rejected
 */
class ProposalController extends Controller
{
    /**
     * Daftar pengajuan organisasi
     */
    public function index()
    {
        $organization = auth()->user()->organization;

        $proposals = $organization->proposals()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total' => $organization->proposals()->count(),
            'approved' => $organization->proposals()->where('status', 'approved')->count(),
            'pending' => $organization->proposals()
                ->whereIn('status', ['submitted', 'under_review'])
                ->count(),
            'rejected' => $organization->proposals()->where('status', 'rejected')->count(),
        ];

        return view('dashboard.proposals.index', compact('organization', 'proposals', 'stats'));
    }

    /**
     * Form buat pengajuan baru
     */
    public function create()
    {
        $types = [
            'dana' => 'Pengajuan Dana',
            'acara' => 'Pengajuan Acara',
            'fasilitas' => 'Pengajuan Fasilitas',
            'lainnya' => 'Lainnya',
        ];

        return view('dashboard.proposals.create', compact('types'));
    }

    /**
     * Simpan pengajuan baru
     */
    public function store(Request $request)
    {
        $organization = auth()->user()->organization;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:dana,acara,fasilitas,lainnya',
            'description' => 'required|string',
            'budget' => 'nullable|numeric|min:0',
            'timeline' => 'nullable|date|after:today',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('proposals', 'public');
        }

        $validated['organization_id'] = $organization->id;
        $validated['submitter_id'] = auth()->id();
        $validated['status'] = 'draft';

        Proposal::create($validated);

        return redirect()->route('proposals.index')
            ->with('success', 'Pengajuan berhasil dibuat. Silakan submit untuk review.');
    }

    /**
     * Tampilkan detail pengajuan
     */
    public function show(Proposal $proposal)
    {
        $this->authorize('view', $proposal);

        return view('dashboard.proposals.show', compact('proposal'));
    }

    /**
     * Form edit pengajuan
     */
    public function edit(Proposal $proposal)
    {
        $this->authorize('update', $proposal);

        if ($proposal->status !== 'draft') {
            return back()->with('error', 'Pengajuan tidak dapat diedit setelah disubmit');
        }

        $types = [
            'dana' => 'Pengajuan Dana',
            'acara' => 'Pengajuan Acara',
            'fasilitas' => 'Pengajuan Fasilitas',
            'lainnya' => 'Lainnya',
        ];

        return view('dashboard.proposals.edit', compact('proposal', 'types'));
    }

    /**
     * Update pengajuan
     */
    public function update(Request $request, Proposal $proposal)
    {
        $this->authorize('update', $proposal);

        if ($proposal->status !== 'draft') {
            return back()->with('error', 'Pengajuan tidak dapat diedit');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:dana,acara,fasilitas,lainnya',
            'description' => 'required|string',
            'budget' => 'nullable|numeric|min:0',
            'timeline' => 'nullable|date|after:today',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            if ($proposal->attachment) {
                \Storage::disk('public')->delete($proposal->attachment);
            }
            $validated['attachment'] = $request->file('attachment')
                ->store('proposals', 'public');
        }

        $proposal->update($validated);

        return redirect()->route('proposals.show', $proposal)
            ->with('success', 'Pengajuan berhasil diperbarui');
    }

    /**
     * Hapus pengajuan (hanya draft)
     */
    public function destroy(Proposal $proposal)
    {
        $this->authorize('delete', $proposal);

        if ($proposal->status !== 'draft') {
            return back()->with('error', 'Hanya pengajuan draft yang dapat dihapus');
        }

        if ($proposal->attachment) {
            \Storage::disk('public')->delete($proposal->attachment);
        }

        $proposal->delete();

        return redirect()->route('proposals.index')
            ->with('success', 'Pengajuan berhasil dihapus');
    }

    /**
     * Submit pengajuan untuk review (ubah status draft -> submitted)
     */
    public function submit(Request $request, Proposal $proposal)
    {
        $this->authorize('update', $proposal);

        if ($proposal->status !== 'draft') {
            return back()->with('error', 'Pengajuan sudah disubmit');
        }

        $proposal->submit();

        return back()->with('success', 'Pengajuan berhasil disubmit untuk review');
    }

    /**
     * [ADMIN ONLY] Move pengajuan ke under_review status
     */
    public function startReview(Request $request, Proposal $proposal)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($proposal->status !== 'submitted') {
            return back()->with('error', 'Pengajuan tidak dalam status submitted');
        }

        $proposal->update(['status' => 'under_review']);

        return back()->with('success', 'Review dimulai');
    }

    /**
     * [ADMIN ONLY] Setujui pengajuan
     */
    public function approve(Request $request, Proposal $proposal)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if (!in_array($proposal->status, ['submitted', 'under_review'])) {
            return back()->with('error', 'Pengajuan tidak dapat disetujui dalam status ini');
        }

        $validated = $request->validate([
            'approval_notes' => 'nullable|string|max:1000',
        ]);

        $proposal->approve(
            auth()->user(),
            $validated['approval_notes'] ?? null
        );

        return back()->with('success', 'Pengajuan berhasil disetujui');
    }

    /**
     * [ADMIN ONLY] Tolak pengajuan
     */
    public function reject(Request $request, Proposal $proposal)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if (!in_array($proposal->status, ['submitted', 'under_review'])) {
            return back()->with('error', 'Pengajuan tidak dapat ditolak dalam status ini');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $proposal->reject(
            auth()->user(),
            $validated['rejection_reason']
        );

        return back()->with('success', 'Pengajuan berhasil ditolak');
    }

    /**
     * [ADMIN ONLY] Lihat semua pengajuan pending untuk dikerjakan
     */
    public function pendingReview()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $proposals = Proposal::whereIn('status', ['submitted', 'under_review'])
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('dashboard.proposals.pending-review', compact('proposals'));
    }
}
