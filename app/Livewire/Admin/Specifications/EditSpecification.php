<?php

namespace App\Livewire\Admin\Specifications;

use Livewire\Component;
use App\Models\{Specification, Brand, VehicleModel, BodyType, EngineType, TransmissionType, DriveType, EngineDisplacement, Destination};
use Illuminate\Support\Facades\DB;

class EditSpecification extends Component
{
    public $specificationId;
    
    // Vehicle Selection
    public $brand_id;
    public $vehicle_model_id;
    public $trim_level; 
    
    // Date Range Handling
    public $start_month, $start_year;
    public $end_month, $end_year;

    // Identity & Technical Fields
    public $chassis_code, $model_code, $is_default;
    public $production_year; // Single year for Variant
    public $destination_id; 
    public $body_type_id, $engine_type_id, $transmission_type_id, $drive_type_id, $engine_displacement_id;
    public $horsepower, $torque, $fuel_capacity, $fuel_efficiency;
    public $seats, $doors, $steering_position, $color, $status;

    public function mount($specificationId)
    {
        $spec = Specification::with(['destinations', 'vehicleModel', 'variant'])->findOrFail($specificationId);
        $this->specificationId = $specificationId;

        // Map Specification Data
        $this->brand_id = $spec->vehicleModel->brand_id;
        $this->vehicle_model_id = $spec->vehicle_model_id;
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
        $this->chassis_code = $spec->chassis_code;
        $this->model_code = $spec->model_code;
        $this->destination_id = $spec->destinations->first()?->id;

        // Parse Start Date (YYYY-MM)
        if ($spec->production_start) {
            $startParts = explode('-', $spec->production_start);
            $this->start_year = $startParts[0];
            $this->start_month = $startParts[1] ?? '';
        }

        // Parse End Date (YYYY-MM or "Present")
        if ($spec->production_end) {
            if ($spec->production_end === 'Present') {
                $this->end_year = '';
                $this->end_month = '';
            } else {
                $endParts = explode('-', $spec->production_end);
                $this->end_year = $endParts[0];
                $this->end_month = $endParts[1] ?? '';
            }
        }

        // Map Variant Data
        if ($spec->variant) {
            $this->trim_level = $spec->variant->trim_level;
            $this->production_year = $spec->variant->production_year;
            $this->is_default = (bool)$spec->variant->is_default;
        }
    }

    public function updatedBrandId()
    {
        $this->vehicle_model_id = null;
    }

    public function getVehicleModelsProperty()
    {
        return $this->brand_id 
            ? VehicleModel::where('brand_id', $this->brand_id)->orderBy('model_name')->get() 
            : collect();
    }

   protected function rules()
{
    return [
        // Vehicle Selection
        'brand_id' => 'required|exists:brands,id',
        'vehicle_model_id' => 'required|exists:vehicle_models,id',
        'trim_level' => 'required|string|max:100',
        
        // Identity & Technical
        'chassis_code' => 'nullable|string|max:100',
        'model_code' => 'nullable|string|max:100',
        'destination_id' => 'nullable|exists:destinations,id',
        'body_type_id' => 'required|exists:body_types,id',
        'engine_type_id' => 'required|exists:engine_types,id',
        'transmission_type_id' => 'nullable|exists:transmission_types,id',
        'drive_type_id' => 'nullable|exists:drive_types,id',
        'engine_displacement_id' => 'nullable|exists:engine_displacements,id',
        
        // Specs (Numeric)
        'horsepower' => 'nullable|integer|min:0',
        'torque' => 'nullable|integer|min:0',
        'fuel_capacity' => 'nullable|numeric|min:0',
        'fuel_efficiency' => 'nullable|string|max:50',
        'seats' => 'nullable|integer|min:1|max:100',
        'doors' => 'nullable|integer|min:1|max:10',
        
        // Settings & Production
        'steering_position' => 'required|in:LEFT,RIGHT',
        'color' => 'nullable|string|max:50',
        'status' => 'required|boolean',
        'production_year' => 'required|integer|min:1900',
        
        // Date Logic: Production can start up to 2 years before the Model Year
        'start_year' => 'required|integer|min:1900|gte:' . ($this->production_year - 2),
        'start_month' => 'nullable|integer|between:1,12',
        'end_year' => 'nullable|integer|min:1900|gte:start_year',
        'end_month' => 'nullable|integer|between:1,12',
    ];
}

    public function save()
    {
        $this->validate();
        
        try {
            DB::transaction(function () {
                $spec = Specification::findOrFail($this->specificationId);

                // Reconstruct strings
                $prodStart = $this->start_year . ($this->start_month ? '-' . str_pad($this->start_month, 2, '0', STR_PAD_LEFT) : '');
                $prodEnd = $this->end_year 
                    ? ($this->end_year . ($this->end_month ? '-' . str_pad($this->end_month, 2, '0', STR_PAD_LEFT) : '')) 
                    : 'Present';
                
                $spec->update([
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
                    'production_start'       => $prodStart,
                    'production_end'         => $prodEnd,
                    'status'                 => $this->status,
                ]);

                if ($spec->variant) {
                    $spec->variant->update([
                        'vehicle_model_id' => $this->vehicle_model_id,
                        'production_year'  => $this->production_year,
                        'trim_level'       => $this->trim_level,
                    ]);

                    $spec->variant->refresh(); 
                    if (method_exists($spec->variant, 'syncNameFromSpec')) {
                        $spec->variant->syncNameFromSpec();
                    }
                }

                $spec->destinations()->sync($this->destination_id ? [$this->destination_id] : []);
            });

            session()->flash('success', 'Specification updated successfully!');
            return redirect()->route('admin.specifications.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.specifications.edit-specification', [
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