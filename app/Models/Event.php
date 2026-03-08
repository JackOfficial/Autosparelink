<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'location',
        'date',
        'time',
        'link',
        'status',
    ];
    
    protected $casts = [
    'date' => 'date',   // now $event->date will be a Carbon instance
    'time' => 'datetime:H:i', // optional if you want time as Carbon
];
    
    protected static function boot()
{
    parent::boot();

    static::deleting(function ($event) {
        $event->event_photos()->delete();
    });
}

    public function organization() {
        return $this->belongsTo(Organization::class);
    }

    public function event_photos() {
        return $this->morphMany(Photo::class, 'imageable');
    }
}
