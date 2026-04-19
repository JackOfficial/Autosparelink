<?php

namespace App\Models;

use Attribute;
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
        'shop_name', // Matches your Controller & Form
        'slug',
        'shop_email', // Matches your Controller
        'description',
        'logo',
        'tin_number',
        'address',
        'phone_number',
        'is_active',
        'commission_rate',
        'is_verified',
    ];

    /**
     * Ensure data types are consistent
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'commission_rate' => 'decimal:2',
    ];

    /**
     * Polymorphic relationship for verification documents
     * (RDB Certificate, IDs, etc.)
     */
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

    /**
     * Connects Shop directly to OrderItems sold
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Track payout history for this shop
     */
    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
    }

    /**
     * Helper to get the public logo URL or a default placeholder
     */
    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-shop-logo.png');
    }

    public function wallet(): HasOne
   {
    return $this->hasOne(Wallet::class);
   }

    /**
     * Helper to check if the shop is ready for business
     */
    public function isOperational(): bool
    {
        return $this->is_active && $this->is_verified;
    }

    // app/Models/Shop.php

public function getFinancialAudit()
    {
        // 1. Get the current rate
        $rawRate = Commission::getRate(); 
        $percentage = $rawRate / 100;

        // 2. Audited Gross Revenue
        $revenueData = $this->orderItems()
            ->where('status', 'completed')
            ->whereHas('order', fn($q) => $q->where('status', 'completed'))
            ->selectRaw("SUM(unit_price * quantity) as total_gross")
            ->first();

        $totalGross = $revenueData->total_gross ?? 0;

        // 3. Financial Breakdown
        $totalCommission = $totalGross * $percentage;
        $netEarnings = $totalGross - $totalCommission;

        // 4. Payout Deductions
        $deductions = $this->payouts()
            ->whereIn('status', ['completed', 'pending', 'processing'])
            ->selectRaw("
                SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_withdrawn,
                SUM(CASE WHEN status IN ('pending', 'processing') THEN amount ELSE 0 END) as total_locked
            ")
            ->first();

        $withdrawn = $deductions->total_withdrawn ?? 0;
        $locked = $deductions->total_locked ?? 0;

        return [
            'totalGross'       => $totalGross,
            'commissionRate'   => $rawRate, // --- ADDED THIS SO BLADE CAN USE IT ---
            'totalCommission'  => $totalCommission,
            'netEarnings'      => $netEarnings,
            'totalWithdrawn'   => $withdrawn,
            'pendingPayouts'   => $locked,
            'availableBalance' => $netEarnings - ($withdrawn + $locked)
        ];
    }

    protected static function booted()
    {
        static::saving(function ($shop) {
            // Updated to reference 'shop_name'
            if ($shop->isDirty('shop_name')) {
                $shop->slug = Str::slug($shop->shop_name);
            }
        });
    }

}