<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organization;
use Illuminate\Http\Request;

class EventOrganisasiController extends Controller
{
    /**
     * Daftar event organisasi
     */
    public function index()
    {
        $organization = auth()->user()->organization;
        $events = $organization->events()
            ->orderBy('event_date', 'desc')
            ->paginate(12);

        $stats = [
            'total' => $organization->events()->count(),
            'published' => $organization->events()->published()->count(),
            'upcoming' => $organization->events()->upcoming()->count(),
            'draft' => $organization->events()->where('status', 'draft')->count(),
        ];

        return view('dashboard.events.index', compact('organization', 'events', 'stats'));
    }

    /**
     * Form buat event baru
     */
    public function create()
    {
        $categories = Event::CATEGORIES ?? [
            'rapat' => 'Rapat',
            'event' => 'Event',
            'akademik' => 'Akademik',
            'sosial' => 'Sosial',
        ];

        return view('dashboard.events.create', compact('categories'));
    }

    /**
     * Simpan event baru
     */
    public function store(Request $request)
    {
        $organization = auth()->user()->organization;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:rapat,event,akademik,sosial',
            'event_date' => 'required|date|after:now',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle poster upload
        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('events', 'public');
        }

        $validated['organization_id'] = $organization->id;
        $validated['creator_id'] = auth()->id();
        $validated['status'] = 'draft';

        Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Event berhasil dibuat');
    }

    /**
     * Tampilkan detail event
     */
    public function show(Event $event)
    {
        $this->authorize('view', $event);

        return view('dashboard.events.show', compact('event'));
    }

    /**
     * Form edit event
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        $categories = Event::CATEGORIES ?? [
            'rapat' => 'Rapat',
            'event' => 'Event',
            'akademik' => 'Akademik',
            'sosial' => 'Sosial',
        ];

        return view('dashboard.events.edit', compact('event', 'categories'));
    }

    /**
     * Update event
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:rapat,event,akademik,sosial',
            'event_date' => 'required|date|after:now',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle poster upload
        if ($request->hasFile('poster')) {
            if ($event->poster) {
                \Storage::disk('public')->delete($event->poster);
            }
            $validated['poster'] = $request->file('poster')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('events.show', $event)->with('success', 'Event berhasil diperbarui');
    }

    /**
     * Hapus event
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        if ($event->poster) {
            \Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus');
    }

    /**
     * Publish event (ubah status dari draft menjadi published)
     */
    public function publish(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        if ($event->status !== 'draft') {
            return back()->with('error', 'Event sudah dipublikasikan');
        }

        $event->update(['status' => 'published']);

        return back()->with('success', 'Event berhasil dipublikasikan');
    }

    /**
     * Unpublish event
     */
    public function unpublish(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $event->update(['status' => 'draft']);

        return back()->with('success', 'Event berhasil disembunyikan');
    }

    /**
     * Tandai event sebagai selesai
     */
    public function markAsCompleted(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $event->update(['status' => 'completed']);

        return back()->with('success', 'Event ditandai sebagai selesai');
    }

    /**
     * API endpoint untuk mendapatkan detail event (AJAX)
     */
    public function getDetail(Event $event)
    {
        return response()->json([
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'event_date' => $event->event_date->format('Y-m-d H:i'),
            'location' => $event->location,
            'category' => $event->category,
            'capacity' => $event->capacity,
            'attendees_count' => $event->attendees_count,
            'available_slots' => $event->getAvailableSlots(),
            'poster_url' => $event->poster_url,
            'is_full' => $event->isFull(),
            'category_color' => $event->getCategoryColor(),
        ]);
    }
}
