<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLog extends Model
{
    protected $fillable = [
        'user_id',
        'tx_ref',           // Matches InTouch 'requesttransactionid'
        'transaction_id',   // Matches InTouch 'transactionid'
        'amount',
        'currency',
        'status',
        'error_message',
        'raw_response'      // Critical for debugging nested JSON payloads
    ];

    /**
     * The attributes that should be cast.
     * This allows you to save and retrieve the full InTouch response as an array.
     */
    protected $casts = [
        'raw_response' => 'array',
        'amount' => 'decimal:2',
    ];
    
    /**
     * Get the user that made the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}