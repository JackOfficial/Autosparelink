<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\Photo;
use Illuminate\Support\Facades\DB;
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

    // ================= VALIDATION RULES =================
    protected function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'model_name' => 'required|string|max:255',
            'photos.*' => 'image|max:5120', // 5MB max per photo
            'has_variants' => 'required|boolean',
            'production_start_year' => 'nullable|digits:4|integer',
            'production_end_year' => 'nullable|digits:4|integer|gte:production_start_year',
        ];
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

            // 2️⃣ Save uploaded photos to polymorphic Photo table
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
        ]);
    }
}
