<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WalletTransaction extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'service_fee',
        'fee_percentage',
        'reference_id',
        'reference_type',
        'description',
        'status',
    ];

    /**
     * Cast attributes to native types.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'fee_percentage' => 'decimal:2',
    ];

    /**
     * Get the wallet that owns the transaction.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Get the parent reference model (Order, PayoutRequest, etc.).
     * This allows you to call $transaction->reference to get the actual object.
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function getTypeBadgeAttribute()
{
    $colors = [
        'credit' => 'success',
        'debit'  => 'danger',
    ];

    return sprintf(
        '<span class="badge bg-%s">%s</span>',
        $colors[$this->type] ?? 'secondary',
        ucfirst($this->type)
    );
}

}