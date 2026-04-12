<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientVehicle extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'brand_id',
        'vehicle_model_id',
        'production_start',
        'trim_level',
        'body_type_id',
        'engine_type_id',
        'transmission_type_id',
        'displacement',
        'steering_position',
        'vin',
        'is_primary',
    ];

    /**
     * The attributes that should be cast.
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

    public function photo()
{
    return $this->morphOne(Photo::class, 'imageable');
}

    /**
     * Get the brand of the vehicle.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the specific model of the vehicle.
     */
    public function vehicleModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    }

    /**
     * Get the body type (SUV, Sedan, etc).
     */
    public function bodyType(): BelongsTo
    {
        return $this->belongsTo(BodyType::class);
    }

    /**
     * Get the engine/fuel type (Petrol, Diesel, etc).
     */
    public function engineType(): BelongsTo
    {
        return $this->belongsTo(EngineType::class);
    }
}