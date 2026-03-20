<?php

namespace App\Livewire;

use App\Events\CommentCreated;
use Livewire\Component;
use App\Models\Blog;
use App\Models\Comment;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;

class BlogComments extends Component
{
    use WithPagination;

    public $post;
    public $newComment = '';
    public $replyingTo = null; 
    public $perPage = 10;
    public $sortBy = 'latest';
    public $newCommentId = null;

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

    public function setReply($commentId)
    {
        $this->replyingTo = $commentId;
        $parentComment = Comment::find($commentId);
        
        if ($parentComment && $parentComment->user) {
            $this->newComment = "@" . $parentComment->user->name . " ";
        }
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->reset('newComment');
    }

    #[On('echo:comments.{post.id},.CommentCreated')]
    public function handleCommentCreated($event)
    {
        if (isset($event['commentId'])) {
        $this->newCommentId = $event['commentId'];
        }
        // Automatically re-renders when other users post
        $this->post->refresh();
        $this->dispatch('clear-highlight');
    }

    public function updateComment($commentId, $content)
    {
        $comment = Comment::findOrFail($commentId);

        if ($comment->user_id != auth()->id()) {
            session()->flash('error', 'Unauthorized action.');
            return;
        }

        if ($comment->created_at->diffInMinutes(now()) >= 15) {
            session()->flash('error', 'The time limit to edit this comment has expired.');
            return;
        }

        $validatedData = Validator::make(
            ['comment' => $content],
            ['comment' => 'required|string|max:500']
        )->validate();

        $comment->update([
            'comment' => $validatedData['comment']
        ]);

        session()->flash('message', 'Comment updated successfully.');
    }

public function postComment()
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $this->validate();

    // 1. Capture the newly created comment in a variable
    $comment = $this->post->comments()->create([
        'user_id' => Auth::id(),
        'comment' => $this->newComment,
        'status' => 1, 
        'parent_id' => $this->replyingTo,
    ]);

    // 2. Pass the $comment object to the broadcast event
    // This allows Pusher to send the commentId and userName to other users
    broadcast(new CommentCreated($this->post->id, $comment))->toOthers();

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

        if ($this->sortBy === 'oldest') {
            $query->oldest();
        } elseif ($this->sortBy === 'popular') {
            $query->withCount(['likes' => function ($q) {
                $q->where('is_like', true);
            }])->orderBy('likes_count', 'desc');
        } else {
            $query->latest();
        }

        return view('livewire.blog-comments', [
            'comments' => $query->paginate($this->perPage) // Updated to use dynamic perPage
        ]);
    }
}