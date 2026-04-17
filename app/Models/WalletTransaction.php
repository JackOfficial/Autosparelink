<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;

class WalletTransaction extends Model
{
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

    protected $casts = [
        'amount' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'fee_percentage' => 'decimal:2',
    ];

    /**
     * Smart Scope: Restrict transactions to the seller's wallet.
     * Admins and Super Admins bypass this filter.
     */
    public function scopeForCurrentSeller(Builder $query): Builder
    {
        $user = auth()->user();

        if (!$user) return $query;

        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            return $query;
        }

        if ($user->hasRole('seller') && $user->shop) {
            return $query->whereHas('wallet', function ($q) use ($user) {
                $q->where('shop_id', $user->shop->id);
            });
        }

        return $query;
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Accessor for a styled UI badge.
     */
    public function getTypeBadgeAttribute()
    {
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
     * Net earnings after service fees.
     */
    public function getNetAmountAttribute(): float
    {
        return (float) $this->amount;
    }

    public function getGrossAmountAttribute(): float
    {
    return (float) $this->amount + (float) $this->service_fee;
    }

    /**
     * Automatic Wallet Balance Management
     */
    protected static function booted()
    {
        static::creating(function ($transaction) {
            if (!in_array($transaction->type, ['credit', 'debit'])) {
                throw new \Exception("Invalid transaction type: {$transaction->type}");
            }
        });

        static::created(function ($transaction) {
            $wallet = $transaction->wallet;

            // 1. Handle COMPLETED transactions (Immediate balance update)
            if ($transaction->status === 'completed') {
                if ($transaction->type === 'credit') {
                    $wallet->increment('balance', $transaction->amount);
                } elseif ($transaction->type === 'debit') {
                    $wallet->decrement('balance', $transaction->amount);
                }
            } 
            
            // 2. Handle PENDING transactions (Escrow/Pending state)
            elseif ($transaction->status === 'pending') {
                $wallet->increment('pending_balance', $transaction->amount);
            }

            // 3. Always update the activity timestamp
            $wallet->update(['last_transaction_at' => now()]);
        });
    }
}