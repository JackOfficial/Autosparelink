<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $postId;

    /**
     * Create a new event instance.
     * We pass the postId so the frontend knows which "room" to listen to.
     */
    public function __construct($postId)
    {
        $this->postId = $postId;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        // Using a public channel: comments.{id}
        return [
            new Channel('comments.' . $this->postId),
        ];
    }

    /**
     * Optional: Define the name the frontend is listening for.
     * If you don't define this, it defaults to 'CommentCreated'.
     */
    public function broadcastAs(): string
    {
        return 'CommentCreated';
    }
}