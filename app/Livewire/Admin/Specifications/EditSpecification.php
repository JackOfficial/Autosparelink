<?php

namespace App\Livewire\Admin\Specifications;

use Livewire\Component;
use App\Models\{Specification, Brand, VehicleModel, BodyType, EngineType, TransmissionType, DriveType, EngineDisplacement, Variant};
use Illuminate\Support\Facades\DB;

class EditSpecification extends Component
{
    public $specificationId;
    
    // Vehicle Selection
    public $brand_id, $vehicle_model_id;
    public $trim_level; 
    public $vehicleModels = [];

    // Form Fields
    public $body_type_id, $engine_type_id, $transmission_type_id, $drive_type_id, $engine_displacement_id;
    public $horsepower, $torque, $fuel_capacity, $fuel_efficiency;
    public $seats, $doors, $steering_position = 'LEFT', $color = '#000000';
    public $production_start, $production_end, $production_year, $status = 1;

    public function mount($specificationId)
    {
        $spec = Specification::findOrFail($specificationId);
        $this->specificationId = $specificationId;

        $this->fill($spec->toArray());

        if ($spec->vehicle_model_id) {
            $this->vehicle_model_id = $spec->vehicle_model_id;
            $this->brand_id = $spec->vehicleModel->brand_id;
            $this->vehicleModels = VehicleModel::where('brand_id', $this->brand_id)->get();
        }
    }

    public function updatedBrandId($value)
    {
        $this->vehicleModels = $value ? VehicleModel::where('brand_id', $value)->get() : [];
        $this->vehicle_model_id = null;
    }

    protected function rules()
    {
        return [
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'trim_level' => 'nullable|string|max:255',
            'body_type_id' => 'required|exists:body_types,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id' => 'nullable|exists:drive_types,id',
            'engine_displacement_id' => 'nullable|exists:engine_displacements,id',
            'horsepower' => 'nullable|numeric',
            'torque' => 'nullable|numeric',
            'seats' => 'nullable|integer|max:20',
            'doors' => 'nullable|integer|max:10',
            'color' => 'nullable|string',
            'status' => 'boolean',
            'production_year' => 'nullable|integer',
        ];
    }

    public function save()
    {
        $this->validate();
        
        try {
            DB::transaction(function () {
                $spec = Specification::findOrFail($this->specificationId);
                
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
                    'production_start' => $this->production_start,
                    'production_end' => $this->production_end,
                    'production_year' => $this->production_year,
                    'status' => $this->status,
                ]);

                dd("here");

                // Sync Variant Name
                if ($spec->variant) {
                    $spec->variant->update(['vehicle_model_id' => $this->vehicle_model_id]);
                    $spec->variant->refresh();
                    $spec->variant->syncNameFromSpec();
                }
            });

            session()->flash('success', 'Specification and Variant name updated!');
            return redirect()->route('admin.specifications.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.specifications.edit-specification', [
            'brands' => Brand::orderBy('brand_name')->get(),
            'bodyTypes' => BodyType::all(),
            'engineTypes' => EngineType::all(),
            'transmissionTypes' => TransmissionType::all(),
            'driveTypes' => DriveType::all(),
            'engineDisplacements' => EngineDisplacement::all(),
        ]);
    }
}