<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'category',
        'location',
        'organizer',
        'description',
        'is_holiday',
        'extracurricular_blocked',
    ];

    protected $casts = [
        'is_holiday' => 'boolean',
        'extracurricular_blocked' => 'boolean',
    ];
}
