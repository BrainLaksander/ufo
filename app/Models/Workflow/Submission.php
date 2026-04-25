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
        if ($this->status === 'approved') {
            return 'Disetujui';
        }
        if ($this->status === 'rejected') {
            return 'Ditolak';
        }
        if ($this->status === 'revised') {
            return 'Perlu Revisi';
        }
        if ($this->status === 'submitted') {
            return 'Menunggu Review';
        }

        return ucfirst((string) $this->status);
    }
}
