<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PartBrand;
use Illuminate\Support\Facades\Storage;

class PartBrandController extends Controller
{
    /**
     * Display a listing of the part brands.
     */
    public function index()
    {
        $brands = PartBrand::orderBy('name')->paginate(20);
        return view('admin.part-brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new part brand.
     */
    public function create()
    {
        $brandTypes = ['IAM', 'OEM'];
        return view('admin.part-brands.create', compact('brandTypes'));
    }

    /**
     * Store a newly created part brand in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:part_brands,name',
            'country' => 'nullable|string|max:100',
            'brand_type' => 'required|in:IAM,OEM,Genuine,Other',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brand-logos', 'public');
        }

        PartBrand::create($validated);

        return redirect()->route('admin.part-brands.index')->with('success', 'Part brand created successfully.');
    }

    /**
     * Show the form for editing the specified part brand.
     */
    public function edit(PartBrand $partBrand)
    {
        $brandTypes = ['IAM', 'OEM', 'Genuine', 'Other'];
        return view('admin.part-brands.edit', compact('partBrand', 'brandTypes'));
    }

    /**
     * Update the specified part brand in storage.
     */
    public function update(Request $request, PartBrand $partBrand)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:part_brands,name,' . $partBrand->id,
            'country' => 'nullable|string|max:100',
            'brand_type' => 'required|in:IAM,OEM,Genuine,Other',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brand-logos', 'public');
        }

        $partBrand->update($validated);

        return redirect()->route('admin.part-brands.index')->with('success', 'Part brand updated successfully.');
    }

    /**
     * Remove the specified part brand from storage.
     */
    public function destroy(PartBrand $partBrand)
    {
        // Optionally: Delete logo file
        if ($partBrand->logo) {
            Storage::disk('public')->delete($partBrand->logo);
        }

        $partBrand->delete();

        return redirect()->route('admin.part-brands.index')->with('success', 'Part brand deleted successfully.');
    }

    /**
     * Display the specified part brand.
     */
    public function show(PartBrand $partBrand)
    {
        return view('admin.part-brands.show', compact('partBrand'));
    }
}
