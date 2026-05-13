<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of events (data-driven). If no data, view remains empty.
     */
    public function index(Request $request)
    {
        $viewMode = $request->input('mode', 'detail');

        // Show events that are approved or have no submission requirement
        $query = \App\Models\Event::with('organization', 'submission')
            ->where(function ($q) {
                $q->whereHas('submission', function ($sq) {
                    $sq->whereIn('status', ['approved', 'disetujui']);
                })->orWhereNull('submission_id');
            })
            ->whereNotIn('category', ['Libur', 'Tidak Boleh Berkegiatan']);

        // Kategori Filter
        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        // Timeline Status Filter
        if ($request->filled('timeline') && $request->timeline !== 'all') {
            $now = now()->toDateString();
            if ($request->timeline === 'active') {
                $query->whereDate('start_at', '<=', $now)
                      ->whereDate('end_at', '>=', $now);
            } elseif ($request->timeline === 'upcoming') {
                $query->whereDate('start_at', '>', $now);
            } elseif ($request->timeline === 'past') {
                $query->whereDate('end_at', '<', $now);
            }
        }

        if ($viewMode === 'summary') {
            // Summary Mode: grouping by Month -> Category
            $events = $query->orderBy('start_at', 'asc')->get();

            $monthlySummary = $events->groupBy(function ($event) {
                return \Carbon\Carbon::parse($event->start_at)->translatedFormat('F Y');
            })->map(function ($monthGroup) {
                return $monthGroup->groupBy('category');
            });

            return view('mahasiswa.events.summary', [
                'monthlySummary' => $monthlySummary,
                'currentCategory' => $request->input('category', 'Semua'),
                'currentTimeline' => $request->input('timeline', 'all')
            ]);
        }

        // Detail Mode
        $events = $query->orderBy('start_at', 'asc')->get();
        return view('mahasiswa.events.index', [
            'events' => $events,
            'currentCategory' => $request->input('category', 'Semua'),
            'currentTimeline' => $request->input('timeline', 'all')
        ]);
    }
}
