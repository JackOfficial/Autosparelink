<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'cause_id',
        'title',
        'slug',
        'summary',
        'description',
        'photo',
        'goal',
        'beneficiaries',
        'budget',
        'start_date',
        'end_date',
        'progress',
        'status',
    ];
    
    protected $casts = [
    'start_date' => 'datetime',
    'end_date' => 'datetime',
];
    
       /**
     * Boot method to handle cascading soft deletes
     */
     
   protected static function boot()
{
    parent::boot();

    static::deleting(function ($project) {
        $project->project_photos()->delete();
        $project->project_photo()->delete();
        $project->documents()->delete();
        $project->donations()->delete(); // if you want donations soft-deleted too
    });
}

    public function organization() {
        return $this->belongsTo(Organization::class);
    }

    public function project_photos() {
        return $this->morphMany(Photo::class, 'imageable');
    }
    
    public function project_photo()
    {
        return $this->morphOne(Photo::class, 'imageable');
    }
    
    public function documents()
{
    return $this->morphMany(Document::class, 'documentable');
}

    public function donations() {
        return $this->hasMany(Donation::class);
    }
    
     public function cause() {
        return $this->belongsTo(Cause::class);
    }
}
