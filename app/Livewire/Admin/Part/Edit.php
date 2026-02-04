<?php

namespace App\Livewire\Admin\Part;

use Livewire\Component;
use App\Models\{Part, Category, PartBrand, VehicleModel, Specification, PartFitment};
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Edit extends Component
{
        use WithFileUploads;

    public Part $part;

    /* FORM FIELDS */
    public $part_number;
    public $part_name;
    public $parentCategoryId;
    public $category_id;
    public $part_brand_id;
    public $oem_number;
    public $price;
    public $stock_quantity;
    public $status;
    public $description;
    public $fitment_specifications = [];
    public $substitution_part_ids = [];
    public $photos = []; // new uploads
    public $existingPhotos = []; // already uploaded

    /* DATA */
    public $parentCategories = [];
    public $childCategories = [];
    public $partBrands = [];
    public $vehicleModels = [];
    public $allParts = [];

    public function mount(Part $part)
    {
        dd($part->id);
        $this->part = $part;

        // Pre-fill form fields
        $this->part_number = $part->part_number;
        $this->part_name = $part->part_name;
        $this->category_id = $part->category_id;
        $this->parentCategoryId = optional($part->category->parent)->id;
        $this->part_brand_id = $part->part_brand_id;
        $this->oem_number = $part->oem_number;
        $this->price = $part->price;
        $this->stock_quantity = $part->stock_quantity;
        $this->status = $part->status ? 'Active' : 'Inactive';
        $this->description = $part->description;

        // Preload relationships
        $this->fitment_specifications = $part->fitments->pluck('id')->toArray();
        $this->substitution_part_ids = $part->substitutions->pluck('id')->toArray();
        $this->existingPhotos = $part->photos;

        // Load selection lists
        $this->parentCategories = Category::whereNull('parent_id')->orderBy('category_name')->get();
        $this->childCategories = $this->parentCategoryId
            ? Category::where('parent_id', $this->parentCategoryId)->orderBy('category_name')->get()
            : [];
        $this->partBrands = PartBrand::orderBy('name')->get();
        $this->vehicleModels = VehicleModel::with(['brand', 'variants.specifications', 'specifications'])->get();
        $this->allParts = Part::with('partBrand')->orderBy('part_name')->get()
            ->where('id', '!=', $part->id); // exclude current part from substitutions
    }

    public function updatedParentCategoryId()
    {
        $this->childCategories = Category::where('parent_id', $this->parentCategoryId)
            ->orderBy('category_name')
            ->get();
        $this->category_id = null;
    }

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

    public function update()
    {
        $this->validate();

        // Update part
        $this->part->update([
            'part_number' => $this->part_number,
            'part_name' => $this->part_name,
            'category_id' => $this->category_id,
            'part_brand_id' => $this->part_brand_id,
            'oem_number' => $this->oem_number,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'status' => $this->status === 'Active' ? 1 : 0,
            'description' => $this->description,
        ]);

        // Sync fitments
        PartFitment::where('part_id', $this->part->id)->delete(); // remove old
        foreach ($this->fitment_specifications as $specId) {
            $spec = Specification::with(['variant', 'vehicleModel'])->find($specId);

            PartFitment::create([
                'part_id' => $this->part->id,
                'variant_id' => $spec->variant_id ?? $spec->variant?->id,
                'vehicle_model_id' => $spec->vehicle_model_id ?? $spec->variant?->vehicle_model_id,
                'status' => 'active',
                'start_year' => $spec->production_start,
                'end_year' => $spec->production_end,
            ]);
        }

        // Sync substitutions
        $this->part->substitutions()->sync($this->substitution_part_ids);

        // Upload new photos
        foreach ($this->photos as $photo) {
            $path = $photo->store('parts/' . Str::slug($this->part->partBrand->name), 'public');

            $this->part->photos()->create([
                'file_path' => $path,
                'caption' => $this->part->part_name,
            ]);
        }

        session()->flash('success', 'Part updated successfully!');
        return redirect()->route('admin.spare-parts.index');
    }

    public function render()
    {
        return view('livewire.admin.part.edit');
    }
}
