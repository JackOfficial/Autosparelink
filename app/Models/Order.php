<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'address_id',
        'guest_name',
        'guest_email',
        'guest_phone',
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

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }

    public function orderItems() 
{
    return $this->hasMany(OrderItem::class);
}

    // Helper
    public function isPending()
    {
        return $this->status === 'pending';
    }

}