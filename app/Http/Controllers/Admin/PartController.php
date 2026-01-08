<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Category;
use App\Models\PartBrand;
use App\Models\Variant;
use App\Models\PartFitment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::with(['category', 'partBrand', 'photos', 'fitment.variant'])
            ->latest()
            ->paginate(20);

        return view('admin.parts.index', compact('parts'));
    }

    public function create()
    {
        return view('admin.parts.create', [
            'categories' => Category::orderBy('category_name')->get(),
            'partBrands' => PartBrand::orderBy('name')->get(),
            'variants'   => Variant::with(['vehicleModel.brand', 'specification'])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_number'    => 'nullable|string|max:255',
            'part_name'      => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'part_brand_id'  => 'required|exists:part_brands,id',
            'oem_number'     => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status'         => 'required|integer',

            'photos'   => 'nullable|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'variants'   => 'nullable|array',
            'variants.*' => 'exists:variants,id',
        ]);

        // Generate SKU
        $brandName = PartBrand::findOrFail($validated['part_brand_id'])->name;
        $categoryName = Category::findOrFail($validated['category_id'])->category_name;
        $validated['sku'] = Part::generateSku(
            $brandName,
            $categoryName,
            $validated['part_name']
        );

        // Create part
        $part = Part::create($validated);

        // Save photos separately
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('parts', 'public');
                $part->photos()->create([
                    'photo_url' => $path,
                    'type'      => $index === 0 ? 'main' : 'detail',
                ]);
            }
        }

        // Save fitments in part_fitments table using variant specification
        if ($request->filled('variants')) {
            foreach ($request->variants as $variantId) {
                $variant = Variant::findOrFail($variantId);
                $spec = $variant->specification;

                PartFitment::create([
                    'part_id'          => $part->id,
                    'variant_id'       => $variantId,
                    'vehicle_model_id' => $variant->vehicle_model_id,
                    'status'           => 1, // active
                    'year_start'       => $spec->production_start ?? null,
                    'year_end'         => $spec->production_end ?? null,
                ]);
            }
        }

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('success', 'Part created successfully.');
    }

    public function edit($id)
    {
        $part = Part::with(['photos', 'fitment.variant'])->findOrFail($id);

        return view('admin.parts.edit', [
            'part'       => $part,
            'categories' => Category::all(),
            'partBrands' => PartBrand::all(),
            'variants'   => Variant::with(['vehicleModel.brand', 'specification'])->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $part = Part::with('photos', 'fitment')->findOrFail($id);

        $validated = $request->validate([
            'part_number'    => 'nullable|string|max:255',
            'part_name'      => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'part_brand_id'  => 'required|exists:part_brands,id',
            'oem_number'     => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status'         => 'required|integer',

            'photos'   => 'nullable|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'variants'   => 'nullable|array',
            'variants.*' => 'exists:variants,id',
        ]);

        // Regenerate SKU only if needed
        if (
            $validated['part_name'] !== $part->part_name ||
            $validated['category_id'] != $part->category_id ||
            $validated['part_brand_id'] != $part->part_brand_id
        ) {
            $brandName = PartBrand::findOrFail($validated['part_brand_id'])->name;
            $categoryName = Category::findOrFail($validated['category_id'])->category_name;
            $validated['sku'] = Part::generateSku(
                $brandName,
                $categoryName,
                $validated['part_name']
            );
        }

        $part->update($validated);

        // Replace photos if new ones uploaded
        if ($request->hasFile('photos')) {
            foreach ($part->photos as $photo) {
                Storage::disk('public')->delete($photo->photo_url);
                $photo->delete();
            }

            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('parts', 'public');
                $part->photos()->create([
                    'photo_url' => $path,
                    'type'      => $index === 0 ? 'main' : 'detail',
                ]);
            }
        }

        // Replace all fitments
        $part->fitment()->delete(); // remove old fitments
        if ($request->filled('variants')) {
            foreach ($request->variants as $variantId) {
                $variant = Variant::findOrFail($variantId);
                $spec = $variant->specification;

                PartFitment::create([
                    'part_id'          => $part->id,
                    'variant_id'       => $variantId,
                    'vehicle_model_id' => $variant->vehicle_model_id,
                    'status'           => 1,
                    'year_start'       => $spec->production_start ?? null,
                    'year_end'         => $spec->production_end ?? null,
                ]);
            }
        }

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('success', 'Part updated successfully.');
    }

    public function destroy($id)
    {
        $part = Part::with('photos', 'fitment')->findOrFail($id);

        // Delete photos from storage
        foreach ($part->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_url);
            $photo->delete();
        }

        // Delete fitments
        $part->fitment()->delete();

        // Delete part
        $part->delete();

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('success', 'Part deleted successfully.');
    }
}
