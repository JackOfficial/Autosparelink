<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
   protected $fillable = ['ticket_id', 'user_id', 'message'];

public function user()
{
    return $this->belongsTo(User::class);
}

public function ticket()
{
    return $this->belongsTo(Ticket::class);
}

/**
 * Check if the reply came from the platform admin
 */
public function isFromStaff()
{
    return $this->user->hasRole('admin') || $this->user->hasRole('super-admin');
}

public function attachments()
{
    return $this->morphMany(Photo::class, 'imageable');
}

protected static function booted()
{
    static::created(function ($reply) {
        $reply->ticket->update([
            'updated_at' => now(), // Keeps the ticket at the top of the list
            'status' => $reply->user_id !== $reply->ticket->user_id ? 'answered' : 'pending'
        ]);
    });
}

}
