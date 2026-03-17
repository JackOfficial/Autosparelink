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
    'order_ref', 
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
}
