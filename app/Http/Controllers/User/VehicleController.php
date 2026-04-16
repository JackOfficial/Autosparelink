<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\{Brand, ClientVehicle, Variant, VehicleModel, BodyType, EngineType, TransmissionType, DriveType};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Storage};
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
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

    public function store(Request $request)
    {
        $validated = $this->validateVehicle($request);

        DB::transaction(function () use ($validated, $request) {
            if ($validated['is_primary']) {
                $this->resetPrimaryVehicles();
            }

            $vehicle = ClientVehicle::create($validated);
            $this->handlePhotoUpload($request, $vehicle);
        });

        return redirect()->route('vehicles.index')->with('success', 'Vehicle added to your garage.');
    }

    public function show(ClientVehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);
        $vehicle->load(['brand', 'vehicleModel', 'photo', 'bodyType', 'engineType', 'transmissionType']);
        
        return view('user.vehicles.show', compact('vehicle'));
    }

    public function edit(ClientVehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);
        $vehicle->load('photo');
        
        return view('user.vehicles.edit', array_merge($this->getFormData(), ['vehicle' => $vehicle]));
    }

    public function update(Request $request, ClientVehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);
        $validated = $this->validateVehicle($request, $vehicle->id);

        DB::transaction(function () use ($validated, $vehicle, $request) {
            if ($validated['is_primary']) {
                $this->resetPrimaryVehicles($vehicle->id);
            }

            $vehicle->update($validated);
            $this->handlePhotoUpload($request, $vehicle);
        });

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(ClientVehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);

        DB::transaction(function () use ($vehicle) {
            if ($vehicle->photo) {
                Storage::disk('public')->delete($vehicle->photo->file_path);
                $vehicle->photo->delete();
            }
            $vehicle->delete();
        });

        return redirect()->route('vehicles.index')->with('success', 'Vehicle removed from garage.');
    }

    /**
     * Helper: Handle Polymorphic Photo Upload
     */
    private function handlePhotoUpload(Request $request, ClientVehicle $vehicle)
    {
        if ($request->hasFile('vehicle_photo')) {
            // Delete old file if it exists
            if ($vehicle->photo) {
                Storage::disk('public')->delete($vehicle->photo->file_path);
            }

            $path = $request->file('vehicle_photo')->store('vehicles', 'public');

            $vehicle->photo()->updateOrCreate(
                ['imageable_id' => $vehicle->id, 'imageable_type' => ClientVehicle::class],
                [
                    'file_path' => $path,
                    'caption'   => "{$vehicle->brand?->brand_name} {$vehicle->vehicleModel?->model_name}"
                ]
            );
        }
    }

    /**
     * Helper: Reset primary status for other vehicles
     */
    private function resetPrimaryVehicles($excludeId = null)
    {
        Auth::user()->vehicles()
            ->where('is_primary', true)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->update(['is_primary' => false]);
    }

    /**
     * Helper: Security Check
     */
    private function authorizeOwner(ClientVehicle $vehicle)
    {
        if ($vehicle->user_id != Auth::id()) {
            abort(403);
        }
    }

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
            'is_primary'           => 'sometimes|boolean',
            'vehicle_photo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        $validated = $request->validate($rules);
        $validated['is_primary'] = $request->boolean('is_primary'); // Cleaner boolean conversion
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