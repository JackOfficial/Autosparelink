<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_description', // Recommended for SEO on legal pages
    ];

    /**
     * The attributes that should be cast.
     * * We cast content to 'array' so that when we save FAQ JSON, 
     * Laravel handles the conversion automatically.
     */
    protected $casts = [
        'content' => 'array',
    ];

    /**
     * Helper to get a snippet of the content for admin previews.
     */
    public function getSnippetAttribute()
    {
        if (is_array($this->content)) {
            return count($this->content) . ' FAQ items';
        }
        return str(strip_tags($this->content))->limit(100);
    }
}