<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specification;
use App\Models\Variant;
use App\Models\BodyType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\DriveType;
use Illuminate\Http\Request;

class VariantSpecificationController extends Controller
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
