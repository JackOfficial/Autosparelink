<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Shipping extends Model
{
    protected $fillable = [
        'order_id',
        'shop_id',          // Will be null for central platform-managed packages
        'address_id',       // Nullable: Linked to Address model for registered users
        'address_text',     // Snapshot: Full address string for Guest Checkouts
        'carrier',
        'shipping_method',
        'shipping_cost',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'notes',
        'status',
        'recipient_name',   
        'recipient_phone',  
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'shipping_cost' => 'decimal:2',
    ];

    /**
     * Smart Scope: Sellers see the shipment if their items are inside the order.
     * Admins see everything.
     */
    public function scopeForCurrentSeller(Builder $query): Builder
    {
        $user = auth()->user();

        if (!$user) return $query;

        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            return $query;
        }

        if ($user->hasRole('seller') && $user->shop) {
            $shopId = $user->shop->id;
            
            // Fix: Check if the master order contains any items belonging to this seller's shop
            return $query->whereHas('order.orderItems', function ($q) use ($shopId) {
                $q->where('shop_id', $shopId);
            });
        }

        return $query;
    }

    // --- Relationships ---

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    // --- Helpers & Accessors ---

    public function isShipped()
    {
        return $this->status === 'shipped';
    }

    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    /**
     * Accessor to get the location regardless of checkout type.
     */
    public function getFullAddressAttribute()
    {
        if ($this->address_id && $this->address) {
            return $this->address->full_address_string;
        }

        return $this->address_text ?? 'No address information available';
    }

    /**
     * Boot logic: Automated status syncing.
     */
    protected static function booted()
    {
        static::creating(function ($shipping) {
            // Safe null check for fallbacks, though checkouts assign null for hub fulfillment
            if (auth()->check() && auth()->user()->hasRole('seller') && empty($shipping->shop_id)) {
                $shipping->shop_id = auth()->user()->shop?->id;
            }
        });

        static::updated(function ($shipping) {
            // 1. Tracking Notification Logic
            if ($shipping->wasChanged('tracking_number') && !empty($shipping->tracking_number)) {
                // Trigger notification safely here
            }

            // 2. Logistics -> Master Order Items Status Sync
            if ($shipping->wasChanged('status') && $shipping->status === 'delivered') {
                if ($shipping->order) {
                    // Fix: Removed 'where shop_id' constraint because all vendors share this package
                    $shipping->order->orderItems()
                        ->where('status', '!=', 'completed')
                        ->get()
                        ->each(function($item) {
                            // updateQuietly prevents infinite event cascades or loops
                            $item->updateQuietly([
                                'status' => 'delivered'
                            ]);
                        });
                }
            }
        });
    }
}