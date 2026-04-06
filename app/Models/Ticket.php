<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
  protected $fillable = [
    'user_id', 
    'category', 
    'subject', 
    'message', 
    'order_id', // Changed from order_ref to order_id for a direct link
    'priority', 
    'status'
];

  /**
     * Get the user that owns the ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
{
    return $this->hasMany(TicketReply::class);
}

public function order(): BelongsTo
{
    return $this->belongsTo(Order::class);
}

public function photos()
{
    return $this->morphMany(Photo::class, 'imageable');
}

}
