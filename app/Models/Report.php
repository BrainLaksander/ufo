<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = ['organization_id', 'event_id', 'title', 'description', 'file_path', 'findings', 'status', 'submitted_at', 'reviewed_at', 'review_notes'];

    protected $casts = ['submitted_at' => 'datetime', 'reviewed_at' => 'datetime'];

    public function organization(): BelongsTo {
        return $this->belongsTo(Organization::class);
    }

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }
}

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'organization_id', 'event_id', 'member_id', 'title', 'content',
        'participants', 'report_type', 'status', 'reviewer_notes',
        'submitted_date', 'approved_date', 'attachment'
    ];

    protected $casts = [
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

    // Helper: Status display
    public function getStatusDisplay(): string
    {
        $statuses = [
            'draft' => 'Draft',
            'submitted' => 'Menunggu Review',
            'reviewing' => 'Sedang Direview',
            'approved' => 'Diterima',
            'rejected' => 'Ditolak',
            'revision_needed' => 'Perlu Revisi'
        ];
        return $statuses[$this->status] ?? ucfirst($this->status);
    }
}
