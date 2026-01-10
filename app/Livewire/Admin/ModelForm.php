<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\Specification;
use App\Models\BodyType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\DriveType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ModelForm extends Component
{
      // ================= MODEL FIELDS =================
    public $brand_id;
    public $model_name;
    public $description;
    public $has_variants = 1;
    public $production_start_year;
    public $production_end_year;
    public $status = 1;

      // ================= SPECIFICATION FIELDS =================
    public $spec = [
        'body_type_id' => null,
        'engine_type_id' => null,
        'transmission_type_id' => null,
        'drive_type_id' => null,
        'horsepower' => null,
        'torque' => null,
        'fuel_capacity' => null,
        'fuel_efficiency' => null,
        'seats' => null,
        'doors' => null,
        'steering_position' => null,
        'color' => null,
    ];

    // ================= VALIDATION =================
    protected function rules()
    {
        $rules = [
            'brand_id' => 'required|exists:brands,id',
            'model_name' => 'required|string|max:255',
            'has_variants' => 'required|boolean',
        ];

        if ($this->has_variants == 0) {
            $rules = array_merge($rules, [
                'spec.body_type_id' => 'required|exists:body_types,id',
                'spec.engine_type_id' => 'required|exists:engine_types,id',
                'spec.transmission_type_id' => 'required|exists:transmission_types,id',
            ]);
        }

        return $rules;
    }

     // ================= SAVE =================
    public function save()
    {
        $this->validate();

        DB::transaction(function () {

            // 1️⃣ Create Vehicle Model
            $model = VehicleModel::create([
                'brand_id' => $this->brand_id,
                'model_name' => $this->model_name,
                'description' => $this->description,
                'has_variants' => $this->has_variants,
                'production_start_year' => $this->production_start_year,
                'production_end_year' => $this->production_end_year,
                'status' => $this->status,
            ]);

            // 2️⃣ Create Specification ONLY if NO variants
            if ($this->has_variants == 0) {
                Specification::create(array_merge(
                    $this->spec,
                    ['vehicle_model_id' => $model->id]
                ));
            }
        });

        session()->flash('success', 'Vehicle model created successfully.');

        return redirect()->route('admin.vehicle-models.index');
    }

    public function render()
    {
        return view('livewire.admin.model-form', [
            'brands' => Brand::orderBy('brand_name')->get(),
            'bodyTypes' => BodyType::all(),
            'engineTypes' => EngineType::all(),
            'transmissionTypes' => TransmissionType::all(),
            'driveTypes' => DriveType::all(),]);
    }
}
