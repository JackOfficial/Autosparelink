<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Display a listing of the news.
     */
    public function index()
    {
        $newsItems = News::with(['category', 'user', 'newsPhoto'])
            ->latest()
            ->paginate(10);

        return view('admin.news.index', compact('newsItems'));
    }

    /**
     * Show the form for creating new news.
     */
    public function create()
    {
        // Only get categories that are meant for 'news' or general
        $categories = BlogCategory::whereIn('type', ['news', 'general'])->get();
        return view('admin.news.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Create the News entry
        $news = News::create([
            'user_id' => Auth::id(),
            'blog_category_id' => $request->blog_category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content, // TinyMCE HTML content
            'status' => 'published',
        ]);

        // Handle Polymorphic Photo Upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('news_images', 'public');
            $news->newsPhoto()->create([
                'file_path' => $path,
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('admin.news.index')->with('success', 'News published successfully!');
    }

    /**
     * Show the form for editing the specified news.
     */
    public function edit(News $news)
    {
        $categories = BlogCategory::whereIn('type', ['news', 'general'])->get();
        return view('admin.news.edit', compact('news', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $news->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'blog_category_id' => $request->blog_category_id,
        ]);

        // Update Polymorphic Photo
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($news->newsPhoto) {
                Storage::disk('public')->delete($news->newsPhoto->file_path);
                $news->newsPhoto->delete();
            }

            $path = $request->file('photo')->store('news_images', 'public');
            $news->newsPhoto()->create([
                'file_path' => $path,
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        // Cleanup storage before deleting
        if ($news->newsPhoto) {
            Storage::disk('public')->delete($news->newsPhoto->file_path);
            $news->newsPhoto->delete();
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully!');
    }
}