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
use App\Models\Specification;
use Illuminate\Validation\ValidationException;

class SpecificationForm extends Component
{
    // ================= FIELDS =================
    public $brand_id;
    public $vehicle_model_id;
    public $variant_id;

    public $body_type_id;
    public $engine_type_id;
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

    // ================= INIT =================
    public $brands;
    public $vehicleModels;      // Filtered by brand
    public $variants;           // All variants
    public $filteredVariants;   // Filtered by selected model
    public $bodyTypes;
    public $engineTypes;
    public $transmissionTypes;
    public $driveTypes;

    public $hideBrandModel = false; // true if redirected with preselected model

    public function mount($vehicle_model_id = null)
{
    $this->brands = Brand::orderBy('brand_name')->get();
    $this->variants = Variant::with('vehicleModel')->orderBy('name')->get();
    $this->vehicleModels = collect();
    $this->filteredVariants = collect();

    // Core options
    $this->bodyTypes = BodyType::orderBy('name')->get();
    $this->engineTypes = EngineType::orderBy('name')->get();
    $this->transmissionTypes = TransmissionType::orderBy('name')->get();
    $this->driveTypes = DriveType::orderBy('name')->get();

    if ($vehicle_model_id) {
        $model = VehicleModel::with('variants')->find($vehicle_model_id);
        if ($model) {
            $this->vehicle_model_id = $model->id;
            $this->brand_id = $model->brand_id;
            $this->vehicleModels = collect([$model]); // only show this model
            $this->filteredVariants = $model->variants; // prefill variants
        }
        $this->hideBrandModel = true; // hide dropdowns for redirect
    }
}

public function updatedBrandId($brand_id)
{
    $this->vehicle_model_id = null;
    $this->variant_id = null;

    $this->vehicleModels = $brand_id
        ? VehicleModel::where('brand_id', $brand_id)->orderBy('model_name')->get()
        : collect();

    $this->filteredVariants = collect(); // reset variants
}

public function updatedVehicleModelId($vehicle_model_id)
{
    $this->variant_id = null;
    $model = VehicleModel::with('variants')->find($vehicle_model_id);
    $this->filteredVariants = $model ? $model->variants : collect();
}

    private function updateVariants()
    {
        if ($this->vehicle_model_id) {
            $this->filteredVariants = $this->variants->where('vehicle_model_id', $this->vehicle_model_id);
        } else {
            $this->filteredVariants = collect();
        }
    }

    // ================= VALIDATION =================
    protected function rules()
    {
        return [
            'brand_id' => 'nullable|exists:brands,id',
            'vehicle_model_id' => 'nullable|exists:vehicle_models,id',
            'variant_id' => 'nullable|exists:variants,id',
            'body_type_id' => 'required|exists:body_types,id',
            'engine_type_id' => 'required|exists:engine_types,id',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'drive_type_id' => 'nullable|exists:drive_types,id',
            'horsepower' => 'nullable|numeric|min:0',
            'torque' => 'nullable|numeric|min:0',
            'fuel_capacity' => 'nullable|numeric|min:0',
            'fuel_efficiency' => 'nullable|numeric|min:0',
            'seats' => 'nullable|integer|min:1',
            'doors' => 'nullable|integer|min:1',
            'steering_position' => 'nullable|in:LEFT,RIGHT',
            'color' => 'nullable|string|max:20',
            'production_start' => 'nullable|integer|min:1950|max:' . date('Y'),
            'production_end' => 'nullable|integer|min:1950|max:' . (date('Y') + 2),
        ];
    }

    // ================= SAVE =================
    public function save()
    {
        $this->validate();

        // XOR logic for variant/model only if not preselected
        if (!$this->hideBrandModel) {
            if (($this->variant_id && $this->vehicle_model_id) ||
                (!$this->variant_id && !$this->vehicle_model_id)) {
                throw ValidationException::withMessages([
                    'vehicle_model_id' => 'You must select either a Variant OR a Vehicle Model, but not both.',
                ]);
            }
        }

        Specification::create([
            'variant_id' => $this->variant_id,
            'vehicle_model_id' => $this->vehicle_model_id,
            'body_type_id' => $this->body_type_id,
            'engine_type_id' => $this->engine_type_id,
            'transmission_type_id' => $this->transmission_type_id,
            'drive_type_id' => $this->drive_type_id,
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
        ]);

        session()->flash('success', 'Specification saved successfully.');

        return redirect()->route('admin.specifications.index');
    }

    public function render()
    {
        return view('livewire.admin.specification-form');
    }
}
