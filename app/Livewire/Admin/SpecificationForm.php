<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\{Specification, Brand, VehicleModel, Variant, BodyType, EngineType, TransmissionType, DriveType, EngineDisplacement, Destination};
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class SpecificationForm extends Component
{
    // Identity & Selection
    public $brand_id, $vehicle_model_id, $trim_level, $production_year;
    public $chassis_code, $model_code, $destination_id;
    public $hideBrandModel = false;

    // Production Timeline
    public $start_year, $start_month, $end_year, $end_month;

    // Technical Fields
    public $body_type_id, $engine_type_id, $transmission_type_id, $drive_type_id, $engine_displacement_id;
    public $horsepower, $torque, $fuel_efficiency, $tank_capacity, $seats, $doors;
    public $steering_position = 'LEFT', $status = 1, $is_default = false;

    public function mount($vehicle_model_id = null)
    {
        if ($vehicle_model_id) {
            $this->vehicle_model_id = $vehicle_model_id;
            $this->hideBrandModel = true;
            $model = VehicleModel::find($vehicle_model_id);
            if ($model) {
                $this->brand_id = $model->brand_id;
            }
        }
    }

    /**
     * Computed property for the dynamic header title in your Blade
     */
    #[Computed]
    public function generatedName()
    {
        $model = VehicleModel::find($this->vehicle_model_id);
        if (!$model) return 'New Specification';
        
        return ($model->brand->brand_name ?? '') . ' ' . $model->model_name . ' ' . ($this->trim_level ?? '');
    }

    /**
     * Computed property for the Model dropdown
     */
    #[Computed]
    public function vehicleModels()
    {
        return $this->brand_id 
            ? VehicleModel::where('brand_id', $this->brand_id)->orderBy('model_name')->get() 
            : collect();
    }

    protected function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'trim_level' => 'required|string|max:100',
            'production_year' => 'required|integer|min:1900',
            'start_year' => 'required|integer|min:1900',
            'start_month' => 'nullable|integer|between:1,12',
            'end_year' => 'nullable|integer|min:1900|gte:start_year',
            'end_month' => 'nullable|integer|between:1,12',
            'body_type_id' => 'required|exists:body_types,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id' => 'required|exists:drive_types,id',
            'engine_displacement_id' => 'required|exists:engine_displacements,id',
        ];
    }

    public function save()
    {
        $this->validate();
        
        try {
            DB::transaction(function () {
                // Handle Default Switch logic
                if ($this->is_default) {
                    Variant::where('vehicle_model_id', $this->vehicle_model_id)->update(['is_default' => false]);
                }

                // 1. Create the Variant
                $variant = Variant::create([
                    'vehicle_model_id' => $this->vehicle_model_id,
                    'production_year'  => $this->production_year,
                    'trim_level'       => $this->trim_level,
                    'status'           => $this->status,
                    'is_default'       => $this->is_default,
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
                    'fuel_efficiency'        => $this->fuel_efficiency,
                    'tank_capacity'          => $this->tank_capacity,
                    'seats'                  => $this->seats,
                    'doors'                  => $this->doors,
                    'steering_position'      => $this->steering_position,
                    'production_start_year'  => $this->start_year,
                    'production_start_month' => $this->start_month,
                    'production_end_year'    => $this->end_year,
                    'production_end_month'   => $this->end_month,
                    'status'                 => $this->status,
                ]);

                // 3. Handle Many-to-Many Destinations
                if ($this->destination_id) {
                    $spec->destinations()->sync([$this->destination_id]);
                }

                // Optional: Sync variant name logic
                if (method_exists($variant, 'syncNameFromSpec')) {
                    $variant->syncNameFromSpec();
                }
            });

            session()->flash('success', 'Specification saved successfully!');
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
            'destinations'        => Destination::orderBy('region_name')->get(),
            'months'              => [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June',
                7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ]
        ]);
    }
}