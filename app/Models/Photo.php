<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Photo extends Model
{
    use HasFactory, SoftDeletes;
    
    // Add imageable_id and imageable_type to allow mass assignment
    protected $fillable = [
        'file_path', 
        'caption', 
        'imageable_id', 
        'imageable_type'
    ]; 

    /**
     * Get the parent imageable model (ClientVehicle, User, etc.).
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}