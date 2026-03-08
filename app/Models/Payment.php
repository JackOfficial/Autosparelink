<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'amount',
        'method',
        'transaction_reference',
        'status',
        'paid_at',
    ];

    // Relationship
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Helpers
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isSuccessful()
    {
        return $this->status === 'successful';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }
}