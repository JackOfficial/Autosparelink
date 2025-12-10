<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
   protected $fillable = [
    'name',
    'vehicle_model_id',
    'body_type_id',
    'engine_type_id',
    'transmission_type_id',
    'chassis_code',
    'model_code',
    'fuel_capacity',
    'seats',
    'doors',
    'drive_type',
    'steering_position',
    'trim_level',
    'color',
    'horsepower',
    'torque',
    'fuel_efficiency',
    'production_start',
    'production_end',
    'photo',
    'status'
];


    // Relationships
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
}
