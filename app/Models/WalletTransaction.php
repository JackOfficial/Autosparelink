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
    // Matching your "bg-soft-primary" style
    $styles = [
        'credit' => ['bg' => 'soft-success', 'text' => 'success'],
        'debit'  => ['bg' => 'soft-danger', 'text' => 'danger'],
    ];

    $style = $styles[$this->type] ?? ['bg' => 'soft-secondary', 'text' => 'secondary'];

    return sprintf(
        '<span class="badge bg-%s text-%s rounded-pill px-3">%s</span>',
        $style['bg'],
        $style['text'],
        ucfirst($this->type)
    );
}

/**
 * Calculate the net amount (Amount - Fees)
 */
public function getNetAmountAttribute(): float
{
    return (float) $this->amount - (float) $this->service_fee;
}

protected static function booted()
{
    static::creating(function ($transaction) {
        if (!in_array($transaction->type, ['credit', 'debit'])) {
            throw new \Exception("Invalid transaction type: {$transaction->type}");
        }
    });
}

}