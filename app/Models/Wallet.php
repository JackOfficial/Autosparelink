<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'shop_id',
        'balance',
        'pending_balance',
        'withdrawn_balance',
        'currency',
        'last_transaction_at'
    ];

    /**
     * The attributes that should be cast to native types.
     * This ensures the balances are treated as numbers (floats/decimals) instead of strings.
     */
    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'withdrawn_balance' => 'decimal:2',
        'last_transaction_at' => 'datetime',
    ];

    /**
     * Get the shop that owns the wallet.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the transactions associated with this wallet.
     * (Assumes you will create a WalletTransaction model)
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Helper to check if the shop can afford a withdrawal.
     */
    public function canWithdraw($amount): bool
    {
        return $this->balance >= $amount;
    }
}