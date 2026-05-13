<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display the announcements page.
     */
    public function index(Request $request)
    {
        $announcements = \App\Models\Announcement::with('organization')
                            ->whereIn('status', ['terpublikasi', 'sent'])
                            ->orderBy('published_at', 'desc')
                            ->get();

        return view('mahasiswa.pengumuman.index', compact('announcements'));
    }
}
