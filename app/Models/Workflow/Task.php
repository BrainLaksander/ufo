<?php

namespace App\Models\Workflow;

use App\Models\Core\Member;
use App\Models\Core\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id', 'assigned_to', 'title', 'description', 'priority', 'status',
        'deadline', 'type', 'task_type', 'related_id', 'related_submission_id',
        'related_report_id', 'completed_at',
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

    public function isOverdue(): bool
    {
        return $this->status !== 'completed' && $this->deadline && now()->isAfter($this->deadline);
    }

    public function getPriorityColor(): string
    {
        return match ($this->priority) {
            'urgent' => 'danger',
            'normal' => 'warning',
            'low' => 'info',
            default => 'secondary',
        };
    }

    public function daysUntilDeadline(): ?int
    {
        if (!$this->deadline) {
            return null;
        }

        return now()->diffInDays($this->deadline, false);
    }
}
