<?php

namespace App\Livewire\Admin\Part;

use App\Models\Variant;
use Livewire\Component;

use Livewire\WithFileUploads;
use App\Models\{Part, Category, PartBrand, VehicleModel, Specification, PartFitment };
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Create extends Component
{
    use WithFileUploads;

    /* =======================
     | FORM FIELDS
     ======================= */
    public $part_number;
    public $part_name;
    public $parentCategoryId;
    public $category_id;
    public $part_brand_id;
    public $oem_number;
    public $price;
    public $stock_quantity;
    public $status = 1;
    public $description;

    public $photos = [];
    public $fitment_specifications = [];

    /* =======================
     | DATA
     ======================= */
    public $parentCategories = [];
    public $childCategories = [];
    public $partBrands = [];
    public $vehicleModels = [];

    /* =======================
     | MOUNT
     ======================= */
    public function mount()
    {
        $this->parentCategories = Category::whereNull('parent_id')->orderBy('category_name')->get();
        $this->partBrands = PartBrand::orderBy('name')->get();

        // IMPORTANT: same logic that WORKED before
        $this->vehicleModels = VehicleModel::with([
            'brand',
            'variants.specifications',
            'specifications'
        ])->orderBy('model_name')->get();
    }

    /* =======================
     | CATEGORY DEPENDENCY
     ======================= */
    public function updatedParentCategoryId()
    {
        $this->category_id = null;

        $this->childCategories = Category::where('parent_id', $this->parentCategoryId)
            ->orderBy('category_name')
            ->get();
    }

    /* =======================
     | SAVE
     ======================= */
    public function save()
    {
        $this->validate([
            'part_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'part_brand_id' => 'required|exists:part_brands,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|integer',

            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'fitment_specifications.*' => 'exists:specifications,id',
        ]);

        /* -------- SKU -------- */
        $sku = Part::generateSku(
            PartBrand::find($this->part_brand_id)->name,
            Category::find($this->category_id)->category_name,
            $this->part_name
        );

        $part = Part::create([
            'part_number' => $this->part_number,
            'part_name' => $this->part_name,
            'sku' => $sku,
            'category_id' => $this->category_id,
            'part_brand_id' => $this->part_brand_id,
            'oem_number' => $this->oem_number,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'status' => $this->status,
            'description' => $this->description,
        ]);

        /* -------- PHOTOS -------- */
        if ($this->photos) {
            $folder = 'parts/' .
                Str::slug($part->partBrand->name) . '/' .
                Str::slug($part->category->category_name);

            foreach ($this->photos as $photo) {
                $filename = Str::slug($this->part_name) . '-' . uniqid() . '.' . $photo->extension();
                $path = $photo->storeAs($folder, $filename, 'public');

                $part->photos()->create([
                    'file_path' => $path,
                    'caption' => $this->part_name,
                ]);
            }
        }

        /* -------- FITMENTS (EXACT SAME LOGIC) -------- */
        foreach ($this->fitment_specifications as $specId) {
            $spec = Specification::with(['variant', 'vehicleModel'])->find($specId);

            PartFitment::create([
                'part_id' => $part->id,
                'variant_id' => $spec->variant_id ?? optional($spec->variant)->id,
                'vehicle_model_id' => $spec->vehicle_model_id ?? optional($spec->variant)->vehicle_model_id,
                'status' => 'active',
                'year_start' => $spec->production_start,
                'year_end' => $spec->production_end,
            ]);
        }

        session()->flash('success', 'Part created successfully.');
        return redirect()->route('admin.spare-parts.index');
    }

    public function render()
    {
        return view('livewire.admin.part.create');
    }
}
