<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::withCount('vehicleModels')->latest()->get();

        return view('admin.vehicle-brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vehicle-brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        // Added 'unique:brands,brand_name' to prevent duplicates
        'brand_name' => 'required|string|max:255|unique:brands,brand_name',
        'brand_logo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        'description' => 'nullable|string',
        'country' => 'nullable|string|max:255',
        'website' => 'nullable|url|max:255',
    ]);

    $logoPath = null;

    if ($request->hasFile('brand_logo')) {
        $logoPath = $request->file('brand_logo')->store('brands', 'public');
    }

    Brand::create([
        'brand_name' => $request->brand_name,
        'brand_logo' => $logoPath,
        'description' => $request->description,
        'country' => $request->country,
        'website' => $request->website,
    ]);

    return redirect()->route('admin.vehicle-brands.index')->with('success', 'Brand created successfully');
}

    /**
 * Display the specified brand along with its vehicle models.
 */

public function show(string $id)
{
    // Find the brand or fail
    $brand = Brand::with(['vehicleModels', 'vehicleModels.brand', 'vehicleModels.variants'])
              ->withCount('vehicleModels')
              ->findOrFail($id);
    // Pass to the view
    return view('admin.vehicle-brands.show', compact('brand'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       $brand = Brand::findOrFail($id);
        return view('admin.vehicle-brands.edit', compact('brand'));
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

    $logoPath = $brand->brand_logo; // keep old image by default

    // If a new image is uploaded
    if ($request->hasFile('brand_logo')) {

        // Delete old image if it exists
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            Storage::disk('public')->delete($logoPath);
        }

        // Store new image
        $logoPath = $request->file('brand_logo')->store('brands', 'public');
    }

    // Update brand
    $brand->update([
        'brand_name' => $request->brand_name,
        'brand_logo' => $logoPath,
        'description' => $request->description,
        'country' => $request->country,
        'website' => $request->website,
    ]);

    return redirect()->route('admin.vehicle-brands.index')->with('success', 'Brand updated successfully');
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

        return redirect()->route('admin.vehicle-brands.index')->with('success', 'Brand deleted successfully');
    }
}
