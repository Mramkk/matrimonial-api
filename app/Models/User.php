<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uid',
        'bhid',
        'name',
        'phone',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function img()
    {
        return $this->hasMany(Img::class, 'uid', 'uid')->orderBy('active', 'DESC');;
    }

    public function imglg()
    {
        return $this->hasMany(Img::class, 'uid', 'uid')->where('type', 'lg');
    }

    public function shortlist()
    {
        return $this->hasMany(Shortlit::class, 'uid', 'uid');
    }
    public function interest()
    {
        return $this->hasMany(Interest::class, 'uid', 'uid');
    }
}
