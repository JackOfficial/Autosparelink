<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DriveType extends Model
{
   protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the specifications associated with this drive type.
     */
    public function specifications(): HasMany
    {
        // Assuming your foreign key in 'specifications' table is 'drive_type_id'
        return $this->hasMany(Specification::class, 'drive_type_id');
    }
}
