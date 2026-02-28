<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Tampilkan form kontak publik
     */
    public function showForm()
    {
        return view('contact.form');
    }

    /**
     * Simpan pesan kontak dari form
     */
    public function storeMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'organization_id' => 'required|exists:organizations,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $validated['status'] = 'new';

        ContactMessage::create($validated);

        return back()->with('success', 'Pesan berhasil dikirim. Tim kami akan segera merespons.');
    }

    /**
     * [ADMIN ONLY] Daftar semua pesan masuk
     */
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $filter = request('filter', 'all'); // all, new, read, replied
        $query = ContactMessage::query();

        if ($filter === 'new') {
            $query->where('status', 'new');
        } elseif ($filter === 'read') {
            $query->where('status', 'read');
        } elseif ($filter === 'replied') {
            $query->where('status', 'replied');
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => ContactMessage::count(),
            'new' => ContactMessage::where('status', 'new')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
        ];

        return view('dashboard.messages.index', compact('messages', 'stats', 'filter'));
    }

    /**
     * [ADMIN ONLY] Tampilkan detail pesan untuk direply
     */
    public function show(ContactMessage $message)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        // Mark as read jika belum dibaca
        if ($message->status === 'new') {
            $message->markAsRead();
        }

        return view('dashboard.messages.show', compact('message'));
    }

    /**
     * [ADMIN ONLY] Balas pesan kontak
     */
    public function reply(Request $request, ContactMessage $message)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'response' => 'required|string|max:2000',
        ]);

        $message->reply($validated['response'], auth()->user());

        // Bisa dikirim via email ke pengirim pesan
        // Mail::to($message->email)->send(new ContactReplyMail($message));

        return back()->with('success', 'Balasan berhasil dikirim');
    }

    /**
     * [ADMIN ONLY] Tandai pesan sebagai sudah dibaca
     */
    public function markAsRead(ContactMessage $message)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $message->markAsRead();

        return back()->with('success', 'Pesan ditandai sebagai sudah dibaca');
    }

    /**
     * [ADMIN ONLY] Hapus pesan
     */
    public function delete(ContactMessage $message)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $message->delete();

        return redirect()->route('messages.index')
            ->with('success', 'Pesan berhasil dihapus');
    }
}
