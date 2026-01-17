<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Part;
use App\Models\VehicleModel;

class SparePartController extends Controller
{
    public function show($id){

    }
 
     public function parts($id)
    {
       // Load part with brand, photos, compatibilities, substitutions
        $part = Part::with([
            'partBrand',            // The brand of this part
            'photos',               // All uploaded photos
            'fitments.vehicleModel.brand', // Compatibility table
            // 'substitutions.partBrand'             // Substitution parts and their brands
        ])->findOrFail($id);

        // Photos for gallery
        $photos = $part->photos;

        // Substitutions (other parts that can replace this one)
        $substitutions = $part->substitutions ?? collect();

        // Compatibility (pivot table linking parts to vehicle variants)
        $compatibilities = $part->fitments ?? collect();

        return view('parts.show', [
            'part' => $part,
            'photos' => $photos,
            'substitutions' => $substitutions,
            'compatibilities' => $compatibilities
        ]);
    }

    public function showCompatibleParts(Request $request, $type, $id)
    {
        // Determine the specification type
        if ($type === 'model') {
            $specification = VehicleModel::with(['brand', 'variants'])->findOrFail($id);
            $partsQuery = Part::whereHas('specifications', function($q) use ($id) {
                $q->where('vehicle_model_id', $id);
            });
        } elseif ($type === 'variant') {
            $specification = Variant::with(['vehicleModel', 'vehicleModel.brand'])->findOrFail($id);
            $partsQuery = Part::whereHas('specifications', function($q) use ($id) {
                $q->where('variant_id', $id);
            });
        } else {
            abort(404);
        }

        // Apply filters
        if ($request->filled('category')) {
            $partsQuery->whereIn('category_id', $request->category);
        }

        if ($request->filled('brand_filter')) {
            $partsQuery->whereIn('brand_id', $request->brand_filter);
        }

        if ($request->filled('in_stock')) {
            $partsQuery->where('in_stock', true);
        }

        if ($request->filled('min_price')) {
            $partsQuery->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $partsQuery->where('price', '<=', $request->max_price);
        }

        // Apply search
        if ($request->filled('q')) {
            $query = $request->q;
            $partsQuery->where('name', 'LIKE', "%{$query}%")
                       ->orWhere('part_number', 'LIKE', "%{$query}%");
        }

        // Apply sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $partsQuery->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $partsQuery->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $partsQuery->orderBy('created_at', 'desc');
                    break;
                case 'popularity':
                    $partsQuery->orderBy('sales_count', 'desc'); // assuming you track sales
                    break;
            }
        } else {
            $partsQuery->latest();
        }

        // Paginate results
        $parts = $partsQuery->paginate(12)->withQueryString();

        // Filters data
        $categories = Category::all();
        $brands = Brand::all();

        return view('parts.compatible', compact(
            'specification', 'parts', 'categories', 'brands', 'type'
        ));
    }

    /**
     * AJAX filter spare parts by category.
     */
    public function filterByCategory(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|integer',
            'category_id' => 'nullable|integer',
        ]);

        $variant = Variant::findOrFail($request->variant_id);

        $query = $variant->spareParts()->with('category');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        return response()->json([
            'parts' => $query->get()
        ]);
    }

    /**
     * AJAX search spare parts by name.
     */
    public function search(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|integer',
            'keyword' => 'nullable|string',
        ]);

        $variant = Variant::findOrFail($request->variant_id);

        $query = $variant->spareParts()->with('category');

        if ($request->keyword) {
            $query->where('name', 'LIKE', '%' . $request->keyword . '%');
        }

        return response()->json([
            'parts' => $query->get()
        ]);
    }
}
