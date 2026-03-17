<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BroadcastMessage extends Notification
{
    use Queueable;

    protected $messageData;

    /**
     * Pass an array or string containing the broadcast details.
     */
    public function __construct(array $messageData)
    {
        $this->messageData = $messageData;
    }

    /**
     * We use 'database' so it saves to the notifications table 
     * and shows up on the user dashboard.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * This defines what is stored in the 'data' column in your database.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->messageData['message'] ?? '',
            'url'     => $this->messageData['url'] ?? null,
            'icon'    => $this->messageData['icon'] ?? 'fas fa-bullhorn', // Default icon
            'type'    => $this->messageData['type'] ?? 'info', // e.g., promo, alert, update
        ];
    }
}