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

    /* FORM FIELDS */
    public $part_number;
    public $part_name;
    public $parentCategoryId;
    public $category_id;
    public $part_brand_id;
    public $oem_number;
    public $price;
    public $stock_quantity;
    public $status = 'Active';
    public $description;
    public $allParts = []; // All parts for selection

    public $photos = [];
    public $fitment_specifications = [];
    public $substitution_part_ids = []; // array of alternative part IDs

    /* DATA */
    public $parentCategories = [];
    public $childCategories = [];
    public $partBrands = [];
    public $vehicleModels = [];

    protected function rules()
    {
        return [
            'part_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'part_brand_id' => 'required|exists:part_brands,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|in:Active,Inactive',

            'photos.*' => 'image|max:2048',
            'fitment_specifications' => 'nullable|array',
            'fitment_specifications.*' => 'exists:specifications,id',

            'substitution_part_ids' => 'nullable|array',
            'substitution_part_ids.*' => 'exists:parts,id',
        ];
    }

    public function mount()
    {
        $this->parentCategories = Category::whereNull('parent_id')->orderBy('category_name')->get();
        $this->partBrands = PartBrand::orderBy('name')->get();
        $this->vehicleModels = VehicleModel::with(['brand', 'variants.specifications', 'specifications'])->get();
        $this->allParts = Part::with('partBrand')->orderBy('part_name')->get();
    }

    public function updatedParentCategoryId()
    {
        $this->childCategories = Category::where('parent_id', $this->parentCategoryId)
            ->orderBy('category_name')
            ->get();

        $this->category_id = null;
    }

    public function save()
{
    $this->validate();

    // 1. Generate SKU
    $brandName = PartBrand::find($this->part_brand_id)->name;
    $categoryName = Category::find($this->category_id)->category_name;
    $sku = Part::generateSku($brandName, $categoryName, $this->part_name);

    // 2. Create Part
    $part = Part::create([
        'part_number'    => $this->part_number,
        'part_name'      => $this->part_name,
        'category_id'    => $this->category_id,
        'part_brand_id'  => $this->part_brand_id,
        'oem_number'     => $this->oem_number,
        'price'          => $this->price,
        'stock_quantity' => $this->stock_quantity,
        'status'         => $this->status === 'Active' ? 1 : 0,
        'description'    => $this->description,
        'sku'            => $sku,
    ]);

    // 3. Save Fitments (Specifications)
    if (!empty($this->fitment_specifications)) {
        foreach ($this->fitment_specifications as $specId) {
            $spec = Specification::find($specId);
            
            // This matches your PartFitment model fillables exactly
            PartFitment::create([
                'part_id'          => $part->id,
                'specification_id' => $spec->id, // Ensure this exists in your DB
                'variant_id'       => $spec->variant_id,
                'vehicle_model_id' => $spec->vehicle_model_id,
                'start_year'       => $spec->production_start,
                'end_year'         => $spec->production_end,
                'status'           => 'active',
            ]);
        }
    }

    // 4. Handle Photos
    foreach ($this->photos as $photo) {
        $path = $photo->store('parts/' . Str::slug($brandName), 'public');
        $part->photos()->create([
            'file_path' => $path,
            'caption'   => $this->part_name,
        ]);
    }

    // 5. Substitutions
    if (!empty($this->substitution_part_ids)) {
        $part->substitutions()->sync($this->substitution_part_ids);
    }

    session()->flash('success', 'Spare part created successfully.');
    return redirect()->route('admin.spare-parts.index');
}
    
    public function render()
    {
        return view('livewire.admin.part.create');
    }
}
