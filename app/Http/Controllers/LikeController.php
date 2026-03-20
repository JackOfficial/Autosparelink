<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'id'    => 'required|integer',
            'type'  => 'required|string|in:blog,comment,news',
            'vote'  => 'required|string|in:like,dislike',
        ]);

        // 1. Map the 'type' string to the actual Model class
        $map = [
            'blog'    => \App\Models\Blog::class,
            'comment' => \App\Models\Comment::class,
            'news'    => \App\Models\News::class,
        ];

        $modelClass = $map[$request->type];
        $item = $modelClass::findOrFail($request->id);
        $isLike = $request->vote === 'like';
        $userId = Auth::id();

        // 2. Check if the user has already voted on this specific item
        $existingLike = $item->likes()->where('user_id', $userId)->first();

        if ($existingLike) {
            if ($existingLike->is_like == $isLike) {
                // If they click the same button again, remove the vote (Toggle Off)
                $existingLike->delete();
                $message = 'Vote removed.';
            } else {
                // If they click the opposite button, update the vote
                $existingLike->update(['is_like' => $isLike]);
                $message = 'Vote updated.';
            }
        } else {
            // 3. Create a new polymorphic like record
            $item->likes()->create([
                'user_id' => $userId,
                'is_like' => $isLike,
            ]);
            $message = 'Vote recorded!';
        }

        return redirect()->back()->with('success', $message);
    }
}