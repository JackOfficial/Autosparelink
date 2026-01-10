<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VehicleModel;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;

class ModelController extends Controller
{
    // List all vehicle models
    public function index()
    {
         $brands = Brand::with(['vehicleModels' => function($query) {
        $query->orderBy('model_name', 'asc');
        }])->orderBy('brand_name', 'asc')->get();
        return view('admin.vehicle-models.index', compact('brands'));
    }

    // Show create form
    public function create()
    {
        $brands = Brand::all();
        return view('admin.vehicle-models.create', compact('brands'));
    }

    // Store new vehicle model
    public function store(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'model_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'production_start_year' => 'nullable|digits:4|integer',
            'production_end_year' => 'nullable|digits:4|integer',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|integer',
        ]);

        $data = $request->only([
            'brand_id',
            'model_name',
            'description',
            'production_start_year',
            'production_end_year',
            'status'
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('vehicle-models', 'public');
        }

        VehicleModel::create($data);

        return redirect()->route('admin.vehicle-models.index')->with('success', 'Vehicle model created successfully.');
    }

    /**
 * Display the specified vehicle model and its variants.
 */
public function show(VehicleModel $vehicleModel)
{
    // Load related brand and variants
     $vehicleModel->load([
        'brand',
        'variants' => function ($query) {
            $query->withCount('specifications');
        }
    ]);

    return view('admin.vehicle-models.show', compact('vehicleModel'));
}


    // Show edit form
    public function edit(VehicleModel $vehicleModel)
    {
        $brands = Brand::all();
        return view('admin.vehicle-models.edit', compact('vehicleModel', 'brands'));
    }

    // Update vehicle model
    public function update(Request $request, VehicleModel $vehicleModel)
    {
        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'model_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'production_start_year' => 'nullable|digits:4|integer',
            'production_end_year' => 'nullable|digits:4|integer',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|integer',
        ]);

        $data = $request->only([
            'brand_id',
            'model_name',
            'description',
            'production_start_year',
            'production_end_year',
            'status'
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($vehicleModel->photo) {
                Storage::disk('public')->delete($vehicleModel->photo);
            }
            $data['photo'] = $request->file('photo')->store('vehicle-models', 'public');
        }

        $vehicleModel->update($data);

        return redirect()->route('admin.vehicle-models.index')->with('success', 'Vehicle model updated successfully.');
    }

    // Delete vehicle model
    public function destroy(VehicleModel $vehicleModel)
    {
        // Delete photo from storage
        if ($vehicleModel->photo) {
            Storage::disk('public')->delete($vehicleModel->photo);
        }

        $vehicleModel->delete();

        return redirect()->route('admin.vehicle-models.index')
                         ->with('success', 'Vehicle model deleted successfully.');
    }
}
