<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
     protected $fillable = [
        'brand_id',
        'model_name',
        'description',
        'production_start_year',
        'production_end_year',
        'photo',
        'status',
    ];

    // Relationships
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
