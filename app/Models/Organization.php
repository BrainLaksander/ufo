<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Organization Model
 * Mewakili organisasi/divisi kampus
 * 
 * Relationship:
 * - Memiliki banyak event
 * - Memiliki banyak announcement
 * - Memiliki banyak proposal
 * - Memiliki banyak lost & found items
 * - Dipimpin oleh leader (user)
 */
class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'email',
        'phone',
        'location',
        'established_date',
        'status',
        'leader_id',
    ];

    protected $casts = [
        'established_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Ketua/Leader organisasi
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Anggota organisasi
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Event organisasi
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Pengumuman organisasi
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    /**
     * Pengajuan/Proposal organisasi
     */
    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    /**
     * Lost & Found items
     */
    public function lostFoundItems(): HasMany
    {
        return $this->hasMany(LostFoundItem::class);
    }

    /**
     * Contact messages
     */
    public function contactMessages(): HasMany
    {
        return $this->hasMany(ContactMessage::class);
    }

    // ========== SCOPES ==========

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    // ========== HELPER METHODS ==========

    public function getActiveMembers()
    {
        return $this->users()->count();
    }

    public function getActiveEvents()
    {
        return $this->events()
            ->whereIn('status', ['published', 'ongoing'])
            ->count();
    }

    public function getUpcomingEvents()
    {
        return $this->events()
            ->where('status', 'published')
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->limit(5)
            ->get();
    }

    public function getRecentAnnouncements()
    {
        return $this->announcements()
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function getPendingProposals()
    {
        return $this->proposals()
            ->whereIn('status', ['submitted', 'under_review'])
            ->count();
    }
}
        return $this->events()->whereIn('status', ['approved', 'berjalan'])->count();
    }

    public function getCompletedEvents() {
        return $this->events()->where('status', 'selesai')->count();
    }

    public function getApprovedSubmissions() {
        return $this->submissions()->where('status', 'approved')->count();
    }

    public function getSubmittedReports() {
        return $this->reports()->whereIn('status', ['submitted', 'pending_review', 'accepted'])->count();
    }

    public function calculateProfileCompletion() {
        $score = 0;
        $total = 8;

        if ($this->logo) $score++;
        if ($this->banner) $score++;
        if ($this->description) $score++;
        if ($this->vision) $score++;
        if ($this->mission) $score++;
        if ($this->email) $score++;
        if ($this->phone) $score++;
        if ($this->members()->count() >= 3) $score++;

        $percentage = ($score / $total) * 100;
        $this->profile_completion_percentage = $percentage;
        $this->profile_status = $percentage >= 75 ? 'lengkap' : 'belum_lengkap';
        $this->save();

        return $percentage;
    }

    // Helper: apakah profil dianggap lengkap
    public function isProfileComplete(): bool
    {
        return !empty($this->logo) && !empty($this->description)
            && $this->members()->where('position', 'ketua')->exists()
            && !empty($this->email) && !empty($this->phone);
    }

    // Helper: Count anggota aktif
    public function activeMembers(): int
    {
        return $this->members()->where('status', 'aktif')->count();
    }

    // Helper: Count event aktif
    public function activeEvents(): int
    {
        return $this->events()
            ->where('status', 'approved')
            ->where('end_date', '>', now())
            ->count();
    }

}
