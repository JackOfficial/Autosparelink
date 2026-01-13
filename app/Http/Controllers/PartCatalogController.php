<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Category;
use App\Models\VehicleModel;
use App\Models\Variant;
use Illuminate\Http\Request;

class PartCatalogController extends Controller
{
    /**
     * Display all spare parts compatible with a vehicle model or variant.
     */
    public function index(Request $request, string $type, int $id)
    {
        /* ----------------------------
         | Resolve context (model / variant)
         |---------------------------- */
        if ($type === 'model') {

            $context = VehicleModel::with('brand')->findOrFail($id);

            $partsQuery = Part::with([
                'category',
                'partBrand',
                'photos'
            ])->whereHas('fitments', function ($q) use ($id) {
                $q->where('vehicle_model_id', $id);
            });

        } elseif ($type === 'variant') {

            $context = Variant::with(['vehicleModel.brand'])->findOrFail($id);

            $partsQuery = Part::with([
                'category',
                'partBrand',
                'photos'
            ])->whereHas('fitments', function ($q) use ($id) {
                $q->where('variant_id', $id);
            });

        } else {
            abort(404);
        }

        /* ----------------------------
         | Filters (professional-grade)
         |---------------------------- */

        // Category filter
        if ($request->filled('category')) {
            $partsQuery->where('category_id', $request->category);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $partsQuery->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $partsQuery->where('price', '<=', $request->max_price);
        }

        // In-stock only
        if ($request->boolean('in_stock')) {
            $partsQuery->where('stock_quantity', '>', 0);
        }

        // Search by name, SKU, or part number
        if ($request->filled('q')) {
            $partsQuery->where(function ($q) use ($request) {
                $q->where('part_name', 'like', "%{$request->q}%")
                  ->orWhere('sku', 'like', "%{$request->q}%")
                  ->orWhere('part_number', 'like', "%{$request->q}%");
            });
        }

        /* ----------------------------
         | Sorting
         |---------------------------- */
        $sort = $request->get('sort', 'latest');

        match ($sort) {
            'price_asc'  => $partsQuery->orderBy('price', 'asc'),
            'price_desc' => $partsQuery->orderBy('price', 'desc'),
            'name_asc'   => $partsQuery->orderBy('part_name', 'asc'),
            default      => $partsQuery->latest(),
        };

        /* ----------------------------
         | Execute query with pagination
         |---------------------------- */
        $parts = $partsQuery->paginate(24)->withQueryString();

        /* ----------------------------
         | Categories (for sidebar filter)
         |---------------------------- */
        $categories = Category::withCount('parts')
            ->whereNull('parent_id')
            ->orderBy('category_name')
            ->get();

        return view('parts.index', [
            'type'       => $type,
            'context'    => $context,   // model or variant
            'parts'      => $parts,
            'categories' => $categories,
        ]);
    }
}
