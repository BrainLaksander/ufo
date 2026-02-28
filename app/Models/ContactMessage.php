<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ContactMessage Model
 * Mewakili pesan dari form kontak
 * 
 * Admin perlu review dan bisa reply ke pesan
 * Status: new, read, replied
 */
class ContactMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'sender_name',
        'sender_email',
        'subject',
        'message',
        'status',
        'reply',
        'replied_by',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== RELATIONSHIPS ==========

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    // ========== SCOPES ==========

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeUnreplied($query)
    {
        return $query->whereIn('status', ['new', 'read']);
    }

    // ========== HELPER METHODS ==========

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'new' => 'danger',
            'read' => 'warning',
            'replied' => 'success',
            default => 'secondary',
        };
    }

    public function markAsRead()
    {
        if ($this->status === 'new') {
            $this->update(['status' => 'read']);
        }
    }

    public function reply(string $message, User $responder)
    {
        $this->update([
            'status' => 'replied',
            'reply' => $message,
            'replied_by' => $responder->id,
            'replied_at' => now(),
        ]);

        // Kirim email reply ke sender
        \Mail::to($this->sender_email)
            ->send(new \App\Mail\ContactMessageReply($this));
    }

    public function getInitials(): string
    {
        $names = explode(' ', $this->sender_name);
        $initials = '';
        foreach (array_slice($names, 0, 2) as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        return $initials;
    }
}
