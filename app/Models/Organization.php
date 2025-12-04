<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
   use HasFactory, SoftDeletes;
   
   protected $fillable = [
        'name',
        'mission',
        'vision',
        'about',
        'email',
        'phone',
        'address',
        'website',
        'logo',
    ];

    public function projects() {
        return $this->hasMany(Projects::class);
    }

    public function events() {
        return $this->hasMany(Events::class);
    }

    public function stories() {
        return $this->hasMany(Stories::class);
    }

    public function teamMembers() {
        return $this->hasMany(Teams::class);
    }

    public function partners() {
        return $this->hasMany(Partners::class);
    }
    
     public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
