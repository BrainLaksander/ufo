<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'shortname', 'logo', 'banner', 'description', 'vision', 'mission',
        'email', 'phone', 'instagram', 'line_id', 'profile_status', 'profile_completion_percentage'
    ];

    protected $casts = [
        'mission' => 'json',
    ];

    public function members(): HasMany {
        return $this->hasMany(Member::class);
    }

    public function events(): HasMany {
        return $this->hasMany(Event::class);
    }

    public function submissions(): HasMany {
        return $this->hasMany(Submission::class);
    }

    public function reports(): HasMany {
        return $this->hasMany(Report::class);
    }

    public function tasks(): HasMany {
        return $this->hasMany(Task::class);
    }

    public function activityLogs(): HasMany {
        return $this->hasMany(ActivityLog::class);
    }

    public function getActiveMembers() {
        return $this->members()->where('status', 'aktif')->count();
    }

    public function getActiveEvents() {
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
