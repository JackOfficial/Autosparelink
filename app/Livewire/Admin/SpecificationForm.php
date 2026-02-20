<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\{Specification, Brand, VehicleModel, Variant, BodyType, EngineType, TransmissionType, DriveType, EngineDisplacement};
use Illuminate\Support\Facades\DB;

class SpecificationForm extends Component
{
    // Vehicle Selection
    public $brand_id;
    public $vehicle_model_id;
    public $trim_level; 
    public $vehicleModels;
    public $hideBrandModel = false; // Added to fix the "Undefined variable" error

    // Form Fields
    public $body_type_id, $engine_type_id, $transmission_type_id, $drive_type_id, $engine_displacement_id;
    public $horsepower, $torque, $fuel_capacity, $fuel_efficiency;
    public $seats, $doors, $steering_position = 'LEFT', $color = '#000000';
    public $production_start, $production_end, $production_year, $status = 1;

    public function mount($vehicle_model_id = null)
    {
        // Initialize as a collection so Blade loops don't crash
        $this->vehicleModels = collect();

        if ($vehicle_model_id) {
            $this->vehicle_model_id = $vehicle_model_id;
            $this->hideBrandModel = true; // Hide fields if ID is provided
            
            $model = VehicleModel::find($vehicle_model_id);
            if ($model) {
                $this->brand_id = $model->brand_id;
                $this->vehicleModels = VehicleModel::where('brand_id', $this->brand_id)
                    ->orderBy('model_name')
                    ->get();
            }
        }
    }

    public function updatedBrandId($value)
    {
        $this->vehicleModels = $value 
            ? VehicleModel::where('brand_id', $value)->orderBy('model_name')->get() 
            : collect();
        $this->vehicle_model_id = null;
    }

    protected function rules()
    {
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
        // 1. Create the Variant (still empty name)
        $variant = Variant::create([
            'vehicle_model_id' => $this->vehicle_model_id,
            'name' => 'Pending Sync...', 
        ]);

        // 2. Create the Specification linked to that Variant
        Specification::create([
            'variant_id' => $variant->id,
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

        // 3. NOW trigger the sync because the Specification now exists!
        // We refresh the variant instance to make sure it knows the spec is there
        $variant->refresh(); 
        $variant->syncNameFromSpec(); 
    });

    session()->flash('success', 'Specification and Variant created successfully.');
    return redirect()->route('admin.specifications.index');
}

    public function render()
    {
        return view('livewire.admin.specification-form', [
            'brands' => Brand::orderBy('brand_name')->get(),
            'bodyTypes' => BodyType::orderBy('name')->get(),
            'engineTypes' => EngineType::orderBy('name')->get(),
            'transmissionTypes' => TransmissionType::orderBy('name')->get(),
            'driveTypes' => DriveType::orderBy('name')->get(),
            'engineDisplacements' => EngineDisplacement::orderBy('name')->get(),
        ]);
    }
}