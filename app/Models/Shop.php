<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-shop-logo.png');
    }

    public function isOperational(): bool
    {
        return $this->is_active && $this->is_verified;
    }

    /**
     * Updated Financial Audit for Markup Model
     * 
     * In this model:
     * - unit_price: Price paid by customer (Base + Commission)
     * - shop_payout: Price set by shop (Base)
     */
    public function getFinancialAudit()
    {
        // 1. Get the shop's specific commission rate
        $currentRate = (float) ($this->commission_rate ?? 0);

        // 2. Aggregate Revenue Data for this shop's specific items
        // We calculate based on 'shop_payout' because the shop receives 100% of their set price.
        $revenueData = $this->orderItems()
            ->where('status', 'completed')
            ->selectRaw("
                SUM(unit_price * quantity) as total_customer_paid,
                SUM(shop_payout * quantity) as total_shop_revenue
            ")
            ->first();

        $totalGross = (float) ($revenueData->total_customer_paid ?? 0);
        $netEarnings = (float) ($revenueData->total_shop_revenue ?? 0);
        
        // The commission is the difference between what the customer paid and what the shop gets
        $totalCommission = $totalGross - $netEarnings;

        // 3. Payout Deductions (Withdrawals)
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
            'totalGross'       => $totalGross,       // What customers paid for this shop's items
            'commissionRate'   => $currentRate,      // The % added to this shop's prices
            'totalCommission'  => $totalCommission,  // Revenue for the platform from this shop
            'netEarnings'      => $netEarnings,      // Total the shop is entitled to (100% of their base price)
            'totalWithdrawn'   => $withdrawn,
            'pendingPayouts'   => $locked,
            'availableBalance' => $netEarnings - ($withdrawn + $locked)
        ];
    }

    protected static function booted()
    {
        static::saving(function ($shop) {
            if ($shop->isDirty('shop_name')) {
                $shop->slug = Str::slug($shop->shop_name);
            }
        });
    }
}