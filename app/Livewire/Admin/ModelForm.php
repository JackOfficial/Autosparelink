<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\VehicleModel;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModelForm extends Component
{
    use WithFileUploads;

    // ================= MODEL FIELDS =================
    public $brand_id;
    public $model_name;
    public $photos = []; // Temporary uploaded files
    public $description;
    public $has_variants = 1;
    public $status = 1;

    // ================= VALIDATION RULES =================
    protected function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'model_name' => 'required|string|max:255',
            'photos.*' => 'image|max:5120', // 5MB per photo
            'has_variants' => 'required|boolean',
        ];
    }

    // ================= SAVE FUNCTION =================
    public function save()
    {
        $this->validate();

        DB::transaction(function () {

            // 2️⃣ Create Vehicle Model
            $model = VehicleModel::create([
                'brand_id' => $this->brand_id,
                'model_name' => $this->model_name,
                'description' => $this->description,
                'has_variants' => $this->has_variants,
                'status' => $this->status,
            ]);

            // 3️⃣ Save uploaded photos with SEO-friendly filenames
            foreach ($this->photos as $photo) {
                $filename = Str::slug($this->model_name) . '-' . time() . '.' . $photo->getClientOriginalExtension();
                $path = $photo->storeAs('vehicle_models/' . $model->id, $filename, 'public');

                $model->photos()->create([
                    'file_path' => $path,
                    'caption' => null,
                ]);
            }

            // 4️⃣ Redirect based on variants
            if ($this->has_variants == 0) {
                session()->flash('success', 'Vehicle model created. Add specifications now.');
                redirect()->route('admin.specifications.create', ['vehicle_model_id' => $model->id]);
            } else {
                session()->flash('success', 'Vehicle model created successfully. Add variant now.');
                redirect()->route('admin.variants.create', ['vehicle_model_id' => $model->id]);
            }

        });
    }

    // ================= RENDER =================
    public function render()
    {
        return view('livewire.admin.model-form', [
            'brands' => Brand::orderBy('brand_name')->get(),
        ]);
    }
}
