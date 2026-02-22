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
        /**
         * hasManyDeep Parameters:
         * 1. The destination model (Part)
         * 2. An array of intermediate models in order (Model -> Variant -> Specification)
         */
        return $this->hasManyDeep(
            Part::class, 
            [VehicleModel::class, Variant::class, Specification::class]
        );
    }
}
