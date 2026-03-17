<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
  protected $fillable = ['category', 'subject', 'message', 'order_ref', 'status', 'priority'];

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
