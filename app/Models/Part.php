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

public function specifications()
{
    return $this->belongsToMany(
        Specification::class,
        'part_fitments',
        'part_id',
        'specification_id'
    )->withTimestamps();
}

 public function photos()
{
   return $this->hasMany(PartPhoto::class);
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

    public static function generateSku($brandName, $categoryName, $partName)
    {
        // 1. Create brand code (first 3 letters, uppercase)
        $brandCode = strtoupper(substr($brandName, 0, 3));

        // 2. Create category code (first 6 letters, uppercase, remove spaces)
        $categoryCode = strtoupper(substr(str_replace(' ', '', $categoryName), 0, 6));

        // 3. Create part code (first 10 letters of cleaned part name)
        $partCode = strtoupper(substr(str_replace(' ', '', $partName), 0, 10));

        // 4. Unique numeric suffix (padded to 5 digits)
        $lastId = self::max('id') + 1;
        $uniqueId = str_pad($lastId, 5, '0', STR_PAD_LEFT);

        return "$brandCode-$categoryCode-$partCode-$uniqueId";
    }
}
