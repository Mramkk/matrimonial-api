<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitedProfile extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne(User::class, 'muid', 'uid');
        // return $this->hasOne(User::class, 'uid', 'uid')->with('imgsm')->with('imgmd')->with('imglg')->withCount('img')->with('shortlist')->with('interest')->with('visited');
    }

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
