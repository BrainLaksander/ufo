<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{

    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    // Helper: Cek apakah event sedang berlangsung
    public function isOngoing(): bool
    {
        return $this->status === 'ongoing' || 
               (now()->between($this->start_date, $this->end_date) && $this->status === 'approved');
    }

    // Helper: Cek apakah event sudah selesai
    public function isCompleted(): bool
    {
        return now()->isAfter($this->end_date);
    }

    // Helper: Status badge
    public function getStatusBadge(): string
    {
        if ($this->isOngoing()) return 'Berjalan';
        if ($this->isCompleted()) return 'Selesai';
        if ($this->status === 'approved') return 'Akan Datang';
        return ucfirst($this->status);
    }
}
