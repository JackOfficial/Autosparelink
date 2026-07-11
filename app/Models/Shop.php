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
        'is_verified',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
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
    // 1. Fetch wallet row safely
    $wallet = $this->wallet ?: $this->wallet()->create(['balance' => 0]);
    
    // 2. Aggregate earnings using snapshot record metrics (safeguarded against future rate adjustments)
    // If you haven't added these columns to your order_items yet, fall back to calculating them line-by-line,
    // but migrating your order_items table to store 'commission_amount' and 'vendor_net_earnings' is best practice.
    $revenueData = $this->orderItems()
        ->where('status', 'completed')
        ->selectRaw("
            SUM(unit_price * quantity) as total_gross,
            SUM(commission_amount) as total_commission_paid,
            SUM(shop_payout) as total_net_earnings
        ")
        ->first();

    // Fallback logic if you don't have snapshot columns yet:
    $totalGross = (float) ($revenueData->total_gross ?? 0);
    
    // If you haven't migrated columns yet, keep your dynamic logic temporary, but plan to change it:
    $globalRate = (float) \App\Models\Commission::getRate();
    $totalCommission = $revenueData->total_commission_paid !== null 
        ? (float) $revenueData->total_commission_paid 
        : ($totalGross * ($globalRate / 100));

    $netEarnings = $revenueData->total_net_earnings !== null 
        ? (float) $revenueData->total_net_earnings 
        : ($totalGross - $totalCommission);

    // 3. Aggregate historical and pending payout ledgers
    $deductions = $this->payouts()
        ->whereIn('status', ['completed', 'pending', 'processing'])
        ->selectRaw("
            SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_withdrawn,
            SUM(CASE WHEN status IN ('pending', 'processing') THEN amount ELSE 0 END) as total_locked
        ")
        ->first();

    $totalWithdrawn = (float) $this->payouts()
    ->where('status', 'completed')
    ->sum('amount');
    
    $calculatedBalance = $netEarnings - $totalWithdrawn;

    // 4. Integrity Check: Log an internal alert if your ledger drifts from live wallet rows
    if (abs($calculatedBalance - (float) $wallet->balance) > 0.01) {
        \Log::warning("Financial drift detected for Shop ID {$this->id}. Calculated ledger: {$calculatedBalance}, Live Wallet balance: {$wallet->balance}");
    }

    return [
        'totalGross'       => $totalGross,
        'commissionRate'   => $globalRate, // Kept for layout UI context tracking
        'totalCommission'  => $totalCommission,
        'netEarnings'      => $netEarnings,
        'totalWithdrawn'   => $totalWithdrawn,
        'pendingPayouts'   => 0,
        'availableBalance' => (float) $wallet->balance // Always drive the wallet view balance off your isolated single source of truth
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