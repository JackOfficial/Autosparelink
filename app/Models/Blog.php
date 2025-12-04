<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'cause_id',
        'title',
        'slug',
        'photo',
        'content',
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function cause(){
        return $this->belongsTo(Cause::class);
    }
    
    public function blogPhoto()
{
    return $this->morphOne(Photo::class, 'imageable');
}
    
    public function likes()
{
    return $this->hasMany(Like::class);
}

public function comments(){
    return $this->hasMany(Comment::class);
}
}
