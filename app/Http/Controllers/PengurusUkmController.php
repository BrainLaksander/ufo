<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Event as OrgEvent;
use App\Models\ActivitySubmission;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PengurusUkmController extends Controller
{
    use \App\Traits\CalendarHelper;

    public function index(\Illuminate\Http\Request $request): View
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('n'));
        
        $calendarData = $this->generateCalendarData($year, $month);

        return view('pengurus-ukm.dashboard', array_merge([
            'user' => auth()->user(),
        ], $calendarData));
    }

    public function announcements(\Illuminate\Http\Request $request): View
    {
        $organization_id = auth()->user()->organization_id;
        $announcements = \App\Models\Announcement::where('organization_id', $organization_id)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('pengurus-ukm.pengumuman.index', compact('announcements'));
    }

    public function storeAnnouncement(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'target' => 'required|string',
            'manual_email' => 'nullable|email|max:255',
        ]);

        $target = $data['target'];
        if ($target === 'Input Manual') {
            if (empty($data['manual_email'])) {
                return back()->withErrors(['manual_email' => 'Alamat email wajib diisi untuk Input Manual'])->withInput();
            }
            $target = $data['manual_email'];
        }

        \App\Models\Announcement::create([
            'organization_id' => auth()->user()->organization_id,
            'title' => $data['title'],
            'content' => $data['content'],
            'category' => $data['category'],
            'target' => $target,
            'status' => 'draft', // All UKM announcements start as draft/menunggu persetujuan
            'published_at' => null,
        ]);

        return redirect()->route('pengurus-ukm.announcements.index')
            ->with('success', 'Pengumuman berhasil diajukan ke kemahasiswaan.');
    }

    public function updateAnnouncement(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'target' => 'required|in:Semua Mahasiswa,Input Manual',
            'manual_email' => 'nullable|required_if:target,Input Manual|email',
        ]);

        $announcement = \App\Models\Announcement::where('organization_id', auth()->user()->organization_id)
            ->findOrFail($id);

        // Can only edit if it is still a draft / pending / rejected
        if ($announcement->status === 'terpublikasi') {
            abort(403, 'Tidak dapat mengedit pengumuman yang sudah terpublikasi.');
        }

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'target' => $request->target,
            'manual_email' => $request->target === 'Input Manual' ? $request->manual_email : null,
            'status' => 'draft', // Reset status to draft/pending for Kemahasiswaan to review again
        ]);

        return redirect()->route('pengurus-ukm.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui dan diajukan ulang.');
    }

    public function destroyAnnouncement($id)
    {
        $announcement = \App\Models\Announcement::where('organization_id', auth()->user()->organization_id)
            ->findOrFail($id);

        // Hanya bisa menghapus jika belum terpublikasi
        if ($announcement->status === 'terpublikasi') {
            abort(403, 'Tidak dapat menghapus pengumuman yang sudah terpublikasi.');
        }

        $announcement->delete();

        return redirect()->route('pengurus-ukm.announcements.index')
            ->with('success', 'Pengumuman berhasil dibatalkan / dihapus.');
    }

    public function submissions(\Illuminate\Http\Request $request): View
    {
        $user = auth()->user();
        $orgId = $user->organization_id;

        $validTabs = ['proposal', 'report'];
        $tab = in_array($request->input('tab'), $validTabs) ? $request->input('tab') : 'proposal';

        $query = ActivitySubmission::with('organization')
            ->where('organization_id', $orgId)
            ->where('kind', $tab)
            ->whereNull('event_id')
            ->orderBy('created_at', 'desc');

        if ($q = $request->input('q')) {
            $query->where('title', 'like', "%{$q}%");
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $items = $query->get()->map(function ($sub) {
            $statusLabels = [
                'diajukan' => 'Diajukan',
                'review' => 'Sedang Direview',
                'revisi' => 'Perlu Revisi',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
            ];
            return [
                'id' => $sub->id,
                'title' => $sub->title,
                'jenis_kegiatan' => $sub->jenis_kegiatan,
                'penanggung_jawab' => $sub->penanggung_jawab,
                'description' => $sub->description,
                'event_date' => $sub->event_date ? \Carbon\Carbon::parse($sub->event_date)->format('Y-m-d') : null,
                'waktu' => $sub->waktu,
                'lokasi' => $sub->lokasi,
                'estimasi_peserta' => $sub->estimasi_peserta,
                'proposal_path' => $sub->proposal_path,
                'lpj_path' => $sub->lpj_path,
                'lpj_catatan' => $sub->lpj_catatan,
                'revision_note' => $sub->revision_note,
                'organization' => $sub->organization->name ?? '-',
                'status' => $sub->status,
                'status_label' => $statusLabels[$sub->status] ?? ucfirst($sub->status),
                'created_at' => $sub->created_at,
            ];
        });

        return view('pengurus-ukm.pengajuan-laporan.index', [
            'items' => $items,
            'tab' => $tab,
            'q' => $request->input('q', ''),
            'status' => $request->input('status', ''),
        ]);
    }

    public function storeSubmission(\Illuminate\Http\Request $request)
    {
        $kind = $request->input('kind', 'proposal');

        if ($kind === 'proposal') {
            $request->validate([
                'title' => 'required|string|max:255',
                'jenis_kegiatan' => 'required|string|max:100',
                'penanggung_jawab' => 'required|string|max:255',
                'event_date' => 'required|date|after_or_equal:today',
                'waktu' => 'required|string|max:50',
                'lokasi' => 'required|string|max:255',
                'estimasi_peserta' => 'nullable|integer|min:1',
                'description' => 'required|string',
                'proposal_file' => 'nullable|file|mimes:pdf|max:10240',
            ]);

            // Item 12: Check if event_date is on a blocked calendar date
            $eventDate = $request->event_date;
            $blocked = \App\Models\CalendarEvent::where('extracurricular_blocked', true)
                ->where('start_date', '<=', $eventDate)
                ->where('end_date', '>=', $eventDate)
                ->first();
            if ($blocked) {
                return back()->withErrors([
                    'event_date' => "Tanggal {$eventDate} bertepatan dengan \"{$blocked->title}\" yang memblokir kegiatan ekstrakurikuler. Silakan pilih tanggal lain."
                ])->withInput();
            }

            $proposalPath = null;
            if ($request->hasFile('proposal_file')) {
                $proposalPath = $request->file('proposal_file')->store('proposals', 'public');
            }

            ActivitySubmission::create([
                'organization_id' => auth()->user()->organization_id,
                'user_id' => auth()->id(),
                'title' => $request->title,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'penanggung_jawab' => $request->penanggung_jawab,
                'event_date' => $request->event_date,
                'waktu' => $request->waktu,
                'lokasi' => $request->lokasi,
                'estimasi_peserta' => $request->estimasi_peserta,
                'description' => $request->description,
                'proposal_path' => $proposalPath,
                'kind' => 'proposal',
                'status' => 'diajukan',
            ]);

            // Notify all Kemahasiswaan users about new submission
            $orgName = auth()->user()->organization->name ?? 'Organisasi';
            $kemahasiswaanUsers = User::where('role', 'kemahasiswaan')->get();
            foreach ($kemahasiswaanUsers as $kUser) {
                $kUser->notify(new GeneralNotification(
                    'Pengajuan Kegiatan Baru',
                    "Pengajuan \"{$request->title}\" dari {$orgName} menunggu review.",
                    'pengajuan_kegiatan',
                    'document',
                    route('kemahasiswaan.submissions.index', ['tab' => 'kegiatan'])
                ));
            }

            return redirect()->route('pengurus-ukm.submissions.index', ['tab' => 'proposal'])
                ->with('success', 'Pengajuan kegiatan berhasil dikirim dan menunggu persetujuan Kemahasiswaan.');

        } else {
            // LPJ / Laporan
            $request->validate([
                'title' => 'required|string|max:255',
                'event_date' => 'required|date',
                'description' => 'required|string',
                'lpj_catatan' => 'nullable|string',
                'lpj_file' => 'nullable|file|mimes:pdf|max:10240',
            ]);

            $lpjPath = null;
            if ($request->hasFile('lpj_file')) {
                $lpjPath = $request->file('lpj_file')->store('lpj', 'public');
            }

            ActivitySubmission::create([
                'organization_id' => auth()->user()->organization_id,
                'user_id' => auth()->id(),
                'title' => $request->title,
                'event_date' => $request->event_date,
                'description' => $request->description,
                'lpj_path' => $lpjPath,
                'lpj_catatan' => $request->lpj_catatan,
                'kind' => 'report',
                'status' => 'diajukan',
            ]);

            // Notify all Kemahasiswaan users about new LPJ report
            $orgName = auth()->user()->organization->name ?? 'Organisasi';
            $kemahasiswaanUsers = User::where('role', 'kemahasiswaan')->get();
            foreach ($kemahasiswaanUsers as $kUser) {
                $kUser->notify(new GeneralNotification(
                    'Laporan Kegiatan (LPJ) Baru',
                    "Laporan \"{$request->title}\" dari {$orgName} menunggu review.",
                    'laporan_masuk',
                    'report',
                    route('kemahasiswaan.submissions.index', ['tab' => 'report'])
                ));
            }

            return redirect()->route('pengurus-ukm.submissions.index', ['tab' => 'report'])
                ->with('success', 'Laporan kegiatan (LPJ) berhasil dikirim dan menunggu persetujuan Kemahasiswaan.');
        }
    }

    public function updateSubmission(\Illuminate\Http\Request $request, $id)
    {
        $submission = ActivitySubmission::where('id', $id)
            ->where('organization_id', auth()->user()->organization_id)
            ->firstOrFail();

        // Only allow updating if status is 'revisi' or 'diajukan'
        if (!in_array($submission->status, ['diajukan', 'revisi'])) {
            return back()->with('error', 'Hanya pengajuan dengan status "Perlu Revisi" atau "Diajukan" yang dapat diubah.');
        }

        $kind = $submission->kind;

        if ($kind === 'proposal') {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'jenis_kegiatan' => 'required|string|max:100',
                'penanggung_jawab' => 'required|string|max:255',
                'event_date' => 'required|date|after_or_equal:today',
                'waktu' => 'required|string|max:50',
                'lokasi' => 'required|string|max:255',
                'estimasi_peserta' => 'nullable|integer|min:1',
                'description' => 'required|string',
                'proposal_file' => 'nullable|file|mimes:pdf|max:10240',
            ]);

            // Item 12: Check if event_date is on a blocked calendar date
            $eventDate = $request->event_date;
            $blocked = \App\Models\CalendarEvent::where('extracurricular_blocked', true)
                ->where('start_date', '<=', $eventDate)
                ->where('end_date', '>=', $eventDate)
                ->first();
            if ($blocked) {
                return back()->withErrors([
                    'event_date' => "Tanggal {$eventDate} bertepatan dengan \"{$blocked->title}\" yang memblokir kegiatan ekstrakurikuler. Silakan pilih tanggal lain."
                ])->withInput();
            }

            if ($request->hasFile('proposal_file')) {
                $data['proposal_path'] = $request->file('proposal_file')->store('proposals', 'public');
            }

            // Once updated, status goes back to 'diajukan' for Kemahasiswaan to review again
            $data['status'] = 'diajukan';
            $data['revision_note'] = null; // Clear revision note

            $submission->update($data);

            return redirect()->route('pengurus-ukm.submissions.index', ['tab' => 'proposal'])
                ->with('success', 'Pengajuan kegiatan berhasil diperbarui dan diajukan ulang.');

        } else {
            // LPJ / Laporan
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'event_date' => 'required|date',
                'description' => 'required|string',
                'lpj_catatan' => 'nullable|string',
                'lpj_file' => 'nullable|file|mimes:pdf|max:10240',
            ]);

            if ($request->hasFile('lpj_file')) {
                $data['lpj_path'] = $request->file('lpj_file')->store('lpj', 'public');
            }

            $data['status'] = 'diajukan';
            $data['revision_note'] = null;

            $submission->update($data);

            return redirect()->route('pengurus-ukm.submissions.index', ['tab' => 'report'])
                ->with('success', 'Laporan kegiatan (LPJ) berhasil diperbarui dan diajukan ulang.');
        }
    }

    public function events(\Illuminate\Http\Request $request): View
    {
        $validTabs = ['pending', 'active', 'completed'];
        $tab = in_array($request->input('tab'), $validTabs) ? $request->input('tab') : 'pending';

        $events = OrgEvent::with('submission')->orderBy('start_at', 'desc')->get()->map(function (OrgEvent $event) {
            $startAt = $event->start_at;
            $submissionStatus = $event->submission ? $event->submission->status : null;
            
            // Auto complete if end_at has passed
            if ($event->end_at && now()->greaterThan($event->end_at) && $event->status !== 'selesai' && in_array($submissionStatus, ['disetujui', 'approved'])) {
                $event->update(['status' => 'selesai']);
                $event->status = 'selesai';
            }

            $status = 'pending';
            $statusLabel = 'Menunggu Persetujuan';

            if ($submissionStatus === 'diajukan') {
                $status = 'pending';
                $statusLabel = 'Menunggu Persetujuan';
            } elseif ($submissionStatus === 'ditolak') {
                $status = 'rejected';
                $statusLabel = 'Ditolak';
            } elseif ($submissionStatus === 'revisi') {
                $status = 'revision';
                $statusLabel = 'Perlu Revisi';
            } elseif ($submissionStatus === 'disetujui' || $submissionStatus === 'approved') {
                $status = 'active';
                $statusLabel = 'Aktif';
                if ($event->status === 'berlangsung') {
                    $statusLabel = 'Berlangsung';
                }
            } elseif ($submissionStatus === 'rejected') {
                $status = 'rejected';
                $statusLabel = 'Ditolak';
            }

            if ($event->status === 'selesai') {
                $status = 'completed';
                $statusLabel = 'Selesai';
            }

            return [
                'id' => $event->id,
                'title' => $event->title,
                'category' => $event->category,
                'description' => $event->description,
                'poster_path' => $event->poster_path,
                'event_date' => $startAt ? $startAt->toDateString() : null,
                'event_time' => $startAt ? $startAt->format('H:i') : null,
                'event_date_label' => $startAt ? $startAt->translatedFormat('d F Y') : '-',
                'time_range' => $startAt ? $startAt->format('H:i') . ' - ' . optional($event->end_at)->format('H:i') : '-',
                'location' => $event->location,
                'registration_link' => $event->registration_link,
                'participants' => $event->participants,
                'status' => $status,
                'status_label' => $statusLabel,
                'submission_id' => $event->submission_id,
                'revision_note' => $event->submission ? $event->submission->revision_note : null,
            ];
        });

        $pendingStatuses = ['pending', 'rejected', 'revision'];

        $filtered = $events->filter(function (array $event) use ($tab, $pendingStatuses) {
            if ($tab === 'pending') {
                return in_array($event['status'], $pendingStatuses);
            }
            if ($tab === 'active') {
                return $event['status'] === 'active';
            }
            return $event['status'] === 'completed';
        })->values();

        return view('pengurus-ukm.events.index', [
            'user' => auth()->user(),
            'events' => $filtered,
            'pendingCount' => $events->whereIn('status', $pendingStatuses)->count(),
            'activeCount' => $events->where('status', 'active')->count(),
            'completedCount' => $events->where('status', 'completed')->count(),
            'tab' => $tab,
        ]);
    }

    public function createNewsFromEvent(\Illuminate\Http\Request $request, $id)
    {
        $event = OrgEvent::findOrFail($id);
        
        if ($event->organization_id !== auth()->user()->organization_id) {
            abort(403);
        }

        // Check if a news announcement already exists for this event
        $existingNews = \App\Models\Announcement::where('organization_id', $event->organization_id)
                            ->where('title', 'Berita: ' . $event->title)
                            ->first();
                            
        if ($existingNews) {
            return redirect()->route('pengurus-ukm.announcements.index')
                ->with('error', 'Berita untuk event ini sudah pernah dibuat.');
        }

        \App\Models\Announcement::create([
            'organization_id' => $event->organization_id,
            'title' => 'Berita: ' . $event->title,
            'content' => "Telah terlaksana kegiatan " . $event->title . " pada " . ($event->start_at ? $event->start_at->translatedFormat('d F Y') : '-') . ".\n\n" . $event->description,
            'category' => 'Berita',
            'target' => 'Semua Mahasiswa',
            'status' => 'draft',
            'published_at' => null,
        ]);

        return redirect()->route('pengurus-ukm.announcements.index')
            ->with('success', 'Berita berhasil di-draft dari event. Silakan edit dan ajukan publikasi.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'poster' => 'nullable|image|max:5120',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'location' => 'nullable|string|max:255',
            'registration_link' => 'nullable|url|max:255',
            'participants' => 'nullable|integer|min:0',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('event-posters', 'public');
        }

        $startAt = \Illuminate\Support\Carbon::parse($data['event_date'] . ' ' . $data['event_time']);

        DB::beginTransaction();

        $org = \App\Models\Organization::where('account_user_id', auth()->id())->first();
        $orgId = $org ? $org->id : null;

        $event = OrgEvent::create([
            'organization_id' => $orgId,
            'user_id' => auth()->id(),
            'submission_id' => null,
            'title' => $data['title'],
            'category' => $data['category'],
            'poster_path' => $posterPath,
            'description' => $data['description'] ?? null,
            'start_at' => $startAt,
            'end_at' => $startAt,
            'location' => $data['location'] ?? null,
            'registration_link' => $data['registration_link'] ?? null,
            'participants' => $data['participants'] ?? 0,
            'status' => 'draft',
        ]);

        $submission = ActivitySubmission::create([
            'organization_id' => $orgId,
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'title' => $data['title'],
            'poster_path' => $posterPath,
            'subtitle' => 'Pengajuan Event',
            'description' => $data['description'] ?? null,
            'registration_link' => $data['registration_link'] ?? null,
            'event_date' => $startAt->toDateString(),
            'kind' => 'proposal',
            'status' => 'diajukan',
        ]);

        $event->update(['submission_id' => $submission->id]);

        DB::commit();

        // Notify all Kemahasiswaan users about new event submission
        $orgName = $org->name ?? 'Organisasi';
        $kemahasiswaanUsers = User::where('role', 'kemahasiswaan')->get();
        foreach ($kemahasiswaanUsers as $kUser) {
            $kUser->notify(new GeneralNotification(
                'Pengajuan Event Baru',
                "Event \"{$data['title']}\" dari {$orgName} menunggu persetujuan.",
                'pengajuan_kegiatan',
                'document',
                route('kemahasiswaan.submissions.index', ['tab' => 'event'])
            ));
        }

        if ($request->wantsJson()) {
            return response()->json(['event' => $event], 201);
        }

        return redirect()->route('pengurus-ukm.events.index')->with('success', 'Event berhasil dibuat');
    }

    public function update(Request $request, OrgEvent $event)
    {
        $data = $request->validate([
            'poster' => 'nullable|image|max:5120',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'location' => 'nullable|string|max:255',
            'registration_link' => 'nullable|url|max:255',
            'participants' => 'nullable|integer|min:0',
        ]);

        $posterPath = $event->poster_path;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('event-posters', 'public');
        }

        $startAt = Carbon::parse($data['event_date'] . ' ' . $data['event_time']);

        $event->update([
            'title' => $data['title'],
            'category' => $data['category'],
            'poster_path' => $posterPath,
            'description' => $data['description'] ?? null,
            'start_at' => $startAt,
            'end_at' => $startAt,
            'location' => $data['location'] ?? null,
            'registration_link' => $data['registration_link'] ?? null,
            'participants' => $data['participants'] ?? 0,
        ]);

        if ($event->submission) {
            $event->submission->update([
                'title' => $data['title'],
                'poster_path' => $posterPath,
                'description' => $data['description'] ?? null,
                'registration_link' => $data['registration_link'] ?? null,
                'event_date' => $startAt->toDateString(),
            ]);
        }

        return redirect()->route('pengurus-ukm.events.index')->with('success', 'Event berhasil diupdate');
    }

    public function destroy(OrgEvent $event)
    {
        if ($event->submission) {
            $event->submission->delete();
        }

        $event->delete();

        return redirect()->route('pengurus-ukm.events.index')->with('success', 'Event berhasil dihapus');
    }

    public function completeEvent(OrgEvent $event)
    {
        $event->update(['status' => 'selesai']);
        return redirect()->back()->with('success', 'Event berhasil ditandai sebagai selesai.');
    }

    public function contacts(\Illuminate\Http\Request $request)
    {
        $q = $request->input('q');
        $query = \App\Models\Organization::query();
        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('kategori', 'like', "%{$q}%")
                    ->orWhere('field', 'like', "%{$q}%");
            });
        }

        $organizations = $query->orderBy('name')->get();
        $totalOrgs = \App\Models\Organization::count();
        $countBEM = \App\Models\Organization::where('kategori', 'BEM')->count();
        $countUKM = \App\Models\Organization::where('kategori', 'UKM')->count();

        return view('shared.contacts', [
            'user' => auth()->user(),
            'organizations' => $organizations,
            'totalOrgs' => $totalOrgs,
            'countBEM' => $countBEM,
            'countUKM' => $countUKM,
            'q' => $q,
            'title' => 'Kontak Organisasi'
        ]);
    }

    public function profile(): View
    {
        $user = auth()->user();
        $organization = $user->organization;

        $students = [];
        $studentJsonPath = base_path('data/students.json');
        if (file_exists($studentJsonPath)) {
            $json = json_decode(file_get_contents($studentJsonPath), true);
            if (isset($json['students'])) {
                foreach ($json['students'] as $s) {
                    $students[] = $s['nama'];
                }
            }
        }

        return view('pengurus-ukm.profile', [
            'user' => $user,
            'organization' => $organization,
            'students' => $students,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $organization = $user->organization;

        if (!$organization) {
            return back()->with('error', 'Organisasi tidak ditemukan.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'motto' => 'nullable|string|max:255',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'budaya_nilai' => 'nullable|string',
            'program_kegiatan' => 'nullable|string',
            'instagram' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'member_count' => 'nullable|integer|min:0',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            // Struktur pengurus: Pengurus UKM bisa edit ketua/sekretaris/bendahara tapi TIDAK advisor
            'ketua_name' => 'nullable|string|max:255',
            'chair_phone' => 'nullable|string|max:50',
            'chair_email' => 'nullable|email|max:255',
            'chair_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'secretary_name' => 'nullable|string|max:255',
            'secretary_phone' => 'nullable|string|max:50',
            'secretary_email' => 'nullable|email|max:255',
            'secretary_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'treasurer_name' => 'nullable|string|max:255',
            'treasurer_phone' => 'nullable|string|max:50',
            'treasurer_email' => 'nullable|email|max:255',
            'treasurer_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_open_recruitment' => 'nullable',
            'recruitment_link' => 'nullable|string|max:255',
            'recruitment_req' => 'nullable|string',
        ]);

        unset($data['logo'], $data['banner']);

        // Explicitly cast is_open_recruitment to boolean only if it's sent in the request
        if ($request->has('is_open_recruitment')) {
            $data['is_open_recruitment'] = (bool) $request->input('is_open_recruitment');
        }

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('organizations/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            $data['banner_path'] = $request->file('banner')->store('organizations/banners', 'public');
        }

        if ($request->hasFile('chair_photo')) {
            $data['chair_photo'] = $request->file('chair_photo')->store('organizations/pengurus', 'public');
        }

        if ($request->hasFile('secretary_photo')) {
            $data['secretary_photo'] = $request->file('secretary_photo')->store('organizations/pengurus', 'public');
        }

        if ($request->hasFile('treasurer_photo')) {
            $data['treasurer_photo'] = $request->file('treasurer_photo')->store('organizations/pengurus', 'public');
        }        $organization->update($data);

        return back()->with('status', 'Profil organisasi berhasil diperbarui.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update(['password' => $request->new_password]);

        return back()->with('status', 'Password berhasil diubah.');
    }

    public function lostFound(\Illuminate\Http\Request $request): View
    {
        $org = auth()->user()->organization;
        $isBemUniversitas = $org && (stripos($org->name, 'BEM UNKLAB') !== false || ($org->kategori === 'BEM' && $org->level === 'Universitas'));
        if (!$isBemUniversitas) {
            abort(403, 'Akses ditolak. Hanya BEM Universitas yang dapat mengakses fitur ini.');
        }
        $orgId = $org->id ?? null;

        $query = \App\Models\LostItem::with('organization')->orderBy('created_at', 'desc');

        // Pengurus UKM sees all items (not just their org) so they can manage/review
        if ($q = $request->input('q')) {
            $query->where('title', 'like', "%{$q}%");
        }
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $items = $query->get();

        return view('pengurus-ukm.lost-found.index', [
            'items' => $items,
            'q' => $request->input('q', ''),
            'type' => $request->input('type', ''),
            'statusFilter' => $request->input('status', ''),
        ]);
    }

    public function storeLostItem(\Illuminate\Http\Request $request)
    {
        $org = auth()->user()->organization;
        $isBemUniversitas = $org && (stripos($org->name, 'BEM UNKLAB') !== false || ($org->kategori === 'BEM' && $org->level === 'Universitas'));
        if (!$isBemUniversitas) {
            abort(403, 'Akses ditolak. Hanya BEM Universitas yang dapat menambahkan laporan.');
        }
        
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

        \App\Models\LostItem::create([
            'organization_id' => auth()->user()->organization_id,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'image_path' => $imagePath,
            'status' => 'active',
        ]);

        return redirect()->route('pengurus-ukm.lost-found.index')
            ->with('success', 'Laporan barang berhasil ditambahkan.');
    }

    public function updateLostItemStatus(\Illuminate\Http\Request $request, $id)
    {
        $org = auth()->user()->organization;
        $isBemUniversitas = $org && (stripos($org->name, 'BEM UNKLAB') !== false || ($org->kategori === 'BEM' && $org->level === 'Universitas'));
        if (!$isBemUniversitas) {
            abort(403, 'Akses ditolak. Hanya BEM Universitas yang dapat mengubah status.');
        }

        $item = \App\Models\LostItem::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,active,resolved,rejected']);
        $item->update(['status' => $request->status]);

        $label = 'Ditolak';
        if ($request->status === 'resolved') $label = 'Sudah Ditemukan';
        if ($request->status === 'active') $label = 'Telah Disetujui (Aktif)';
        if ($request->status === 'pending') $label = 'Menunggu Review (Pending)';

        return redirect()->route('pengurus-ukm.lost-found.index')
            ->with('success', "Status barang diubah menjadi: {$label}");
    }
}
