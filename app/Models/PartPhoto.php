<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartPhoto extends Model
{
    protected $fillable = [
        'part_id',
        'photo_url',
        'type'
    ];

     public function partFitments()
    {
        return $this->hasMany(PartFitment::class, 'part_id', 'part_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
