<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Destination extends Model
{
    protected $fillable = ['region_name', 'region_code'];

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class);
    }

    public function destinations(): BelongsToMany
{
    return $this->belongsToMany(Destination::class, 'destination_variant');
} 

}
