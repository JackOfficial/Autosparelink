<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Category;
use App\Models\PartBrand;
use App\Models\PartState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Added for transactions

class PartController extends Controller
{
    public function index(Request $request)
    {
        $parts = Part::forCurrentSeller()
            ->with([
                'category:id,category_name', 
                'partBrand:id,name', 
                'state',
                'photos', 
                'fitments.vehicleModel.brand'
            ])
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('part_name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%")
                      ->orWhere('part_number', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('shop.parts.index', compact('parts'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = PartBrand::all();
        $states = PartState::all();
        return view('shop.parts.create', compact('categories', 'brands', 'states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_name'      => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'part_brand_id'  => 'nullable|exists:part_brands,id',
            'part_state_id'  => 'required|exists:part_states,id',
            'price'          => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'part_number'    => 'nullable|string|max:100',
            'description'    => 'nullable|string',
        ]);

        // Using a transaction is safer when model boot logic is doing math
        DB::transaction(function () use ($validated) {
            auth()->user()->shop->parts()->create($validated);
        });

        return redirect()->route('shop.parts.index')->with('success', 'Part added successfully.');
    }

    public function edit(Part $part)
    {
        // Use the scope to prevent unauthorized access
        $part = Part::forCurrentSeller()->findOrFail($part->id);
        
        $categories = Category::all();
        $brands = PartBrand::all();
        $states = PartState::all();

        return view('shop.parts.edit', compact('part', 'categories', 'brands', 'states'));
    }

    public function update(Request $request, Part $part)
    {
        $part = Part::forCurrentSeller()->findOrFail($part->id);

        $validated = $request->validate([
            'part_name'      => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'part_brand_id'  => 'nullable|exists:part_brands,id',
            'price'          => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status'         => 'required|in:active,inactive',
        ]);

        $part->update($validated);

        return redirect()->route('shop.parts.index')->with('success', 'Part updated.');
    }

    public function destroy(Part $part)
    {
        $part = Part::forCurrentSeller()->findOrFail($part->id);

        // Optional: Check if part is in active orders before deleting
        $part->delete();

        return redirect()->route('shop.parts.index')->with('success', 'Part deleted.');
    }
}