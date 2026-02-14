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
    $spec = $this->activeSpecifications()->first();

    if (!$spec) {
        return $this->name;
    }

    $parts = [$this->name];

    // Production Year
    if ($spec->production_year) {
        $parts[] = '(' . $spec->production_year . ')';
    }

    // Body Type
    if ($spec->bodyType && !str_contains($this->name, $spec->bodyType->name)) {
        $parts[] = $spec->bodyType->name;
    }

    // Engine Displacement
    if ($spec->engineDisplacement && !str_contains($this->name, $spec->engineDisplacement->name)) {
        $parts[] = $spec->engineDisplacement->name;
    }

    // Engine Type
    if ($spec->engineType && !str_contains($this->name, $spec->engineType->name)) {
        $parts[] = $spec->engineType->name;
    }

    // Transmission
    if ($spec->transmissionType && !str_contains($this->name, $spec->transmissionType->name)) {
        $parts[] = $spec->transmissionType->name;
    }

    // Drive Type (NEW)
    if ($spec->driveType && !str_contains($this->name, $spec->driveType->name)) {
        $parts[] = $spec->driveType->name; // FWD / AWD / RWD
    }

    return implode(' ', $parts);
}

}
