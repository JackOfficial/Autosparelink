<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
   protected $fillable = [
        'shop_id',
        'amount',
        'payout_method',
        'account_details',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Relationship to the Shop
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Helper to check if payout is finished
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
