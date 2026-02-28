<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    // Support both controller field names used across the codebase
    protected $fillable = [
        'organization_id', 'member_id', 'action', 'activity_type',
        'description', 'model_type', 'model_id', 'related_model', 'related_id',
        'changes', 'metadata'
    ];

    protected $casts = [
        'changes' => 'json',
        'metadata' => 'json',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    // Helper: Tampilkan waktu relatif (e.g., "2 jam lalu")
    public function getRelativeTime(): string
    {
        return $this->created_at?->diffForHumans() ?? '';
    }

    // Helper: Icon untuk tipe aktivitas (fallback to 'action' or 'activity_type')
    public function getActivityIcon(): string
    {
        $type = $this->activity_type ?? $this->action ?? null;

        return match($type) {
            'event_published' => '📅',
            'member_joined' => '👤',
            'submission_approved' => '✅',
            'report_received' => '📝',
            'announcement_created' => '📢',
            'profile_updated' => '⚙️',
            'task_created', 'task_completed' => '📋',
            default => '📌'
        };
    }

    public function getActivityLabel(): string
    {
        $type = $this->activity_type ?? $this->action ?? null;

        return match($type) {
            'event_published' => 'Event Dipublikasikan',
            'member_joined' => 'Anggota Baru Bergabung',
            'submission_approved' => 'Pengajuan Disetujui',
            'report_received' => 'Laporan Diterima',
            'announcement_created' => 'Pengumuman Dibuat',
            'profile_updated' => 'Profil Diperbarui',
            'task_created' => 'Tugas Baru Dibuat',
            'task_completed' => 'Tugas Diselesaikan',
            default => ucfirst(str_replace('_', ' ', (string) $type))
        };
    }
}
