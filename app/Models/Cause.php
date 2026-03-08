<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cause extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status'
    ];
    
    public function photos()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }

    public function mainPhoto()
    {
        return $this->morphOne(Photo::class, 'imageable')->latest();
    }
    
    public function stories()
    {
        return $this->hasMany(Story::class);
    }
    
     public function projects()
    {
        return $this->hasMany(Project::class);
    }
    
}
