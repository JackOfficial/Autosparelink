<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\VehicleModel;
use App\Models\Variant;
use App\Models\Part;
use App\Models\PartCategory;

class ModelPartController extends Controller
{
    // Show parts for a specific vehicle model
    public function model_parts(Request $request, $model_id)
    {
        $model = VehicleModel::with('brand', 'variants')->findOrFail($model_id);

        // Fetch parts with relationships
        $query = Part::with(['variant.vehicleModel.brand', 'vehicleModel.brand', 'category']);

        // Filter by model: include all parts of all variants
        $variantIds = $model->variants->pluck('id');
        $query->whereIn('variant_id', $variantIds);

        // Apply filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('part_name')) {
            $query->where('name', 'like', '%' . $request->part_name . '%');
        }
        if ($request->filled('variant_id')) {
            $query->where('variant_id', $request->variant_id);
        }

        $parts = $query->latest()->get();
        $categories = Category::all();

        return view('parts.index', compact('model', 'parts', 'categories'));
    }

    // Show parts for a specific variant
    public function variant_parts(Request $request, $variant_id)
    {
        $variant = Variant::with('vehicleModel.brand')->findOrFail($variant_id);

        $query = Part::with(['variant.vehicleModel.brand', 'vehicleModel.brand', 'category'])
            ->where('variant_id', $variant_id);

        // Apply filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('part_name')) {
            $query->where('name', 'like', '%' . $request->part_name . '%');
        }

        $parts = $query->latest()->get();
        $categories = Category::all();

        return view('parts.index', compact('variant', 'parts', 'categories'));
    }
}
