<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Specification extends Model
{
    protected $table = 'specifications';

    protected $fillable = [
        'variant_id',
        'vehicle_model_id', 
        'body_type_id',
        'engine_type_id',
        'transmission_type_id',
        'drive_type_id',
        'production_start',   
        'production_end',
        'horsepower',
        'torque',
        'fuel_capacity',
        'seats',
        'doors',
        'fuel_efficiency',
        'steering_position',
        'color',
        'status',
    ];

    /* =======================
     | Relationships
     ======================= */

    // Belongs to a Variant
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function vehicleModel()
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function bodyType()
    {
        return $this->belongsTo(BodyType::class);
    }

    public function engineType()
    {
        return $this->belongsTo(EngineType::class);
    }

    public function transmissionType()
    {
        return $this->belongsTo(TransmissionType::class);
    }

    public function driveType()
    {
        return $this->belongsTo(DriveType::class);
    }

    public function engineDisplacement()
{
    return $this->belongsTo(EngineDisplacement::class, 'engine_displacement_id');
}

    /* =======================
     | Scopes (Optional)
     ======================= */

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    

    protected static function booted()
    {
        static::saving(function ($specification) {

            // Block empty specification
            if (
                empty($specification->vehicle_model_id) &&
                empty($specification->variant_id)
            ) {
                throw ValidationException::withMessages([
                    'vehicle_model_id' => 'Specification must have at least a Vehicle Model or a Variant.',
                    'variant_id' => 'Specification must have at least a Vehicle Model or a Variant.',
                ]);
            }
        });
    }
}
