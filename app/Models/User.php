<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // public function stories() {
    //     return $this->hasMany(Stories::class);
    // }
    
    public function blogs(){
        return $this->hasMany(Blog::class);
    }
    
    public function likes()
{
    return $this->hasMany(Like::class);
}

 public function comments()
{
    return $this->hasMany(Comment::class);
}

/**
 * Quick check if the user actually has an active shop.
 */
public function hasActiveShop(): bool
{
    // This is faster if the relationship is already loaded
    return $this->shop && $this->shop->is_active;
}

/**
 * Helper to check if they should see the "Become a Seller" CTA.
 */
public function canBecomeVendor(): bool
{
    // Show only to regular 'user' roles who don't have a shop yet
    return $this->hasRole('user') && !$this->shop()->exists();
}

/**
 * Access the shop's parts directly from the user.
 */
public function shopParts()
{
    return $this->hasManyThrough(Part::class, Shop::class);
}

public function addresses()
{
    return $this->hasMany(Address::class);
}

public function orders()
{
    return $this->hasMany(Order::class);
}

// Items in the order
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Payment for the order
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Shipping details
    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }

    public function cartItems()
{
    return $this->hasMany(CartItem::class);
}

public function vehicles() {
    return $this->hasMany(ClientVehicle::class);
}

public function primaryVehicle() {
    return $this->hasOne(ClientVehicle::class)
        ->where('is_primary', true)
        ->latest(); // Safety: always take the newest if multiple exist
}
/**
 * Orders received as a seller (from other buyers)
 */
public function vendorOrders()
{
    return $this->hasManyThrough(Order::class, Shop::class);
}

public function tickets()
{
    return $this->hasMany(Ticket::class);
}

public function shop()
{
    return $this->hasOne(Shop::class);
}

}
