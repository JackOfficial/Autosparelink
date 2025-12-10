<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Variant;
use App\Models\Category;

class SparePartController extends Controller
{
     public function parts($variantId)
    {
        // Load variant with related model, brand, and parts
        $variant = Variant::with([
            'vehicleModel.brand',
            'parts.category'
        ])->findOrFail($variantId);

        // Get the parent vehicle model
        $model = $variant->vehicleModel;

        // Get all categories (to display in the Categories tab)
        $categories = Category::orderBy('name', 'asc')->get();

        return view('spare-parts', compact(
            'variant',
            'model',
            'categories'
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
