<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Destination extends Model
{
    protected $fillable = ['name', 'code'];

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class);
    }
}
