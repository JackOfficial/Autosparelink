<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\ClientVehicle;
use App\Models\Variant;
use App\Models\VehicleModel;
use App\Models\BodyType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\DriveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    /**
     * Display a listing of the user's vehicles.
     */
    public function index()
    {
        $vehicles = Auth::user()->vehicles()
            ->with(['brand', 'vehicleModel', 'bodyType', 'engineType', 'photo'])
            ->latest()
            ->get();

        return view('user.vehicles.index', compact('vehicles'));
    }

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

        DB::transaction(function () use ($validated, $request) {
            if ($validated['is_primary']) {
                ClientVehicle::where('user_id', Auth::id())->update(['is_primary' => false]);
            }

            $vehicle = ClientVehicle::create($validated);

            // Handle Photo Upload
            if ($request->hasFile('vehicle_photo')) {
                $path = $request->file('vehicle_photo')->store('vehicles', 'public');
                
                $vehicle->photo()->create([
                    'file_path' => $path,
                    'caption'   => $vehicle->brand?->brand_name . ' ' . $vehicle->vehicleModel?->model_name
                ]);
            }
        });

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle added to your garage.');
    }

    public function show(string $id)
    {
        $vehicle = ClientVehicle::where('user_id', Auth::id())->with('photo')->findOrFail($id);
        return view('user.vehicles.show', compact('vehicle'));
    }

    public function edit(string $id)
    {
        $vehicle = ClientVehicle::where('user_id', Auth::id())->with('photo')->findOrFail($id);
        $data = array_merge($this->getFormData(), ['vehicle' => $vehicle]);

        return view('user.vehicles.edit', $data);
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, string $id)
    {
        $vehicle = ClientVehicle::where('user_id', Auth::id())->findOrFail($id);
        $validated = $this->validateVehicle($request, $vehicle->id);

        DB::transaction(function () use ($validated, $vehicle, $request) {
            if ($validated['is_primary']) {
                ClientVehicle::where('user_id', Auth::id())
                    ->where('id', '!=', $vehicle->id)
                    ->update(['is_primary' => false]);
            }

            $vehicle->update($validated);

            // Handle Photo Update (Replace existing)
            if ($request->hasFile('vehicle_photo')) {
                // Delete old file from storage
                if ($vehicle->photo) {
                    Storage::disk('public')->delete($vehicle->photo->file_path);
                    $vehicle->photo->delete();
                }

                $path = $request->file('vehicle_photo')->store('vehicles', 'public');
                $vehicle->photo()->create([
                    'file_path' => $path,
                    'caption'   => $vehicle->brand?->brand_name . ' ' . $vehicle->vehicleModel?->model_name
                ]);
            }
        });

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vehicle = ClientVehicle::where('user_id', Auth::id())->findOrFail($id);
        
        // Clean up photo storage before deleting record
        if ($vehicle->photo) {
            Storage::disk('public')->delete($vehicle->photo->file_path);
            $vehicle->photo->delete();
        }

        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle removed from garage.');
    }

    /**
     * Internal Validation Logic
     */
    protected function validateVehicle(Request $request, $ignoreId = null)
    {
        $rules = [
            'brand_id'             => 'required|exists:brands,id',
            'vehicle_model_id'     => 'required|exists:vehicle_models,id',
            'production_start'     => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'trim_level'           => 'nullable|string|max:100',
            'body_type_id'         => 'required|exists:body_types,id',
            'engine_type_id'       => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id'        => 'nullable|exists:drive_types,id',
            'displacement'         => 'nullable|string|max:50',
            'steering_position'    => 'nullable|string|in:LHD,RHD',
            'vin'                  => [
                'nullable', 'string', 'size:17',
                Rule::unique('client_vehicles')->where('user_id', Auth::id())->ignore($ignoreId)
            ],
            'is_primary'           => 'nullable|boolean',
            'vehicle_photo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB limit
        ];

        $validated = $request->validate($rules);

        $validated['is_primary'] = $request->has('is_primary');
        $validated['user_id'] = Auth::id();

        return $validated;
    }

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