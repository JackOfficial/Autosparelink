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
    public function index()
    {
        // Using paginate for a professional admin list
        $blogCategories = BlogCategory::latest()->paginate(10);
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
            'category' => 'required|string|max:255|unique:blog_categories,name',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
        
        $image_path = null; 

        if ($request->hasFile('photo')) {
            $image_path = $request->file('photo')->store('blog_categories', 'public');
        }
             
        $blogCategory = BlogCategory::create([
            'name' => $request->input('category'),
            'slug' => Str::slug($request->input('category')),
            'photo' => $image_path,
        ]);

        if($blogCategory){
            return redirect()->route('admin.blog-categories.index')->with('message', 'Blog category saved successfully!');
        }
        
        return redirect()->back()->with('message', 'Blog category could not be saved. Try again!');
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
            'category' => 'required|string|max:255|unique:blog_categories,name,' . $id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
       
        $image_path = $blogCategory->photo; 

        if ($request->hasFile('photo')) {
            // Delete old photo if a new one is uploaded
            if ($blogCategory->photo) {
                Storage::disk('public')->delete($blogCategory->photo);
            }
            $image_path = $request->file('photo')->store('blog_categories', 'public');
        }

        $status = $blogCategory->update([
            'name' => $request->input('category'),
            'slug' => Str::slug($request->input('category')),
            'photo' => $image_path,
        ]);

        if($status){
            return redirect()->route('admin.blog-categories.index')->with('message', 'Blog category updated successfully!');
        }

        return redirect()->back()->with('message', 'Blog category could not be updated. Try again!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $blogCategory = BlogCategory::findOrFail($id);
        
        // Note: Since you use SoftDeletes, this doesn't actually delete the file yet.
        // If you want to permanently delete the image, do it in a forceDelete logic.
        $deleted = $blogCategory->delete();

        if($deleted){
          return redirect()->back()->with('message', 'Blog category deleted successfully');
        }

        return redirect()->back()->with('message', 'Blog category could not be deleted');
    }
}