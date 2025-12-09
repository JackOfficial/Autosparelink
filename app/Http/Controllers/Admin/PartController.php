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

        // Store the photo using Storage
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('parts', 'public');
        }

        Part::create($validated);

        return redirect()->route('admin.spare-parts.index')->with('success', 'Part created successfully.');
    }

    public function edit(string $id)
    {
        return view('admin.parts.edit', [
            'part' => Part::findOrFail($id),
            'categories' => Category::all(),
            'brands' => Brand::all()
        ]);
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

        $data = $request->except('photo');

        // If user uploads new photo
        if ($request->hasFile('photo')) {

            // Delete old photo from storage
            if ($part->photo && Storage::disk('public')->exists($part->photo)) {
                Storage::disk('public')->delete($part->photo);
            }

            // Store new photo
            $data['photo'] = $request->file('photo')->store('parts', 'public');
        }

        // Update part
        $part->update($data);

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('success', 'Part updated successfully');
    }

    public function destroy(string $id)
    {
        $part = Part::findOrFail($id);

        if ($part->photo && Storage::disk('public')->exists($part->photo)) {
            Storage::disk('public')->delete($part->photo);
        }

        $part->delete();

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('success', 'Part deleted successfully.');
    }
}
