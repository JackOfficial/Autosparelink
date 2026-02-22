<?php

namespace App\Livewire\Admin\Specifications;

use Livewire\Component;
use App\Models\{Specification, Brand, VehicleModel, BodyType, EngineType, TransmissionType, DriveType, EngineDisplacement, Variant, Destination};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EditSpecification extends Component
{
    public $specificationId;
    
    // Vehicle Selection
    public $brand_id;
    public $vehicle_model_id;
    public $trim_level; 

    // Identity Fields (Stored in Variants Table)
    public $chassis_code, $model_code, $is_default;
    public $production_year;       // Variant level (Single Year)
    public $destination_id; 

    // Technical Fields (Stored in Specifications Table)
    public $production_year_start; // Spec level (Range Start)
    public $production_year_end;   // Spec level (Range End)
    public $body_type_id, $engine_type_id, $transmission_type_id, $drive_type_id, $engine_displacement_id;
    public $horsepower, $torque, $fuel_capacity, $fuel_efficiency;
    public $seats, $doors, $steering_position, $color, $status;

    /**
     * Computed Property: Re-fetch models when brand changes.
     * Prevents 500 errors by not storing model collections in properties.
     */
    public function getVehicleModelsProperty()
    {
        return $this->brand_id 
            ? VehicleModel::where('brand_id', $this->brand_id)->orderBy('model_name')->get() 
            : collect();
    }

    /**
     * Computed Property: For the Live Preview title (matches Create logic)
     */
    public function getGeneratedNameProperty()
    {
        $brandModel = VehicleModel::with('brand')->find($this->vehicle_model_id);
        
        $body = $this->body_type_id ? BodyType::find($this->body_type_id)?->name : null;
        $displacement = $this->engine_displacement_id ? EngineDisplacement::find($this->engine_displacement_id)?->name : null;
        $engine = $this->engine_type_id ? EngineType::find($this->engine_type_id)?->name : null;
        $trans = $this->transmission_type_id ? TransmissionType::find($this->transmission_type_id)?->name : null;

        $pieces = [
            $brandModel?->brand?->brand_name,
            $brandModel?->model_name,
            $this->trim_level,
            $body,
            $this->production_year,
            $displacement ? ($displacement) : null,
            $engine,
            $trans,
        ];

        return implode(' ', array_filter($pieces)) ?: 'Editing Specification';
    }

    public function mount($specificationId)
    {
        $spec = Specification::with('variant.destinations', 'vehicleModel')->findOrFail($specificationId);
        $this->specificationId = $specificationId;

        // 1. Fill Specification Table Data
        $this->vehicle_model_id = $spec->vehicle_model_id;
        $this->brand_id = $spec->vehicleModel->brand_id;
        $this->body_type_id = $spec->body_type_id;
        $this->engine_type_id = $spec->engine_type_id;
        $this->transmission_type_id = $spec->transmission_type_id;
        $this->drive_type_id = $spec->drive_type_id;
        $this->engine_displacement_id = $spec->engine_displacement_id;
        $this->horsepower = $spec->horsepower;
        $this->torque = $spec->torque;
        $this->fuel_capacity = $spec->fuel_capacity;
        $this->fuel_efficiency = $spec->fuel_efficiency;
        $this->seats = $spec->seats;
        $this->doors = $spec->doors;
        $this->steering_position = $spec->steering_position;
        $this->color = $spec->color;
        $this->status = $spec->status;
        $this->production_year_start = $spec->production_start;
        $this->production_year_end = $spec->production_end;

        // 2. Fill Variant Table Data
        if ($spec->variant) {
            $this->trim_level = $spec->variant->trim_level;
            $this->production_year = $spec->variant->production_year;
            $this->chassis_code = $spec->variant->chassis_code;
            $this->model_code = $spec->variant->model_code;
            $this->is_default = (bool)$spec->variant->is_default;
            $this->destination_id = $spec->variant->destinations->first()?->id;
        }
    }

    public function updatedBrandId()
    {
        $this->vehicle_model_id = null;
    }

    protected function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'trim_level' => 'required|string|max:50',
            'production_year' => 'required|integer|min:1950|max:' . (date('Y') + 2),
            'production_year_start' => 'required|integer|min:1950',
            'production_year_end' => 'nullable|integer|gte:production_year_start',
            'body_type_id' => 'required|exists:body_types,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'engine_displacement_id' => 'required|exists:engine_displacements,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'steering_position' => 'required|in:LEFT,RIGHT',
        ];
    }

  public function save()
{
    $this->validate();
    
    try {
        DB::transaction(function () {
            $spec = Specification::findOrFail($this->specificationId);
            
            // 1. Update Specification Table
            $spec->update([
                'vehicle_model_id'       => $this->vehicle_model_id,
                'body_type_id'           => $this->body_type_id,
                'engine_type_id'         => $this->engine_type_id,
                'transmission_type_id'   => $this->transmission_type_id,
                'drive_type_id'          => $this->drive_type_id,
                'engine_displacement_id' => $this->engine_displacement_id,
                'horsepower'             => $this->horsepower,
                'torque'                 => $this->torque,
                'fuel_capacity'          => $this->fuel_capacity,
                'fuel_efficiency'        => $this->fuel_efficiency,
                'seats'                  => $this->seats,
                'doors'                  => $this->doors,
                'steering_position'      => $this->steering_position,
                'color'                  => $this->color,
                'production_start'       => $this->production_year_start,
                'production_end'         => $this->production_year_end,
                'status'                 => $this->status,
            ]);

            // 2. Update Variant Table
            if ($spec->variant) {
                $spec->variant->update([
                    'vehicle_model_id' => $this->vehicle_model_id,
                    'production_year'  => $this->production_year,
                    'trim_level'       => $this->trim_level,
                    'chassis_code'     => $this->chassis_code,
                    'model_code'       => $this->model_code,
                    'status'           => $this->status,
                ]);

                // 3. Sync Name & Slug 
                // We refresh so the Variant pulls the NEW Spec data (engine, trans, etc.) 
                // into its relations before the 'saving' hook fires.
                $spec->variant->refresh(); 
                $spec->variant->syncNameFromSpec(); 
                
                if ($this->destination_id) {
                    $spec->variant->destinations()->sync([$this->destination_id]);
                }
            }
        });

        session()->flash('success', 'Update successful!');
        return redirect()->route('admin.specifications.index');

    } catch (\Exception $e) {
        session()->flash('error', 'Error: ' . $e->getMessage());
    }
}

    public function render()
    {
        return view('livewire.admin.specifications.edit-specification', [
            'vehicleModels'       => VehicleModel::orderBy('model_name')->get(),
            'brands'              => Brand::orderBy('brand_name')->get(),
            'bodyTypes'           => BodyType::orderBy('name')->get(),
            'engineTypes'         => EngineType::orderBy('name')->get(),
            'transmissionTypes'   => TransmissionType::orderBy('name')->get(),
            'driveTypes'          => DriveType::orderBy('name')->get(),
            'engineDisplacements' => EngineDisplacement::orderBy('name')->get(),
            'destinations'        => Destination::orderBy('region_name')->get(),
        ]);
    }
}