<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'user_id',
        'submission_id',
        'title',
        'category',
        'poster_path',
        'description',
        'start_at',
        'end_at',
        'location',
        'registration_link',
        'participants',
        'status',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(\App\Models\ActivitySubmission::class);
    }

    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class);
    }
}
