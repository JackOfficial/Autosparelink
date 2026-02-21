<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\{Specification, Brand, VehicleModel, Variant, BodyType, EngineType, TransmissionType, DriveType, EngineDisplacement, Destination};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SpecificationForm extends Component
{
    // Vehicle Selection
    public $brand_id;
    public $vehicle_model_id;
    public $trim_level; 
    public $vehicleModels;
    public $hideBrandModel = false;

    // Variant Identity Fields
    public $chassis_code, $model_code, $is_default = false;
    
    // Newly Added lifecycle & market fields
    public $production_year_start;
    public $production_year_end;
    public $destination_id; // For the Market Destination

    // Form Fields (Specifications)
    public $body_type_id, $engine_type_id, $transmission_type_id, $drive_type_id, $engine_displacement_id;
    public $horsepower, $torque, $fuel_capacity, $fuel_efficiency;
    public $seats, $doors, $steering_position = 'LEFT', $color = '#000000';
    public $status = 1;

    public function mount($vehicle_model_id = null)
    {
        $this->vehicleModels = collect();

        if ($vehicle_model_id) {
            $this->vehicle_model_id = $vehicle_model_id;
            $this->hideBrandModel = true;
            
            $model = VehicleModel::find($vehicle_model_id);
            if ($model) {
                $this->brand_id = $model->brand_id;
                $this->loadModels();
            }
        }
    }

    public function updatedBrandId($value)
    {
        $this->loadModels();
        $this->vehicle_model_id = null;
    }

    protected function loadModels()
    {
        $this->vehicleModels = $this->brand_id 
            ? VehicleModel::where('brand_id', $this->brand_id)->orderBy('model_name')->get() 
            : collect();
    }

    protected function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'trim_level' => 'required|string|max:50',
            
            'chassis_code' => 'nullable|string|max:50',
            'model_code' => 'nullable|string|max:50',
            'is_default' => 'boolean',

            'production_year_start' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'production_year_end' => 'nullable|integer|min:1900|gte:production_year_start',
            'destination_id' => 'nullable|exists:destinations,id',

            'body_type_id' => 'required|exists:body_types,id',
            'engine_displacement_id' => 'required|exists:engine_displacements,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id' => 'nullable|exists:drive_types,id',
            
            'horsepower' => 'nullable|numeric|min:0',
            'torque' => 'nullable|numeric|min:0',
            'fuel_capacity' => 'nullable|numeric|min:0',
            'fuel_efficiency' => 'nullable|numeric|min:0',
            'seats' => 'nullable|integer|min:1',
            'doors' => 'nullable|integer|min:1',
            'steering_position' => 'required|in:LEFT,RIGHT',
            'color' => 'nullable|string',
        ];
    }

    public function save()
    {
        $this->validate();
        
        try {
            DB::transaction(function () {
                // 1. Create the Variant
                $variant = Variant::create([
                    'vehicle_model_id' => $this->vehicle_model_id,
                    'trim_level'       => $this->trim_level,
                    'chassis_code'     => $this->chassis_code,
                    'model_code'       => $this->model_code,
                    'is_default'       => $this->is_default,
                    'status'           => $this->status,
                    'name'             => 'Generating...', 
                    'slug'             => Str::slug($this->trim_level . '-' . ($this->chassis_code ?? rand(1000,9999)) . '-' . Str::random(5)),
                ]);

                // 2. Attach Destination (Market) if selected
                if ($this->destination_id) {
                    $variant->destinations()->sync([$this->destination_id]);
                }

                // 3. Create the Specification 
                Specification::create([
                    'variant_id'             => $variant->id,
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

                // 4. Finalize Variant Name
                $variant->refresh(); 
                if (method_exists($variant, 'syncNameFromSpec')) {
                    $variant->syncNameFromSpec(); 
                }
            });

            session()->flash('success', 'Vehicle Variant created successfully with full specifications.');
            return redirect()->route('admin.specifications.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Database Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.specification-form', [
            'brands'              => Brand::orderBy('brand_name')->get(),
            'bodyTypes'           => BodyType::orderBy('name')->get(),
            'engineTypes'         => EngineType::orderBy('name')->get(),
            'transmissionTypes'   => TransmissionType::orderBy('name')->get(),
            'driveTypes'          => DriveType::orderBy('name')->get(),
            'engineDisplacements' => EngineDisplacement::orderBy('name')->get(),
            'destinations'        => Destination::orderBy('code')->get(), // For the dropdown
        ]);
    }
}