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
    public function product()
    {
        return view('product');
    }
}
