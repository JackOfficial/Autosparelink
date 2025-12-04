<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Volunteer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'dob',
        'email',
        'phone',
        'reason',
        'status',
    ];
    
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
    
    public function user()
{
    return $this->belongsTo(User::class);
}

}
