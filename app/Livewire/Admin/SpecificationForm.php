<?php

namespace App\Livewire\Admin\Specifications;

use Livewire\Component;
use App\Models\{Specification, Brand, VehicleModel, Variant, BodyType, EngineType, TransmissionType, DriveType, EngineDisplacement};
use Illuminate\Support\Facades\DB;

class EditSpecification extends Component
{
    public $specificationId;
    
    // Vehicle Selection
    public $brand_id, $vehicle_model_id;
    public $trim_level; // Text input to match your Create form
    public $vehicleModels = [];

    // Form Fields (Matching your Create form Schema/Rules)
    public $body_type_id, $engine_type_id, $transmission_type_id, $drive_type_id, $engine_displacement_id;
    public $horsepower, $torque, $fuel_capacity, $fuel_efficiency;
    public $seats, $doors, $steering_position = 'LEFT', $color = '#000000';
    public $production_start, $production_end, $production_year, $status = 1;

    public function mount($specificationId)
    {
        $spec = Specification::findOrFail($specificationId);
        $this->specificationId = $specificationId;

        // 1. Map existing data (this fills $trim_level, $body_type_id, etc.)
        $this->fill($spec->toArray());

        // 2. Reconstruct Brand/Model dropdowns
        if ($spec->vehicle_model_id) {
            $this->vehicle_model_id = $spec->vehicle_model_id;
            $this->brand_id = $spec->vehicleModel->brand_id;
            $this->vehicleModels = VehicleModel::where('brand_id', $this->brand_id)->orderBy('model_name')->get();
        }
    }

    // Reactive Dropdown for Brand -> Model
    public function updatedBrandId($value)
    {
        $this->vehicleModels = $value 
            ? VehicleModel::where('brand_id', $value)->orderBy('model_name')->get() 
            : collect();
        $this->vehicle_model_id = null;
    }

    protected function rules()
    {
        // Matching your Create form rules exactly
        return [
            'brand_id' => 'required|exists:brands,id',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'trim_level' => 'required|string|max:50',
            'body_type_id' => 'required|exists:body_types,id',
            'production_year' => 'required|integer|min:1950',
            'engine_displacement_id' => 'required|exists:engine_displacements,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            
            'drive_type_id' => 'nullable|exists:drive_types,id',
            'horsepower' => 'nullable|numeric|min:0',
            'torque' => 'nullable|numeric|min:0',
            'fuel_capacity' => 'nullable|numeric|min:0',
            'seats' => 'nullable|integer',
            'doors' => 'nullable|integer',
            'color' => 'nullable|string',
        ];
    }

    public function save()
    {
        $this->validate();
        
        DB::transaction(function () {
            $spec = Specification::findOrFail($this->specificationId);

            // 1. Update Specification
            $spec->update([
                'vehicle_model_id' => $this->vehicle_model_id,
                'trim_level' => $this->trim_level,
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
                'production_start' => $this->production_start ?: null,
                'production_end' => $this->production_end ?: null,
                'production_year' => $this->production_year ?: null,
                'status' => $this->status,
            ]);

            // 2. Headless Variant Sync
            // Since this is EDIT, we update the existing variant name to trigger the observer
            if ($spec->variant) {
                $spec->variant->update([
                    'vehicle_model_id' => $this->vehicle_model_id,
                    'name' => 'Syncing...', // Observer handles the rest
                ]);
            }
        });

        session()->flash('success', 'Specification updated successfully!');
        return redirect()->route('admin.specifications.index');
    }

    public function render()
    {
        return view('livewire.admin.specifications.edit-specification', [
            'brands' => Brand::orderBy('brand_name')->get(),
            'bodyTypes' => BodyType::orderBy('name')->get(),
            'engineTypes' => EngineType::orderBy('name')->get(),
            'transmissionTypes' => TransmissionType::orderBy('name')->get(),
            'driveTypes' => DriveType::orderBy('name')->get(),
            'engineDisplacements' => EngineDisplacement::orderBy('name')->get(),
        ]);
    }
}