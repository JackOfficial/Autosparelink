<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',          // e.g., "RDB Certificate"
        'file_path',
        'file_type',      // e.g., "pdf" or "application/pdf"
        'file_size',
        'uploaded_by',
        'documentable_id',
        'documentable_type',
    ];

    /**
     * Get the parent documentable model (Shop, User, etc.).
     */
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The user who uploaded the document.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}