<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Proposal Model
 * Mewakili pengajuan/proposal dari organisasi (dana, acara, fasilitas, dll)
 * 
 * Workflow: draft → submitted → under_review → approved/rejected
 * Admin bisa memberikan review notes
 */
class Proposal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'submitted_by',
        'title',
        'description',
        'type',
        'status',
        'attachment',
        'reviewed_by',
        'review_notes',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== RELATIONSHIPS ==========

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ========== SCOPES ==========

    public function scopePending($query)
    {
        return $query->whereIn('status', ['submitted', 'under_review']);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // ========== HELPER METHODS ==========

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'submitted' => 'info',
            'under_review' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'dana' => '💰 Pengajuan Dana',
            'acara' => '🎯 Pengajuan Acara',
            'fasilitas' => '🏢 Pengajuan Fasilitas',
            'lainnya' => '📋 Pengajuan Lainnya',
            default => 'Pengajuan',
        };
    }

    public function canBeSubmitted(): bool
    {
        return $this->status === 'draft';
    }

    public function submit()
    {
        $this->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // Trigger notification ke admin
        event(new \App\Events\ProposalSubmitted($this));
    }

    public function approve(User $reviewer, string $notes = '')
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $reviewer->id,
            'review_notes' => $notes,
            'reviewed_at' => now(),
        ]);

        // Trigger notification ke submitter
        event(new \App\Events\ProposalApproved($this));
    }

    public function reject(User $reviewer, string $reason)
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewer->id,
            'review_notes' => $reason,
            'reviewed_at' => now(),
        ]);

        // Trigger notification ke submitter
        event(new \App\Events\ProposalRejected($this));
    }
}
