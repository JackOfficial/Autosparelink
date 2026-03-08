<?php

namespace App\Livewire\Admin\Part;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\{Part, Category, PartBrand, VehicleModel, Specification, PartFitment};
use Illuminate\Support\Str;

class Create extends Component
{
    use WithFileUploads;

    // Form fields
    public $part_number, $part_name, $parentCategoryId, $category_id, $part_brand_id;
    public $oem_number, $price, $stock_quantity, $description;
    public $status = 'Active';

    // Selections
    public $fitment_specifications = [];
    public $substitution_part_ids = [];
    public $photos = [];

    // Search
    public $searchVehicle = '';

    public function mount()
    {
        $this->parentCategories = Category::whereNull('parent_id')->orderBy('category_name')->get();
        $this->partBrands = PartBrand::orderBy('name')->get();
        $this->allParts = Part::with('partBrand')->orderBy('part_name')->get();
    }

    public function updatedParentCategoryId()
    {
        $this->childCategories = Category::where('parent_id', $this->parentCategoryId)
            ->orderBy('category_name')->get();
        $this->category_id = null;
    }

    public function toggleFitment($specId)
    {
        if (in_array($specId, $this->fitment_specifications)) {
            $this->fitment_specifications = array_diff($this->fitment_specifications, [$specId]);
        } else {
            $this->fitment_specifications[] = $specId;
        }
    }

    public function save()
    {
        $this->validate([
            'part_name' => 'required|string|max:255',
            'category_id' => 'required',
            'part_brand_id' => 'required',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        $brand = PartBrand::find($this->part_brand_id);
        $category = Category::find($this->category_id);
        
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
            'sku'            => Part::generateSku($brand->name, $category->category_name, $this->part_name),
        ]);

        foreach ($this->fitment_specifications as $specId) {
            $spec = Specification::find($specId);
            if($spec) {
                PartFitment::create([
                    'part_id' => $part->id,
                    'specification_id' => $spec->id,
                    'variant_id' => $spec->variant_id,
                    'vehicle_model_id' => $spec->vehicle_model_id,
                    'start_year' => $spec->production_start,
                    'end_year' => $spec->production_end,
                    'status' => 'active'
                ]);
            }
        }

        foreach ($this->photos as $photo) {
            $path = $photo->store('parts/' . Str::slug($brand->name), 'public');
            $part->photos()->create(['file_path' => $path, 'caption' => $this->part_name]);
        }

        if (!empty($this->substitution_part_ids)) {
            $part->substitutions()->sync($this->substitution_part_ids);
        }

        session()->flash('success', 'Part Created!');
        return redirect()->route('admin.spare-parts.index');
    }

    public function render()
    {
        $searchResults = [];
        if (strlen($this->searchVehicle) >= 2) {
            $searchResults = Specification::with(['vehicleModel.brand', 'variant'])
                ->whereHas('vehicleModel', function($q) {
                    $q->where('model_name', 'like', '%' . $this->searchVehicle . '%')
                      ->orWhereHas('brand', fn($b) => $b->where('brand_name', 'like', '%' . $this->searchVehicle . '%'));
                })
                ->limit(10)
                ->get();
        }

        return view('livewire.admin.part.create', [
            'searchResults' => $searchResults,
            'selectedSpecs' => Specification::whereIn('id', $this->fitment_specifications)
                ->with(['vehicleModel.brand', 'variant'])->get(),
            'parentCategories' => Category::whereNull('parent_id')->get(),
            'partBrands' => PartBrand::all(),
            'childCategories' => $this->childCategories ?? [],
            'allParts' => Part::all()
        ]);
    }
}