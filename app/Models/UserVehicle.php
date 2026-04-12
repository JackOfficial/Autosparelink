<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVehicle extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'brand_id',
        'vehicle_model_id',
        'variant_id',
        'body_type_id',
        'engine_type_id',
        'transmission_type_id',
        'drive_type_id',
        'production_start',
        'horsepower',
        'torque',
        'displacement',
        'steering_position',
        'color',
        'vin',
        'is_primary'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'production_start' => 'integer',
    ];

    /**
     * Get the user that owns the vehicle.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the brand associated with the vehicle.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the model associated with the vehicle.
     */
    public function vehicleModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    }

    /**
     * Get the specific trim/variant.
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    /**
     * Get the body type (Sedan, SUV, etc.).
     */
    public function bodyType(): BelongsTo
    {
        return $this->belongsTo(BodyType::class, 'body_type_id');
    }

    /**
     * Get the engine type (Petrol, Diesel, etc.).
     */
    public function engineType(): BelongsTo
    {
        return $this->belongsTo(EngineType::class, 'engine_type_id');
    }

    /**
     * Get the transmission type.
     */
    public function transmissionType(): BelongsTo
    {
        return $this->belongsTo(TransmissionType::class, 'transmission_type_id');
    }

    /**
     * Get the drive type (AWD, RWD, etc.).
     */
    public function driveType(): BelongsTo
    {
        return $this->belongsTo(DriveType::class, 'drive_type_id');
    }
}