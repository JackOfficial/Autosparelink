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
    public $production_start_year; // New Field
    public $production_end_year;   // New Field
    public $photos = [];
    public $description;
    public $status = 1;

    protected function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'model_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vehicle_models', 'model_name')
                    ->where('brand_id', $this->brand_id) 
            ],
            'production_start_year' => 'nullable|integer|min:1886|max:' . (date('Y') + 2),
            'production_end_year'   => [
                'nullable',
                'integer',
                'max:' . (date('Y') + 10),
                'gte:production_start_year' // End year must be Greater Than or Equal to Start Year
            ],
            'photos.*' => 'image|max:5120',
        ];
    }

    public function save()
    {
        $this->validate();

        $newModelId = null;

        try {
            DB::transaction(function () use (&$newModelId) {
                $model = VehicleModel::create([
                    'brand_id'              => $this->brand_id,
                    'model_name'            => $this->model_name,
                    'production_start_year' => $this->production_start_year,
                    'production_end_year'   => $this->production_end_year,
                    'description'           => $this->description,
                    'status'                => $this->status,
                    'has_variants'          => 1, 
                ]);

                $newModelId = $model->id;

                foreach ($this->photos as $photo) {
                    $filename = Str::slug($this->model_name) . '-' . time() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('vehicle_models/' . $model->id, $filename, 'public');

                    $model->photos()->create([
                        'file_path' => $path,
                        'caption'   => null,
                    ]);
                }
            });

            session()->flash('success', 'Vehicle model created successfully.');
            
            return redirect()->route('admin.specifications.create', [
                'vehicle_model_id' => $newModelId
            ]);

        } catch (\Exception $e) {
            logger($e->getMessage());
            $this->addError('model_name', 'A database error occurred while saving: ' . $e->getMessage());
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