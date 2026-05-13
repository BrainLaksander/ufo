<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivitySubmission extends Model
{
    use HasFactory;

    protected $table = 'activity_submissions';

    protected $fillable = [
        'organization_id',
        'user_id',
        'event_id',
        'title',
        'jenis_kegiatan',
        'penanggung_jawab',
        'poster_path',
        'proposal_path',
        'lpj_path',
        'lpj_catatan',
        'subtitle',
        'description',
        'registration_link',
        'event_date',
        'waktu',
        'lokasi',
        'estimasi_peserta',
        'kind',
        'status',
        'revision_note',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class);
    }
}
