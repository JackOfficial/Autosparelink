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

    // Production Timeline (Split for UI, concatenated for DB)
    public $start_year, $start_month, $end_year, $end_month;

    // Technical Fields
    public $body_type_id, $engine_type_id, $transmission_type_id, $drive_type_id, $engine_displacement_id;
    public $horsepower, $torque, $fuel_efficiency, $tank_capacity; // Match DB column name
    public $seats, $doors, $color;
    public $steering_position = 'LEFT', $status = 1;

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
     * Syncs with the {{ $this->generatedName }} in your Blade header
     */
    #[Computed]
    public function generatedName()
    {
        $model = VehicleModel::find($this->vehicle_model_id);
        if (!$model) return 'New Specification';
        
        return ($model->brand->brand_name ?? '') . ' ' . $model->model_name . ' ' . ($this->trim_level ?? '');
    }

    /**
     * Syncs with @foreach($this->vehicleModels as $model) in your Blade
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
            
            // Timeline Validation
            'start_year' => 'required|integer|min:1900',
            'start_month' => 'nullable|integer|between:1,12',
            'end_year' => 'nullable|integer|min:1900|gte:start_year',
            'end_month' => 'nullable|integer|between:1,12',

            // Technical
            'body_type_id' => 'required|exists:body_types,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'nullable|exists:transmission_types,id',
            'drive_type_id' => 'nullable|exists:drive_types,id',
            'engine_displacement_id' => 'nullable|exists:engine_displacements,id',
            
            'horsepower' => 'nullable|integer|min:0',
            'torque' => 'nullable|integer|min:0',
            'tank_capacity' => 'nullable|numeric|min:0',
            'fuel_efficiency' => 'nullable|string|max:50',
            'seats' => 'nullable|integer|min:1',
            'doors' => 'nullable|integer|min:1',
            'steering_position' => 'required|in:LEFT,RIGHT',
            'status' => 'required|boolean',
        ];
    }

    public function save()
    {
        $this->validate();
        
        try {
            DB::transaction(function () {
                // 1. Format Dates to Match Edit Component (YYYY-MM)
                $prodStart = $this->start_year . ($this->start_month ? '-' . str_pad($this->start_month, 2, '0', STR_PAD_LEFT) : '');
                
                $prodEnd = $this->end_year 
                    ? ($this->end_year . ($this->end_month ? '-' . str_pad($this->end_month, 2, '0', STR_PAD_LEFT) : '')) 
                    : 'Present';

               

                // 3. Create the Variant
                $variant = Variant::create([
                    'vehicle_model_id' => $this->vehicle_model_id,
                    'production_year'  => $this->production_year,
                    'trim_level'       => $this->trim_level,
                    'status'           => $this->status,
                ]);

                // 4. Create the Specification (Aligned with Edit Component columns)
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
                    'color'                  => $this->color,
                    'production_start'       => $prodStart, // Matches Edit logic
                    'production_end'         => $prodEnd,   // Matches Edit logic
                    'status'                 => $this->status,
                ]);

                // 5. Handle Destinations
                if ($this->destination_id) {
                    $spec->destinations()->sync([$this->destination_id]);
                }

                // 6. Name Sync Logic
                if (method_exists($variant, 'syncNameFromSpec')) {
                    $variant->syncNameFromSpec();
                }
            });

            session()->flash('success', 'Specification created successfully!');
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
                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
                7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
            ]
        ]);
    }
}