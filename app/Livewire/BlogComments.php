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
        // 1. Ensure user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Run validation
        $this->validate();

        // 3. Create the comment using the relationship
        $this->post->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $this->newComment,
        ]);

        // 4. Reset the input field and refresh the post to show the new comment
        $this->reset('newComment');
        $this->post->refresh();
        
        session()->flash('message', 'Comment posted successfully!');
    }

    /**
     * Delete a specific comment.
     */
    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // Security: Ensure user owns the comment or is an admin
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
            'comments' => $this->post->comments()->latest()->get()
        ]);
    }
}