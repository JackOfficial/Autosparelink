<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'career_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'country_id',
        'level_of_education',
        'field_of_study',
        'notice_period',
        'desired_salary',
        'resume',
        'cover_letter',
        'status',
    ];
    
    public function career(){
        return $this->belongsTo(Career::class);
    }
    
    public function volunteer(){
        return $this->belongsTo(Volunteer::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
}
