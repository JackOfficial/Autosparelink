<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
     protected $fillable = [
        'brand_id',
        'model_name',
        'has_variants',
        'description',
        'production_start_year',
        'production_end_year',
        'status',
    ];

    public function fitments()
{
    return $this->hasMany(PartFitment::class);
}

public function specifications()
{
    return $this->belongsToMany(
        Specification::class,
        'part_fitments',
        'part_id',
        'specification_id'
    )->withTimestamps();
}

    // Relationships
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function photos() {
    return $this->morphMany(Photo::class, 'imageable');
    }

    public function mainPhoto()
{
    return $this->morphOne(Photo::class, 'imageable')->latestOfMany();
}

     public function spec()
    {
        return $this->hasMany(Specification::class)
                    ->whereNull('variant_id'); // only specs directly for model
    }
}
