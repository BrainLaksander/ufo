<?php

namespace App\Models\Workflow;

use App\Models\Core\Member;
use App\Models\Core\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_REVIEWING = 'reviewing';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_REVISED = 'revised';

    public const REVIEWABLE_STATUSES = [
        self::STATUS_SUBMITTED,
        self::STATUS_REVIEWING,
    ];

    public const PENGURUS_SUBMITTABLE_STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_REVISED,
    ];

    protected $fillable = [
        'organization_id', 'member_id', 'title', 'description', 'file_path', 'type',
        'status', 'rejection_reason', 'notes', 'feedback', 'revision_count',
        'submitted_at', 'approved_at', 'submitted_date', 'approved_date',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'submitted_date' => 'date',
        'approved_date' => 'date',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function tasks(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'id', 'related_submission_id');
    }

    public function getApprovalStatus(): string
    {
        if ($this->status === self::STATUS_APPROVED) {
            return 'Disetujui';
        }
        if ($this->status === self::STATUS_REJECTED) {
            return 'Ditolak';
        }
        if ($this->status === self::STATUS_REVISED) {
            return 'Perlu Revisi';
        }
        if ($this->status === self::STATUS_SUBMITTED) {
            return 'Menunggu Review';
        }

        return ucfirst((string) $this->status);
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
