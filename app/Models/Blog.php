<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'blog_category_id',
        'title',
        'slug',
        'content',
        'status', // Added this to prevent the "Unknown column 'status'" error
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
        // Explicitly mapping blog_category_id to the category model
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
     * Get the likes for the blog post.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get all of the blog's comments (Polymorphic).
     * This fixes the "Unknown column 'blog_id'" error by looking for 
     * 'commentable_id' and 'commentable_type' instead.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}