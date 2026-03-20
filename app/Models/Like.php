<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'likeable_id',   // The ID of the Blog, Comment, or News
        'likeable_type', // The class name (e.g., App\Models\Blog)
        'is_like',       // true for Like, false for Dislike
    ];

    /**
     * Get the parent likeable model (Blog, Comment, or News).
     * This replaces the old blog() method.
     */
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The user who cast the vote.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}