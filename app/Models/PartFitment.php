<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartFitment extends Model
{
    protected $fillable = [
        'part_id',
        'specification_id',
        'status',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function vehicleModel()
{
    return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
}

public function specification()
    {
        return $this->belongsTo(Specification::class);
    }

    // The variant specification this fitment belongs to
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

}
