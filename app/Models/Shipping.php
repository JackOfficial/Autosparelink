<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Shipping extends Model
{
    protected $fillable = [
        'order_id',
        'shop_id',
        'carrier',
        'shipping_method',
        'shipping_cost',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'notes',
        'status',
        'recipient_name',   // Added: Direct access for courier
        'recipient_phone',  // Added: Direct access for courier
        'address_id',       // Added: Link to address
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'shipping_cost' => 'decimal:2',
    ];

    /**
     * Smart Scope: Sellers only see their shipments; Admins see all.
     */
    public function scopeForCurrentSeller(Builder $query): Builder
    {
        $user = auth()->user();

        if (!$user) return $query;

        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            return $query;
        }

        if ($user->hasRole('seller') && $user->shop) {
            return $query->where('shop_id', $user->shop->id);
        }

        return $query;
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    // Helpers
    public function isShipped()
    {
        return $this->status === 'shipped';
    }

    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    /**
     * Boot logic to auto-assign shop_id
     */
    protected static function booted()
    {
        static::creating(function ($shipping) {
            if (auth()->check() && auth()->user()->hasRole('seller') && empty($shipping->shop_id)) {
                $shipping->shop_id = auth()->user()->shop->id;
            }
        });
    }
}