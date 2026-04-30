<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Wallet extends Model
{
    /**
     * Remove balances from fillable for financial security.
     * They are modified via WalletTransaction logic.
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
     * Secure data retrieval based on user roles.
     */
    public function scopeForCurrentSeller(Builder $query): Builder
    {
        $user = auth()->user();

        if (!$user || $user->hasRole('super-admin') || $user->hasRole('admin')) {
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
     * Business Logic: Validate withdrawal requests.
     */
    public function canWithdraw($amount): bool
    {
        return (float) $this->balance >= (float) $amount;
    }

    /**
     * Logic for calculating Total Historical Earnings.
     */
    public function getTotalEarningsAttribute(): float
    {
        return (float) $this->balance + (float) $this->withdrawn_balance;
    }

    /**
     * UI Helper: Professional formatting for RWF.
     */
    public function getFormattedBalanceAttribute(): string
    {
        // Rwandan Francs typically do not use decimals in display
        return number_format($this->balance, 0) . ' ' . ($this->currency ?? 'RWF');
    }
}