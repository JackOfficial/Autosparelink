<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
        'sku',             // Internal SKU
        'part_number',     // Manufacturer/Supplier part number
        'part_name',
        'category_id',
        'part_brand_id',   // References PartBrand
        'oem_number',      // Optional OEM number
        'description',
        'price',
        'stock_quantity',
        'status',
        'photo',
    ];

    /**
     * Get the category of the part.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    /**
     * Get the brand of the part.
     */
    public function partBrand()
    {
        return $this->belongsTo(PartBrand::class);
    }

    public function fitment()
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
        )->withPivot(['vehicle_model_id', 'status', 'year_start', 'year_end'])
         ->withTimestamps();
    }
}
