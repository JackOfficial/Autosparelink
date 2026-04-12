<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\UserVehicle;
use App\Models\Variant;
use App\Models\VehicleModel;
use App\Models\BodyType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\DriveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    /**
     * Display a listing of the user's vehicles.
     */
    public function index()
    {
        $vehicles = Auth::user()->vehicles()
            ->with(['brand', 'vehicleModel', 'variant', 'bodyType'])
            ->latest()
            ->get();

        return view('user.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create()
    {
        return view('user.vehicles.create', $this->getFormData());
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateVehicle($request);

        DB::transaction(function () use ($validated) {
            if ($validated['is_primary']) {
                Auth::user()->vehicles()->update(['is_primary' => false]);
            }

            Auth::user()->vehicles()->create($validated);
        });

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle added to your garage.');
    }

    /**
     * Display the specified vehicle.
     */
    public function show(string $id)
    {
        $vehicle = Auth::user()->vehicles()->findOrFail($id);
        return view('user.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit(string $id)
    {
        $vehicle = Auth::user()->vehicles()->findOrFail($id);
        $data = array_merge($this->getFormData(), ['vehicle' => $vehicle]);

        return view('user.vehicles.edit', $data);
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, string $id)
    {
        $vehicle = Auth::user()->vehicles()->findOrFail($id);
        $validated = $this->validateVehicle($request, $vehicle->id);

        DB::transaction(function () use ($validated, $vehicle) {
            if ($validated['is_primary']) {
                Auth::user()->vehicles()
                    ->where('id', '!=', $vehicle->id)
                    ->update(['is_primary' => false]);
            }

            $vehicle->update($validated);
        });

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vehicle = Auth::user()->vehicles()->findOrFail($id);
        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle removed from garage.');
    }

    /**
     * Internal Validation Logic
     */
    protected function validateVehicle(Request $request, $ignoreId = null)
    {
        $validated = $request->validate([
            'brand_id'             => 'required|exists:brands,id',
            'vehicle_model_id'     => 'required|exists:vehicle_models,id',
            'variant_id'           => 'nullable|exists:variants,id',
            'body_type_id'         => 'required|exists:body_types,id',
            'engine_type_id'       => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id'        => 'nullable|exists:drive_types,id',
            'production_start'     => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'horsepower'           => 'nullable|string|max:50',
            'torque'               => 'nullable|string|max:50',
            'displacement'         => 'nullable|string|max:50',
            'steering_position'    => 'nullable|string|in:LHD,RHD',
            'color'                => 'nullable|string|max:50',
            'vin'                  => [
                'nullable', 'string', 'size:17',
                Rule::unique('user_vehicles')->where('user_id', Auth::id())->ignore($ignoreId)
            ],
            'is_primary'           => 'nullable|boolean',
        ]);

        $validated['is_primary'] = $request->has('is_primary');
        $validated['user_id'] = Auth::id();

        return $validated;
    }

    /**
     * Helper to load all dropdown data
     */
    private function getFormData()
    {
        return [
            'brands'            => Brand::orderBy('brand_name')->get(),
            'models'            => VehicleModel::orderBy('model_name')->get(),
            'variants'          => Variant::orderBy('trim_level')->get(),
            'bodyTypes'         => BodyType::all(),
            'engineTypes'       => EngineType::all(),
            'transmissionTypes' => TransmissionType::all(),
            'driveTypes'        => DriveType::all(),
        ];
    }
}