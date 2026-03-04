<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use SoftDeletes;

    protected $fillable = ['organization_id', 'title', 'description', 'file_path', 'type', 'status', 'rejection_reason', 'notes', 'submitted_at', 'approved_at'];

    protected $casts = ['submitted_at' => 'datetime', 'approved_at' => 'datetime'];

    public function organization(): BelongsTo {
        return $this->belongsTo(Organization::class);
    }
}

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    protected $fillable = [
        'organization_id', 'member_id', 'title', 'description', 'type',
        'status', 'feedback', 'revision_count', 'submitted_date', 'approved_date', 'file_path'
    ];

    protected $casts = [
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

    // Helper: Status approval timeline
    public function getApprovalStatus(): string
    {
        if ($this->status === 'approved') return 'Disetujui';
        if ($this->status === 'rejected') return 'Ditolak';
        if ($this->status === 'revised') return 'Perlu Revisi';
        if ($this->status === 'submitted') return 'Menunggu Review';
        return ucfirst($this->status);
    }
}
