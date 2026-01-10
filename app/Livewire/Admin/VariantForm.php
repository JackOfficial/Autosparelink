<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;

class VariantForm extends Component
{
    use WithFileUploads;

    /* =====================
     | Vehicle selection
     ===================== */
    public $brand_id;
    public $vehicle_model_id;

    /* =====================
     | Variant fields
     ===================== */
    public $name;
    public $chassis_code;
    public $model_code;
    public $trim_level;
    public $status = 1;

    /* =====================
     | Media
     ===================== */
    public $photos = []; // MULTIPLE PHOTOS

    /* =====================
     | Specs question
     ===================== */
    public $has_specifications = null;

    /* =====================
     | Data
     ===================== */
    public $brands = [];
    public $vehicleModels = [];

    public function mount()
    {
        $this->brands = Brand::orderBy('brand_name')->get();
    }

    /* =========================
     | Dynamic dropdowns
     ========================= */
    public function updatedBrandId()
    {
        $this->vehicle_model_id = null;

        $this->vehicleModels = VehicleModel::where('brand_id', $this->brand_id)
            ->orderBy('model_name')
            ->get();
    }

    /* =========================
     | Validation rules
     ========================= */
    protected function rules()
    {
        return [
            'brand_id'           => 'required|exists:brands,id',
            'vehicle_model_id'   => 'required|exists:vehicle_models,id',
            'name'               => 'required|string|max:255',
            'chassis_code'       => 'nullable|string|max:100',
            'model_code'         => 'nullable|string|max:100',
            'trim_level'         => 'nullable|string|max:100',
            'status'             => 'required|boolean',

            'photos.*'           => 'nullable|image|max:2048',
            'has_specifications' => 'required|boolean',
        ];
    }

    /* =========================
     | Save
     ========================= */
    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $variant = Variant::create([
                'vehicle_model_id' => $this->vehicle_model_id,
                'name'             => $this->name,
                'chassis_code'     => $this->chassis_code,
                'model_code'       => $this->model_code,
                'trim_level'       => $this->trim_level,
                'status'           => $this->status,
            ]);

            /* ===== Save photos ===== */
            foreach ($this->photos as $photo) {
                $variant->photos()->create([
                    'path' => $photo->store('variants', 'public'),
                ]);
            }

            DB::commit();

            session()->flash('success', 'Variant created successfully.');

            /* ===== Redirect based on specs ===== */
            if ($this->has_specifications) {
                return redirect()->route('admin.specifications.create', [
                    'variant_id' => $variant->id,
                ]);
            }

            return redirect()->route('admin.variants.index');

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.admin.variant-form');
    }
}
