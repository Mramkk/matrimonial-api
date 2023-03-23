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
        return $this->hasMany(Img::class, 'uid', 'uid')->where('type', 'lg');
    }
    public function imgsm()
    {
        return $this->hasMany(Img::class, 'uid', 'uid')->where('type', 'sm')->where('active', '1');
    }
    public function imgmd()
    {
        return $this->hasMany(Img::class, 'uid', 'uid')->where('type', 'md')->where('active', '1');
    }
    public function imglg()
    {
        return $this->hasMany(Img::class, 'uid', 'uid')->where('type', 'lg')->where('active', '1');
    }

    public function shortlist()
    {
        return $this->hasMany(Shortlit::class, 'uid', 'uid');
    }
    public function interest()
    {
        return $this->hasMany(Interest::class, 'uid', 'uid');
    }
    public function visited()
    {
        return $this->hasMany(VisitedProfile::class, 'uid', 'uid');
    }
}
