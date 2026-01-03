<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartFitment extends Model
{
    protected $fillable = [
        'part_id',
        'variant_specification_id',
        'status',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    // public function model()
    // {
    //     return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    // }

    // public function variant()
    // {
    //     return $this->belongsTo(Variant::class);
    // }

    
    // The variant specification this fitment belongs to
    public function Specification()
    {
        return $this->belongsTo(Specification::class, 'specification_id');
    }

   public function photos()
    {
        return $this->hasMany(PartPhoto::class, 'part_id', 'part_id');
    }

}
