<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Category;
use App\Models\PartBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::with(['category', 'partBrand'])->latest()->get();
        return view('admin.parts.index', compact('parts'));
    }

    public function create()
    {
        return view('admin.parts.create', [
            'categories' => Category::all(),
            'partBrands' => PartBrand::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku'             => 'required|string|max:255|unique:parts,sku',
            'part_number'     => 'nullable|string|max:255',
            'part_name'       => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'part_brand_id'   => 'required|exists:part_brands,id',
            'oem_number'      => 'nullable|string|max:255',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'stock_quantity'  => 'required|integer|min:0',
            'status'          => 'required|integer',
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Store photo if uploaded
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('parts', 'public');
        }

        Part::create($validated);

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('success', 'Part created successfully.');
    }

    public function edit(string $id)
    {
        return view('admin.parts.edit', [
            'part'       => Part::findOrFail($id),
            'categories' => Category::all(),
            'partBrands' => PartBrand::all(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $part = Part::findOrFail($id);

        $validated = $request->validate([
            'sku'             => "required|string|max:255|unique:parts,sku,{$id}",
            'part_number'     => 'nullable|string|max:255',
            'part_name'       => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'part_brand_id'   => 'required|exists:part_brands,id',
            'oem_number'      => 'nullable|string|max:255',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'stock_quantity'  => 'required|integer|min:0',
            'status'          => 'required|integer',
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle photo
        if ($request->hasFile('photo')) {
            if ($part->photo && Storage::disk('public')->exists($part->photo)) {
                Storage::disk('public')->delete($part->photo);
            }
            $validated['photo'] = $request->file('photo')->store('parts', 'public');
        }

        $part->update($validated);

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('success', 'Part updated successfully.');
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
