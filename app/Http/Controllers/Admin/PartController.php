<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Category;
use App\Models\PartBrand;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::with(['category', 'partBrand', 'variants'])->latest()->paginate(20);
        return view('admin.parts.index', compact('parts'));
    }

    public function create()
    {
        return view('admin.parts.create', [
            'categories' => Category::all(),
            'partBrands' => PartBrand::all(),
            'variants'   => Variant::with(['vehicleModel.brand', 'engineType'])->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_number'      => 'nullable|string|max:255',
            'part_name'        => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'part_brand_id'    => 'required|exists:part_brands,id',
            'oem_number'       => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'stock_quantity'   => 'required|integer|min:0',
            'status'           => 'required|integer',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'variants'         => 'nullable|array',
            'variants.*'       => 'exists:variants,id',
        ]);

        // Generate SEO-friendly SKU
        $brandName = PartBrand::findOrFail($request->part_brand_id)->name;
        $categoryName = Category::findOrFail($request->category_id)->category_name;
        $validated['sku'] = Part::generateSku($brandName, $categoryName, $request->part_name);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('parts', 'public');
        }

        $part = Part::create($validated);

        // Attach variants
        $part->variants()->sync($request->variants ?? []);

        return redirect()->route('admin.spare-parts.index')
                         ->with('success', 'Part created successfully.');
    }

    public function edit($id)
    {
        $part = Part::with('variants')->findOrFail($id);

        return view('admin.parts.edit', [
            'part'       => $part,
            'categories' => Category::all(),
            'partBrands' => PartBrand::all(),
            'variants'   => Variant::with(['vehicleModel.brand', 'engineType'])->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $part = Part::findOrFail($id);

        $validated = $request->validate([
            'part_number'      => 'nullable|string|max:255',
            'part_name'        => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'part_brand_id'    => 'required|exists:part_brands,id',
            'oem_number'       => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'stock_quantity'   => 'required|integer|min:0',
            'status'           => 'required|integer',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'variants'         => 'nullable|array',
            'variants.*'       => 'exists:variants,id',
        ]);

        // Regenerate SKU if part name, brand, or category changes
        if (
            $request->part_name !== $part->part_name ||
            $request->category_id != $part->category_id ||
            $request->part_brand_id != $part->part_brand_id
        ) {
            $brandName = PartBrand::findOrFail($request->part_brand_id)->name;
            $categoryName = Category::findOrFail($request->category_id)->category_name;
            $validated['sku'] = Part::generateSku($brandName, $categoryName, $request->part_name);
        } else {
            $validated['sku'] = $part->sku; // keep old SKU
        }

        // Handle photo replacement
        if ($request->hasFile('photo')) {
            if ($part->photo && Storage::disk('public')->exists($part->photo)) {
                Storage::disk('public')->delete($part->photo);
            }
            $validated['photo'] = $request->file('photo')->store('parts', 'public');
        }

        $part->update($validated);

        // Sync variants
        $part->variants()->sync($request->variants ?? []);

        return redirect()->route('admin.spare-parts.index')
                         ->with('success', 'Part updated successfully.');
    }

    public function destroy($id)
    {
        $part = Part::findOrFail($id);

        if ($part->photo && Storage::disk('public')->exists($part->photo)) {
            Storage::disk('public')->delete($part->photo);
        }

        $part->variants()->detach();
        $part->delete();

        return redirect()->route('admin.spare-parts.index')
                         ->with('success', 'Part deleted successfully.');
    }
}
