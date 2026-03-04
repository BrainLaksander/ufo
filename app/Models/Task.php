<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = ['organization_id', 'title', 'description', 'priority', 'status', 'deadline', 'type', 'related_id', 'completed_at'];

    protected $casts = ['deadline' => 'date', 'completed_at' => 'datetime'];

    public function organization(): BelongsTo {
        return $this->belongsTo(Organization::class);
    }
}

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'organization_id', 'assigned_to', 'title', 'description',
        'priority', 'status', 'task_type', 'deadline', 'completed_at',
        'related_submission_id', 'related_report_id'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'assigned_to');
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class, 'related_submission_id');
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'related_report_id');
    }

    // Helper: Cek apakah task overdue
    public function isOverdue(): bool
    {
        return $this->status !== 'completed' && $this->deadline && now()->isAfter($this->deadline);
    }

    // Helper: Priority color
    public function getPriorityColor(): string
    {
        return match($this->priority) {
            'urgent' => 'danger',
            'normal' => 'warning',
            'low' => 'info',
            default => 'secondary'
        };
    }

    // Helper: Days until deadline
    public function daysUntilDeadline(): ?int
    {
        if (!$this->deadline) return null;
        return now()->diffInDays($this->deadline, false);
    }
}
