<?php

namespace App\Models\Workflow;

use App\Models\Core\Member;
use App\Models\Core\Organization;
use App\Models\Engagement\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id', 'event_id', 'member_id', 'title', 'description', 'content',
        'file_path', 'attachment', 'findings', 'participants', 'report_type',
        'status', 'review_notes', 'reviewer_notes', 'submitted_at', 'reviewed_at',
        'submitted_date', 'approved_date',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'submitted_date' => 'date',
        'approved_date' => 'date',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'id', 'related_report_id');
    }

    public function getStatusDisplay(): string
    {
        $statuses = [
            'draft' => 'Draft',
            'submitted' => 'Menunggu Review',
            'reviewing' => 'Sedang Direview',
            'approved' => 'Diterima',
            'rejected' => 'Ditolak',
            'revision_needed' => 'Perlu Revisi',
        ];

        return $statuses[$this->status] ?? ucfirst((string) $this->status);
    }
}
