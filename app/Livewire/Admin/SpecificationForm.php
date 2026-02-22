<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\{Specification, Brand, VehicleModel, Variant, BodyType, EngineType, TransmissionType, DriveType, EngineDisplacement, Destination};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;

class SpecificationForm extends Component
{
    // Vehicle Selection
    public $brand_id;
    public $vehicle_model_id;
    public $trim_level; 
    public $hideBrandModel = false;

    // Identity Fields
    public $chassis_code, $model_code;
    public $production_year;       
    public $production_year_start; 
    public $production_year_end;   
    public $destination_id; 

    // Technical Fields
    public $body_type_id, $engine_type_id, $transmission_type_id, $drive_type_id, $engine_displacement_id;
    public $horsepower, $torque, $fuel_capacity, $fuel_efficiency;
    public $seats, $doors, $steering_position = 'LEFT', $color = '#000000', $status = 1;

    public function mount($vehicle_model_id = null)
    {
        if ($vehicle_model_id) {
            $this->vehicle_model_id = $vehicle_model_id;
            $this->hideBrandModel = true;
            $model = VehicleModel::find($vehicle_model_id);
            $this->brand_id = $model?->brand_id;
        }
    }

    #[Computed]
    public function vehicleModels()
    {
        return $this->brand_id 
            ? VehicleModel::where('brand_id', $this->brand_id)->orderBy('model_name')->get() 
            : collect();
    }

    public function updatedBrandId()
    {
        $this->vehicle_model_id = null;
    }

    #[Computed]
    public function generatedName()
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
            $displacement ? ($displacement . 'L') : null,
            $engine,
            $trans,
        ];

        $fullName = implode(' ', array_filter($pieces));
        return $fullName ?: 'New Vehicle Specification';
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
            'drive_type_id' => 'required|exists:drive_types,id',
            'steering_position' => 'required|in:LEFT,RIGHT',
            'destination_id' => 'nullable|exists:destinations,id',
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
                    'production_year'  => $this->production_year,
                    'trim_level'       => $this->trim_level,
                    'status'           => $this->status,
                ]);

                // 2. Create the Specification
                $spec = Specification::create([
                    'variant_id'             => $variant->id,
                    'vehicle_model_id'       => $this->vehicle_model_id,
                    'body_type_id'           => $this->body_type_id,
                    'engine_type_id'         => $this->engine_type_id,
                    'transmission_type_id'   => $this->transmission_type_id,
                    'drive_type_id'          => $this->drive_type_id,
                    'engine_displacement_id' => $this->engine_displacement_id,
                    'chassis_code'           => $this->chassis_code,
                    'model_code'             => $this->model_code,
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

                if ($this->destination_id) {
                    $spec->destinations()->sync([$this->destination_id]);
                }

                // 3. Sync Name (Uses your model logic to compile the final string)
                $variant->refresh();
                if (method_exists($variant, 'syncNameFromSpec')) {
                    $variant->syncNameFromSpec(); 
                }
            });

            session()->flash('success', 'Variant and Specifications created!');
            return redirect()->route('admin.specifications.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
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
            'destinations'        => Destination::orderBy('region_name')->get(),
        ]);
    }
}