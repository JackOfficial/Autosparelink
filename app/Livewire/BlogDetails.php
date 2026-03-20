<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class BlogDetails extends Component
{
    public $post;
    public $comment = '';

    public function mount(Blog $post)
    {
        $this->post = $post;
    }

    public function toggleVote($id, $type, $voteType)
    {
        if (!Auth::check()) return redirect()->route('login');

        $model = ($type === 'blog') ? Blog::find($id) : Comment::find($id);
        $isLike = ($voteType === 'like');

        $existing = $model->likes()->where('user_id', Auth::id())->first();

        if ($existing) {
            if ($existing->is_like === $isLike) {
                $existing->delete();
            } else {
                $existing->update(['is_like' => $isLike]);
            }
        } else {
            $model->likes()->create([
                'user_id' => Auth::id(),
                'is_like' => $isLike
            ]);
        }

        // Refresh model to update counts
        $this->post->load('likes', 'comments.likes');
    }

    public function postComment()
    {
        $this->validate(['comment' => 'required|min:3']);

        if (!Auth::check()) return;

        $this->post->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $this->comment,
        ]);

        $this->comment = '';
        $this->post->load('comments');
        session()->flash('success', 'Comment posted successfully!');
    }

    public function render()
    {
        return view('livewire.blog-details');
    }
}