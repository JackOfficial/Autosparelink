<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Commission extends Model
{
    /**
     * The attributes that are mass assignable.
     * Logic: Simplified to a global rate model for the entire app.
     */
    protected $fillable = [
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
     * Helper to retrieve the active global commission rate.
     * 
     * We've added caching here because this method is called frequently
     * (every time a part is added or an order is processed).
     * 
     * @return float
     */
    public static function getRate(): float
    {
        // Cache the rate for 24 hours (86400 seconds)
        // This is cleared automatically by the CommissionController when updated.
        return Cache::remember('active_commission_rate', 86400, function () {
            $rate = self::where('is_active', true)
                ->latest()
                ->value('rate');

            // Fallback to 10% if no active rate exists
            return $rate !== null ? (float) $rate : 10.00;
        });
    }

    /**
     * Helper to calculate the markup price based on a base price.
     * Useful for UI displays or Part model logic.
     */
    public static function calculateMarkup(float $basePrice): float
    {
        $rate = self::getRate();
        return round($basePrice + ($basePrice * ($rate / 100)), 2);
    }
}