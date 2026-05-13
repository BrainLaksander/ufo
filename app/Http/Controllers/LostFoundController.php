<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LostItem;

class LostFoundController extends Controller
{
    /**
     * Display the Lost & Found page (Mahasiswa portal).
     */
    public function index(Request $request)
    {
        $query = LostItem::with('organization')
                    ->whereIn('status', ['active', 'resolved'])
                    ->orderBy('created_at', 'desc');

        if ($q = $request->input('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('title', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($sort = $request->input('sort')) {
            if ($sort === 'az') {
                $query->orderBy('title', 'asc');
            } elseif ($sort === 'za') {
                $query->orderBy('title', 'desc');
            } else {
                // terbaru is default, already sorted by created_at desc in line 16
            }
        }

        $items = $query->get();

        return view('mahasiswa.lost-found.index', compact('items'));
    }

    /**
     * Store a new lost/found report from Mahasiswa.
     * Items start as 'pending' and need BEM review.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:lost,found',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|before_or_equal:today',
            'location' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'image' => 'required|image|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('lost-found', 'public');
        }

        LostItem::create([
            'organization_id' => null, // Reported by mahasiswa, not by org
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'image_path' => $imagePath,
            'status' => 'pending', // Needs BEM review
        ]);

        return redirect()->route('lost-found.index')
            ->with('success', 'Laporan berhasil dikirim dan menunggu review oleh BEM Universitas.');
    }

}

