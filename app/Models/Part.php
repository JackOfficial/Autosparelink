<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
        'price',            // The Base Price set by the Shop
        'old_price',        // Base discount price set by shop
        'unit_price',       // The Markup Price shown to customers
        'old_unit_price',   // Markup discount price for customers
        'applied_rate',     // The commission rate at time of save
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
            'specification_id'
        )->withTimestamps();
    }

    public function vehicleModels()
    {
        return $this->belongsToMany(
            VehicleModel::class,
            'part_fitments',
            'part_id',
            'vehicle_model_id'
        )
        ->withPivot(['specification_id', 'start_year', 'end_year'])
        ->withTimestamps();
    }

    public function photos() 
    {
        return $this->morphMany(Photo::class, 'imageable');
    }

    public function substitutions()
    {
        return $this->belongsToMany(
             Part::class,
            'part_substitutions',
            'part_id',
            'substitution_part_id'
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

    public function reviews(): MorphMany
{
    return $this->morphMany(Review::class, 'reviewable')->where('status', 'approved');
}

public function getAverageRatingAttribute(): float
{
    return (float) $this->reviews()->avg('rating') ?: 0.0;
}

    public function isAvailable($requestedQuantity = 1)
    {
        return $this->status === 'active' && $this->stock_quantity >= $requestedQuantity;
    }
    
    public function scopeForCurrentSeller(Builder $query): Builder
{
    $user = auth()->user();

    if (!$user) {
        return $query;
    }

    // If they are an admin OR super-admin but are actively browsing the Shop Panel,
    // restrict them specifically to their shop's inventory.
    if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
        if (request()->is('shop*') && $user->shop) {
            return $query->where('shop_id', $user->shop->id);
        }
        
        return $query; // Otherwise, let them see everything in the main Admin Panel
    }

    // Normal seller fallback
    if ($user->hasRole('seller') && $user->shop) {
        return $query->where('shop_id', $user->shop->id);
    }

    return $query;
}

    public function getUnitPriceAttribute(): float
    {
        return Commission::calculateMarkup((float) $this->price);
    }

    public function getOldUnitPriceAttribute(): ?float
    {
        if (empty($this->old_price)) {
            return null;
        }

        return Commission::calculateMarkup((float) $this->old_price);
    }

    public function getAppliedRateAttribute(): float
    {
        return Commission::getRate();
    }

    protected static function booted()
    {
        static::saving(function ($part) {

        // Assign Shop ID for anyone using the shop panel who has an attached shop
    if (auth()->check() && empty($part->shop_id)) {
        $user = auth()->user();
        if (($user->hasRole('seller') || $user->hasRole('admin')) && $user->shop) {
            $part->shop_id = $user->shop->id;
        }
        }
            // 1. Assign Shop ID for Sellers
            // if (auth()->check() && auth()->user()->hasRole('seller') && empty($part->shop_id)) {
            //     $part->shop_id = auth()->user()->shop->id;
            // }

            // 3. SKU Generation
            if ($part->isDirty(['part_name', 'part_brand_id', 'category_id']) || empty($part->sku)) {
                $brandName = $part->partBrand ? $part->partBrand->name : null;
                $categoryName = $part->category ? $part->category->category_name : null;

                $part->sku = self::generateSku($brandName, $categoryName, $part->part_name);
            }
        });
    }
}