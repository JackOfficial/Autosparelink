<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

   public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

public function parts(): HasMany
{
    return $this->hasMany(Part::class);
}

public function orders()
{
    return $this->hasManyThrough(OrderItem::class, Part::class);
}

protected static function booted()
{
    static::saving(function ($shop) {
        if ($shop->isDirty('name')) {
            $shop->slug = \Illuminate\Support\Str::slug($shop->name);
        }
    });
}

}
