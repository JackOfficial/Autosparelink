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
    public function index(){
       return view('parts.index');
    }

  public function catalog($brandSlug = null, $modelSlug = null, $variantSlug = null)
{
    // 1. Translate Slugs to IDs so Livewire doesn't crash
    $brandId   = $brandSlug   ? \App\Models\Brand::where('slug', $brandSlug)->value('id') : null;
    $modelId   = $modelSlug   ? \App\Models\VehicleModel::where('slug', $modelSlug)->value('id') : null;
    $variantId = $variantSlug ? \App\Models\Variant::where('slug', $variantSlug)->value('id') : null;

    // 2. Fetch the vehicle info for your 'vinData' display
    $vehicleData = null;
    if ($modelId) {
        $vehicleData = \App\Models\VehicleModel::with('brand')->find($modelId);
    }

    // 3. Match the names to your Blade variables
    return view('parts.index', [
        'brandId'     => $brandId,     // Matches $brandId in your @livewire
        'modelId'     => $modelId,     // Matches $modelId in your @livewire
        'variantId'   => $variantId,   // Matches $variantId in your @livewire
        'vehicleData' => $vehicleData, // Matches $vehicleData in your @livewire
        'search'      => request('search'),
    ]);
}
 
   public function parts($id)
{
    // 1. Load everything in ONE query to the database
    $part = Part::with([
        'partBrand',
        'photos',
        'fitments.vehicleModel.brand',
        'substitutions.partBrand', // Uncommented and added brand eager loading
        'substitutions.photos'     // Helpful if you want to show small icons of the alternatives
    ])->findOrFail($id);

    // 2. Simplify variables using the loaded relationship
    // No need to use ?? collect() if the relationship is properly defined; 
    // Laravel returns an empty collection by default for HasMany/BelongsToMany.
    
    return view('parts.show', [
        'part'            => $part,
        'photos'          => $part->photos,
        'substitutions'   => $part->substitutions,
        'compatibilities' => $part->fitments,
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
