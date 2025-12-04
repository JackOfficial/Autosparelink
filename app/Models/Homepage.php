<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homepage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'header', 'caption', 'link', 'status'
    ];

    public function photos()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }
}
