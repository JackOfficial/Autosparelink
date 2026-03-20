<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Blog;
use App\Models\Comment;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class BlogComments extends Component
{
    use WithPagination;

    public $post;
    public $newComment = '';
    public $replyingTo = null; // Track which comment is being replied to
    public $perPage = 10;
    public $sortBy = 'latest';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'newComment' => 'required|min:3|max:2000',
    ];

    public function mount(Blog $post)
    {
        $this->post = $post;
    }

    public function updatedSortBy()
{
    $this->resetPage();
}

    /**
     * Set the comment ID the user wants to reply to.
     */
   public function setReply($commentId)
{
    $this->replyingTo = $commentId;
    
    // Find the comment and its author
    $parentComment = Comment::find($commentId);
    
    if ($parentComment && $parentComment->user) {
        // Pre-fill the textarea with "@Username "
        $this->newComment = "@" . $parentComment->user->name . " ";
    }
}

    /**
     * Cancel the reply mode.
     */
    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->reset('newComment');
    }

    public function postComment()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate();

        $this->post->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $this->newComment,
            'status' => 1, 
            'parent_id' => $this->replyingTo, // Link to parent if replying
        ]);

        $this->reset(['newComment', 'replyingTo']);
        $this->post->refresh();
        
        session()->flash('message', 'Comment posted successfully!');
    }

    public function toggleCommentLike($commentId, $isLike)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $comment = Comment::findOrFail($commentId);
        $userId = Auth::id();

        $existing = $comment->likes()->where('user_id', $userId)->first();

        if ($existing) {
            if ($existing->is_like == $isLike) {
                $existing->delete();
            } else {
                $existing->update(['is_like' => $isLike]);
            }
        } else {
            $comment->likes()->create([
                'user_id' => $userId,
                'is_like' => $isLike,
            ]);
        }
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if (Auth::id() == $comment->user_id || Auth::user()->hasAnyRole(['admin', 'super admin'])) {
            $comment->delete();
            $this->post->refresh();
        }
    }

  public function render()
{
    $query = $this->post->comments()
        ->whereNull('parent_id')
        ->with(['user', 'likes', 'replies.user', 'replies.likes']);

    // Apply Sorting Logic
    if ($this->sortBy === 'oldest') {
        $query->oldest();
    } elseif ($this->sortBy === 'popular') {
        // Sort by the count of likes (polymorphic relationship)
        $query->withCount(['likes' => function ($q) {
            $q->where('is_like', true);
        }])->orderBy('likes_count', 'desc');
    } else {
        $query->latest(); // Default: Newest first
    }

    return view('livewire.blog-comments', [
        'comments' => $query->paginate(10)
    ]);
}
}