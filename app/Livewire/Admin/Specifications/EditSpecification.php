<?php

namespace App\Livewire\Admin\Specifications;

use Livewire\Component;
use App\Models\{Specification, Brand, VehicleModel, Variant, BodyType, EngineType, TransmissionType, DriveType, EngineDisplacement};

class EditSpecification extends Component
{
    public $specificationId;
    
    // Vehicle Selection
    public $brand_id, $vehicle_model_id, $variant_id;
    public $vehicleModels = [], $filteredVariants = [];

    // Form Fields (Matching your Schema)
    public $body_type_id, $engine_type_id, $transmission_type_id, $drive_type_id, $engine_displacement_id;
    public $horsepower, $torque, $fuel_capacity, $fuel_efficiency;
    public $seats, $doors, $steering_position = 'LEFT', $color = '#000000';
    public $production_start, $production_end, $production_year, $status = 1;

    public function mount($specificationId)
    {
        $spec = Specification::findOrFail($specificationId);
        $this->specificationId = $specificationId;

        // 1. Map existing data
        $this->fill($spec->toArray());

        // 2. Reconstruct the Dropdown Hierarchy
        // If it's linked to a variant, we need to find the model and brand
        if ($spec->variant_id) {
            $this->variant_id = $spec->variant_id;
            $this->vehicle_model_id = $spec->variant->vehicle_model_id;
            $this->brand_id = $spec->variant->vehicleModel->brand_id;
        } elseif ($spec->vehicle_model_id) {
            $this->vehicle_model_id = $spec->vehicle_model_id;
            $this->brand_id = $spec->vehicleModel->brand_id;
        }

        // 3. Load the dependent lists so dropdowns aren't empty on load
        if ($this->brand_id) {
            $this->vehicleModels = VehicleModel::where('brand_id', $this->brand_id)->get();
        }
        if ($this->vehicle_model_id) {
            $this->filteredVariants = Variant::where('vehicle_model_id', $this->vehicle_model_id)->get();
        }
    }

    // Reactive Dropdowns (Same as Create)
    public function updatedBrandId($value)
    {
        $this->vehicleModels = $value ? VehicleModel::where('brand_id', $value)->get() : [];
        $this->vehicle_model_id = null;
        $this->variant_id = null;
        $this->filteredVariants = [];
    }

    public function updatedVehicleModelId($value)
    {
        $this->filteredVariants = $value ? Variant::where('vehicle_model_id', $value)->get() : [];
        $this->variant_id = null;
    }

    protected function rules()
    {
        return [
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'variant_id' => 'nullable|exists:variants,id',
            'body_type_id' => 'required|exists:body_types,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id' => 'nullable|exists:drive_types,id',
            'engine_displacement_id' => 'nullable|exists:engine_displacements,id',
            'horsepower' => 'nullable|numeric',
            'torque' => 'nullable|numeric',
            'seats' => 'nullable|integer|max:20',
            'doors' => 'nullable|integer|max:10',
            'color' => 'nullable|string',
            'status' => 'boolean',
            'production_year' => 'nullable|integer',
        ];
    }

    public function save()
    {
        $this->validate();
        
        $spec = Specification::findOrFail($this->specificationId);
        $spec->update([
            'vehicle_model_id' => $this->vehicle_model_id,
            'variant_id' => $this->variant_id,
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
            'production_year' => $this->production_year,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Specification updated successfully!');
        return redirect()->route('admin.specifications.index');
    }

    public function render()
    {
        return view('livewire.admin.specifications.edit-specification', [
            'brands' => Brand::orderBy('brand_name')->get(),
            'bodyTypes' => BodyType::all(),
            'engineTypes' => EngineType::all(),
            'transmissionTypes' => TransmissionType::all(),
            'driveTypes' => DriveType::all(),
            'engineDisplacements' => EngineDisplacement::all(),
        ]);
    }
}