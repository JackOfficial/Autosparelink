<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Part extends Model
{
    protected $fillable = [
        'sku',
        'part_number',
        'part_name',
        'category_id',
        'part_brand_id',
        'oem_number',
        'description',
        'price',
        'stock_quantity',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function partBrand()
    {
        return $this->belongsTo(PartBrand::class);
    }

    public function fitments()
    {
        return $this->hasMany(PartFitment::class);
    }

    public function variants()
    {
        return $this->belongsToMany(
            Variant::class,
            'part_fitments',
            'part_id',
            'variant_id'
        )
        ->withPivot(['vehicle_model_id', 'year_start', 'year_end'])
        ->withTimestamps();
    }

    public function vehicleModels()
    {
        return $this->belongsToMany(
            VehicleModel::class,
            'part_fitments',
            'part_id',
            'vehicle_model_id'
        )
        ->withPivot(['variant_id', 'year_start', 'year_end'])
        ->withTimestamps();
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }

     /**
     * Generate SKU for a part
     */
    public static function generateSku(string $brand, string $category, string $partName): string
    {
        return strtoupper(
            Str::slug(
                $brand . '-' . $category . '-' . $partName,
                '-'
            )
        );
    }
}
