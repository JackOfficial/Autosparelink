<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog_categories;
use Inertia\Inertia;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogCategories = Blog_categories::all();
        return view('admin.manage.blog-categories', compact('blogCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create.blog-category');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'photo' => 'nullable'
        ]);
        
        $image_path = null; 

        if ($request->hasFile('photo')) {
            $image_path = $request->file('photo')->store('images/blog categories', 'public');
             }
             
             $blogCategory = Blog_categories::create([
                'blog_category' => $request->input('category'),
                'photo' => $image_path,
            ]);

        if($blogCategory){
            return redirect('/admin/blogCategories')->with('message', 'Blog category saved successfully!');
        }
        else{
            return redirect()->back()->with('message', 'Blog category could not be saved. Try again!');
          }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Blog_categories::where('id', $id)
        ->first();
        return view('admin.edit.blog-category', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
        ]);
       
        $image_path = null; 

        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048',
            ]);
            $image_path = $request->file('photo')->store('images/blog categories', 'public');
             }

             $blogCategory = Blog_categories::where('id', $id)->update([
                'blog_category' => $request->input('category'),
                'photo' => $image_path,
            ]);

        if($blogCategory){
            return redirect('/admin/blogCategories')->with('message', 'Blog category updated successfully!');
        }
        else{
            return redirect()->back()->with('message', 'Blog category could not be updated. Try again!');
          }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blogCategory = Blog_categories::where('id', $id)->delete();
        if($blogCategory){
          return redirect()->back()->with('message', 'Blog category deleted successfully');
        }
        else{
            return redirect()->back()->with('message', 'Blog category could not be deleted');
        }
    }
}
