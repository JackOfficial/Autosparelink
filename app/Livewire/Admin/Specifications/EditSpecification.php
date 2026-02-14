<?php

namespace App\Livewire\Admin\Specifications;

use Livewire\Component;
use Illuminate\Validation\ValidationException;
use App\Models\{
    Specification, Brand, VehicleModel, Variant, BodyType,
    EngineType, TransmissionType, DriveType, EngineDisplacement
};

class EditSpecification extends Component
{
    public $specificationId;

    // Vehicle selection
    public $brand_id;
    public $vehicle_model_id;
    public $variant_id;

    // Dependent dropdowns
    public $vehicleModels;
    public $filteredVariants;

    // Core specs
    public $body_type_id;
    public $engine_type_id;
    public $transmission_type_id;
    public $drive_type_id;
    public $engine_displacement_id;

    // Performance & Capacity
    public $horsepower;
    public $torque;
    public $fuel_capacity;
    public $fuel_efficiency;

    // Interior & Layout
    public $seats;
    public $doors;
    public $steering_position;
    public $color;

    // Production
    public $production_start;
    public $production_end;

    // Optional UI
    public $hideBrandModel = false;
    public $hideVariant = false;

    // Lookup tables
    public $brands;
    public $bodyTypes;
    public $engineTypes;
    public $transmissionTypes;
    public $driveTypes;
    public $engineDisplacements;

    public function mount($specificationId)
    {
        $this->specificationId = $specificationId;

        // Load lookup tables
        $this->brands = Brand::orderBy('brand_name')->get();
        $this->bodyTypes = BodyType::orderBy('name')->get();
        $this->engineTypes = EngineType::orderBy('name')->get();
        $this->transmissionTypes = TransmissionType::orderBy('name')->get();
        $this->driveTypes = DriveType::orderBy('name')->get();
        $this->engineDisplacements = EngineDisplacement::orderBy('name')->get();

        // Load the specification
        $spec = Specification::findOrFail($specificationId);

        // Vehicle/brand logic
        $this->variant_id = $spec->variant_id;
        $this->vehicle_model_id = $spec->vehicle_model_id ?? ($spec->variant->vehicle_model_id ?? null);
        $this->brand_id = $spec->variant_id
            ? $spec->variant->vehicleModel->brand_id ?? null
            : ($spec->vehicleModel->brand_id ?? null);

        if ($this->variant_id) {
            $this->hideBrandModel = true;
            $this->hideVariant = true;
        } elseif ($this->vehicle_model_id) {
            $this->hideBrandModel = true;
        }

        // Core specs
        $this->body_type_id = $spec->body_type_id;
        $this->engine_type_id = $spec->engine_type_id;
        $this->transmission_type_id = $spec->transmission_type_id;
        $this->drive_type_id = $spec->drive_type_id;
        $this->engine_displacement_id = $spec->engine_displacement_id;

        // Performance & Capacity
        $this->horsepower = $spec->horsepower;
        $this->torque = $spec->torque;
        $this->fuel_capacity = $spec->fuel_capacity;
        $this->fuel_efficiency = $spec->fuel_efficiency;

        // Interior & Layout
        $this->seats = $spec->seats;
        $this->doors = $spec->doors;
        $this->steering_position = $spec->steering_position;
        $this->color = $spec->color;

        // Production
        $this->production_start = $spec->production_start;
        $this->production_end = $spec->production_end;

        // Dependent dropdowns
        $this->vehicleModels = $this->brand_id ? VehicleModel::where('brand_id', $this->brand_id)->orderBy('model_name')->get() : collect();
        $this->filteredVariants = $this->vehicle_model_id ? Variant::where('vehicle_model_id', $this->vehicle_model_id)->orderBy('name')->get() : collect();
    }

    // ---------------- Dependent dropdowns ----------------
    public function updatedBrandId($value)
    {
        $this->vehicle_model_id = null;
        $this->variant_id = null;
        $this->vehicleModels = $value ? VehicleModel::where('brand_id', $value)->orderBy('model_name')->get() : collect();
        $this->filteredVariants = collect();
    }

    public function updatedVehicleModelId($value)
    {
        $this->variant_id = null;
        $this->filteredVariants = $value ? Variant::where('vehicle_model_id', $value)->orderBy('name')->get() : collect();
    }

    // ---------------- Validation ----------------
    protected function rules()
    {
        return [
            'vehicle_model_id' => 'required_without:variant_id|exists:vehicle_models,id',
            'variant_id' => 'nullable|exists:variants,id',
            'body_type_id' => 'required|exists:body_types,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id' => 'nullable|exists:drive_types,id',
            'engine_displacement_id' => 'nullable|exists:engine_displacements,id',
            'horsepower' => 'nullable|numeric|min:0',
            'torque' => 'nullable|numeric|min:0',
            'fuel_capacity' => 'nullable|numeric|min:0',
            'fuel_efficiency' => 'nullable|numeric|min:0',
            'seats' => 'nullable|integer|min:1|max:20',
            'doors' => 'nullable|integer|min:1|max:6',
            'steering_position' => 'nullable|in:LEFT,RIGHT',
            'color' => 'nullable|string|max:50',
            'production_start' => 'nullable|integer|min:1950|max:' . date('Y'),
            'production_end' => 'nullable|integer|min:1950|max:' . (date('Y') + 2),
        ];
    }

    // ---------------- Save ----------------
    public function save()
    {
        $this->validate();

        // Require at least model or variant
        if (!$this->vehicle_model_id && !$this->variant_id) {
            throw ValidationException::withMessages([
                'vehicle_model_id' => 'You must select a Vehicle Model or Variant.',
                'variant_id' => 'You must select a Vehicle Model or Variant.',
            ]);
        }

        $spec = Specification::findOrFail($this->specificationId);

        $spec->update([
            'variant_id' => $this->variant_id,
            'vehicle_model_id' => $this->vehicle_model_id,
            'body_type_id' => $this->body_type_id,
            'engine_type_id' => $this->engine_type_id,
            'transmission_type_id' => $this->transmission_type_id,
            'drive_type_id' => $this->drive_type_id,
            'engine_displacement_id' => $this->engine_displacement_id,
            'horsepower' => $this->horsepower,
            'torque' => $this->torque,
            'fuel_capacity' => $this->fuel_capacity,
            'fuel_efficiency' => $this->fuel_efficiency,
            'seats' => $this->seats,
            'doors' => $this->doors,
            'steering_position' => $this->steering_position,
            'color' => $this->color,
            'production_start' => $this->production_start,
            'production_end' => $this->production_end,
        ]);

        session()->flash('success', 'Specification updated successfully!');
        return redirect()->route('admin.specifications.index');
    }

    public function render()
    {
        return view('livewire.admin.specifications.edit-specification');
    }
}