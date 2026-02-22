<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\{Specification, Brand, VehicleModel, Variant, BodyType, EngineType, TransmissionType, DriveType, EngineDisplacement, Destination};
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class SpecificationForm extends Component
{
    // Vehicle Selection
    public $brand_id, $vehicle_model_id, $trim_level, $hideBrandModel = false;

    // Identity & Market
    public $chassis_code, $model_code, $destination_id, $production_year;

    // Production Timeline (Separate Year/Month)
    public $start_year, $start_month, $end_year, $end_month;

    // Technical Fields
    public $body_type_id, $engine_type_id, $transmission_type_id, $drive_type_id, $engine_displacement_id;
    public $horsepower, $torque, $fuel_efficiency, $tank_capacity, $seats, $doors;
    public $steering_position = 'LEFT', $status = 1, $is_default = false;

    // ... mount() and vehicleModels() computed property remains the same ...

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
            // ... other rules ...
        ];
    }

    public function save()
    {
        $this->validate();
        
        try {
            DB::transaction(function () {
                if ($this->is_default) {
                    Variant::where('vehicle_model_id', $this->vehicle_model_id)->update(['is_default' => false]);
                }

                $variant = Variant::create([
                    'vehicle_model_id' => $this->vehicle_model_id,
                    'production_year'  => $this->production_year,
                    'trim_level'       => $this->trim_level,
                    'status'           => $this->status,
                    'is_default'       => $this->is_default,
                ]);

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

                if ($this->destination_id) {
                    $spec->destinations()->sync([$this->destination_id]);
                }

                $variant->refresh();
                if (method_exists($variant, 'syncNameFromSpec')) { $variant->syncNameFromSpec(); }
            });

            session()->flash('success', 'Specification saved!');
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
            'months'              => [
                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
                7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
            ]
        ]);
    }
}