<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Story extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'title', 'slug', 'organization_id', 'user_id',
        'cover_photo', 'summary', 'content', 'status'
    ]; 
    
    protected static function boot()
{
    parent::boot();

    static::deleting(function ($story) {
        $story->photos()->delete();
    });
}
    
     public function cause()
    {
        return $this->belongsTo(Cause::class, 'cause_id');
    }

    public function organization() {
        return $this->belongsTo(Organization::class);
    }

    public function photos() {
        return $this->morphMany(Photo::class, 'imageable');
    }
    
     public function photo()
    {
        return $this->morphOne(Photo::class, 'imageable');
    }

    public function user() {
        return $this->belongsTo(User::class); // admin who posted the story
    }
    
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
