<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * LostFoundItem Model
 * Mewakili barang hilang/ditemukan
 * 
 * Dua skenario workflow:
 * 1. User lapor hilang → Admin tandai ditemukan → User claim → Closed
 * 2. Admin input ditemukan → User klaim → Verifikasi → Closed
 * 
 * Status: active, claimed, closed
 */
class LostFoundItem extends Model
{
    use SoftDeletes;

    protected $table = 'lost_found_items';

    protected $fillable = [
        'organization_id',
        'reported_by',
        'item_name',
        'description',
        'image',
        'location_found',
        'type',
        'status',
        'claimed_by',
        'claimed_at',
        'resolved_at',
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== RELATIONSHIPS ==========

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function claimer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }

    // ========== SCOPES ==========

    public function scopeLost($query)
    {
        return $query->where('type', 'lost');
    }

    public function scopeFound($query)
    {
        return $query->where('type', 'found');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeClaimed($query)
    {
        return $query->where('status', 'claimed');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    // ========== HELPER METHODS ==========

    public function getTypeBadgeClass(): string
    {
        return match($this->type) {
            'lost' => 'danger',
            'found' => 'success',
            default => 'secondary',
        };
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'lost' => ' Barang Hilang',
            'found' => ' Barang Ditemukan',
            default => 'Barang',
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'active' => 'info',
            'claimed' => 'warning',
            'closed' => 'secondary',
            default => 'secondary',
        };
    }

    public function canBeClaimed(): bool
    {
        return $this->status === 'active';
    }

    public function claim(User $user)
    {
        $this->update([
            'status' => 'claimed',
            'claimed_by' => $user->id,
            'claimed_at' => now(),
        ]);

        // Trigger notification untuk reporter
        event(new \App\Events\ItemClaimed($this));
    }

    public function markAsFound()
    {
        $this->update([
            'status' => 'found',
        ]);
    }

    public function close()
    {
        $this->update([
            'status' => 'closed',
            'resolved_at' => now(),
        ]);
    }
}
