<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * User Model
 * Mewakili pengguna sistem dengan role-based access
 * 
 * Roles:
 * - admin: Mengelola keseluruhan sistem
 * - pengurus: Mengelola organisasi spesifik
 * - mahasiswa: Pengguna regular
 */
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

    // ========== ROLE CONSTANTS ==========

    public const ROLE_ADMIN = 'admin';
    public const ROLE_PENGURUS = 'pengurus';
    public const ROLE_MAHASISWA = 'mahasiswa';

    // ========== RELATIONSHIPS ==========

    /**
     * Organisasi yang menjadi anggota/pengurus
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Organisasi yang dipimpin (jika leader)
     */
    public function leaderOrganizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'leader_id');
    }

    /**
     * Event yang di-create
     */
    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    /**
     * Announcement yang di-create
     */
    public function createdAnnouncements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    /**
     * Announcement yang di-approve
     */
    public function approvedAnnouncements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'approved_by');
    }

    /**
     * Proposal yang di-submit
     */
    public function submittedProposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'submitted_by');
    }

    /**
     * Proposal yang di-review
     */
    public function reviewedProposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'reviewed_by');
    }

    /**
     * Lost & Found items yang di-report
     */
    public function reportedLostItems(): HasMany
    {
        return $this->hasMany(LostFoundItem::class, 'reported_by');
    }

    /**
     * Lost & Found items yang di-claim
     */
    public function claimedLostItems(): HasMany
    {
        return $this->hasMany(LostFoundItem::class, 'claimed_by');
    }

    /**
     * Contact messages yang di-reply
     */
    public function repliedMessages(): HasMany
    {
        return $this->hasMany(ContactMessage::class, 'replied_by');
    }

    // ========== ROLE HELPERS ==========

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
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
        return in_array($this->role, $roles);
    }

    // ========== AUTHORIZATION ==========

    public function can($ability, $arguments = [])
    {
        // Role-based permissions
        if ($this->isAdmin()) {
            return true; // Admin bisa semua
        }

        return parent::can($ability, $arguments);
    }

    // ========== SCOPES ==========

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    public function scopePengurus($query)
    {
        return $query->where('role', self::ROLE_PENGURUS);
    }

    public function scopeMahasiswa($query)
    {
        return $query->where('role', self::ROLE_MAHASISWA);
    }

    // ========== HELPER METHODS ==========

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
