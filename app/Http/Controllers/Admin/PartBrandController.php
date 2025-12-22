<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = PartBrand::latest()->get();

        return view('admin.part-brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.part-brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_name' => 'required|string|max:255|unique:part_brands,name',
            'description' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url',
            'type' => 'required|in:OEM,Aftermarket',
            'brand_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $brand = new PartBrand();
        $brand->name = $validated['brand_name'];
        $brand->description = $validated['description'] ?? null;
        $brand->country = $validated['country'] ?? null;
        $brand->website = $validated['website'] ?? null;
        $brand->type = $validated['type'];

        if ($request->hasFile('brand_logo')) {
            $brand->logo = $request->file('brand_logo')
                ->store('part-brands', 'public');
        }

        $brand->save();

        return redirect()
            ->route('admin.part-brands.index')
            ->with('success', 'Part brand added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = PartBrand::findOrFail($id);

        return view('admin.part-brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brand = PartBrand::findOrFail($id);

        return view('admin.part-brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $brand = PartBrand::findOrFail($id);

        $validated = $request->validate([
            'brand_name' => 'required|string|max:255|unique:part_brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url',
            'type' => 'required|in:OEM,Aftermarket',
            'brand_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $brand->name = $validated['brand_name'];
        $brand->description = $validated['description'] ?? null;
        $brand->country = $validated['country'] ?? null;
        $brand->website = $validated['website'] ?? null;
        $brand->type = $validated['type'];

        if ($request->hasFile('brand_logo')) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }

            $brand->logo = $request->file('brand_logo')
                ->store('part-brands', 'public');
        }

        $brand->save();

        return redirect()
            ->route('admin.part-brands.index')
            ->with('success', 'Part brand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = PartBrand::findOrFail($id);

        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()
            ->route('admin.part-brands.index')
            ->with('success', 'Part brand deleted successfully.');
    }
}
