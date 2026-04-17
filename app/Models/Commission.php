<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    /**
     * The attributes that are mass assignable.
     * We removed 'type' and 'shop_id'.
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
     * Since the boss wants one rate for everyone, we no longer need $shopId.
     * * @return float
     */
    public static function getRate(): float
    {
        // Get the latest active commission rate set by the admin
        $rate = self::where('is_active', true)
            ->latest()
            ->value('rate');

        // Fallback to 10.00 if no active rate exists in the database
        return $rate !== null ? (float) $rate : 10.00;
    }
}