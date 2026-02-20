<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Brand;
use App\Models\Variant;
use App\Models\VehicleModel;
use App\Models\BodyType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\DriveType;
use App\Models\EngineDisplacement;
use App\Models\Specification;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class SpecificationForm extends Component
{
    // ================= FIELDS =================
    public $brand_id;
    public $vehicle_model_id;
    public $trim_level; // Changed: No longer variant_id, now trim_level input

    public $body_type_id;
    public $engine_type_id;
    public $engine_displacement_id;
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

    public $production_start;
    public $production_end;
    public $production_year;

    // ================= INIT =================
    public $brands;
    public $vehicleModels;
    public $bodyTypes;
    public $engineTypes;
    public $engineDisplacements;
    public $transmissionTypes;
    public $driveTypes;

    public $hideBrandModel = false;

    public function mount($vehicle_model_id = null)
    {
        $this->brands = Brand::orderBy('brand_name')->get();
        $this->vehicleModels = collect();

        $this->bodyTypes = BodyType::orderBy('name')->get();
        $this->engineTypes = EngineType::orderBy('name')->get();
        $this->engineDisplacements = EngineDisplacement::orderBy('name')->get();
        $this->transmissionTypes = TransmissionType::orderBy('name')->get();
        $this->driveTypes = DriveType::orderBy('name')->get();

        if ($vehicle_model_id) {
            $this->vehicle_model_id = $vehicle_model_id;
            $model = VehicleModel::find($vehicle_model_id);
            if ($model) {
                $this->brand_id = $model->brand_id;
                $this->updatedBrandId($this->brand_id);
            }
            $this->hideBrandModel = true;
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
        'trim_level' => 'required|string|max:50', // Now Required (e.g., "S")
        'body_type_id' => 'required|exists:body_types,id', // Required (e.g., "Hatchback")
        'production_year' => 'required|integer|min:1950', // Required (e.g., "2011")
        'engine_displacement_id' => 'required|exists:engine_displacements,id', // Required (e.g., "1.4")
        'engine_type_id' => 'required|exists:engine_types,id', // Required (e.g., "Diesel")
        'transmission_type_id' => 'required|exists:transmission_types,id', // Required (e.g., "Manual")
        
        // Performance/Optional fields remain nullable
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
            // 1. HEADLESS VARIANT MANAGEMENT
            // We create a "Shell" variant that the Specification will point to
            $variant = Variant::create([
                'vehicle_model_id' => $this->vehicle_model_id,
                'name' => 'Pending Sync...', // Will be updated by Specification Observer
            ]);

            // 2. CREATE SPECIFICATION
            Specification::create([
                'variant_id' => $variant->id,
                'vehicle_model_id' => $this->vehicle_model_id,
                'trim_level' => $this->trim_level, // Saved here now
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
            ]);
            
            // Note: Your Specification Model's 'saved' boot method 
            // should now call $variant->syncNameFromSpec()
        });

        session()->flash('success', 'Specification and Variant generated successfully.');
        return redirect()->route('admin.specifications.index');
    }

    public function render()
    {
        return view('livewire.admin.specification-form');
    }
}