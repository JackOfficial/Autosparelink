<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Fixed: Use the standard Laravel framework helper

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'transaction_id',
        'user_id',
        'total_amount',
        'net_total_amount',
        'delivery_price',
        'method',
        'gateway',
        'address_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'guest_shipping_address',
        'is_guest',
        'status'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function orderItems() 
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * One single master package for consolidated shipping
     */
    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }

    // Helpers
    public function isPending() 
    {
        return $this->status === 'pending';
    }

    public function scopeForCurrentSeller($query)
    {
        $shopId = auth()->user()->shop?->id;

        if (!$shopId) {
            return $query->whereRaw('1 = 0'); 
        }

        return $query->whereHas('orderItems.part', function ($q) use ($shopId) {
            $q->where('shop_id', $shopId);
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = self::generateUniqueOrderNumber();
        });
    }

    private static function generateUniqueOrderNumber()
    {
        do {
            $number = 'AS-' . strtoupper(Str::random(6));
        } while (self::where('order_number', $number)->exists());

        return $number;
    }
}