<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
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
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-shop-logo.png');
    }

    protected static function booted()
    {
        static::saving(function ($shop) {
            if ($shop->isDirty('name')) {
                $shop->slug = Str::slug($shop->name);
            }
        });
    }
}