<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Specification;
use App\Models\Variant;
use App\Models\BodyType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\DriveType;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class SpecificationController extends Controller
{
    /**
     * List all specifications (with optional filtering).
     */
    public function index(Request $request)
    {
        // Optional: filter by variant if a variant_id is provided.
        $query = Specification::with(['variant.vehicleModel', 'bodyType', 'engineType', 'transmissionType', 'driveType']);

        if ($request->filled('variant_id')) {
            $query->where('variant_id', $request->variant_id);
        }

        $specifications = $query->latest()->paginate(20);

        return view('admin.specifications.index', compact('specifications'));
    }

public function model_specification(Request $request, $model_id)
{
    $model = VehicleModel::with('brand')->findOrFail($model_id);

    // Start query for specifications
    $query = Specification::with([
        'variant.vehicleModel.brand',
        'specifications.bodyType',
        'specifications.engineType',
        'specifications.transmissionType',
        'specifications.driveType'
    ])->where(function ($q) use ($model_id) {
        $q->whereHas('variant', function ($q2) use ($model_id) {
            $q2->where('vehicle_model_id', $model_id);
        })->orWhere('vehicle_model_id', $model_id);
    });

    // Apply filters from request
    if ($request->filled('body')) {
        $query->whereHas('bodyType', fn($q) => $q->where('name', 'like', '%' . $request->body . '%'));
    }
    if ($request->filled('engine')) {
        $query->whereHas('engineType', fn($q) => $q->where('name', 'like', '%' . $request->engine . '%'));
    }
    if ($request->filled('transmission')) {
        $query->whereHas('transmissionType', fn($q) => $q->where('name', 'like', '%' . $request->transmission . '%'));
    }
    if ($request->filled('drive')) {
        $query->whereHas('driveType', fn($q) => $q->where('name', 'like', '%' . $request->drive . '%'));
    }
    if ($request->filled('production_start')) {
        $query->where('production_start', '>=', $request->production_start);
    }
    if ($request->filled('production_end')) {
        $query->where('production_end', '<=', $request->production_end);
    }
    if ($request->filled('seats')) {
        $query->where('seats', $request->seats);
    }
    if ($request->filled('doors')) {
        $query->where('doors', $request->doors);
    }
    if ($request->filled('horsepower')) {
        $query->where('horsepower', $request->horsepower);
    }
    if ($request->filled('torque')) {
        $query->where('torque', $request->torque);
    }
    if ($request->filled('steering_position')) {
        $query->where('steering_position', 'like', '%' . $request->steering_position . '%');
    }
    if ($request->filled('fuel_efficiency')) {
        $query->where('fuel_efficiency', $request->fuel_efficiency);
    }

    $specifications = $query->latest()->get();

    // Reference tables for filters dropdowns if needed
    $vehicleModels = VehicleModel::all();
    $bodyTypes = BodyType::all();
    $engineTypes = EngineType::all();
    $driveTypes = DriveType::all();
    $transmissionTypes = TransmissionType::all();

    return view('specification', compact(
        'model', 
        'specifications', 
        'vehicleModels', 
        'bodyTypes', 
        'engineTypes', 
        'driveTypes', 
        'transmissionTypes'
    ));
}


public function variant_specification($variant_id, Request $request)
{
    // Fetch the variant for header info
    $variant = Variant::with('vehicleModel.brand')->findOrFail($variant_id);

    // Fetch all specifications for this variant
    $query = Specification::with([
        'variant.vehicleModel.brand',
        'bodyType',
        'engineType',
        'transmissionType',
        'driveType'
    ])->where('variant_id', $variant_id);

    // Optional filters (keep UI/UX consistent with model page)
    if ($request->filled('body')) {
        $query->whereHas('bodyType', fn($q) => $q->where('name', 'like', '%' . $request->body . '%'));
    }
    if ($request->filled('engine')) {
        $query->whereHas('engineType', fn($q) => $q->where('name', 'like', '%' . $request->engine . '%'));
    }
    if ($request->filled('transmission')) {
        $query->whereHas('transmissionType', fn($q) => $q->where('name', 'like', '%' . $request->transmission . '%'));
    }
    if ($request->filled('drive')) {
        $query->whereHas('driveType', fn($q) => $q->where('name', 'like', '%' . $request->drive . '%'));
    }

    $specifications = $query->latest()->get();

    // Pass filter data (for select boxes if needed)
    $vehicleModels = VehicleModel::all();
    $bodyTypes = BodyType::all();
    $engineTypes = EngineType::all();
    $driveTypes = DriveType::all();
    $transmissionTypes = TransmissionType::all();

    return view('specification', compact(
        'variant', 
        'specifications', 
        'vehicleModels', 
        'bodyTypes', 
        'engineTypes', 
        'driveTypes', 
        'transmissionTypes'
    ));
}

    /**
     * Show form to create a new specification.
     */
    public function create()
    {
        // Data needed for select dropdowns
        $variants = Variant::with('vehicleModel')->orderBy('name')->get();
        $bodyTypes = BodyType::orderBy('name')->get();
        $engineTypes = EngineType::orderBy('name')->get();
        $transmissionTypes = TransmissionType::orderBy('name')->get();
        $driveTypes = DriveType::orderBy('name')->get();

        return view('admin.specifications.create', compact(
            'variants',
            'bodyTypes',
            'engineTypes',
            'transmissionTypes',
            'driveTypes'
        ));
    }

    /**
     * Store a new specification.
     */
    public function store(Request $request)
    {
        // Basic validation; tweak as needed
        $validated = $request->validate([
            'variant_id'            => 'required|exists:variants,id',
            'body_type_id'          => 'required|exists:body_types,id',
            'engine_type_id'        => 'required|exists:engine_types,id',
            'transmission_type_id'  => 'required|exists:transmission_types,id',
            'drive_type_id'         => 'nullable|exists:drive_types,id',

            'production_start'      => 'nullable|digits:4|integer',
            'production_end'        => 'nullable|digits:4|integer',
            'horsepower'            => 'nullable|string|max:50',
            'torque'                => 'nullable|string|max:50',
            'fuel_capacity'         => 'nullable|string|max:50',
            'seats'                 => 'nullable|integer',
            'doors'                 => 'nullable|integer',
            'fuel_efficiency'       => 'nullable|string|max:100',
            'steering_position'     => 'nullable|string|max:50',
            'color'                 => 'nullable|string|max:50',
            'status'                => 'nullable|in:0,1',
        ]);

        // Default status = 1 if not provided
        if (!isset($validated['status'])) {
            $validated['status'] = 1;
        }

        Specification::create($validated);

        return redirect()
            ->route('admin.specifications.index')
            ->with('success', 'Specification added successfully.');
    }

    /**
     * Show form to edit an existing specification.
     */
    public function edit($id)
    {
        $spec = Specification::findOrFail($id);

        $variants = Variant::with('vehicleModel')->orderBy('name')->get();
        $bodyTypes = BodyType::orderBy('name')->get();
        $engineTypes = EngineType::orderBy('name')->get();
        $transmissionTypes = TransmissionType::orderBy('name')->get();
        $driveTypes = DriveType::orderBy('name')->get();

        return view('admin.specifications.edit', compact(
            'spec',
            'variants',
            'bodyTypes',
            'engineTypes',
            'transmissionTypes',
            'driveTypes'
        ));
    }

    /**
     * Update an existing specification.
     */
    public function update(Request $request, $id)
    {
        $spec = Specification::findOrFail($id);

        $validated = $request->validate([
            'variant_id'            => 'required|exists:variants,id',
            'body_type_id'          => 'required|exists:body_types,id',
            'engine_type_id'        => 'required|exists:engine_types,id',
            'transmission_type_id'  => 'required|exists:transmission_types,id',
            'drive_type_id'         => 'nullable|exists:drive_types,id',

            'production_start'      => 'nullable|digits:4|integer',
            'production_end'        => 'nullable|digits:4|integer',
            'horsepower'            => 'nullable|string|max:50',
            'torque'                => 'nullable|string|max:50',
            'fuel_capacity'         => 'nullable|string|max:50',
            'seats'                 => 'nullable|integer',
            'doors'                 => 'nullable|integer',
            'fuel_efficiency'       => 'nullable|string|max:100',
            'steering_position'     => 'nullable|string|max:50',
            'color'                 => 'nullable|string|max:50',
            'status'                => 'nullable|in:0,1',
        ]);

        // Default status = 1 if not provided
        if (!isset($validated['status'])) {
            $validated['status'] = 1;
        }

        $spec->update($validated);

        return redirect()
            ->route('admin.specifications.index')
            ->with('success', 'Specification updated successfully.');
    }

    /**
     * Delete a specification.
     */
    public function destroy($id)
    {
        $spec = Specification::findOrFail($id);
        $spec->delete();

        return redirect()
            ->route('admin.specifications.index')
            ->with('success', 'Specification removed successfully.');
    }
}
