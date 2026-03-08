<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = [
        'order_id',
        'carrier',
        'tracking_number',
        'status',
        'shipped_at',
        'delivered_at',
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