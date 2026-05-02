<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
    'category_name',
    'photo',
    'parent_id',
    'shipping_price',
    ];

     // Parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Child categories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

      public function parts()
    {
        return $this->hasMany(Part::class);
    }

    /**
     * Get the accurate shipping price for the category.
     * Checks the subcategory first, then falls back to the parent category.
     */
    public function getEffectiveShippingPriceAttribute()
    {
        // 1. If this subcategory has a custom shipping price, return it
        if ($this->shipping_price > 0) {
            return $this->shipping_price;
        }

        // 2. If it's a subcategory but has no price, check its parent
        if ($this->parent_id && $this->parent) {
            return $this->parent->shipping_price ?? 0;
        }

        // 3. Fallback to 0 if nothing is set anywhere
        return 0;
    }
}
