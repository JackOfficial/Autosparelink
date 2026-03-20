<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Blog;

class BlogEngagement extends Component
{
    public $post;

    /**
     * Initialize the component with the post data passed from the Blade view.
     */
    public function mount(Blog $post)
    {
        $this->post = $post;
    }

    /**
     * Toggle the Like/Dislike status for the authenticated user.
     */
    public function toggleLike($isLike)
    {
        // 1. Guard: User must be logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userId = auth()->id();

        // 2. Check if a vote already exists for this user on this post
        $existingVote = $this->post->likes()
            ->where('user_id', $userId)
            ->first();

        if ($existingVote) {
            if ($existingVote->is_like == $isLike) {
                // If they click the SAME button again, remove their vote (Undo)
                $existingVote->delete();
            } else {
                // If they click the OPPOSITE button, update the vote
                $existingVote->update(['is_like' => $isLike]);
            }
        } else {
            // 3. Create a new vote if none exists
            $this->post->likes()->create([
                'user_id' => $userId,
                'is_like' => $isLike,
            ]);
        }

        // 4. Refresh the model to update the counts in the UI instantly
        $this->post->refresh();
    }

    public function render()
    {
        return view('livewire.blog-engagement');
    }
}