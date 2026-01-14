<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Category;
use App\Models\PartBrand;
use App\Models\Variant;
use App\Models\Specification;
use App\Models\PartFitment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::with(['category', 'partBrand', 'photos', 'variants'])
            ->latest()
            ->paginate(20);

        return view('admin.parts.index', compact('parts'));
    }

    public function create()
    {
        return view('admin.parts.create', [
            'categories' => Category::orderBy('category_name')->get(),
            'partBrands' => PartBrand::orderBy('name')->get(),
            'variants'   => Variant::with(['vehicleModel.brand', 'specifications'])->get(),
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

            'variant_specifications'   => 'nullable|array',
            'variant_specifications.*' => 'exists:specifications,id',
        ]);

        // Generate SKU
        $brandName = PartBrand::findOrFail($validated['part_brand_id'])->name;
        $categoryName = Category::findOrFail($validated['category_id'])->category_name;

        $validated['sku'] = Part::generateSku(
            $brandName,
            $categoryName,
            $validated['part_name']
        );

        $part = Part::create($validated);

        /* ---------------------------
         | SEO-FRIENDLY PHOTO UPLOAD
         |--------------------------- */
        if ($request->hasFile('photos')) {

            $brandSlug    = Str::slug($part->partBrand->name);
            $categorySlug = Str::slug($part->category->category_name);
            $partSlug     = Str::slug($part->part_name);

            $folder = "parts/{$brandSlug}/{$categorySlug}";

            foreach ($request->file('photos') as $photo) {

                $filename = "{$partSlug}-" . uniqid() . '.' . $photo->getClientOriginalExtension();
                $path = $photo->storeAs($folder, $filename, 'public');

                $part->photos()->create([
                    'file_path' => $path,
                    'caption'   => "{$part->part_name} spare part",
                ]);
            }
        }

        /* ---------------------------
         | Save fitments
         |--------------------------- */
        if ($request->filled('variant_specifications')) {
            foreach ($request->variant_specifications as $specId) {
                $spec = Specification::findOrFail($specId);
                $variant = $spec->variant;

                PartFitment::create([
                    'part_id'          => $part->id,
                    'variant_id'       => $variant->id,
                    'vehicle_model_id' => $variant->vehicle_model_id,
                    'status'           => 'active',
                    'year_start'       => $spec->production_start,
                    'year_end'         => $spec->production_end,
                ]);
            }
        }

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('success', 'Part created successfully.');
    }

    public function edit($id)
    {
        $part = Part::with(['photos', 'fitments'])->findOrFail($id);

        return view('admin.parts.edit', [
            'part'       => $part,
            'categories' => Category::all(),
            'partBrands' => PartBrand::all(),
            'variants'   => Variant::with(['vehicleModel.brand', 'specifications'])->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $part = Part::with(['photos', 'fitments'])->findOrFail($id);

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

            'variant_specifications'   => 'nullable|array',
            'variant_specifications.*' => 'exists:specifications,id',
        ]);

        /* ---------------------------
         | Regenerate SKU if changed
         |--------------------------- */
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

        /* ---------------------------
         | Replace photos (SEO-safe)
         |--------------------------- */
        if ($request->hasFile('photos')) {

            foreach ($part->photos as $photo) {
                Storage::disk('public')->delete($photo->file_path);
                $photo->delete();
            }

            $brandSlug    = Str::slug($part->partBrand->name);
            $categorySlug = Str::slug($part->category->category_name);
            $partSlug     = Str::slug($part->part_name);

            $folder = "parts/{$brandSlug}/{$categorySlug}";

            foreach ($request->file('photos') as $photo) {

                $filename = "{$partSlug}-" . uniqid() . '.' . $photo->getClientOriginalExtension();
                $path = $photo->storeAs($folder, $filename, 'public');

                $part->photos()->create([
                    'file_path' => $path,
                    'caption'   => "{$part->part_name} spare part",
                ]);
            }
        }

        /* ---------------------------
         | Replace fitments
         |--------------------------- */
        $part->fitment()->delete();

        if ($request->filled('variant_specifications')) {
            foreach ($request->variant_specifications as $specId) {
                $spec = Specification::findOrFail($specId);
                $variant = $spec->variant;

                PartFitment::create([
                    'part_id'          => $part->id,
                    'variant_id'       => $variant->id,
                    'vehicle_model_id' => $variant->vehicle_model_id,
                    'status'           => 'active',
                    'year_start'       => $spec->production_start,
                    'year_end'         => $spec->production_end,
                ]);
            }
        }

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('success', 'Part updated successfully.');
    }

    public function destroy($id)
    {
        $part = Part::with(['photos', 'fitments'])->findOrFail($id);

        foreach ($part->photos as $photo) {
            Storage::disk('public')->delete($photo->file_path);
            $photo->delete();
        }

        $part->fitment()->delete();
        $part->delete();

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('success', 'Part deleted successfully.');
    }
}
