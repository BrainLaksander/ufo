<?php

namespace App\Models\Core;

use App\Models\Engagement\Announcement;
use App\Models\Engagement\ContactMessage;
use App\Models\Engagement\Event;
use App\Models\Engagement\LostFoundItem;
use App\Models\Workflow\Proposal;
use App\Models\Workflow\Report;
use App\Models\Workflow\Submission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function lostFoundItems(): HasMany
    {
        return $this->hasMany(LostFoundItem::class);
    }

    public function contactMessages(): HasMany
    {
        return $this->hasMany(ContactMessage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    public function getActiveMembers()
    {
        return $this->users()->count();
    }

    public function getActiveEvents()
    {
        return $this->events()->whereIn('status', ['published', 'ongoing'])->count();
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

    public function getCompletedEvents()
    {
        return $this->events()->where('status', 'selesai')->count();
    }

    public function getApprovedSubmissions()
    {
        return $this->submissions()->where('status', 'approved')->count();
    }

    public function getSubmittedReports()
    {
        return $this->reports()->whereIn('status', ['submitted', 'pending_review', 'accepted'])->count();
    }

    public function calculateProfileCompletion()
    {
        $score = 0;
        $total = 8;

        if ($this->logo) {
            $score++;
        }
        if ($this->banner) {
            $score++;
        }
        if ($this->description) {
            $score++;
        }
        if ($this->vision) {
            $score++;
        }
        if ($this->mission) {
            $score++;
        }
        if ($this->email) {
            $score++;
        }
        if ($this->phone) {
            $score++;
        }
        if ($this->members()->count() >= 3) {
            $score++;
        }

        $percentage = ($score / $total) * 100;
        $this->profile_completion_percentage = $percentage;
        $this->profile_status = $percentage >= 75 ? 'lengkap' : 'belum_lengkap';
        $this->save();

        return $percentage;
    }

    public function isProfileComplete(): bool
    {
        return !empty($this->logo) && !empty($this->description)
            && $this->members()->where('position', 'ketua')->exists()
            && !empty($this->email) && !empty($this->phone);
    }

    public function activeMembers(): int
    {
        return $this->members()->where('status', 'aktif')->count();
    }

    public function activeEvents(): int
    {
        return $this->events()
            ->where('status', 'approved')
            ->where('end_date', '>', now())
            ->count();
    }
}
