<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payout extends Model
{
    protected $fillable = [
        'shop_id',
        'amount',
        'currency',
        'payout_method',
        'account_details', // The phone number for InTouch
        'status',
        'reference',               // Internal tracking ID (WD-...)
        'gateway_transaction_id',   // The ID returned by InTouch
        'admin_note',
        'error_log',               // Stores failure reasons from API
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    /**
     * Relationship to the Shop
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Auto-generate reference on creation if missing
     */
    protected static function booted()
    {
        static::creating(function ($payout) {
            if (empty($payout->reference)) {
                $payout->reference = 'WD-' . strtoupper(Str::random(8)) . '-' . time();
            }
        });
    }

    /**
     * Helper to check if payout is finished
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Scope for the current seller's shop
     */
    public function scopeForCurrentSeller($query)
    {
        return $query->where('shop_id', auth()->user()->shop?->id);
    }
}