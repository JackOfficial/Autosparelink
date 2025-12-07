<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'brand_name' => 'required|string|max:255',
        'brand_logo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        'description' => 'nullable|string',
        'country' => 'nullable|string|max:255',
        'website' => 'nullable|url|max:255',
    ]);

    $logoPath = null;

    if ($request->hasFile('brand_logo')) {
        // Store in "public/brands" folder, filename auto-generated
        $logoPath = $request->file('brand_logo')->store('brands', 'public');
    }

    Brand::create([
        'brand_name' => $request->brand_name,
        'brand_logo' => $logoPath,
        'description' => $request->description,
        'country' => $request->country,
        'website' => $request->website,
    ]);

    return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'brand_name' => 'required|string|max:255',
            'brand_logo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'description' => 'nullable|string',
            'country' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        $logoName = $brand->brand_logo;

        if ($request->hasFile('brand_logo')) {
            if ($logoName && file_exists(public_path('uploads/brands/' . $logoName))) {
                unlink(public_path('uploads/brands/' . $logoName));
            }

            $logoName = time() . '.' . $request->brand_logo->extension();
            $request->brand_logo->move(public_path('uploads/brands'), $logoName);
        }

        $brand->update([
            'brand_name' => $request->brand_name,
            'brand_logo' => $logoName,
            'description' => $request->description,
            'country' => $request->country,
            'website' => $request->website,
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $brand = Brand::findOrFail($id);

        if ($brand->brand_logo && file_exists(public_path('uploads/brands/' . $brand->brand_logo))) {
            unlink(public_path('uploads/brands/' . $brand->brand_logo));
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully');
    }
}
