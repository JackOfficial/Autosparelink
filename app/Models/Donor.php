<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donor extends Model
{
     use HasFactory, SoftDeletes;
     
     protected static function boot()
{
    parent::boot();

    static::deleting(function ($donor) {
        $donor->donations()->delete();
    });
}


     public function donations() {
        return $this->hasMany(Donation::class);
    }
    
    public function photos()
{
    return $this->morphMany(Photo::class, 'imageable');
}

public function documents()
{
    return $this->morphMany(Document::class, 'documentable');
}
    
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
