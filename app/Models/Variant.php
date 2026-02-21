<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Variant extends Model
{
  protected $fillable = [
        'vehicle_model_id',
        'name',
        'slug',
        'production_year',
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

    public function destinations(): BelongsToMany
    {
       return $this->belongsToMany(Destination::class);
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
    return $this->name;
    // $spec = $this->activeSpecifications()->first();

    // if (!$spec) {
    //     return $this->name;
    // }

    // $parts = [$this->name];

    // // Production Year
    // if ($spec->production_year) {
    //     $parts[] = '(' . $spec->production_year . ')';
    // }

    // // Body Type
    // if ($spec->bodyType && !str_contains($this->name, $spec->bodyType->name)) {
    //     $parts[] = $spec->bodyType->name;
    // }

    // // Engine Displacement
    // if ($spec->engineDisplacement && !str_contains($this->name, $spec->engineDisplacement->name)) {
    //     $parts[] = $spec->engineDisplacement->name;
    // }

    // // Engine Type
    // if ($spec->engineType && !str_contains($this->name, $spec->engineType->name)) {
    //     $parts[] = $spec->engineType->name;
    // }

    // // Transmission
    // if ($spec->transmissionType && !str_contains($this->name, $spec->transmissionType->name)) {
    //     $parts[] = $spec->transmissionType->name;
    // }

    // // Drive Type (NEW)
    // if ($spec->driveType && !str_contains($this->name, $spec->driveType->name)) {
    //     $parts[] = $spec->driveType->name; // FWD / AWD / RWD
    // }

    // return implode(' ', $parts);
}

// app/Models/Variant.php

public function syncNameFromSpec()
{
    $spec = $this->specifications()->with([
        'bodyType', 
        'engineType', 
        'transmissionType', 
        'engineDisplacement'
    ])->first();

    if (!$spec) return;

    $model = $this->vehicleModel;
    
    $pieces = [
        $model?->brand?->brand_name,
        $model?->model_name,
        $this->trim_level,
        $spec->bodyType?->name,
        $this->production_year, // CHANGED: now pulls from the Variant itself
        $spec->engineDisplacement ? $spec->engineDisplacement->name : null,
        $spec->engineType?->name,
        $spec->transmissionType?->name,
    ];

    $generatedName = implode(' ', array_filter($pieces));

    $this->update([
        'name' => $generatedName,
        'slug' => Str::slug($generatedName . '-' . ($this->chassis_code ?? Str::random(4))),
    ]);
}

}
