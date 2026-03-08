<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Staudenmeir\EloquentHasManyDeep\HasRelationships; // Import the trait

class Brand extends Model
{
    use HasRelationships;

    protected $fillable = [
        'brand_name',
        'slug',
        'brand_logo',
        'description',
        'country',
        'website',
    ];

     public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function vehicleModels()
    {
        return $this->hasMany(VehicleModel::class, 'brand_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

 public function parts()
{
    return $this->hasManyDeep(
        Part::class,
        [
            VehicleModel::class, 
            Variant::class, 
            Specification::class, 
            'part_fitments' // Add the pivot table here
        ],
        [
            'brand_id',         // Foreign key on vehicle_models
            'vehicle_model_id', // Foreign key on variants
            'variant_id',       // Foreign key on specifications
            'specification_id', // Foreign key on part_fitments (links to spec)
            'id'                // Local key on parts (matched to part_fitments.part_id)
        ],
        [
            'id',               // Local key on brands
            'id',               // Local key on vehicle_models
            'id',               // Local key on variants
            'id',               // Local key on specifications
            'part_id'           // Foreign key on part_fitments (links to part)
        ]
    );
}
}
