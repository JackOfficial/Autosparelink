<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Career extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_type_id',
        'title',
        'description',
        'qualification',
        'deadline',
        'status',
    ];
    
    public function applications(){
        return $this->hasMany(Application::class);
    }

    public function jobType()
    {
        return $this->belongsTo(JobType::class);
    }

    public function getCreatedAtAttribute($date){
    return Carbon::parse($date)->format('M d, Y');
    }

    public function getUpdatedAtAttribute($date){
    return Carbon::parse($date)->format('M d, Y');
    }

    public function getDeadlineAttribute($date){
        return Carbon::parse($date)->format('M d, Y');
    }
}
