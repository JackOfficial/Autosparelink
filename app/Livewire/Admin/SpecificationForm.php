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

class SpecificationForm extends Component
{
    // ================= FIELDS =================
    public $brand_id;
    public $vehicle_model_id;
    public $variant_id;

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

    // ✅ FIXED YEARS
    public $production_start;
    public $production_end;
    public $production_year;

    // ================= INIT =================
    public $brands;
    public $vehicleModels;
    public $variants;
    public $filteredVariants;
    public $bodyTypes;
    public $engineTypes;
    public $engineDisplacements;
    public $transmissionTypes;
    public $driveTypes;

    public $hideBrandModel = false;
    public $hideVariant = false;

    public function mount($vehicle_model_id = null, $variant_id = null)
    {
        $this->brands = Brand::orderBy('brand_name')->get();
        $this->variants = Variant::with('vehicleModel')->orderBy('name')->get();
        $this->vehicleModels = collect();
        $this->filteredVariants = collect();

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
            }
            $this->hideBrandModel = true;
            $this->updateVariants();
        }

        if ($variant_id) {
            $variant = Variant::find($variant_id);
            if ($variant) {
                $this->variant_id = $variant->id;
                $this->vehicle_model_id = $variant->vehicle_model_id;
                $this->brand_id = $variant->vehicleModel->brand_id ?? null;
            }
            $this->hideBrandModel = true;
            $this->hideVariant = true;
        }
    }

    // ================= DROPDOWNS =================
    public function updatedBrandId($value)
    {
        $this->vehicleModels = $value
            ? VehicleModel::where('brand_id', $value)->orderBy('model_name')->get()
            : collect();

        $this->vehicle_model_id = null;
        $this->variant_id = null;
        $this->filteredVariants = collect();
    }

    public function updatedVehicleModelId()
    {
        $this->updateVariants();
        $this->variant_id = null;
    }

    private function updateVariants()
    {
        $this->filteredVariants = $this->vehicle_model_id
            ? $this->variants->where('vehicle_model_id', $this->vehicle_model_id)
            : collect();
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
            'engine_displacement_id' => 'nullable|exists:engine_displacements,id',
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
            'production_year' => 'nullable|integer|min:1950|max:' . date('Y'),
        ];
    }

    // ================= SAVE =================
    public function save()
    {
        $this->validate();

        // Require at least model or variant
        if (!$this->vehicle_model_id && !$this->variant_id) {
            throw ValidationException::withMessages([
                'vehicle_model_id' => 'You must select a Vehicle Model or Variant.',
                'variant_id' => 'You must select a Vehicle Model or Variant.',
            ]);
        }

        // ✅ FIX EMPTY STRING → NULL
        $productionStart = $this->production_start ?: null;
        $productionEnd   = $this->production_end ?: null;
        $productionYear   = $this->production_year ?: null;

        // ✅ FIX LOGICAL ORDER
        if ($productionStart && $productionEnd && $productionEnd < $productionStart) {
            throw ValidationException::withMessages([
                'production_end' => 'Production end year must be greater than or equal to start year.',
            ]);
        }

        Specification::create([
            'variant_id' => $this->variant_id,
            'vehicle_model_id' => $this->vehicle_model_id,
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

            // ✅ FIXED
            'production_start' => $productionStart,
            'production_end'   => $productionEnd,
            'production_year'   => $productionYear,
        ]);

        session()->flash('success', 'Specification saved successfully.');

        return redirect()->route('admin.specifications.index');
    }

    public function render()
    {
        return view('livewire.admin.specification-form');
    }
}
