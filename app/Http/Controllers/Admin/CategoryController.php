<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
             // Parent categories only (parent_id IS NULL)
        $categories = Category::with(['children' => function ($query) {
        $query->orderBy('category_name', 'asc')->withCount('parts'); // Count parts in subcategories
    }])
    ->withCount('children')
    ->whereNull('parent_id')
    ->orderBy('category_name', 'asc')
    ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Category::whereNull('parent_id')->get(); // top-level categories
        return view('admin.categories.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $photoPath = null;
       if ($request->hasFile('photo')) {
    // Creates a name like: braking-system-171045600.jpg
    $filename = Str::slug($request->category_name) . '-' . time() . '.' . $request->file('photo')->extension();
    
    $photoPath = $request->file('photo')->storeAs('categories', $filename, 'public');
    }

        Category::create([
            'category_name' => $request->category_name,
            'photo' => $photoPath,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
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
    public function edit(Category $category)
    {
       $parents = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Category $category)
{
    $request->validate([
        'category_name' => 'required|string|max:255',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'parent_id' => 'nullable|exists:categories,id',
    ]);

    $data = $request->only(['category_name', 'parent_id']);

    if ($request->hasFile('photo')) {
        // 1. Delete old photo if it exists
        if ($category->photo && Storage::disk('public')->exists($category->photo)) {
            Storage::disk('public')->delete($category->photo);
        }

        // 2. Generate a clean, SEO-friendly name: "category-name-171045600.webp"
        $extension = $request->file('photo')->extension();
        $filename = Str::slug($request->category_name) . '-' . time() . '.' . $extension;

        // 3. Store with the custom name
        $data['photo'] = $request->file('photo')->storeAs('categories', $filename, 'public');
    }

    $category->update($data);

    return redirect()->route('admin.categories.index')
        ->with('success', 'Category updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(Category $category)
    {
        if ($category->photo && Storage::disk('public')->exists($category->photo)) {
            Storage::disk('public')->delete($category->photo);
        }
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
