<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Category;
use App\Models\PartBrand;
use Illuminate\Http\Request;

class PartController extends Controller
{
    /**
     * Display a listing of the shop's parts.
     */
    public function index(Request $request)
    {
        $parts = Part::with(['category:id,category_name', 'partBrand:id,name', 'photos'])
            ->where('shop_id', auth()->user()->shop_id)
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

    /**
     * Show the form for creating a new part.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = PartBrand::all();
        return view('shop.parts.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created part in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            // Add other validations as needed
        ]);

        // Automatically inject the shop_id from the authenticated user
        $validated['shop_id'] = auth()->user()->shop_id;

        Part::create($validated);

        return redirect()->route('shop.parts.index')->with('success', 'Part added successfully.');
    }

    /**
     * Show the form for editing the specified part.
     */
    public function edit(Part $part)
    {
        // Security Check: Ensure the part belongs to this shop
        if ($part->shop_id !== auth()->user()->shop_id) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::all();
        $brands = PartBrand::all();
        
        return view('shop.parts.edit', compact('part', 'categories', 'brands'));
    }

    /**
     * Update the specified part in storage.
     */
    public function update(Request $request, Part $part)
    {
        if ($part->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        $validated = $request->validate([
            'part_name' => 'required|string|max:255',
            // ... other validations
        ]);

        $part->update($validated);

        return redirect()->route('shop.parts.index')->with('success', 'Part updated.');
    }

    /**
     * Remove the specified part from storage.
     */
    public function destroy(Part $part)
    {
        if ($part->shop_id !== auth()->user()->shop_id) {
            abort(403);
        }

        $part->delete();

        return redirect()->route('shop.parts.index')->with('success', 'Part deleted.');
    }
}