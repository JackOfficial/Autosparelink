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
    $query = Specification::with([
        'variant.vehicleModel.brand',
        'vehicleModel.brand',
        'bodyType',
        'engineDisplacement',
        'engineType',
        'transmissionType',
        'driveType',
    ]);

    if ($request->filled('variant_id')) {
        $query->where('variant_id', $request->variant_id);
    }

    if ($request->filled('vehicle_model_id')) {
        $query->where('vehicle_model_id', $request->vehicle_model_id);
    }

    // Get all specs
    $specifications = $query->latest()->get();

    // Sort by brand, model, variant
    $specifications = $specifications->sortBy([
        function ($spec) {
            return optional(optional($spec->variant)->vehicleModel->brand ?? $spec->vehicleModel->brand)->brand_name ?? 'N/A';
        },
        function ($spec) {
            return optional(optional($spec->variant)->vehicleModel ?? $spec->vehicleModel)->model_name ?? 'N/A';
        },
        function ($spec) {
            return optional($spec->variant)->name ?? 'N/A';
        },
    ]);

    // Group by Brand | Model | Variant
    $groupedSpecs = $specifications->groupBy(function ($spec) {
        $brand = optional(optional($spec->variant)->vehicleModel->brand ?? $spec->vehicleModel->brand)->brand_name ?? 'N/A';
        $model = optional(optional($spec->variant)->vehicleModel ?? $spec->vehicleModel)->model_name ?? 'N/A';
        $variant = optional($spec->variant)->name ?? 'N/A';

        return $brand.'|'.$model.'|'.$variant;
    });

    return view('admin.specifications.index', compact('groupedSpecs'));
}


    public function create()
    {
        $variants = Variant::with('vehicleModel')->orderBy('name')->get();
        $vehicleModels = VehicleModel::orderBy('model_name')->get();
        $bodyTypes = BodyType::orderBy('name')->get();
        $engineTypes = EngineType::orderBy('name')->get();
        $transmissionTypes = TransmissionType::orderBy('name')->get();
        $driveTypes = DriveType::orderBy('name')->get();

        return view('admin.specifications.create', compact(
            'variants',
            'vehicleModels',
            'bodyTypes',
            'engineTypes',
            'transmissionTypes',
            'driveTypes'
        ));
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
