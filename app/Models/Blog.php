<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'blog_category_id',
        'title',
        'slug',
        'content',
        'status', 
    ];
    
    /**
     * Get the user that authored the blog post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category the blog belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }
    
    /**
     * Get the blog's featured photo (Polymorphic).
     */
    public function blogPhoto(): MorphOne
    {
        return $this->morphOne(Photo::class, 'imageable');
    }

    /**
     * Get all of the blog's likes (Polymorphic).
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Get all of the blog's comments (Polymorphic).
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Check if the blog is liked by a specific user.
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
     * Check if the blog is disliked by a specific user.
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