<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'part_id',
        'shop_id',
        'quantity',
        'unit_price',
        'part_name',
        'status',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class); 
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function isPaid(): bool
{
    return $this->order->payment && $this->order->payment->isSuccessful();
}

    // Use an Attribute for subtotal (cleaner for Blade/Logic)
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }
}