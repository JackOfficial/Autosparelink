<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class News extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 
        'blog_category_id', 
        'title', 
        'slug', 
        'content', 
        'status', 
        'views'
    ];

    /**
     * Auto-generate slug when creating news
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($news) {
            $news->slug = Str::slug($news->title);
        });
    }

    /**
     * Relationship to the Author
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * Polymorphic Relationship for the Featured Image
     */
    public function newsPhoto(): MorphOne
    {
        return $this->morphOne(Photo::class, 'imageable');
    }

    /**
     * Shared Polymorphic Relationship for Comments
     * This replaces the hasMany('news_id') logic that was causing your SQL error.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes()
{
    return $this->morphMany(Like::class, 'likeable');
}
}