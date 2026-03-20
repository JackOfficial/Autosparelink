<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'commentable_id',   // The ID of the Blog or News
        'commentable_type', // The Class name (App\Models\Blog or App\Models\News)
        'comment',
        'status',
    ];

    /**
     * Get the parent commentable model (Blog or News).
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who wrote the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Polymorphic Relationship: A comment can have many likes/dislikes.
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Check if the given user has liked this specific comment.
     */
    public function isLikedBy($userId): bool
    {
        if (!$userId) return false;

        return $this->likes()
            ->where('user_id', $userId)
            ->where('is_like', true)
            ->exists();
    }

    /**
     * Check if the given user has disliked this specific comment.
     */
    public function isDislikedBy($userId): bool
    {
        if (!$userId) return false;

        return $this->likes()
            ->where('user_id', $userId)
            ->where('is_like', false)
            ->exists();
    }
}