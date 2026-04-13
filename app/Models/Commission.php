<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type',
        'shop_id',
        'rate',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the shop associated with a specific commission rule.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Helper to retrieve the applicable commission rate.
     * Logic: Specific Shop Rate > Global Rate > Default (10%)
     * * @param int|null $shopId
     * @return float
     */
    public static function getRateForShop(?int $shopId = null): float
    {
        // 1. Check if there is an active custom rate for this specific shop
        if ($shopId) {
            $shopRate = self::where('shop_id', $shopId)
                ->where('type', 'shop')
                ->where('is_active', true)
                ->value('rate');

            if ($shopRate !== null) {
                return (float) $shopRate;
            }
        }

        // 2. Fallback to the active global rate
        $globalRate = self::where('type', 'global')
            ->where('is_active', true)
            ->value('rate');

        if ($globalRate !== null) {
            return (float) $globalRate;
        }

        // 3. Absolute fallback if no rules are set in the database
        return 10.00;
    }
}