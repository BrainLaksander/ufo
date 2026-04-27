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

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_REVIEWING = 'reviewing';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_REVISION_NEEDED = 'revision_needed';

    public const REVIEWABLE_STATUSES = [
        self::STATUS_SUBMITTED,
        self::STATUS_REVIEWING,
    ];

    public const PENGURUS_SUBMITTABLE_STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_REVISION_NEEDED,
    ];

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
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMITTED => 'Menunggu Review',
            self::STATUS_REVIEWING => 'Sedang Direview',
            self::STATUS_APPROVED => 'Diterima',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_REVISION_NEEDED => 'Perlu Revisi',
        ];

        return $statuses[$this->status] ?? ucfirst((string) $this->status);
    }

    public static function isSubmittableByPengurus(string $status): bool
    {
        return in_array($status, self::PENGURUS_SUBMITTABLE_STATUSES, true);
    }

    public static function isReviewableByKemahasiswaan(string $status): bool
    {
        return in_array($status, self::REVIEWABLE_STATUSES, true);
    }
}
