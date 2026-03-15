<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification
{
    use Queueable;

    protected $lowStockParts;

    /**
     * Pass the parts into the notification when it's created
     */
    public function __construct($parts)
    {
        $this->lowStockParts = $parts;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $email = (new MailMessage)
            ->subject('⚠️ Low Stock Alert: AutoSpareLink')
            ->greeting('Hello Admin,')
            ->line('The following spare parts are running low on stock:');

        foreach ($this->lowStockParts as $part) {
            $email->line("- {$part->name}: Only **{$part->stock_quantity}** left.");
        }

        return $email->action('View Inventory', url('/admin/parts'))
                     ->line('Please restock soon to avoid missing sales!');
    }
}