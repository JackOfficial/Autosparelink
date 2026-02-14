<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
  protected $fillable = [
        'vehicle_model_id',
        'name',
        'slug',
        'chassis_code',
        'model_code',
        'trim_level',
        'is_default',
        'status',
    ];

    /* =======================
     | Relationships
     ======================= */

    // Variant belongs to a vehicle model
    public function vehicleModel()
    {
        return $this->belongsTo(VehicleModel::class);
    }

    // Variant has many specifications (year / engine / transmission etc.)
    public function specifications()
    {
        return $this->hasMany(Specification::class);
    }

    // Optional: active specifications only
    public function activeSpecifications()
    {
        return $this->hasMany(Specification::class)
                    ->where('status', 1);
    }

    // Parts fitted to this variant
    public function parts()
    {
        return $this->belongsToMany(
            Part::class,
            'part_fitments',
            'variant_id',
            'part_id'
        )->withPivot([
            'vehicle_model_id',
            'status',
            'year_start',
            'year_end',
        ])->withTimestamps();
    }

    public function fitments()
    {
        return $this->hasMany(PartFitment::class);
    }

    public function photos() {
    return $this->morphMany(Photo::class, 'imageable');
    }

    public function getFullNameAttribute()
{
    $parts = [];

    // Variant trim / name
    if ($this->trim_level) {
        $parts[] = $this->trim_level; // e.g., LS, SE, XLE
    } elseif ($this->name) {
        $parts[] = $this->name;
    }

    // Pull first active specification
    $spec = $this->activeSpecifications()->with(['engineType', 'transmissionType', 'driveType'])->first();

    if ($spec) {
        if ($spec->engineType) $parts[] = $spec->engineType->name;      // 1.8L, 2.0L
        if ($spec->fuel_type) $parts[] = $spec->fuel_type ?? '';        // Hybrid, Gasoline
        if ($spec->transmissionType) $parts[] = $spec->transmissionType->name; // Automatic, Manual
    }

    return implode(' ', array_filter($parts));
}

}
