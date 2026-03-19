<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Using paginate(10) to match your Category index style
        $blogs = Blog::with(['blogPhoto', 'category', 'user'])->latest()->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BlogCategory::all(); 
        return view('admin.blogs.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'content'          => 'required|string',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        return DB::transaction(function () use ($request) {
            $blog = Blog::create([
                'user_id'          => auth()->id(),
                'blog_category_id' => $request->blog_category_id,
                'title'            => $request->title,
                'slug'             => Str::slug($request->title),
                'content'          => $request->content,
            ]);

            // Handle polymorphic photo upload
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('uploads/blogs', 'public');
                $blog->blogPhoto()->create(['file_path' => $path]);
            }

            return redirect()->route('admin.blogs.index')->with('success', 'Blog post published successfully.');
        });
    }

    /**
     * Show the form for editing the specified blog.
     */
    public function edit(Blog $blog)
    {
        $categories = BlogCategory::all();
        $blog->load('blogPhoto');
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified blog in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'content'          => 'required|string',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        return DB::transaction(function () use ($request, $blog) {
            $blog->update([
                'title'            => $request->title,
                'blog_category_id' => $request->blog_category_id,
                'slug'             => Str::slug($request->title),
                'content'          => $request->content,
            ]);

            if ($request->hasFile('photo')) {
                // Delete old photo file and record
                if ($blog->blogPhoto) {
                    Storage::disk('public')->delete($blog->blogPhoto->file_path);
                    $blog->blogPhoto()->delete();
                }

                $path = $request->file('photo')->store('uploads/blogs', 'public');
                $blog->blogPhoto()->create(['file_path' => $path]);
            }

            return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully.');
        });
    }

    /**
     * Remove the specified blog from storage.
     */
    public function destroy(Blog $blog)
    {
        // Note: With SoftDeletes, we usually keep the photo. 
        // If you want to delete the file only on Force Delete, move this logic.
        if ($blog->blogPhoto) {
            Storage::disk('public')->delete($blog->blogPhoto->file_path);
            $blog->blogPhoto()->delete();
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post moved to trash.');
    }
}