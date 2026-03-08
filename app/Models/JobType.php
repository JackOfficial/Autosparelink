<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'status'];

    public function careers()
    {
        return $this->hasMany(Career::class);
    }
}
