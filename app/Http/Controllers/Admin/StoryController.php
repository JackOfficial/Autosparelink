<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Photo;
use App\Models\Organization;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stories= Story::with(['organization', 'user', 'photo'])->latest()->get();
        return view('admin.manage.stories', compact('stories'));
       //dd("Hello world");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('admin.create.story');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'title' => 'required|string|max:255',
        'summary' => 'nullable|string',
        'content' => 'required|string',
        'status' => 'in:draft,published,archived',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = Str::slug($request->title);
        // Ensure unique slug
        if (Story::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }
        
        $organization = Organization::first();

        $story = Story::create([
            'title' => $request->title,
            'slug' => $slug,
            'organization_id' => $organization->id,
            'user_id' => auth()->id(),
            'summary' => $request->summary ?? null,
            'content' => $request->content,
            'status' => $request->status ?? 'published',
        ]);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $filePath = $request->file('photo')->store('stories', 'public');

            $story->photo()->create([
                'file_path' => $filePath,
                'caption' => $request->title,
            ]);
        }

        return redirect()->route('admin.stories.index')->with('success', 'Story created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Story $story)
    {
       return view('admin.stories.show', compact('story'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Story $story)
    {
        return view('admin.edit.story', compact('story'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Story $story)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'summary' => 'nullable|string',
        'content' => 'required|string',
        'status' => 'in:draft,published,archived',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Generate slug if title changed
    $slug = Str::slug($request->title);
    if ($slug !== $story->slug && Story::where('slug', $slug)->where('id', '!=', $story->id)->exists()) {
        $slug .= '-' . time();
    }

    // Update story details
    $story->update([
        'title' => $request->title,
        'slug' => $slug,
        'summary' => $request->summary ?? null,
        'content' => $request->content,
        'status' => $request->status ?? $story->status,
    ]);

    // Handle photo replacement
    if ($request->hasFile('photo')) {
        // Delete old photo from storage
        if ($story->photo) {
            Storage::disk('public')->delete($story->photo->file_path);
            $story->photo()->delete();
        }

        // Store new photo
        $filePath = $request->file('photo')->store('stories', 'public');
        $story->photo()->create([
            'file_path' => $filePath,
            'caption' => $request->title,
        ]);
    }

    return redirect()->route('admin.stories.index')->with('success', 'Story updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Story $story)
    {
        // Delete photo from storage before deleting story
        if ($story->photo) {
            Storage::disk('public')->delete($story->photo->file_path);
            $story->photo->delete();
        }
        
        $story->delete();

        return redirect()->route('admin.stories.index')->with('success', 'Story deleted successfully.');
    }
}
