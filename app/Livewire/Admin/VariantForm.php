<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\Variant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class VariantForm extends Component
{
    use WithFileUploads;

    // Selection
    public $brand_id;
    public $vehicle_model_id;
    public $disableModelDropdown = false;

    // Variant fields
    public $name;
    public $chassis_code;
    public $model_code;
    public $status = 1;

    // Media
    public $photos = [];

    // Does this variant have specifications?
    public $has_specifications = 0;

    // Data
    public $brands;
    public $vehicleModels = [];

    public function mount($vehicle_model_id = null)
    {
        $this->brands = Brand::orderBy('brand_name')->get();

        if ($vehicle_model_id) {
            $model = VehicleModel::with('brand')->find($vehicle_model_id);
            if ($model) {
                $this->vehicle_model_id = $model->id;
                $this->brand_id = $model->brand_id;

                $this->vehicleModels = VehicleModel::where('brand_id', $this->brand_id)
                    ->orderBy('model_name')
                    ->get();

                $this->disableModelDropdown = true;
            }
        }
    }

    // Dynamic dropdowns
    public function updatedBrandId($value)
    {
        $this->vehicleModels = VehicleModel::where('brand_id', $value)
            ->orderBy('model_name')
            ->get();

        $this->vehicle_model_id = null;
    }

    // Validation
    protected function rules()
    {
        return [
            'brand_id' => 'required|exists:brands,id',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'name' => 'required|string|max:255',
            'chassis_code' => 'nullable|string|max:100',
            'model_code' => 'nullable|string|max:100',
            'status' => 'required|boolean',
            'photos.*' => 'nullable|image|max:2048',
            'has_specifications' => 'required|boolean',
        ];
    }

    // Save
    public function save()
    {
        $this->validate();

        DB::transaction(function () {

            // 1️⃣ Create Variant
            $variant = Variant::create([
                'vehicle_model_id' => $this->vehicle_model_id,
                'name' => $this->name,
                'chassis_code' => $this->chassis_code ?: null,
                'model_code' => $this->model_code ?: null,
                'status' => $this->status,
            ]);

            // 2️⃣ SEO-friendly photo upload
            if (!empty($this->photos)) {
                $model = VehicleModel::with('brand')->find($this->vehicle_model_id);

                foreach ($this->photos as $photo) {
                    $filename = Str::slug(
                        $model->brand->brand_name . '-' .
                        $model->model_name . '-' .
                        $this->name
                    ) . '-' . time() . '.' . $photo->getClientOriginalExtension();

                    $path = $photo->storeAs(
                        'variants/' . $variant->id,
                        $filename,
                        'public'
                    );

                    $variant->photos()->create([
                        'file_path' => $path,
                        'caption' => null,
                    ]);
                }
            }

            // 3️⃣ Redirect logic
            if ($this->has_specifications == 1) {
                session()->flash('success', 'Variant created successfully. Add specifications now.');
                redirect()->route('admin.specifications.create', [
                    'variant_id' => $variant->id
                ]);
            } else {
                session()->flash('success', 'Variant created successfully.');
                redirect()->route('admin.variants.index');
            }
        });
    }

    public function render()
    {
        return view('livewire.admin.variant-form');
    }
}
