<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user who wrote the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
{
    return $this->morphMany(Like::class, 'likeable');
}

}