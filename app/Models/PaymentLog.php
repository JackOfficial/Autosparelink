<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLog extends Model
{
   protected $fillable = [
        'user_id',
        'tx_ref',
        'transaction_id',
        'amount',
        'currency',
        'status',
        'error_message',
        'raw_response'
    ];
    
    /**
     * Get the user that made the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
