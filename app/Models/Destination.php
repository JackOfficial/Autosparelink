<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Destination extends Model
{
    protected $fillable = ['region_name', 'region_code'];

    /**
     * If you want to keep the link to Variant, you can, 
     * but based on our discussion, Specification is the better home.
     */
    public function specifications(): BelongsToMany
    {
        // This links the Destination back to the technical specs table
        return $this->belongsToMany(Specification::class, 'destination_specification');
    }
}