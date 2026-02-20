<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VehicleModel;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ModelController extends Controller
{
    // List all vehicle models
    public function index()
    {
          $brands = Brand::with([
        'vehicleModels' => function ($query) {
            $query->orderBy('model_name', 'asc')
                  ->with('photos');
        }
    ])
    ->orderBy('brand_name', 'asc')
    ->get();

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
        'model_name' => [
            'required',
            'string',
            'max:255',
            // This rule checks the vehicle_models table where model_name exists 
            // AND brand_id matches the one currently being submitted.
            Rule::unique('vehicle_models')->where(fn ($query) => 
                $query->where('brand_id', $request->brand_id)
            ),
        ],
        'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ], [
        // Custom error message for the unique constraint
        'model_name.unique' => 'This model name already exists for the selected brand.',
    ]);

    $vehicleModel = VehicleModel::create(
        $request->only([
            'brand_id',
            'model_name',
            'description',
            'production_start_year',
            'production_end_year',
            'status',
        ])
    );

    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('vehicle-models', 'public');

        $vehicleModel->photos()->create([
            'file_path' => $path,
        ]);
    }

    return redirect()
        ->route('admin.vehicle-models.index')
        ->with('success', 'Vehicle model created successfully.');
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
        'model_name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('vehicle_models')
                ->where(fn ($query) => $query->where('brand_id', $request->brand_id))
                ->ignore($vehicleModel->id), // Essential: Don't count the current record as a duplicate
        ],
        'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ], [
        'model_name.unique' => 'This model name is already assigned to this brand.',
    ]);

    $vehicleModel->update(
        $request->only([
            'brand_id',
            'model_name',
            'description',
            'production_start_year',
            'production_end_year',
            'status',
        ])
    );

    if ($request->hasFile('photo')) {
        // Handle photo update for morph relationship
        if ($vehicleModel->photos()->exists()) {
            foreach ($vehicleModel->photos as $oldPhoto) {
                Storage::disk('public')->delete($oldPhoto->file_path);
                $oldPhoto->delete();
            }
        }

        $path = $request->file('photo')->store('vehicle-models', 'public');

        $vehicleModel->photos()->create([
            'file_path' => $path,
        ]);
    }

    return redirect()
        ->route('admin.vehicle-models.index')
        ->with('success', 'Vehicle model updated successfully.');
}


    // Delete vehicle model
   public function destroy(VehicleModel $vehicleModel)
{
    foreach ($vehicleModel->photos as $photo) {
        Storage::disk('public')->delete($photo->file_path);
        $photo->delete();
    }

    $vehicleModel->delete();

    return redirect()
        ->route('admin.vehicle-models.index')
        ->with('success', 'Vehicle model deleted successfully.');
}
}
