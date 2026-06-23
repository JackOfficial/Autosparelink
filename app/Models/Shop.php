<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, MorphMany, HasOne};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_name',
        'slug',
        'shop_email',
        'description',
        'logo',
        'tin_number',
        'address',
        'phone_number',
        'is_active',
        'commission_rate',
        'is_verified',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'commission_rate' => 'decimal:2',
    ];

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-shop-logo.png');
    }

    public function isOperational(): bool
    {
        return $this->is_active && $this->is_verified;
    }

    /**
     * Audited Financial Calculation Logic
     * Centralized to verify mathematical consistency against the Wallet System.
     */
    public function getFinancialAudit(): array
    {
        // 1. Fetch current centralized wallet metrics to guarantee single-source balance consistency
        $wallet = $this->wallet ?? $this->wallet()->create(['balance' => 0]);
        $currentRate = (float) ($this->commission_rate ?? 0);

        // 2. Perform fast aggregates optimized for execution indexing
        $revenueData = $this->orderItems()
            ->where('status', 'completed')
            ->selectRaw("
                SUM(unit_price * quantity) as total_customer_paid,
                SUM(shop_payout * quantity) as total_shop_revenue
            ")
            ->first();

        $totalGross = (float) ($revenueData->total_customer_paid ?? 0);
        $netEarnings = (float) ($revenueData->total_shop_revenue ?? 0);
        $totalCommission = $totalGross - $netEarnings;

        // 3. Keep strict tracking on payouts using uniform states matching your PayoutController
        $deductions = $this->payouts()
            ->whereIn('status', ['completed', 'pending', 'processing'])
            ->selectRaw("
                SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_withdrawn,
                SUM(CASE WHEN status IN ('pending', 'processing') THEN amount ELSE 0 END) as total_locked
            ")
            ->first();

        $withdrawn = (float) ($deductions->total_withdrawn ?? 0);
        $locked = (float) ($deductions->total_locked ?? 0);

        return [
            'totalGross'       => $totalGross,       // Absolute customer gross payment profile
            'commissionRate'   => $currentRate,      // Fixed target base percentage
            'totalCommission'  => $totalCommission,  // Platform markup capture yield
            'netEarnings'      => $netEarnings,      // All-time historic earned ledger index
            'totalWithdrawn'   => $withdrawn,        // Confirmed processed payout settlements
            'pendingPayouts'   => $locked,           // Retained transaction locks
            
            // CRITICAL FIX: Base available balance on your audited Wallet balance row
            // to stay aligned with updates from your PayoutController webhooks.
            'availableBalance' => (float) $wallet->balance - $locked
        ];
    }

    protected static function booted()
    {
        static::saving(function ($shop) {
            if ($shop->isDirty('shop_name')) {
                $shop->slug = Str::slug($shop->shop_name);
            }
        });

        // Automatically provision an accompanying Wallet record whenever a new Shop is verified
        static::created(function ($shop) {
            $shop->wallet()->create(['balance' => 0]);
        });
    }
}