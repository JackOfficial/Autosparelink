<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    /**
     * Remove balances from fillable. 
     * They should only be modified by Transactions, not $request->all().
     */
    protected $fillable = [
        'shop_id',
        'currency',
        'last_transaction_at'
    ];

    protected $casts = [
        'balance'             => 'decimal:2',
        'pending_balance'     => 'decimal:2',
        'withdrawn_balance'   => 'decimal:2',
        'last_transaction_at' => 'datetime',
    ];

    /**
     * Admin-aware scope for secure data retrieval.
     */
    public function scopeForCurrentSeller($query)
    {
        $user = auth()->user();

        if (!$user) {
            return $query;
        }

        if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
            return $query;
        }

        return $query->where('shop_id', $user->shop?->id);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Business Logic Helpers
     */

    public function canWithdraw($amount): bool
    {
        return (float) $this->balance >= (float) $amount;
    }

    /**
     * Accessor for total historical earnings.
     */
    public function getTotalEarningsAttribute()
    {
        return (float) $this->balance + (float) $this->withdrawn_balance;
    }

    /**
     * Helper to get a formatted balance string (e.g., 5,000 RWF)
     */
    public function getFormattedBalanceAttribute()
    {
        return number_format($this->balance) . ' ' . ($this->currency ?? 'RWF');
    }
}