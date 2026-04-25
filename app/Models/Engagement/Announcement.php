<?php

namespace App\Models\Engagement;

use App\Models\Core\Organization;
use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'created_by',
        'title',
        'content',
        'status',
        'approved_by',
        'rejection_reason',
        'scheduled_at',
        'published_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('scheduled_at')
                    ->orWhere('scheduled_at', '<=', now());
            });
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'draft' => 'secondary',
            'pending' => 'warning',
            'approved' => 'success',
            'published' => 'info',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    public function canBePublished(): bool
    {
        return in_array($this->status, ['draft', 'approved'], true)
            && auth()->user()->can('approveAnnouncement', $this);
    }

    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function reject(string $reason, User $rejector)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'approved_by' => $rejector->id,
        ]);
    }

    public function approve(User $approver)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
        ]);
    }
}
