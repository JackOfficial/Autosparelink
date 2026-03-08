<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'part_id',
        'quantity',
        'unit_price',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    // Helper
    public function subtotal()
    {
        return $this->quantity * $this->unit_price;
    }
}