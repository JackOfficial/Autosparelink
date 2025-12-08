<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::with(['category', 'brand'])->latest()->get();
        return view('admin.parts.index', compact('parts'));
    }

    public function create()
    {
        return view('admin.parts.create', [
            'categories' => Category::all(),
            'brands' => Brand::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_number'     => 'required|string|max:255',
            'part_name'       => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'brand_id'        => 'required|exists:brands,id',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'stock_quantity'  => 'required|integer|min:0',
            'status'          => 'required|integer',
            'photo'           => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('parts', 'public');
        }

        Part::create($validated);

        return redirect()->route('admin.spare-parts.index')->with('success', 'Part created successfully.');
    }

    public function edit(String $id)
    {
        $part = Part::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.parts.edit', compact('part', 'categories', 'brands'));
    }

    public function update(Request $request, string $id)
{
    $part = Part::findOrFail($id);

    $request->validate([
        'part_number'    => 'required|string|max:255',
        'part_name'      => 'required|string|max:255',
        'category_id'    => 'required|exists:categories,id',
        'brand_id'       => 'required|exists:brands,id',
        'description'    => 'nullable|string',
        'price'          => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'status'         => 'required|integer',
        'photo'          => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
    ]);

    // Keep the old photo name
    $photoName = $part->photo;

    // If new photo uploaded
    if ($request->hasFile('photo')) {

        // Delete old file
        if ($photoName && file_exists(public_path('uploads/parts/' . $photoName))) {
            unlink(public_path('uploads/parts/' . $photoName));
        }

        // Generate new name
        $photoName = time() . '.' . $request->photo->extension();

        // Move uploaded file
        $request->photo->move(public_path('uploads/parts'), $photoName);
    }

    // Update part
    $part->update([
        'part_number'    => $request->part_number,
        'part_name'      => $request->part_name,
        'category_id'    => $request->category_id,
        'brand_id'       => $request->brand_id,
        'description'    => $request->description,
        'price'          => $request->price,
        'stock_quantity' => $request->stock_quantity,
        'status'         => $request->status,
        'photo'          => $photoName,
    ]);

    return redirect()
        ->route('admin.spare-parts.index')
        ->with('success', 'Part updated successfully');
}


   public function destroy(string $id)
{
    $part = Part::findOrFail($id);

    // Delete photo if exists
    if ($part->photo && file_exists(public_path('uploads/parts/' . $part->photo))) {
        unlink(public_path('uploads/parts/' . $part->photo));
    }

    // Delete part
    $part->delete();

    return redirect()
        ->route('admin.spare-parts.index')
        ->with('success', 'Part deleted successfully.');
}
}
