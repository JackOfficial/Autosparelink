<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\Specification;
use App\Models\BodyType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\DriveType;
use App\Models\Photo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class ModelForm extends Component
{
    use WithFileUploads;

    // ================= MODEL FIELDS =================
    public $brand_id;
    public $model_name;
    public $photos = []; // Temporary uploaded files
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
        'variant_id' => null,         // XOR
        'vehicle_model_id' => null,   // XOR
    ];

    // ================= VALIDATION RULES =================
    protected function rules()
    {
        $rules = [
            'brand_id' => 'required|exists:brands,id',
            'model_name' => 'required|string|max:255',
            'photos.*' => 'image|max:5120', // 5MB max per photo
            'has_variants' => 'required|boolean',
            'production_start_year' => 'nullable|digits:4|integer',
            'production_end_year' => 'nullable|digits:4|integer|gte:production_start_year',
        ];

        // Only validate spec if model has NO variants
        if ($this->has_variants == 0) {
            $rules = array_merge($rules, [
                'spec.body_type_id' => 'required|exists:body_types,id',
                'spec.engine_type_id' => 'required|exists:engine_types,id',
                'spec.transmission_type_id' => 'required|exists:transmission_types,id',
                'spec.horsepower' => 'nullable|numeric|min:0',
                'spec.torque' => 'nullable|numeric|min:0',
                'spec.fuel_capacity' => 'nullable|numeric|min:0',
                'spec.fuel_efficiency' => 'nullable|numeric|min:0',
                'spec.seats' => 'nullable|integer|min:1',
                'spec.doors' => 'nullable|integer|min:1',
                'spec.steering_position' => 'nullable|in:LEFT,RIGHT',
                'spec.color' => 'nullable|string|max:20',
            ]);
        }

        return $rules;
    }

    // ================= SAVE FUNCTION =================
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

            // 2️⃣ Create Specification ONLY if model has NO variants
            if ($this->has_variants == 0) {

                $this->spec['vehicle_model_id'] = $model->id;
                $this->spec['variant_id'] = null;

                // XOR enforcement
                if (($this->spec['variant_id'] && $this->spec['vehicle_model_id']) ||
                    (!$this->spec['variant_id'] && !$this->spec['vehicle_model_id'])) {
                    throw ValidationException::withMessages([
                        'spec' => ['Specification must be linked to either a variant OR a vehicle model, but not both.']
                    ]);
                }

                Specification::create($this->spec);
            }

            // 3️⃣ Save uploaded photos to polymorphic Photo table
            foreach ($this->photos as $photo) {
                $path = $photo->store('vehicle_models/' . $model->id, 'public');
                $model->photos()->create([
                    'file_path' => $path,
                    'caption' => null,
                ]);
            }
        });

        session()->flash('success', 'Vehicle model created successfully.');
        return redirect()->route('admin.vehicle-models.index');
    }

    // ================= RENDER =================
    public function render()
    {
        return view('livewire.admin.model-form', [
            'brands' => Brand::orderBy('brand_name')->get(),
            'bodyTypes' => BodyType::all(),
            'engineTypes' => EngineType::all(),
            'transmissionTypes' => TransmissionType::all(),
            'driveTypes' => DriveType::all(),
        ]);
    }
}
