<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class BlogComments extends Component
{
    public $post;
    public $newComment = '';

    /**
     * Validation rules for the comment field.
     */
    protected $rules = [
        'newComment' => 'required|min:3|max:2000',
    ];

    /**
     * Initialize the component.
     */
    public function mount(Blog $post)
    {
        $this->post = $post;
    }

    /**
     * Store a new comment in the database.
     */
    public function postComment()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate();

        $this->post->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $this->newComment,
            'status' => 'approved', // Or default status based on your model
        ]);

        $this->reset('newComment');
        $this->post->refresh();
        
        session()->flash('message', 'Comment posted successfully!');
    }

    /**
     * Toggle the Like/Dislike status for a specific comment.
     */
    public function toggleCommentLike($commentId, $isLike)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $comment = Comment::findOrFail($commentId);
        $userId = Auth::id();

        // Check for existing polymorphic vote on this comment
        $existing = $comment->likes()->where('user_id', $userId)->first();

        if ($existing) {
            if ($existing->is_like == $isLike) {
                $existing->delete(); // Undo vote
            } else {
                $existing->update(['is_like' => $isLike]); // Flip vote
            }
        } else {
            // Create new polymorphic like record
            $comment->likes()->create([
                'user_id' => $userId,
                'is_like' => $isLike,
            ]);
        }
        
        // No full refresh needed; render() will pick up the change
    }

    /**
     * Delete a specific comment.
     */
    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // Security check
        if (Auth::id() == $comment->user_id || Auth::user()->hasAnyRole(['admin', 'super admin'])) {
            $comment->delete();
            $this->post->refresh();
        }
    }

    /**
     * Render the view with the latest comments.
     */
    public function render()
    {
        return view('livewire.blog-comments', [
            'comments' => $this->post->comments()
                ->with(['user', 'likes']) // Eager load to prevent N+1 queries
                ->latest()
                ->get()
        ]);
    }
}