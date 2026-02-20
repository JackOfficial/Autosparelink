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
    public $status = 1;

    // ================= VALIDATION RULES =================
    protected function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'model_name' => 'required|string|max:255',
            'photos.*' => 'image|max:5120', // 5MB per photo
        ];
    }

    // ================= SAVE FUNCTION =================
    public function save()
    {
        $this->validate();

        DB::transaction(function () {

            // 1. Create Vehicle Model
            $model = VehicleModel::create([
                'brand_id' => $this->brand_id,
                'model_name' => $this->model_name,
                'description' => $this->description,
                'status' => $this->status,
                // If your DB column still exists, we default it to 1 or remove it from here
                'has_variants' => 1, 
            ]);

            // 2. Save uploaded photos with SEO-friendly filenames
            foreach ($this->photos as $photo) {
                $filename = Str::slug($this->model_name) . '-' . time() . '.' . $photo->getClientOriginalExtension();
                $path = $photo->storeAs('vehicle_models/' . $model->id, $filename, 'public');

                $model->photos()->create([
                    'file_path' => $path,
                    'caption' => null,
                ]);
            }

            // 3. Redirect to Specification Form
            // Since variants are now "headless" and managed via Specs, 
            // we always go straight to the spec builder.
            session()->flash('success', 'Vehicle model created. Now, let\'s add its first specification.');
            
            // return redirect()->route('admin.specifications.create', [
            //     'vehicle_model_id' => $model->id
            // ]);
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