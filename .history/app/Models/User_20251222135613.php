<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Role constants
    public const ROLE_ADMIN = 'admin';
    public const ROLE_KEMAHASISWAAN = 'kemahasiswaan';
    public const ROLE_PENGURUS = 'pengurus';
    public const ROLE_MAHASISWA = 'mahasiswa';

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isKemahasiswaan(): bool
    {
        return $this->role === self::ROLE_KEMAHASISWAAN;
    }

    public function isPengurus(): bool
    {
        return $this->role === self::ROLE_PENGURUS;
    }
}
