<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = [
        'order_id',
        'carrier', // e.g., 'GIG Logistics', 'Local Courier'
        'shipping_method', // e.g., 'Pickup', 'Home Delivery'
        'shipping_cost',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'notes',         // Added for delivery instructions
        'status', // 'pending', 'at_hub', 'shipped', 'delivered'
    ];

    protected $casts = [
    'shipped_at' => 'datetime',
    'delivered_at' => 'datetime',
];

    // Relationship
    public function order()
    {
        return $this->belongsTo(Order::class);
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
}