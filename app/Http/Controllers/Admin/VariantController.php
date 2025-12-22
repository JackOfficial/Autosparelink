<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Variant;
use App\Models\VehicleModel;
use App\Models\BodyType;
use App\Models\DriveType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use Illuminate\Support\Facades\Storage;

class VariantController extends Controller
{
    public function index()
    {
        $variants = Variant::with([
            'vehicleModel', 
            'bodyType', 
            'engineType', 
            'transmissionType', 
            'driveType'
        ])->latest()->get();

        return view('admin.variants.index', compact('variants'));
    }

    public function create()
    {
        $vehicleModels = VehicleModel::all();
        $bodyTypes = BodyType::all();
        $engineTypes = EngineType::all();
        $driveTypes = DriveType::all();
        $transmissionTypes = TransmissionType::all();

        return view('admin.variants.create', compact('vehicleModels','bodyTypes','engineTypes','transmissionTypes', 'driveTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'body_type_id' => 'required|exists:body_types,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id' => 'nullable|exists:drive_types,id',
            'chassis_code' => 'nullable|string|max:255',
            'model_code' => 'nullable|string|max:255',
            'options' => 'nullable|string',
            'fuel_capacity' => 'nullable|string|max:255',
            'seats' => 'nullable|integer',
            'doors' => 'nullable|integer',
            'drive_type' => 'nullable|string|max:255',
            'steering_position' => 'nullable|string|max:255',
            'trim_level' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'horsepower' => 'nullable|string|max:255',
            'torque' => 'nullable|string|max:255',
            'fuel_efficiency' => 'nullable|string|max:255',
            'production_start' => 'nullable|string|max:255',
            'production_end' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|integer',
        ]);

        $data = $request->except('_token');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('variants', 'public');
        }

        Variant::create($data);

        return redirect()->route('admin.variants.index')->with('success', 'Variant created successfully.');
    }

    public function edit(Variant $variant)
    {
        $vehicleModels = VehicleModel::all();
        $bodyTypes = BodyType::all();
        $engineTypes = EngineType::all();
        $driveTypes = DriveType::all();
        $transmissionTypes = TransmissionType::all();

        return view('admin.variants.edit', compact('variant','vehicleModels','bodyTypes','engineTypes','transmissionTypes','driveTypes'));
    }

    public function update(Request $request, Variant $variant)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'body_type_id' => 'required|exists:body_types,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id' => 'nullable|exists:drive_types,id',
            'chassis_code' => 'nullable|string|max:255',
            'model_code' => 'nullable|string|max:255',
            'options' => 'nullable|string',
            'fuel_capacity' => 'nullable|string|max:255',
            'seats' => 'nullable|integer',
            'doors' => 'nullable|integer',
            'drive_type' => 'nullable|string|max:255',
            'steering_position' => 'nullable|string|max:255',
            'trim_level' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'horsepower' => 'nullable|string|max:255',
            'torque' => 'nullable|string|max:255',
            'fuel_efficiency' => 'nullable|string|max:255',
            'production_start' => 'nullable|string|max:255',
            'production_end' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|integer',
        ]);

        $data = $request->except('_token');

        if ($request->hasFile('photo')) {
            if ($variant->photo) {
                Storage::disk('public')->delete($variant->photo);
            }
            $data['photo'] = $request->file('photo')->store('variants', 'public');
        }

        $variant->update($data);

        return redirect()->route('admin.variants.index')->with('success', 'Variant updated successfully.');
    }

    public function destroy(Variant $variant)
    {
        if ($variant->photo) {
            Storage::disk('public')->delete($variant->photo);
        }

        $variant->delete();

        return redirect()->route('admin.variants.index')->with('success', 'Variant deleted successfully.');
    }
}
