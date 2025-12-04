<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
    'organization_id',
    'name',
    'position',
    'email',
    'phone',
    'bio',
    'facebook',
    'linkedin',
    'twitter',
    'status',
];

    public function organization() {
        return $this->belongsTo(Organization::class);
    }
    
    public function photos() {
        return $this->morphMany(Photo::class, 'imageable');
    }
    
     public function profilePhoto()
    {
        return $this->morphOne(Photo::class, 'imageable');
    }
    
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
