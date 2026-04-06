<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'user_id', 
        'part_id', 
        'shop_id', 
        'quantity', 
        'price' // Store price at time of adding to cart
    ];

   public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

public function part(): BelongsTo
{
    return $this->belongsTo(Part::class);
}

public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }

}
