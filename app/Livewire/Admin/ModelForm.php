<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\VehicleModel;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ModelForm extends Component
{
    use WithFileUploads;

    // ================= MODEL FIELDS =================
  public $brand_id;
    public $model_name;
    public $photos = [];
    public $description;
    public $status = 1;

    protected function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            // This ensures the model_name is unique in the vehicle_models table
            'model_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vehicle_models', 'model_name')
                    ->where('brand_id', $this->brand_id) // Only blocks if same brand has same name
            ],
            'photos.*' => 'image|max:5120',
        ];
    }

    public function save()
    {
        // 1. This will now stop the process and show a red error if a duplicate exists
        $this->validate();

        $newModelId = null;

        try {
            DB::transaction(function () use (&$newModelId) {
                $model = VehicleModel::create([
                    'brand_id' => $this->brand_id,
                    'model_name' => $this->model_name,
                    'description' => $this->description,
                    'status' => $this->status,
                    'has_variants' => 1, 
                ]);

                $newModelId = $model->id;

                foreach ($this->photos as $photo) {
                    $filename = Str::slug($this->model_name) . '-' . time() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('vehicle_models/' . $model->id, $filename, 'public');

                    $model->photos()->create([
                        'file_path' => $path,
                        'caption' => null,
                    ]);
                }
            });

            session()->flash('success', 'Vehicle model created successfully.');
            
            return redirect()->route('admin.specifications.create', [
                'vehicle_model_id' => $newModelId
            ]);

        } catch (\Exception $e) {
            logger($e->getMessage());
            $this->addError('model_name', 'A database error occurred while saving.');
        }
    }

    // ================= RENDER =================
    public function render()
    {
        return view('livewire.admin.model-form', [
            'brands' => Brand::orderBy('brand_name')->get(),
        ]);
    }
}