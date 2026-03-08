<?php

namespace App\Livewire\Admin\Part;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\{
    Part,
    Category,
    PartBrand,
    VehicleModel,
    Specification,
    PartFitment
};

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

    public $photos = [];
    public $existingPhotos = [];

    public $fitment_specifications = [];
    public $substitution_part_ids = [];

    /* DATA */
    public $parentCategories = [];
    public $childCategories = [];
    public $partBrands = [];
    public $vehicleModels = [];
    public $allParts = [];

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
        ];
    }

    public function mount(Part $part)
    {
        $this->part = $part->load(['photos', 'fitments.specification', 'substitutions']);

        /* Load dropdown data */
        $this->parentCategories = Category::whereNull('parent_id')->orderBy('category_name')->get();
        $this->partBrands = PartBrand::orderBy('name')->get();
        $this->vehicleModels = VehicleModel::with(['brand', 'variants.specifications', 'specifications'])->get();
        $this->allParts = Part::where('id', '!=', $part->id)->orderBy('part_name')->get();

        /* Fill form fields */
        $this->part_number = $part->part_number;
        $this->part_name = $part->part_name;
        $this->category_id = $part->category_id;
        $this->parentCategoryId = optional($part->category)->parent_id;
        $this->part_brand_id = $part->part_brand_id;
        $this->oem_number = $part->oem_number;
        $this->price = $part->price;
        $this->stock_quantity = $part->stock_quantity;
        $this->status = $part->status ? 'Active' : 'Inactive';
        $this->description = $part->description;

        /* Existing photos */
        $this->existingPhotos = $part->photos;

        /* Fitments */
        $this->fitment_specifications = $part->fitments
            ->pluck('specification_id')
            ->filter()
            ->toArray();

        /* Substitutions */
        $this->substitution_part_ids = $part->substitutions
            ->pluck('id')
            ->toArray();

        $this->updatedParentCategoryId();
    }

    public function updatedParentCategoryId()
    {
        $this->childCategories = Category::where('parent_id', $this->parentCategoryId)->get();
    }

    public function deletePhoto($photoId)
    {
        $photo = $this->part->photos()->find($photoId);
        if ($photo) {
            \Storage::disk('public')->delete($photo->file_path);
            $photo->delete();
        }

        $this->existingPhotos = $this->part->fresh()->photos;
    }

    public function update()
    {
        $this->validate();

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

        /* Sync Substitutions */
        $this->part->substitutions()->sync($this->substitution_part_ids);

        /* Sync Fitments */
        PartFitment::where('part_id', $this->part->id)->delete();

        foreach ($this->fitment_specifications as $specId) {
            $spec = Specification::find($specId);

            PartFitment::create([
                'part_id' => $this->part->id,
                'variant_id' => $spec->variant_id,
                'vehicle_model_id' => $spec->vehicle_model_id,
                'specification_id' => $spec->id,
                'status' => 'active',
                'start_year' => $spec->production_start,
                'end_year' => $spec->production_end,
            ]);
        }

        /* Upload New Photos */
        foreach ($this->photos as $photo) {
            $path = $photo->store(
                'parts/' . Str::slug($this->part->partBrand->name),
                'public'
            );

            $this->part->photos()->create([
                'file_path' => $path,
                'caption' => $this->part->part_name,
            ]);
        }

        session()->flash('success', 'Spare part updated successfully.');

        return redirect()->route('admin.spare-parts.index');
    }

    public function render()
    {
        return view('livewire.admin.part.edit');
    }
}
