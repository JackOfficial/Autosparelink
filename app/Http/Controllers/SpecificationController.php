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
    public function index(Request $request)
    {
        $query = Specification::with(['variant.vehicleModel', 'vehicleModel', 'bodyType', 'engineType', 'transmissionType', 'driveType']);

        if ($request->filled('variant_id')) {
            $query->where('variant_id', $request->variant_id);
        }

        if ($request->filled('vehicle_model_id')) {
            $query->where('vehicle_model_id', $request->vehicle_model_id);
        }

        $specifications = $query->latest()->paginate(20);

        return view('admin.specifications.index', compact('specifications'));
    }

    public function show($type, $id)
    {
        if ($type === 'model') {
            $item = VehicleModel::with(['brand', 'variants'])->findOrFail($id);
            $specifications = Specification::where('vehicle_model_id', $id)->get();
        } elseif ($type === 'variant') {
            $item = Variant::with(['vehicleModel', 'vehicleModel.brand'])->findOrFail($id);
            $specifications = Specification::with(['vehicleModel', 'variant', 'bodyType', 'engineType', 'transmissionType', 'driveType'])->where('variant_id', $id)->get();
        } else {
            abort(404);
        }

        return view('specification', compact('item', 'specifications', 'type'));
    }

    public function model_specification(Request $request, $model_id)
    {
        $model = VehicleModel::with('brand', 'variants')->findOrFail($model_id);

        $query = Specification::with(['variant.vehicleModel.brand', 'vehicleModel', 'bodyType', 'engineType', 'transmissionType', 'driveType'])
            ->where(function ($q) use ($model_id) {
                $q->where('vehicle_model_id', $model_id) // specs attached directly to model
                  ->orWhereHas('variant', fn($q2) => $q2->where('vehicle_model_id', $model_id)); // specs attached via variant
            });

        // Apply filters
        if ($request->filled('body')) $query->whereHas('bodyType', fn($q) => $q->where('name', 'like', '%' . $request->body . '%'));
        if ($request->filled('engine')) $query->whereHas('engineType', fn($q) => $q->where('name', 'like', '%' . $request->engine . '%'));
        if ($request->filled('transmission')) $query->whereHas('transmissionType', fn($q) => $q->where('name', 'like', '%' . $request->transmission . '%'));
        if ($request->filled('drive')) $query->whereHas('driveType', fn($q) => $q->where('name', 'like', '%' . $request->drive . '%'));
        if ($request->filled('production_start')) $query->where('production_start', '>=', $request->production_start);
        if ($request->filled('production_end')) $query->where('production_end', '<=', $request->production_end);
        if ($request->filled('seats')) $query->where('seats', $request->seats);
        if ($request->filled('doors')) $query->where('doors', $request->doors);
        if ($request->filled('horsepower')) $query->where('horsepower', $request->horsepower);
        if ($request->filled('torque')) $query->where('torque', $request->torque);
        if ($request->filled('steering_position')) $query->where('steering_position', 'like', '%' . $request->steering_position . '%');
        if ($request->filled('fuel_efficiency')) $query->where('fuel_efficiency', $request->fuel_efficiency);

        $specifications = $query->latest()->get();

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

    public function variant_specification(Request $request, $variant_id)
    {
        $variant = Variant::with('vehicleModel.brand')->findOrFail($variant_id);

        $query = Specification::with(['variant.vehicleModel.brand', 'vehicleModel', 'bodyType', 'engineType', 'transmissionType', 'driveType'])
            ->where('variant_id', $variant_id);

        if ($request->filled('body')) $query->whereHas('bodyType', fn($q) => $q->where('name', 'like', '%' . $request->body . '%'));
        if ($request->filled('engine')) $query->whereHas('engineType', fn($q) => $q->where('name', 'like', '%' . $request->engine . '%'));
        if ($request->filled('transmission')) $query->whereHas('transmissionType', fn($q) => $q->where('name', 'like', '%' . $request->transmission . '%'));
        if ($request->filled('drive')) $query->whereHas('driveType', fn($q) => $q->where('name', 'like', '%' . $request->drive . '%'));

        $specifications = $query->latest()->get();

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

    public function create()
    {
        $variants = Variant::with('vehicleModel')->orderBy('name')->get();
        $bodyTypes = BodyType::orderBy('name')->get();
        $engineTypes = EngineType::orderBy('name')->get();
        $transmissionTypes = TransmissionType::orderBy('name')->get();
        $driveTypes = DriveType::orderBy('name')->get();

        return view('admin.specifications.create', compact('variants', 'bodyTypes', 'engineTypes', 'transmissionTypes', 'driveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => 'nullable|exists:variants,id',
            'vehicle_model_id' => 'nullable|exists:vehicle_models,id',
            'body_type_id' => 'required|exists:body_types,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id' => 'nullable|exists:drive_types,id',
            'production_start' => 'nullable|digits:4|integer',
            'production_end' => 'nullable|digits:4|integer',
            'horsepower' => 'nullable|string|max:50',
            'torque' => 'nullable|string|max:50',
            'fuel_capacity' => 'nullable|string|max:50',
            'seats' => 'nullable|integer',
            'doors' => 'nullable|integer',
            'fuel_efficiency' => 'nullable|string|max:100',
            'steering_position' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'status' => 'nullable|in:0,1',
        ]);

        // Require either variant_id or vehicle_model_id
        if (!$validated['variant_id'] && !$validated['vehicle_model_id']) {
            return back()->withErrors(['variant_id' => 'You must select a variant or a vehicle model.']);
        }

        $validated['status'] = $validated['status'] ?? 1;

        Specification::create($validated);

        return redirect()->route('admin.specifications.index')->with('success', 'Specification added successfully.');
    }

    public function edit($id)
    {
        $spec = Specification::findOrFail($id);
        $variants = Variant::with('vehicleModel')->orderBy('name')->get();
        $bodyTypes = BodyType::orderBy('name')->get();
        $engineTypes = EngineType::orderBy('name')->get();
        $transmissionTypes = TransmissionType::orderBy('name')->get();
        $driveTypes = DriveType::orderBy('name')->get();

        return view('admin.specifications.edit', compact('spec', 'variants', 'bodyTypes', 'engineTypes', 'transmissionTypes', 'driveTypes'));
    }

    public function update(Request $request, $id)
    {
        $spec = Specification::findOrFail($id);

        $validated = $request->validate([
            'variant_id' => 'nullable|exists:variants,id',
            'vehicle_model_id' => 'nullable|exists:vehicle_models,id',
            'body_type_id' => 'required|exists:body_types,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id' => 'nullable|exists:drive_types,id',
            'production_start' => 'nullable|digits:4|integer',
            'production_end' => 'nullable|digits:4|integer',
            'horsepower' => 'nullable|string|max:50',
            'torque' => 'nullable|string|max:50',
            'fuel_capacity' => 'nullable|string|max:50',
            'seats' => 'nullable|integer',
            'doors' => 'nullable|integer',
            'fuel_efficiency' => 'nullable|string|max:100',
            'steering_position' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'status' => 'nullable|in:0,1',
        ]);

        if (!$validated['variant_id'] && !$validated['vehicle_model_id']) {
            return back()->withErrors(['variant_id' => 'You must select a variant or a vehicle model.']);
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

