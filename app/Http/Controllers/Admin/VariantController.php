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
    /**
     * Display a listing of variants
     */
    public function index()
    {
        $variants = Variant::with(['vehicleModel.brand'])
            ->latest()
            ->get();

        return view('admin.variants.index', compact('variants'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.variants.create', [
            'vehicleModels'      => VehicleModel::all(),
            'bodyTypes'          => BodyType::all(),
            'engineTypes'        => EngineType::all(),
            'driveTypes'         => DriveType::all(),
            'transmissionTypes' => TransmissionType::all(),
        ]);
    }

    /**
     * Store new variant
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'name'             => 'nullable|string|max:255',
            'chassis_code'     => 'nullable|string|max:255',
            'model_code'       => 'nullable|string|max:255',
            'trim_level'       => 'nullable|string|max:255',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'           => 'required|in:0,1',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('variants', 'public');
        }

        Variant::create($validated);

        return redirect()
            ->route('admin.variants.index')
            ->with('success', 'Variant created successfully.');
    }

    /**
 * Display the specified variant and its specifications
 */
public function show($id)
{
    // Load variant with its vehicle model, brand, and specifications
    $variant = Variant::with([
        'vehicleModel.brand',
        'specifications.engineType',
        'specifications.transmissionType',
        'specifications.driveType',
        'specifications.bodyType',
    ])->findOrFail($id);

    return view('admin.variants.show', compact('variant'));
}


    /**
     * Show edit form
     */
    public function edit(Variant $variant)
    {
        return view('admin.variants.edit', [
            'variant'            => $variant,
            'vehicleModels'      => VehicleModel::all(),
            'bodyTypes'          => BodyType::all(),
            'engineTypes'        => EngineType::all(),
            'driveTypes'         => DriveType::all(),
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
            'name'             => 'nullable|string|max:255',
            'chassis_code'     => 'nullable|string|max:255',
            'model_code'       => 'nullable|string|max:255',
            'trim_level'       => 'nullable|string|max:255',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'           => 'required|in:0,1',
        ]);

        if ($request->hasFile('photo')) {

            if ($variant->photo) {
                Storage::disk('public')->delete($variant->photo);
            }

            $validated['photo'] = $request->file('photo')
                ->store('variants', 'public');
        }

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
