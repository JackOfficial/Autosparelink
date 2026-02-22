<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Variant;
use App\Models\VehicleModel;
use App\Models\BodyType;
use App\Models\Brand;
use App\Models\DriveType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use Illuminate\Support\Facades\Storage;

class VariantController extends Controller
{
    /**
     * Display a listing of variants grouped by brand
     */
    public function index()
    {
        $brands = Brand::with(['vehicleModels.variants' => function($q) {
            $q->orderBy('name');
        }])->orderBy('brand_name')->get();

        return view('admin.variants.index', compact('brands'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.variants.create', [
            'vehicleModels'      => VehicleModel::orderBy('model_name')->get(),
            'bodyTypes'          => BodyType::all(),
            'engineTypes'        => EngineType::all(),
            'driveTypes'         => DriveType::all(),
            'transmissionTypes'  => TransmissionType::all(),
        ]);
    }

    /**
     * Store new variant
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'production_year'  => 'required|integer|min:1900|max:' . (date('Y') + 2),
            'trim_level'       => 'nullable|string|max:255',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'           => 'required|in:0,1',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('variants', 'public');
        }

        // The name and slug are generated automatically by Variant Model (booted/saving event)
        Variant::create($validated);

        return redirect()
            ->route('admin.variants.index')
            ->with('success', 'Variant created successfully.');
    }

    /**
     * Display the specified variant and its technical specifications
     */
    public function show($id)
    {
        $variant = Variant::with([
            'vehicleModel.brand',
            'specifications.engineType',
            'specifications.transmissionType',
            'specifications.driveType',
            'specifications.bodyType',
            'specifications.engineDisplacement',
        ])->findOrFail($id);

        return view('admin.variants.show', compact('variant'));
    }

    /**
     * Show edit form
     */
    public function edit(Variant $variant)
    {
        return view('admin.variants.edit', [
            'variant'           => $variant,
            'vehicleModels'     => VehicleModel::orderBy('model_name')->get(),
            'bodyTypes'         => BodyType::all(),
            'engineTypes'       => EngineType::all(),
            'driveTypes'        => DriveType::all(),
            'transmissionTypes' => TransmissionType::all(),
        ]);
    }

    /**
     * Update variant
     */
    public function update(Request $request, Variant $variant)
    {
        $validated = $request->validate([
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'production_year'  => 'required|integer|min:1900|max:' . (date('Y') + 2),
            'trim_level'       => 'nullable|string|max:255',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'           => 'required|in:0,1',
        ]);

        if ($request->hasFile('photo')) {
            if ($variant->photo) {
                Storage::disk('public')->delete($variant->photo);
            }
            $validated['photo'] = $request->file('photo')->store('variants', 'public');
        }

        // Updating triggers the Variant Model 'saving' hook to refresh the name and slug
        $variant->update($validated);

        return redirect()
            ->route('admin.variants.index')
            ->with('success', 'Variant updated successfully.');
    }

    /**
     * Delete variant
     */
    public function destroy(Variant $variant)
    {
        if ($variant->photo) {
            Storage::disk('public')->delete($variant->photo);
        }

        $variant->delete();

        return redirect()
            ->route('admin.variants.index')
            ->with('success', 'Variant deleted successfully.');
    }
}