<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Part extends Model
{
    protected $fillable = [
        'sku',
        'shop_id',
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

public function specifications()
{
    return $this->belongsToMany(
        Specification::class,
        'part_fitments',
        'part_id',
        'specification_id' // Correct column
    )->withTimestamps();
}

    // public function variants()
    // {
    //     return $this->belongsToMany(
    //         Variant::class,
    //         'part_fitments',
    //         'part_id',
    //         'variant_id'
    //     )
    //     ->withPivot(['vehicle_model_id', 'start_year', 'end_year'])
    //     ->withTimestamps();
    // }

   public function vehicleModels()
{
    return $this->belongsToMany(
        VehicleModel::class,
        'part_fitments',
        'part_id',
        'vehicle_model_id'
    )
    ->withPivot(['specification_id', 'start_year', 'end_year']) // Fixed column name here
    ->withTimestamps();
}

    public function photos()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }

    public function substitutions()
{
    return $this->belongsToMany(
         Part::class,               // The related model
        'part_substitutions',      // Pivot table
        'part_id',                 // Foreign key on pivot for this part
        'substitution_part_id'     // Foreign key on pivot for the substitution
    );
}

public function substitutedFor()
{
    return $this->belongsToMany(
        Part::class,
        'part_substitutions',
        'substitution_part_id',
        'part_id'
    );
}

public function shop(): BelongsTo
{
    return $this->belongsTo(Shop::class);
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

public function scopeForSpecification($query, $specId)
{
    return $query->whereHas('specifications', function ($q) use ($specId) {
        $q->where('specifications.id', $specId);
    });
}

// Add this to your Part.php model
public function isAvailable($requestedQuantity = 1)
{
    return $this->status === 'active' && $this->stock_quantity >= $requestedQuantity;
}

protected static function booted()
{
    // 1. Logic to run BEFORE a part is created in the database
    static::creating(function ($part) {
        // Automatically assign the Shop ID for Sellers
        if (auth()->check() && auth()->user()->hasRole('seller')) {
            $part->shop_id = auth()->user()->shop->id;
        }

        // Auto-generate SKU if it wasn't manually entered
        if (empty($part->sku)) {
            $part->sku = self::generateSku(
                $part->partBrand->name ?? 'GEN', 
                $part->category->name ?? 'CAT', 
                $part->part_name ?? 'Part-name'
            );
        }
    });

    // 2. The Global Scope for Sellers
    static::addGlobalScope('sellerParts', function ($builder) {
        if (auth()->check() && auth()->user()->hasRole('seller')) {
            // Check if user has a shop to avoid null errors
            if (auth()->user()->shop) {
                $builder->where('shop_id', auth()->user()->shop->id);
            }
        }
    });
}

}
