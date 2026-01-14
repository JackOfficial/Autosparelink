<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Category;
use App\Models\PartBrand;
use App\Models\Variant;
use App\Models\Specification;
use App\Models\PartFitment;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartController extends Controller
{
    /* ============================
     | LIST
     ============================ */
    public function index()
    {
        $parts = Part::with([
                'category',
                'partBrand',
                'photos',
                'fitments.variant',
                'fitments.vehicleModel'
            ])
            ->latest()
            ->paginate(20);

        return view('admin.parts.index', compact('parts'));
    }

    /* ============================
     | CREATE
     ============================ */
    public function create()
    {
        return view('admin.parts.create', [
            'categories' => Category::orderBy('category_name')->get(),
            'partBrands' => PartBrand::orderBy('name')->get(),

            // Models (including those WITHOUT variants)
            'vehicleModels' => VehicleModel::with(['brand', 'variants.specifications', 'specifications'])
                ->orderBy('model_name')
                ->get(),

            // Variants (for models that have them)
            'variants' => Variant::with(['vehicleModel.brand', 'specifications'])
                ->orderBy('name')
                ->get(),
        ]);
    }

    /* ============================
     | STORE
     ============================ */
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

            // Variant-based
            'variant_specifications'   => 'nullable|array',
            'variant_specifications.*' => 'exists:specifications,id',

            // Model-only (NO variants)
            'model_fitments'   => 'nullable|array',
            'model_fitments.*' => 'exists:specifications,id',
        ]);

        /* -------- SKU -------- */
        $validated['sku'] = Part::generateSku(
            PartBrand::findOrFail($validated['part_brand_id'])->name,
            Category::findOrFail($validated['category_id'])->category_name,
            $validated['part_name']
        );

        $part = Part::create($validated);

        /* -------- SEO PHOTOS -------- */
        if ($request->hasFile('photos')) {

            $folder = 'parts/' .
                Str::slug($part->partBrand->name) . '/' .
                Str::slug($part->category->category_name);

            foreach ($request->file('photos') as $photo) {
                $filename = Str::slug($part->part_name) . '-' . uniqid() . '.' . $photo->extension();
                $path = $photo->storeAs($folder, $filename, 'public');

                $part->photos()->create([
                    'file_path' => $path,
                    'caption'   => $part->part_name,
                ]);
            }
        }

        /* -------- VARIANT FITMENTS -------- */
        if ($request->filled('variant_specifications')) {
            foreach ($request->variant_specifications as $specId) {

                $spec = Specification::with('variant')->findOrFail($specId);

                PartFitment::create([
                    'part_id'          => $part->id,
                    'variant_id'       => $spec->variant->id,
                    'vehicle_model_id' => $spec->variant->vehicle_model_id,
                    'status'           => 'active',
                    'year_start'       => $spec->production_start,
                    'year_end'         => $spec->production_end,
                ]);
            }
        }

        /* -------- MODEL-ONLY FITMENTS -------- */
        if ($request->filled('model_fitments')) {
            foreach ($request->model_fitments as $specId) {

                $spec = Specification::with('vehicleModel')->findOrFail($specId);

                PartFitment::create([
                    'part_id'          => $part->id,
                    'vehicle_model_id' => $spec->vehicle_model_id,
                    'variant_id'       => null,
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

    /* ============================
     | EDIT
     ============================ */
    public function edit($id)
    {
        $part = Part::with(['photos', 'fitments'])->findOrFail($id);

        return view('admin.parts.edit', [
            'part'          => $part,
            'categories'    => Category::all(),
            'partBrands'    => PartBrand::all(),
            'vehicleModels' => VehicleModel::with(['brand', 'variants.specifications', 'specifications'])->get(),
            'variants'      => Variant::with(['vehicleModel.brand', 'specifications'])->get(),
        ]);
    }

    /* ============================
     | UPDATE
     ============================ */
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

            'model_fitments'   => 'nullable|array',
            'model_fitments.*' => 'exists:specifications,id',
        ]);

        /* -------- SKU CHANGE -------- */
        if (
            $validated['part_name'] !== $part->part_name ||
            $validated['category_id'] != $part->category_id ||
            $validated['part_brand_id'] != $part->part_brand_id
        ) {
            $validated['sku'] = Part::generateSku(
                PartBrand::findOrFail($validated['part_brand_id'])->name,
                Category::findOrFail($validated['category_id'])->category_name,
                $validated['part_name']
            );
        }

        $part->update($validated);

        /* -------- REPLACE PHOTOS -------- */
        if ($request->hasFile('photos')) {
            foreach ($part->photos as $photo) {
                Storage::disk('public')->delete($photo->file_path);
                $photo->delete();
            }

            $folder = 'parts/' .
                Str::slug($part->partBrand->name) . '/' .
                Str::slug($part->category->category_name);

            foreach ($request->file('photos') as $photo) {
                $filename = Str::slug($part->part_name) . '-' . uniqid() . '.' . $photo->extension();
                $path = $photo->storeAs($folder, $filename, 'public');

                $part->photos()->create([
                    'file_path' => $path,
                    'caption'   => $part->part_name,
                ]);
            }
        }

        /* -------- RESET FITMENTS -------- */
        $part->fitments()->delete();

        /* VARIANT */
        if ($request->filled('variant_specifications')) {
            foreach ($request->variant_specifications as $specId) {
                $spec = Specification::with('variant')->findOrFail($specId);

                PartFitment::create([
                    'part_id'          => $part->id,
                    'variant_id'       => $spec->variant->id,
                    'vehicle_model_id' => $spec->variant->vehicle_model_id,
                    'status'           => 'active',
                    'year_start'       => $spec->production_start,
                    'year_end'         => $spec->production_end,
                ]);
            }
        }

        /* MODEL-ONLY */
        if ($request->filled('model_fitments')) {
            foreach ($request->model_fitments as $specId) {
                $spec = Specification::findOrFail($specId);

                PartFitment::create([
                    'part_id'          => $part->id,
                    'vehicle_model_id' => $spec->vehicle_model_id,
                    'variant_id'       => null,
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

    /* ============================
     | DELETE
     ============================ */
    public function destroy($id)
    {
        $part = Part::with(['photos', 'fitments'])->findOrFail($id);

        foreach ($part->photos as $photo) {
            Storage::disk('public')->delete($photo->file_path);
            $photo->delete();
        }

        $part->fitments()->delete();
        $part->delete();

        return redirect()
            ->route('admin.spare-parts.index')
            ->with('success', 'Part deleted successfully.');
    }
}
