<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartFitment extends Model
{
    protected $fillable = [
        'part_id',
        'vehicle_model_id',
        'variant_id',
        'status',
        'year_start',
        'year_end',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

   public function photos()
    {
        return $this->hasMany(PartPhoto::class, 'part_id', 'part_id');
    }

}
