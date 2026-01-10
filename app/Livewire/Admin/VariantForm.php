<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\Variant;

class VariantForm extends Component
{
    use WithFileUploads;

    // Selection
    public $brand_id;
    public $vehicle_model_id;

    // Variant fields
    public $name;
    public $chassis_code;
    public $model_code;
    public $trim_level;
    public $status = 1;

    // Media
    public $photos = []; // multiple photos

    // Does this variant have specifications?
    public $has_specifications = 0;

    // Data
    public $brands;
    public $vehicleModels = [];

    public function mount()
    {
        $this->brands = Brand::orderBy('brand_name')->get();
    }

    // Dynamic dropdowns
    public function updatedBrandId($value)
    {
        $this->vehicleModels = VehicleModel::where('brand_id', $value)
            ->orderBy('model_name')
            ->get();
        $this->vehicle_model_id = null;
    }

    // Validation rules
    protected function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'name' => 'required|string|max:255',
            'chassis_code' => 'nullable|string|max:100',
            'model_code' => 'nullable|string|max:100',
            'trim_level' => 'nullable|string|max:100',
            'status' => 'required|boolean',
            'photos.*' => 'nullable|image|max:2048', // each photo max 2MB
            'has_specifications' => 'required|boolean',
        ];
    }

    // Save method
    public function save()
    {
        $this->validate();

        // Create Variant
        $variant = Variant::create([
            'vehicle_model_id' => $this->vehicle_model_id,
            'name' => $this->name,
            'chassis_code' => $this->chassis_code,
            'model_code' => $this->model_code,
            'trim_level' => $this->trim_level,
            'status' => $this->status,
        ]);

        // Upload multiple photos if any
        if (!empty($this->photos)) {
             foreach ($this->photos as $photo) {
            $path = $photo->store('variants/' . $variant->id, 'public');
            $variant->photos()->create([
                'file_path' => $path,
                'caption' => null,
            ]);
        }
        }

         // Redirect based on specifications
        if ($this->has_specifications == 1) {
            session()->flash('success', 'Variant created successfully. Add specifications now.');
            return redirect()->route('admin.specifications.create', [
                'variant_id' => $variant->id
            ]);
        } else {
            session()->flash('success', 'Variant created successfully.');
            return redirect()->route('admin.variants.index');
        }
    }

    public function render()
    {
        return view('livewire.admin.variant-form');
    }
}
