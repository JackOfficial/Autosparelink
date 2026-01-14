<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartFitment extends Model
{
    protected $fillable = [
        'part_id',
        'variant_id',
        'vehicle_model_id',
        'status',
        'year_start',
        'year_end',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function vehicleModel()
{
    return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
}

    // The variant specification this fitment belongs to
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

}
