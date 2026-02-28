<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Event Model
 * Mewakili event/kegiatan organisasi
 * 
 * Categories: rapat, event, akademik, sosial
 * Status: draft, published, ongoing, completed, cancelled
 */
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

    // ========== RELATIONSHIPS ==========

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ========== SCOPES ==========

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

    // ========== HELPER METHODS ==========

    /**
     * Warna badge untuk kategori event
     */
    public function getCategoryColor(): string
    {
        return match($this->category) {
            'rapat' => 'info',
            'event' => 'primary',
            'akademik' => 'success',
            'sosial' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Label kategori dengan icon
     */
    public function getCategoryLabel(): string
    {
        return match($this->category) {
            'rapat' => '📘 Rapat',
            'event' => '🎉 Event',
            'akademik' => '📚 Akademik',
            'sosial' => '🤝 Sosial',
            default => 'Kegiatan',
        };
    }

    /**
     * Bootstrap badge class untuk status
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'published' => 'success',
            'ongoing' => 'info',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Cek event apakah upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->event_date > now();
    }

    /**
     * Cek event apakah sudah penuh
     */
    public function isFull(): bool
    {
        return $this->capacity && $this->registered >= $this->capacity;
    }

    /**
     * Dapatkan slot yang tersedia
     */
    public function getAvailableSlots(): int
    {
        if (!$this->capacity) return 999;
        return max(0, $this->capacity - $this->registered);
    }
}
