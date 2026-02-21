<?php

namespace App\Livewire\Admin\Spareparts;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\{Part, Category, PartBrand, Specification, PartFitment};
use Illuminate\Support\Str;

class CreateComponent extends Component
{
    use WithFileUploads;

    // Basic Info
    public $part_name, $part_number, $oem_number, $description;
    public $parentCategoryId, $category_id, $part_brand_id;
    public $searchPart = '';
    // Inventory & Subs
    public $price, $stock_quantity;
    public $substitution_part_ids = []; // For the multiple select
    
    // Photos
    public $photos = [];
    
    // Search & Fitment
    public $selectedSpecs = []; 
    public $searchVehicle = '';

    public function updatedParentCategoryId($value)
    {
        $this->category_id = null; // Reset child when parent changes
    }

    public function toggleVehicle($specId)
    {
        if (in_array($specId, $this->selectedSpecs)) {
            $this->selectedSpecs = array_diff($this->selectedSpecs, [$specId]);
        } else {
            $this->selectedSpecs[] = $specId;
        }
    }

    public function toggleSubstitution($partId)
{
    if (in_array($partId, $this->substitution_part_ids)) {
        $this->substitution_part_ids = array_diff($this->substitution_part_ids, [$partId]);
    } else {
        $this->substitution_part_ids[] = $partId;
    }
    $this->searchPart = ''; // Clear search after selection
}

    public function save()
    {
        $this->validate([
            'part_name' => 'required|min:3',
            'category_id' => 'required',
            'part_brand_id' => 'required',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        $brand = PartBrand::find($this->part_brand_id);
        $category = Category::find($this->category_id);

        $part = Part::create([
            'part_name'      => $this->part_name,
            'part_number'    => $this->part_number,
            'oem_number'     => $this->oem_number,
            'category_id'    => $this->category_id,
            'part_brand_id'  => $this->part_brand_id,
            'price'          => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'description'    => $this->description,
            'status'         => 1,
            'sku'            => Part::generateSku($brand->name, $category->category_name, $this->part_name),
        ]);

        // 1. Save Fitments
        foreach ($this->selectedSpecs as $id) {
            $spec = Specification::find($id);
            if ($spec) {
                PartFitment::create([
                    'part_id' => $part->id,
                    'specification_id' => $spec->id,
                    'vehicle_model_id' => $spec->vehicle_model_id,
                    'variant_id' => $spec->variant_id,
                    'start_year' => $spec->production_start,
                    'status' => 'active'
                ]);
            }
        }

        // 2. Save Photos
        foreach ($this->photos as $photo) {
            $path = $photo->store('parts/' . Str::slug($brand->name), 'public');
            $part->photos()->create(['file_path' => $path]);
        }

        // 3. Save Substitutions
        if (!empty($this->substitution_part_ids)) {
            $part->substitutions()->sync($this->substitution_part_ids);
        }

        session()->flash('success', 'Part Created!');
        return redirect()->route('admin.spare-parts.index');
    }

    public function render()
    {
        $searchResults = [];
        $partResults = [];
        if (strlen($this->searchVehicle) >= 2) {
            $searchResults = Specification::with(['vehicleModel.brand', 'variant'])
                ->whereHas('vehicleModel', function($q) {
                    $q->where('model_name', 'like', "%{$this->searchVehicle}%")
                      ->orWhereHas('brand', fn($b) => $b->where('brand_name', 'like', "%{$this->searchVehicle}%"));
                })->limit(15)->get();
        }

        
    if (strlen($this->searchPart) >= 2) {
        $partResults = Part::with('partBrand')
            ->where('part_name', 'like', "%{$this->searchPart}%")
            ->orWhere('part_number', 'like', "%{$this->searchPart}%")
            ->limit(10)
            ->get();
    }

        return view('livewire.admin.spareparts.create-component', [
            'searchResults'    => $searchResults,
            'displaySpecs'     => Specification::whereIn('id', $this->selectedSpecs)->with('vehicleModel.brand')->get(),
            'parentCategories' => Category::whereNull('parent_id')->orderBy('category_name')->get(),
            'childCategories'  => Category::where('parent_id', $this->parentCategoryId)->orderBy('category_name')->get(),
            'brands'           => PartBrand::orderBy('name')->get(),
            'allParts'         => Part::with('partBrand')->orderBy('part_name')->get(),
            'partResults' => $partResults,
            'selectedSubstitutions' => Part::whereIn('id', $this->substitution_part_ids)->with('partBrand')->get(),
        ]);
    }
}