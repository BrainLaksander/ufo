<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'abbreviation', 'kategori','field','level','description',
        'motto','visi','misi','budaya_nilai','program_kegiatan',
        'instagram','whatsapp','website','member_count',
        'is_open_recruitment','recruitment_link','recruitment_req',
        'logo_path','banner_path',
        'ketua_name','chair_phone','chair_email','chair_photo',
        'secretary_name','secretary_phone','secretary_email','secretary_photo',
        'treasurer_name','treasurer_phone','treasurer_email','treasurer_photo',
        'advisor_name','advisor_phone','advisor_email','advisor_photo',
        'status','account_user_id','account_email'
    ];

    public function accountUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'account_user_id');
    }
}
