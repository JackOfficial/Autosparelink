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

}
