<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Category;
use App\Models\Specification;
use Illuminate\Http\Request;

class PartCatalogController extends Controller
{
    /**
     * Display all spare parts compatible with a specification
     * (model-based or variant-based).
     */

    public function parts(){
        return view('parts.index');
    }

    public function index(Request $request, string $type, Specification $specification)
    {
        /* -------------------------------------------------
         | Validate route ↔ specification relationship
         |-------------------------------------------------- */

        // CASE 1: Model-based specification
        if (
            $type === 'model' &&
            $specification->vehicle_model_id &&
            is_null($specification->variant_id)
        ) {
             //dd("'Model and specification!' spec id: " . $specification->id . " and type: " . $type . " and mode ID: " . $specification->vehicle_model_id . " and variant ID: " . $specification->variant_id);
            $context = $specification->vehicleModel()->with('brand')->first();

            $partsQuery = Part::with(['category', 'partBrand', 'photos'])
                ->forSpecification($specification, $type);
        }

        // CASE 2: Variant-based specification
        elseif (
            $type === 'variant' &&
            $specification->vehicle_model_id &&
            $specification->variant_id
        ) {
             //dd("'Variant and specification!' spec id: " . $specification->id . " and type: " . $type . " and mode ID: " . $specification->vehicle_model_id . " and variant ID: " . $specification->variant_id);
            $context = $specification->variant()
                ->with(['vehicleModel.brand'])
                ->first();

            $partsQuery = Part::with(['category', 'partBrand', 'photos'])
                ->whereHas('fitments', function ($q) use ($specification) {
                    $q->where('variant_id', $specification->variant_id);
                });
        }

        // INVALID URL → kill it
        else {
           // dd("spec id: " . $specification->id . " and type: " . $type . " and mode ID: " . $specification->vehicle_model_id . " and variant ID: " . $specification->variant_id);
            abort(404);
        }

        /* -------------------------------------------------
         | Filters
         |-------------------------------------------------- */

        if ($request->filled('category')) {
            $partsQuery->where('category_id', $request->category);
        }

        if ($request->filled('min_price')) {
            $partsQuery->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $partsQuery->where('price', '<=', $request->max_price);
        }

        if ($request->boolean('in_stock')) {
            $partsQuery->where('stock_quantity', '>', 0);
        }

        if ($request->filled('q')) {
            $partsQuery->where(function ($q) use ($request) {
                $q->where('part_name', 'like', "%{$request->q}%")
                  ->orWhere('sku', 'like', "%{$request->q}%")
                  ->orWhere('part_number', 'like', "%{$request->q}%");
            });
        }

        /* -------------------------------------------------
         | Sorting
         |-------------------------------------------------- */

        match ($request->get('sort', 'latest')) {
            'price_asc'  => $partsQuery->orderBy('price', 'asc'),
            'price_desc' => $partsQuery->orderBy('price', 'desc'),
            'name_asc'   => $partsQuery->orderBy('part_name', 'asc'),
            default      => $partsQuery->latest(),
        };

        /* -------------------------------------------------
         | Execute query
         |-------------------------------------------------- */

        $parts = $partsQuery->paginate(24)->withQueryString();

        /* -------------------------------------------------
         | Categories (sidebar)
         |-------------------------------------------------- */

        $categories = Category::withCount('parts')
            ->whereNull('parent_id')
            ->orderBy('category_name')
            ->get();

        return view('parts.index', [
            'type'          => $type,
            'specification' => $specification,
            'context'       => $context, // model OR variant
            'parts'         => $parts,
            'categories'    => $categories,
        ]);
    }

    public function show(Part $part){
        dd("here");
          // Load relationships (eager loading)
    $part->load([
        'partBrand',                         // Brand of this part
        'photos',                            // Uploaded photos
        'fitments.vehicleModel.brand',       // Compatibility chain
        'substitutions.partBrand'          // Optional substitutions
    ]);

    // Photos for gallery
    $photos = $part->photos;

    // Substitutions (fallback to empty collection)
    $substitutions = $part->substitutions ?? collect();

    // Compatibility / fitments
    $compatibilities = $part->fitments ?? collect();

    return view('parts.show', compact(
        'part',
        'photos',
        'substitutions',
        'compatibilities'
    ));
    }
}
