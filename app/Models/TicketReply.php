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
        // Logic: 
        // 1. If Admin/Staff replies -> Status becomes 'pending' (waiting for User)
        // 2. If User/Shop owner replies -> Status becomes 'open' (waiting for Admin)
        $newStatus = $reply->isFromStaff() ? 'pending' : 'open';

        $reply->ticket->update([
            'updated_at' => now(),
            'status'     => $newStatus
        ]);
    });
}

}
