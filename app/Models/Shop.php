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
    // 1. Fetch centralized wallet metrics to guarantee consistency
    $wallet = $this->wallet ?? $this->wallet()->create(['balance' => 0]);
    
    // 2. Pull the cached global rate from your Commission Model configuration
    $globalRate = (float) \App\Models\Commission::getRate();

    // 3. Aggregate total customer sales safely
    $revenueData = $this->orderItems()
        ->where('status', 'completed')
        ->selectRaw("SUM(unit_price * quantity) as total_customer_paid")
        ->first();

    $totalGross = (float) ($revenueData->total_customer_paid ?? 0);

    // 4. Calculate commission dynamically using your Commission Model configuration
    $totalCommission = $totalGross * ($globalRate / 100); 
    $netEarnings = $totalGross - $totalCommission;

    // 5. Aggregate active payouts tracking matching your historical ledger status
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
        'totalGross'       => $totalGross,       // 85,300
        'commissionRate'   => $globalRate,       // 10
        'totalCommission'  => $totalCommission,  // 8,530 (Fixed!)
        'netEarnings'      => $netEarnings,      // 76,770 (Fixed!)
        'totalWithdrawn'   => $withdrawn,        // 76,700
        'pendingPayouts'   => $locked,           // 0
        'availableBalance' => $netEarnings - $withdrawn - $locked // 70 (available balance on his/her wallet remaining)
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