<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Part extends Model
{
    protected $fillable = [
        'sku',
        'shop_id',
        'part_state_id',
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
    
    public function state()
    {
        return $this->belongsTo(PartState::class, 'part_state_id');
    }

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
    public static function generateSku(?string $brand, ?string $category, string $partName): string
    {
        $brand = $brand ?? 'GEN';
        $category = $category ?? 'CAT';
        
        return strtoupper(
            Str::slug($brand . '-' . $category . '-' . $partName . '-' . Str::random(4), '-')
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

/**
     * Updated Scope: Smart filtering for Sellers vs Admins
     */
    public function scopeForCurrentSeller(Builder $query): Builder
    {
        $user = auth()->user();

        // 1. If guest, show everything (Public Pages)
        if (!$user) {
            return $query;
        }

        // 2. If Admin/Super-Admin, bypass the filter (Dashboard Management)
        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            return $query;
        }

        // 3. If Seller, strictly filter by their shop ID
        if ($user->hasRole('seller') && $user->shop) {
            return $query->where('shop_id', $user->shop->id);
        }

        return $query;
    }

    protected static function booted()
{
    static::saving(function ($part) {
        // 1. Automatically assign Shop ID for Sellers (only on creation)
        if (auth()->check() && auth()->user()->hasRole('seller') && empty($part->shop_id)) {
            $part->shop_id = auth()->user()->shop->id;
        }

        // 2. Sync/Generate SKU
        // We check if the name, brand, or category changed, or if the SKU is empty
        if ($part->isDirty(['part_name', 'part_brand_id', 'category_id']) || empty($part->sku)) {
            
            // Load relationships if they aren't loaded to get the names for the SKU
            $brandName = $part->partBrand ? $part->partBrand->name : null;
            $categoryName = $part->category ? $part->category->category_name : null;

            $part->sku = self::generateSku(
                $brandName, 
                $categoryName, 
                $part->part_name
            );
        }
    });
}

}
