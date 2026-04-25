<?php

namespace App\Models\Core;

use App\Models\Engagement\Announcement;
use App\Models\Engagement\ContactMessage;
use App\Models\Engagement\Event;
use App\Models\Engagement\LostFoundItem;
use App\Models\Workflow\Proposal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'organization_id',
        'phone',
        'avatar',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const ROLE_KEMAHASISWAAN = 'kemahasiswaan';
    public const ROLE_PENGURUS = 'pengurus';
    public const ROLE_MAHASISWA = 'mahasiswa';

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function leaderOrganizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'leader_id');
    }

    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function createdAnnouncements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function approvedAnnouncements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'approved_by');
    }

    public function submittedProposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'submitted_by');
    }

    public function reviewedProposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'reviewed_by');
    }

    public function reportedLostItems(): HasMany
    {
        return $this->hasMany(LostFoundItem::class, 'reported_by');
    }

    public function claimedLostItems(): HasMany
    {
        return $this->hasMany(LostFoundItem::class, 'claimed_by');
    }

    public function repliedMessages(): HasMany
    {
        return $this->hasMany(ContactMessage::class, 'replied_by');
    }

    public function isKemahasiswaan(): bool
    {
        return $this->role === self::ROLE_KEMAHASISWAAN;
    }

    public function isPengurus(): bool
    {
        return $this->role === self::ROLE_PENGURUS;
    }

    public function isMahasiswa(): bool
    {
        return $this->role === self::ROLE_MAHASISWA;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function can($ability, $arguments = [])
    {
        if ($this->isKemahasiswaan()) {
            return true;
        }

        return parent::can($ability, $arguments);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeKemahasiswaan($query)
    {
        return $query->where('role', self::ROLE_KEMAHASISWAAN);
    }

    public function scopePengurus($query)
    {
        return $query->where('role', self::ROLE_PENGURUS);
    }

    public function scopeMahasiswa($query)
    {
        return $query->where('role', self::ROLE_MAHASISWA);
    }

    public function getAvatarUrl(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
    }

    public function getInitials(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';

        foreach (array_slice($names, 0, 2) as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }

        return $initials ?: 'U';
    }

    public function recordLogin()
    {
        $this->update(['last_login_at' => now()]);
    }
}
