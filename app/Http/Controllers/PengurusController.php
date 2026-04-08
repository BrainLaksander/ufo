<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Event;
use App\Models\Member;
use App\Models\Submission;
use App\Models\Report;
use App\Models\Task;
use App\Models\ActivityLog;
use Illuminate\View\View;

class PengurusController extends Controller
{
    // Dummy organization (in real app, fetch from auth/session)
    protected function getOrganization()
    {
        return [
            'id' => 1,
            'name' => 'Himpunan Mahasiswa Teknik Informatika',
            'shortname' => 'HMTI',
            'logo' => 'bi-people-fill',
            'role' => 'Ketua',
            'members_count' => 45,
        ];
    }

    // Dashboard
    public function dashboard(): View
    {
        $org = $this->getOrganization();
        
        $stats = [
            ['label' => 'Total Anggota', 'value' => 45, 'icon' => 'bi-people-fill'],
            ['label' => 'Event Aktif', 'value' => 3, 'icon' => 'bi-calendar-event-fill'],
            ['label' => 'Pengumuman Aktif', 'value' => 8, 'icon' => 'bi-megaphone-fill'],
            ['label' => 'Laporan Lost & Found', 'value' => 12, 'icon' => 'bi-search'],
        ];

        $recent_events = [
            ['id' => 1, 'name' => 'Workshop Coding', 'date' => '2026-02-15', 'participants' => 25, 'status' => 'Open'],
            ['id' => 2, 'name' => 'Diskusi AI', 'date' => '2026-02-20', 'participants' => 18, 'status' => 'Open'],
            ['id' => 3, 'name' => 'Bootcamp Web Dev', 'date' => '2026-03-01', 'participants' => 0, 'status' => 'Draft'],
        ];

        $recent_applications = [
            ['id' => 1, 'name' => 'Budi Santoso', 'nim' => '210101001', 'faculty' => 'Teknik Informatika', 'date' => '2026-01-28'],
            ['id' => 2, 'name' => 'Siti Nurhaliza', 'nim' => '210101002', 'faculty' => 'Sistem Informasi', 'date' => '2026-01-27'],
            ['id' => 3, 'name' => 'Ahmad Rifki', 'nim' => '210102005', 'faculty' => 'Teknik Informatika', 'date' => '2026-01-26'],
        ];

        $recent_lostandfound = [
            ['id' => 1, 'item' => 'Dompet Merah', 'category' => 'Dompet', 'status' => 'Found', 'date' => '2026-01-28'],
            ['id' => 2, 'item' => 'ID Card', 'category' => 'Kartu Identitas', 'status' => 'Pending', 'date' => '2026-01-27'],
        ];

        return view('pages.pengurus.dashboard', compact('org', 'stats', 'recent_events', 'recent_applications', 'recent_lostandfound'));
    }

    // Events
    public function events(): View
    {
        $org = $this->getOrganization();
        $events = [
            ['id' => 1, 'name' => 'Workshop Coding', 'date' => '2026-02-15', 'time' => '14:00', 'location' => 'Lab A', 'quota' => 40, 'participants' => 25, 'status' => 'Open'],
            ['id' => 2, 'name' => 'Diskusi AI', 'date' => '2026-02-20', 'time' => '15:30', 'location' => 'Aula', 'quota' => 100, 'participants' => 18, 'status' => 'Open'],
            ['id' => 3, 'name' => 'Bootcamp Web Dev', 'date' => '2026-03-01', 'time' => '09:00', 'location' => 'Lab B', 'quota' => 30, 'participants' => 0, 'status' => 'Draft'],
        ];
        return view('pages.pengurus.events.list', compact('org', 'events'));
    }

    public function eventCreate(): View
    {
        $org = $this->getOrganization();
        return view('pages.pengurus.events.form', compact('org'));
    }

    public function eventDetail($id): View
    {
        $org = $this->getOrganization();
        $event = [
            'id' => $id,
            'name' => 'Workshop Coding',
            'date' => '2026-02-15',
            'time' => '14:00',
            'location' => 'Lab A',
            'quota' => 40,
            'description' => 'Workshop intensif coding untuk mahasiswa baru.',
            'status' => 'Open',
            'participants' => [
                ['name' => 'Budi Santoso', 'nim' => '210101001', 'status' => 'Hadir'],
                ['name' => 'Siti Nurhaliza', 'nim' => '210101002', 'status' => 'Belum Hadir'],
                ['name' => 'Ahmad Rifki', 'nim' => '210102005', 'status' => 'Hadir'],
            ]
        ];
        return view('pages.pengurus.events.detail', compact('org', 'event'));
    }

    // Announcements
    public function announcements(): View
    {
        $org = $this->getOrganization();
        $announcements = [
            ['id' => 1, 'title' => 'Pengumuman Rapat Rutin', 'category' => 'Rapat', 'status' => 'Published', 'date' => '2026-01-28'],
            ['id' => 2, 'title' => 'Info Workshop Terbaru', 'category' => 'Event', 'status' => 'Published', 'date' => '2026-01-26'],
            ['id' => 3, 'title' => 'Pengumuman Terbaru', 'category' => 'Lainnya', 'status' => 'Draft', 'date' => '2026-01-25'],
        ];
        return view('pages.pengurus.announcements.list', compact('org', 'announcements'));
    }

    public function announcementCreate(): View
    {
        $org = $this->getOrganization();
        return view('pages.pengurus.announcements.form', compact('org'));
    }

    // Members
    public function members(): View
    {
        $org = $this->getOrganization();
        $members = [
            ['id' => 1, 'name' => 'Budi Santoso', 'nim' => '210101001', 'position' => 'Ketua', 'status' => 'Aktif'],
            ['id' => 2, 'name' => 'Siti Nurhaliza', 'nim' => '210101002', 'position' => 'Sekretaris', 'status' => 'Aktif'],
            ['id' => 3, 'name' => 'Ahmad Rifki', 'nim' => '210102005', 'position' => 'Bendahara', 'status' => 'Aktif'],
            ['id' => 4, 'name' => 'Rina Wijaya', 'nim' => '210102010', 'position' => 'Staff', 'status' => 'Aktif'],
            ['id' => 5, 'name' => 'Doni Pratama', 'nim' => '210102015', 'position' => 'Staff', 'status' => 'Nonaktif'],
        ];
        return view('pages.pengurus.members.list', compact('org', 'members'));
    }

    // Applications
    public function applications(): View
    {
        $org = $this->getOrganization();
        $applications = [
            ['id' => 1, 'name' => 'Rizka Dwi', 'nim' => '220101001', 'faculty' => 'Teknik Informatika', 'reason' => 'Ingin belajar lebih dalam tentang IT', 'date' => '2026-01-28', 'status' => 'Pending'],
            ['id' => 2, 'name' => 'Hendra Gunawan', 'nim' => '220101002', 'faculty' => 'Sistem Informasi', 'reason' => 'Tertarik dengan web development', 'date' => '2026-01-27', 'status' => 'Pending'],
            ['id' => 3, 'name' => 'Eka Putri', 'nim' => '220102010', 'faculty' => 'Teknik Informatika', 'reason' => 'Mengikuti teman-teman', 'date' => '2026-01-26', 'status' => 'Pending'],
        ];
        return view('pages.pengurus.applications.list', compact('org', 'applications'));
    }

    // Lost & Found Moderation
    public function lostandfound(): View
    {
        $org = $this->getOrganization();
        $items = [
            ['id' => 1, 'item' => 'Dompet Merah', 'category' => 'Dompet', 'location' => 'Gedung A', 'status' => 'Found', 'reporter' => 'Budi', 'date' => '2026-01-28'],
            ['id' => 2, 'item' => 'ID Card Teknik', 'category' => 'Kartu Identitas', 'location' => 'Kantin', 'status' => 'Pending', 'reporter' => 'Siti', 'date' => '2026-01-27'],
            ['id' => 3, 'item' => 'Kunci Hostel', 'category' => 'Kunci', 'location' => 'Perpustakaan', 'status' => 'Resolved', 'reporter' => 'Ahmad', 'date' => '2026-01-20'],
        ];
        return view('pages.pengurus.lostandfound.list', compact('org', 'items'));
    }

    // Proposals & Archive
    public function proposals(): View
    {
        $org = $this->getOrganization();
        $proposals = [
            ['id' => 1, 'name' => 'Proposal Workshop 2026', 'date' => '2026-01-15', 'status' => 'Approved', 'size' => '2.5 MB'],
            ['id' => 2, 'name' => 'Proposal Pengumpulan Dana', 'date' => '2026-01-10', 'status' => 'Draft', 'size' => '1.8 MB'],
            ['id' => 3, 'name' => 'Laporan Kegiatan 2025', 'date' => '2026-01-05', 'status' => 'Approved', 'size' => '3.2 MB'],
        ];
        return view('pages.pengurus.proposals.list', compact('org', 'proposals'));
    }

    // Settings
    public function settings(): View
    {
        $org = $this->getOrganization();
        $orgData = [
            'name' => 'Himpunan Mahasiswa Teknik Informatika',
            'shortname' => 'HMTI',
            'description' => 'Organisasi kemahasiswaan bidang Teknik Informatika',
            'vision' => 'Menjadi organisasi yang berdedikasi dalam pengembangan IT',
            'mission' => [
                'Mengedukasi mahasiswa tentang teknologi terkini',
                'Memfasilitasi kolaborasi antar mahasiswa',
                'Mengembangkan keterampilan teknis mahasiswa'
            ],
            'email' => 'hmti@unklab.ac.id',
            'phone' => '0431891035',
            'instagram' => '@hmti_unklab',
            'line' => '@hmti_unklab',
        ];
        return view('pages.pengurus.settings', compact('org', 'orgData'));
    }
}
