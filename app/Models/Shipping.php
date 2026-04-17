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
        // Prioritize the linked profile address if it exists
        if ($this->address_id && $this->address) {
            return $this->address->full_address_string;
        }

        // Fallback to the snapshot text (Guest checkout)
        return $this->address_text ?? 'No address information available';
    }

    /**
     * Boot logic: Automation and Finance Bridging.
     */
    protected static function booted()
    {
        static::creating(function ($shipping) {
            // Auto-assign shop_id for sellers
            if (auth()->check() && auth()->user()->hasRole('seller') && empty($shipping->shop_id)) {
                $shipping->shop_id = auth()->user()->shop->id;
            }
        });

        static::updated(function ($shipping) {
    // 1. Tracking Notification Logic
    if ($shipping->wasChanged('tracking_number') && !empty($shipping->tracking_number)) {
        // Trigger notification: "Your package is moving! Tracking: {$shipping->tracking_number}"
    }

    // 2. Logistics -> Inspection Bridge:
    // When marked 'delivered', move OrderItems to 'delivered' status.
    // We DO NOT set 'completed' here because the client needs time to inspect the part.
    if ($shipping->wasChanged('status') && $shipping->status === 'delivered') {
        $shipping->order->orderItems()
            ->where('shop_id', $shipping->shop_id)
            ->where('status', '!=', 'completed') // Safety: don't revert if already completed
            ->get()
            ->each(function($item) {
                // We update to 'delivered'. 
                // This does NOT trigger the vendor payment logic in your observer.
                $item->status = 'delivered'; 
                $item->save(); 
            });
    }
});
    }
}