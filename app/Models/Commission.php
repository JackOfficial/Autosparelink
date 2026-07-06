<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Commission extends Model
{
    protected $fillable = [
        'rate',
        'description',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     * Automatically clears the commission cache whenever a record is saved or deleted.
     */
    protected static function booted(): void
    {
        $clearCache = function () {
            Cache::forget('active_commission_rate');
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }

    /**
     * Helper to retrieve the active global commission rate.
     */
    public static function getRate(): float
    {
        // Cache the rate for 24 hours. Clear events handle updates automatically now.
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
     */
    public static function calculateMarkup(float $basePrice): float
    {
        $rate = self::getRate();
        return round($basePrice + ($basePrice * ($rate / 100)), 2);
    }
}