<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Photo;
use App\Models\Cause;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::with(['blogPhoto', 'cause', 'user'])->latest()->get();
        return view('admin.manage.blogs', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $causes = Cause::all(); // since blog belongs to cause
        return view('admin.create.blog', compact('causes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'cause_id' => 'required|exists:causes,id',
            'content' => 'required|string',
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);

        $blog = Blog::create($validated);

        // Handle photo upload (morph)
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('uploads/blogs', 'public');
            $blog->blogPhoto()->create(['file_path' => $path]);
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully.');
    }

    /**
     * Show the form for editing the specified blog.
     */
    public function edit(Blog $blog)
    {
        $causes = Cause::all();
        $blog->load('blogPhoto');
        return view('admin.edit.blog', compact('blog', 'causes'));
    }

    /**
     * Update the specified blog in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'cause_id' => 'required|exists:causes,id',
            'content' => 'required|string',
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $blog->update($validated);

        // Replace photo if a new one is uploaded
        if ($request->hasFile('photo')) {
            if ($blog->blogPhoto) {
                Storage::disk('public')->delete($blog->blogPhoto->file_path);
                $blog->blogPhoto->delete();
            }

            $path = $request->file('photo')->store('uploads/blogs', 'public');
            $blog->blogPhoto()->create(['file_path' => $path]);
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    /**
     * Remove the specified blog from storage.
     */
    public function destroy(Blog $blog)
    {
        // Delete photo from storage
        if ($blog->blogPhoto) {
            Storage::disk('public')->delete($blog->blogPhoto->file_path);
            $blog->blogPhoto->delete();
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted successfully.');
    }
}
