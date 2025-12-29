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
        // Load relationships: category and partBrand
        $parts = Part::with(['category', 'partBrand', 'variants'])->latest()->get();
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
            'sku'              => 'required|string|max:255|unique:parts,sku',
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

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('parts', 'public');
        }

        $part = Part::create($validated);

        // Attach variants (fitments)
        if ($request->has('variants')) {
            $part->variants()->sync($request->variants);
        }

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
            'variants'   => Variant::with(['vehicleModel.vehicleBrand', 'engineType'])->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $part = Part::findOrFail($id);

        $validated = $request->validate([
            'sku'              => "required|string|max:255|unique:parts,sku,{$part->id}",
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

        // Delete photo
        if ($part->photo && Storage::disk('public')->exists($part->photo)) {
            Storage::disk('public')->delete($part->photo);
        }

        // Detach variants
        $part->variants()->detach();

        $part->delete();

        return redirect()->route('admin.spare-parts.index')
                         ->with('success', 'Part deleted successfully.');
    }
}
