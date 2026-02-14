<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EngineDisplacement extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * Specifications that use this engine displacement
     */
    public function specifications()
    {
        return $this->hasMany(Specification::class);
    }
}
