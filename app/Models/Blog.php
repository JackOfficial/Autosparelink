<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'blog_category_id',
        'title',
        'slug',
        'content',
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    // Inside App\Models\Blog.php

public function category()
{
    // Explicitly mapping blog_category_id to the category model
    return $this->belongsTo(BlogCategory::class, 'blog_category_id');
}
    
    public function blogPhoto()
{
    return $this->morphOne(Photo::class, 'imageable');
}
    
    public function likes()
{
    return $this->hasMany(Like::class);
}

public function comments(){
    return $this->hasMany(Comment::class);
}
}
