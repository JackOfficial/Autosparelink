<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Variant;
use App\Models\VehicleModel;
use App\Models\BodyType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\DriveType;
use App\Models\Specification;
use Illuminate\Validation\ValidationException;

class SpecificationForm extends Component
{
    // ================= FIELDS =================
    public $variant_id;
    public $vehicle_model_id;

    public $body_type_id;
    public $engine_type_id;
    public $transmission_type_id;
    public $drive_type_id;
    public $horsepower;
    public $torque;
    public $fuel_capacity;
    public $fuel_efficiency;
    public $seats;
    public $doors;
    public $steering_position;
    public $color;

    // ================= INIT =================
    public $variants;
    public $vehicleModels;
    public $bodyTypes;
    public $engineTypes;
    public $transmissionTypes;
    public $driveTypes;

    // Hide variant select if model is passed
    public $hideVariantSelect = false;

    public function mount($vehicle_model_id = null)
    {
        $this->variants = Variant::with('vehicleModel')->orderBy('name')->get();
        $this->vehicleModels = VehicleModel::orderBy('model_name')->get();
        $this->bodyTypes = BodyType::orderBy('name')->get();
        $this->engineTypes = EngineType::orderBy('name')->get();
        $this->transmissionTypes = TransmissionType::orderBy('name')->get();
        $this->driveTypes = DriveType::orderBy('name')->get();

        if ($vehicle_model_id) {
            $this->vehicle_model_id = $vehicle_model_id;
            $this->hideVariantSelect = true; // No variants allowed
        }
    }

    // ================= VALIDATION =================
    protected function rules()
    {
        return [
            'variant_id' => $this->hideVariantSelect ? 'nullable' : 'nullable|exists:variants,id',
            'vehicle_model_id' => 'nullable|exists:vehicle_models,id',
            'body_type_id' => 'required|exists:body_types,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id' => 'nullable|exists:drive_types,id',
            'horsepower' => 'nullable|numeric|min:0',
            'torque' => 'nullable|numeric|min:0',
            'fuel_capacity' => 'nullable|numeric|min:0',
            'fuel_efficiency' => 'nullable|numeric|min:0',
            'seats' => 'nullable|integer|min:1',
            'doors' => 'nullable|integer|min:1',
            'steering_position' => 'nullable|in:LEFT,RIGHT',
            'color' => 'nullable|string|max:20',
        ];
    }

    // ================= SAVE =================
    public function save()
    {
        $this->validate();

        // XOR enforcement only if variants are allowed
        if (!$this->hideVariantSelect) {
            if (($this->variant_id && $this->vehicle_model_id) ||
                (!$this->variant_id && !$this->vehicle_model_id)) {
                throw ValidationException::withMessages([
                    'vehicle_model_id' => 'You must select either a Variant OR a Vehicle Model, but not both.',
                ]);
            }
        }

        Specification::create([
            'variant_id' => $this->variant_id,
            'vehicle_model_id' => $this->vehicle_model_id,
            'body_type_id' => $this->body_type_id,
            'engine_type_id' => $this->engine_type_id,
            'transmission_type_id' => $this->transmission_type_id,
            'drive_type_id' => $this->drive_type_id,
            'horsepower' => $this->horsepower,
            'torque' => $this->torque,
            'fuel_capacity' => $this->fuel_capacity,
            'fuel_efficiency' => $this->fuel_efficiency,
            'seats' => $this->seats,
            'doors' => $this->doors,
            'steering_position' => $this->steering_position,
            'color' => $this->color,
        ]);

        session()->flash('success', 'Specification saved successfully.');

        return redirect()->route('admin.specifications.index');
    }

    public function render()
    {
        return view('livewire.admin.specification-form');
    }
}
