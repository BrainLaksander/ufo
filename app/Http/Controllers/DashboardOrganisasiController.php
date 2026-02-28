<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class DashboardOrganisasiController extends Controller
{
    /**
     * Menampilkan Dashboard Pengurus Organisasi
     */
    public function index(): View
    {
        $stats = [
            [
                'label' => 'Status Profil',
                'value' => 'Lengkap',
                'color' => 'success',
            ],
            [
                'label' => 'Anggota Aktif',
                'value' => 124,
                'color' => 'primary',
            ],
            [
                'label' => 'Event Aktif',
                'value' => 3,
                'color' => 'warning',
            ],
            [
                'label' => 'Event Selesai',
                'value' => 12,
                'color' => 'secondary',
            ],
            [
                'label' => 'Pengajuan Disetujui',
                'value' => 5,
                'color' => 'info',
            ],
            [
                'label' => 'Laporan Terkirim',
                'value' => 8,
                'color' => 'danger',
            ],
        ];

        $recentActivities = [
            ['title' => 'Event "Workshop AI" dipublikasikan', 'time' => '10 menit lalu'],
            ['title' => '15 pendaftar baru divisi IT', 'time' => '1 jam lalu'],
            ['title' => 'Pengumuman recruitment disetujui', 'time' => '2 jam lalu'],
            ['title' => 'Proposal Seminar Cybersecurity disetujui', 'time' => '3 jam lalu'],
        ];

        $tasks = [
            [
                'title' => 'Revisi Proposal Event',
                'priority' => 'Urgent',
                'deadline' => '2 hari lagi',
            ],
            [
                'title' => 'Update Profil Organisasi',
                'priority' => 'Normal',
                'deadline' => '5 hari lagi',
            ],
            [
                'title' => 'Upload Dokumentasi Event',
                'priority' => 'Low',
                'deadline' => '1 minggu lagi',
            ],
        ];

        return view('portal.pengurus.dashboard', compact('stats', 'recentActivities', 'tasks'));
    }

    /**
     * Hitung persentase kelengkapan profil organisasi
     */
    private function calculateProfileCompletion(Organization $org): int
    {
        $total_fields = 8;
        $completed = 0;

        // Cek setiap field
        if ($org->logo) $completed++;
        if ($org->banner) $completed++;
        if ($org->description) $completed++;
        if ($org->vision) $completed++;
        if ($org->mission) $completed++;
        if ($org->email) $completed++;
        if ($org->phone) $completed++;
        if ($org->members()->where('position', 'ketua')->exists()) $completed++;

        return round(($completed / $total_fields) * 100);
    }

    /**
     * Dapatkan list field profil yang belum lengkap
     */
    private function getMissingProfileItems(Organization $org): array
    {
        $missing = [];

        if (!$org->logo) $missing[] = 'Logo Organisasi';
        if (!$org->banner) $missing[] = 'Banner Organisasi';
        if (!$org->description) $missing[] = 'Deskripsi Organisasi';
        if (!$org->vision) $missing[] = 'Visi Organisasi';
        if (!$org->mission) $missing[] = 'Misi Organisasi';
        if (!$org->email) $missing[] = 'Email Resmi';
        if (!$org->phone) $missing[] = 'Nomor Telepon';
        if (!$org->members()->where('position', 'ketua')->exists()) $missing[] = 'Ketua Organisasi';

        return $missing;
    }

    /**
     * Hitung kesehatan organisasi (0-100)
     */
    private function calculateOrganizationHealth(Organization $org): int
    {
        $score = 0;

        // Profile completion (30%)
        $score += $this->calculateProfileCompletion($org) * 0.3;

        // Active members (20%)
        $active_members_ratio = ($org->activeMembers() / max($org->members()->count(), 1)) * 100;
        $score += $active_members_ratio * 0.2;

        // On-time submissions (25%)
        $ontime_submissions = $org->submissions()
            ->where('status', '!=', 'draft')
            ->where('submitted_date', '<=', $org->submissions()->avg('deadline'))
            ->count() / max($org->submissions()->count(), 1);
        $score += $ontime_submissions * 25;

        // Task completion (25%)
        $completed_tasks = $org->tasks()
            ->where('status', 'completed')
            ->count() / max($org->tasks()->count(), 1);
        $score += $completed_tasks * 25;

        return min(100, round($score));
    }

    /**
     * Dapatkan item yang perlu perhatian urgent
     */
    private function getUrgentAttentionItems(Organization $org): array
    {
        $urgent = [];

        // Submissions menunggu review yang sudah lama
        $org->submissions()
            ->where('status', 'submitted')
            ->where('submitted_date', '<', now()->subDays(3))
            ->each(function ($submission) use (&$urgent) {
                $urgent[] = [
                    'type' => 'submission',
                    'message' => "Pengajuan '{$submission->title}' menunggu review sejak " . $submission->submitted_date->format('d M Y'),
                    'link' => "/portal/pengurus/submissions/{$submission->id}"
                ];
            });

        // Tasks overdue
        $org->tasks()
            ->where('status', 'pending')
            ->where('deadline', '<', now())
            ->each(function ($task) use (&$urgent) {
                $urgent[] = [
                    'type' => 'task',
                    'message' => "Task '{$task->title}' sudah melewati deadline",
                    'link' => "/portal/pengurus/tasks/{$task->id}"
                ];
            });

        // Profil belum lengkap
        if (!$org->isProfileComplete()) {
            $urgent[] = [
                'type' => 'profile',
                'message' => 'Profil organisasi belum lengkap',
                'link' => "/portal/pengurus/settings"
            ];
        }

        return $urgent;
    }

    /**
     * Mark task sebagai selesai (AJAX)
     */
    public function completeTask($taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Task ditandai selesai']);
    }

    /**
     * Get activity logs untuk filter
     */
    public function getActivities($org_id, $filter = null)
    {
        $query = ActivityLog::where('organization_id', $org_id)
            ->orderByDesc('created_at');

        if ($filter) {
            $query->where('activity_type', $filter);
        }

        return $query->paginate(15);
    }
}
