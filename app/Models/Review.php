<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    // CRITICAL FIX: Added the polymorphic tracking columns to mass assignment
    protected $fillable = [
        'user_id', 
        'rating', 
        'comment', 
        'status', 
        'reviewable_id', 
        'reviewable_type'
    ];

    /**
     * Get the modern cast definitions for type safety.
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'rating' => 'integer',
            'reviewable_id' => 'integer',
        ];
    }

    /**
     * Get the owning reviewable model (Part or Shop).
     */
    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the authenticated user who wrote this review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}