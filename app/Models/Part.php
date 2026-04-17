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
        static::creating(function ($part) {
            // 1. Automatically assign Shop ID for Sellers
            if (auth()->check() && auth()->user()->hasRole('seller') && empty($part->shop_id)) {
                $part->shop_id = auth()->user()->shop->id;
            }

            // 2. Auto-generate SKU if empty
            if (empty($part->sku)) {
                // Note: Relationships might be null here if not pre-loaded.
                // We use a fallback or random string to ensure uniqueness.
                $part->sku = self::generateSku(
                    $part->partBrand->name ?? null, 
                    $part->category->category_name ?? null, 
                    $part->part_name
                );
            }
        });
    }

}
