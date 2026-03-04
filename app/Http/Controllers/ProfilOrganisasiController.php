<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProfilOrganisasiController extends Controller
{
    /**
     * Tampilkan profil organisasi user yang sedang login
     */
    public function show()
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return redirect()->route('dashboard')->with('error', 'Organisasi tidak ditemukan');
        }

        $members = $organization->users()->get();
        $stats = [
            'total_members' => $members->count(),
            'active_events' => $organization->events()->published()->count(),
            'announcements' => $organization->announcements()->published()->count(),
            'pending_proposals' => $organization->proposals()
                ->whereIn('status', ['submitted', 'under_review'])
                ->count(),
        ];

        return view('dashboard.profil.show', compact('organization', 'members', 'stats'));
    }

    /**
     * Form edit profil organisasi
     */
    public function edit()
    {
        $organization = auth()->user()->organization;

        Gate::authorize('update', $organization);

        return view('dashboard.profil.edit', compact('organization'));
    }

    /**
     * Update profil organisasi
     */
    public function update(Request $request)
    {
        $organization = auth()->user()->organization;

        Gate::authorize('update', $organization);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:organizations,email,' . $organization->id,
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($organization->logo) {
                \Storage::disk('public')->delete($organization->logo);
            }

            $path = $request->file('logo')->store('organizations', 'public');
            $validated['logo'] = $path;
        }

        $organization->update($validated);

        return redirect()->route('profil.show')->with('success', 'Profil organisasi berhasil diperbarui');
    }

    /**
     * Upload logo organisasi
     */
    public function uploadLogo(Request $request)
    {
        $organization = auth()->user()->organization;

        Gate::authorize('update', $organization);

        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old logo
        if ($organization->logo) {
            \Storage::disk('public')->delete($organization->logo);
        }

        $path = $request->file('logo')->store('organizations', 'public');
        $organization->update(['logo' => $path]);

        return back()->with('success', 'Logo berhasil diperbarui');
    }

    /**
     * Kelola anggota organisasi (Admin/Leader hanya)
     */
    public function members()
    {
        $organization = auth()->user()->organization;

        if (!auth()->user()->isAdmin() && auth()->user()->id !== $organization->leader_id) {
            abort(403);
        }

        $members = $organization->users()->paginate(15);

        return view('dashboard.profil.members', compact('organization', 'members'));
    }

    /**
     * Hapus anggota dari organisasi
     */
    public function removeMember(Request $request, $userId)
    {
        $organization = auth()->user()->organization;

        Gate::authorize('update', $organization);

        $user = $organization->users()->find($userId);

        if (!$user) {
            return back()->with('error', 'Anggota tidak ditemukan');
        }

        // Prevent removing the leader
        if ($user->id === $organization->leader_id) {
            return back()->with('error', 'Tidak dapat menghapus ketua organisasi');
        }

        $user->organization_id = null;
        $user->save();

        return back()->with('success', 'Anggota berhasil dihapus');
    }
}
