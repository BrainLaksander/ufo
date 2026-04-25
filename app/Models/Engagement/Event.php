<?php

namespace App\Models\Engagement;

use App\Models\Core\Organization;
use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'created_by',
        'title',
        'description',
        'poster',
        'event_date',
        'location',
        'category',
        'status',
        'capacity',
        'registered',
    ];

    protected $casts = [
        'event_date' => 'datetime',
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

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('event_date', '>=', now()->toDateString());
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function getCategoryColor(): string
    {
        return match ($this->category) {
            'rapat' => 'info',
            'event' => 'primary',
            'akademik' => 'success',
            'sosial' => 'warning',
            default => 'secondary',
        };
    }

    public function getCategoryLabel(): string
    {
        return match ($this->category) {
            'rapat' => ' Rapat',
            'event' => ' Event',
            'akademik' => ' Akademik',
            'sosial' => ' Sosial',
            default => 'Kegiatan',
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'draft' => 'secondary',
            'published' => 'success',
            'ongoing' => 'info',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function isUpcoming(): bool
    {
        return $this->event_date > now();
    }

    public function isFull(): bool
    {
        return $this->capacity && $this->registered >= $this->capacity;
    }

    public function getAvailableSlots(): int
    {
        if (!$this->capacity) {
            return 999;
        }

        return max(0, $this->capacity - $this->registered);
    }
}
