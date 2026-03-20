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
    public $comment; // Add this property

    /**
     * Create a new event instance.
     * We pass the postId and the newly created Comment model.
     */
    public function __construct($postId, $comment = null)
    {
        $this->postId = $postId;
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('comments.' . $this->postId),
        ];
    }

    /**
     * Specify the data to be broadcasted.
     * This keeps the payload small and fast for Pusher.
     */
    public function broadcastWith(): array
    {
        return [
            'postId' => $this->postId,
            'commentId' => $this->comment ? $this->comment->id : null,
            'userName' => $this->comment && $this->comment->user ? $this->comment->user->name : 'A user',
            'message' => $this->comment ? $this->comment->comment : '',
        ];
    }

    /**
     * Keep the explicit name for the frontend listener.
     */
    public function broadcastAs(): string
    {
        return 'CommentCreated';
    }
}