<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specification;
use App\Models\Variant;
use App\Models\VehicleModel;
use App\Models\BodyType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\DriveType;
use Illuminate\Http\Request;

class SpecificationController extends Controller
{

public function index(Request $request)
{
    // 1. Efficient Eager Loading
    $query = Specification::with([
        'variant.vehicleModel.brand',
        'bodyType',
        'engineDisplacement',
        'engineType',
        'transmissionType',
        'driveType',
    ]);

    // 2. Database Level Filtering
    if ($request->filled('variant_id')) {
        $query->where('variant_id', $request->variant_id);
    }

    if ($request->filled('vehicle_model_id')) {
        $query->where('vehicle_model_id', $request->vehicle_model_id);
    }

    // 3. Sorting & Retrieval 
    // Note: In a large DB, move these sorts to 'join' statements for speed
    $specifications = $query->latest()->get();

    // 4. Grouping by the unique Variant identity
    $groupedSpecs = $specifications->groupBy(function ($spec) {
        // Fallback logic if a variant isn't assigned yet
        $brand = $spec->variant?->vehicleModel?->brand?->brand_name ?? 'Unknown Brand';
        $model = $spec->variant?->vehicleModel?->model_name ?? 'Unknown Model';
        $variant = $spec->variant?->name ?? 'Unassigned Variant';

        return "{$brand}|{$model}|{$variant}";
    });

    return view('admin.specifications.index', compact('groupedSpecs'));
}


   public function create(Request $request)
{
    // Capture the ID from the redirect: 
    // redirect()->route('admin.specifications.create', ['vehicle_model_id' => $model->id])
    $vehicle_model_id = $request->query('vehicle_model_id');

    return view('admin.specifications.create', compact('vehicle_model_id'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'variant_id'        => 'nullable|exists:variants,id',
            'vehicle_model_id'  => 'nullable|exists:vehicle_models,id',
            'body_type_id'      => 'required|exists:body_types,id',
            'engine_type_id'    => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id'     => 'nullable|exists:drive_types,id',
            'production_start'  => 'nullable|digits:4|integer',
            'production_end'    => 'nullable|digits:4|integer',
            'horsepower'        => 'nullable|string|max:50',
            'torque'            => 'nullable|string|max:50',
            'fuel_capacity'     => 'nullable|string|max:50',
            'seats'             => 'nullable|integer',
            'doors'             => 'nullable|integer',
            'fuel_efficiency'   => 'nullable|string|max:100',
            'steering_position' => 'nullable|string|max:50',
            'color'             => 'nullable|string|max:50',
            'status'            => 'nullable|in:0,1',
        ]);

        if (!$validated['variant_id'] && !$validated['vehicle_model_id']) {
            return back()->withErrors(['variant_id' => 'You must select either a variant or a vehicle model.']);
        }

        $validated['status'] = $validated['status'] ?? 1;

        Specification::create($validated);

        return redirect()->route('admin.specifications.index')->with('success', 'Specification added successfully.');
    }

    public function edit($id)
    {
        $specification = Specification::findOrFail($id);
        $variants = Variant::with('vehicleModel')->orderBy('name')->get();
        $vehicleModels = VehicleModel::orderBy('model_name')->get();

        return view('admin.specifications.edit', compact(
            'specification',
            'variants',
            'vehicleModels',
        ));
    }

   public function show($id)
{
    $specification = Specification::with([
        // When specification belongs to a variant
        'variant.vehicleModel.brand',

        // When specification belongs directly to a model (no variant)
        'vehicleModel.brand',

        // Specification attributes
        'bodyType',
        'engineType',
        'transmissionType',
        'driveType',
    ])->findOrFail($id);

    return view('admin.specifications.show', compact('specification'));
}


    public function update(Request $request, $id)
    {
        $spec = Specification::findOrFail($id);

        $validated = $request->validate([
            'variant_id'        => 'nullable|exists:variants,id',
            'vehicle_model_id'  => 'nullable|exists:vehicle_models,id',
            'body_type_id'      => 'required|exists:body_types,id',
            'engine_type_id'    => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id'     => 'nullable|exists:drive_types,id',
            'production_start'  => 'nullable|digits:4|integer',
            'production_end'    => 'nullable|digits:4|integer',
            'horsepower'        => 'nullable|string|max:50',
            'torque'            => 'nullable|string|max:50',
            'fuel_capacity'     => 'nullable|string|max:50',
            'seats'             => 'nullable|integer',
            'doors'             => 'nullable|integer',
            'fuel_efficiency'   => 'nullable|string|max:100',
            'steering_position' => 'nullable|string|max:50',
            'color'             => 'nullable|string|max:50',
            'status'            => 'nullable|in:0,1',
        ]);

        if (!$validated['variant_id'] && !$validated['vehicle_model_id']) {
            return back()->withErrors(['variant_id' => 'You must select either a variant or a vehicle model.']);
        }

        $validated['status'] = $validated['status'] ?? 1;

        $spec->update($validated);

        return redirect()->route('admin.specifications.index')->with('success', 'Specification updated successfully.');
    }

    public function destroy($id)
    {
        Specification::findOrFail($id)->delete();
        return redirect()->route('admin.specifications.index')->with('success', 'Specification removed successfully.');
    }
}
