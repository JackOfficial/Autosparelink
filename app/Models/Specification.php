<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    protected $table = 'specifications';

    protected $fillable = [
        'variant_id',
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

    /* =======================
     | Scopes (Optional)
     ======================= */

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function parts()
    {
    return $this->belongsToMany(
        Part::class,
        'part_fitments',
        'specification_id',
        'part_id'
     )->withTimestamps();
    }
}
