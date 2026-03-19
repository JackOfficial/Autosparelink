<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    // We order by 'type' first, then by the latest created date
    // This effectively groups all 'blog' together and all 'news' together
    $blogCategories = BlogCategory::withCount('posts')
        ->orderBy('type', 'asc') // 'blog' starts with B, 'news' with N, so B comes first
        ->latest()
        ->paginate(10);

    return view('admin.blog-categories.index', compact('blogCategories'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blog-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category'    => 'required|string|max:255|unique:blog_categories,name',
            'type'        => 'required|in:blog,news',
            'description' => 'nullable|string|max:1000',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
        
        $image_path = null; 

        if ($request->hasFile('photo')) {
            $image_path = $request->file('photo')->store('blog_categories', 'public');
        }
             
        $blogCategory = BlogCategory::create([
            'name'        => $request->input('category'),
            'slug'        => Str::slug($request->input('category')),
            'type'        => $request->input('type'),
            'description' => $request->input('description'),
            'photo'       => $image_path,
        ]);

        if($blogCategory){
            return redirect()->route('admin.blog-categories.index')->with('message', 'Category saved successfully!');
        }
        
        return redirect()->back()->with('message', 'Category could not be saved. Try again!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = BlogCategory::findOrFail($id);
        return view('admin.blog-categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $blogCategory = BlogCategory::findOrFail($id);

        $request->validate([
            'category'    => 'required|string|max:255|unique:blog_categories,name,' . $id,
            'type'        => 'required|in:blog,news',
            'description' => 'nullable|string|max:1000',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
       
        $image_path = $blogCategory->photo; 

        if ($request->hasFile('photo')) {
            // Delete old photo if a new one is uploaded
            if ($blogCategory->photo && Storage::disk('public')->exists($blogCategory->photo)) {
                Storage::disk('public')->delete($blogCategory->photo);
            }
            $image_path = $request->file('photo')->store('blog_categories', 'public');
        }

        $status = $blogCategory->update([
            'name'        => $request->input('category'),
            'slug'        => Str::slug($request->input('category')),
            'type'        => $request->input('type'),
            'description' => $request->input('description'),
            'photo'       => $image_path,
        ]);

        if($status){
            return redirect()->route('admin.blog-categories.index')->with('message', 'Category updated successfully!');
        }

        return redirect()->back()->with('message', 'Category could not be updated. Try again!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $blogCategory = BlogCategory::findOrFail($id);
        
        // Note: Using SoftDeletes in your Model means the database record stays, 
        // so we don't delete the physical photo file here.
        $deleted = $blogCategory->delete();

        if($deleted){
          return redirect()->back()->with('message', 'Category moved to trash successfully');
        }

        return redirect()->back()->with('message', 'Category could not be deleted');
    }
}