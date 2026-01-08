<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Part;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // List all products with optional search
    public function products(Request $request)
    {
        $query = Part::with('partBrand');

        // If there is a search query, filter the results
        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('part_number', 'like', "%{$search}%")
                  ->orWhere('vin', 'like', "%{$search}%")
                  ->orWhere('frame_number', 'like', "%{$search}%");
            });
        }

        // Paginate results, keep query string for pagination links
        $parts = $query->latest()->paginate(30)->withQueryString();

        return view('products', compact('parts'));
    }

    // Single product view
public function product($id)
{
    // Main product with all required relationships
  $part = Part::with([
    'category',
    'partBrand',
    'photos',
    'variants.vehicleModel.brand',
    'variants.specifications.engineType',   // correct way
    'variants.specifications.transmissionType',
    'variants.specifications.driveType',
    'variants.vehicleModel'
])->findOrFail($id);

    // Main image (safe fallback guaranteed)
    $mainPhoto = $part->photos
        ->where('type', 'main')
        ->first()
        ?? $part->photos->first();

    // All photos (used for gallery, thumbnails, fullscreen)
    $photos = $part->photos;

    // Substitutions (same name or OEM, different product)
    $substitutions = Part::with('partBrand')
        ->where('id', '!=', $part->id)
        ->where(function ($q) use ($part) {
            $q->where('part_name', $part->part_name)
              ->orWhere('oem_number', $part->oem_number);
        })
        ->take(10)
        ->get();

    // Compatibility (via variants pivot)
    $compatibilities = $part->variants;

    dd($compatibilities);

    return view('product', compact(
        'part',
        'photos',
        'mainPhoto',
        'substitutions',
        'compatibilities'
    ));
}
}
