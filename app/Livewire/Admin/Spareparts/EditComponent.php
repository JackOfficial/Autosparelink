<?php

namespace App\Livewire\Admin\Spareparts;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\{Part, Category, PartBrand, Specification, PartFitment};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EditComponent extends Component
{
    use WithFileUploads;

    public Part $part;

    // Basic Info
    public $part_name, $part_number, $oem_number, $description;
    public $parentCategoryId, $category_id, $part_brand_id;
    
    // Inventory & Subs
    public $price, $stock_quantity;
    public $substitution_part_ids = []; 
    public $searchPart = '';
    
    // Photos
    public $photos = [];
    public $existingPhotos = [];
    
    // Search & Fitment
    public $selectedSpecs = []; 
    public $searchVehicle = '';

    /**
     * Initialize the component with existing Part data
     */
    public function mount(Part $part)
    {
        $this->part = $part->load(['photos', 'fitments', 'substitutions', 'category']);

        // Map model attributes to public properties
        $this->part_name      = $part->part_name;
        $this->part_number    = $part->part_number;
        $this->oem_number     = $part->oem_number;
        $this->description    = $part->description;
        $this->price          = $part->price;
        $this->stock_quantity = $part->stock_quantity;
        
        $this->part_brand_id  = $part->part_brand_id;
        $this->category_id    = $part->category_id;
        $this->parentCategoryId = optional($part->category)->parent_id;

        // Load existing relations for the UI
        $this->existingPhotos = $part->photos;
        $this->substitution_part_ids = $part->substitutions->pluck('id')->toArray();
        $this->selectedSpecs = $part->fitments->pluck('specification_id')->toArray();
    }

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
        $this->searchVehicle = ''; // Clear search to close dropdown
    }

    public function toggleSubstitution($partId)
    {
        if (in_array($partId, $this->substitution_part_ids)) {
            $this->substitution_part_ids = array_diff($this->substitution_part_ids, [$partId]);
        } else {
            $this->substitution_part_ids[] = $partId;
        }
        $this->searchPart = ''; // Clear search to close dropdown
    }

    public function deletePhoto($photoId)
    {
        $photo = $this->part->photos()->find($photoId);
        if ($photo) {
            Storage::disk('public')->delete($photo->file_path);
            $photo->delete();
        }
        // Refresh existing photos list
        $this->existingPhotos = $this->part->fresh()->photos;
    }

    public function save()
    {
        $this->validate([
            'part_name'      => 'required|min:3',
            'category_id'    => 'required',
            'part_brand_id'  => 'required',
            'price'          => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        $brand = PartBrand::find($this->part_brand_id);
        $category = Category::find($this->category_id);

        // Update main Part record
        $this->part->update([
            'part_name'      => $this->part_name,
            'part_number'    => $this->part_number,
            'oem_number'     => $this->oem_number,
            'category_id'    => $this->category_id,
            'part_brand_id'  => $this->part_brand_id,
            'price'          => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'description'    => $this->description,
            // Re-generate SKU using the logic from CreateComponent
            'sku'            => Part::generateSku($brand->name, $category->category_name, $this->part_name),
        ]);

        // 1. Sync Fitments (Delete and Re-create to maintain CreateComponent style)
        PartFitment::where('part_id', $this->part->id)->delete();
        foreach ($this->selectedSpecs as $id) {
            $spec = Specification::find($id);
            if ($spec) {
                PartFitment::create([
                    'part_id'          => $this->part->id,
                    'specification_id' => $spec->id,
                    'vehicle_model_id' => $spec->vehicle_model_id,
                    'variant_id'       => $spec->variant_id,
                    'start_year'       => $spec->production_start,
                    'status'           => 'active'
                ]);
            }
        }

        // 2. Save New Photos
        foreach ($this->photos as $photo) {
            $path = $photo->store('parts/' . Str::slug($brand->name), 'public');
            $this->part->photos()->create(['file_path' => $path]);
        }

        // 3. Sync Substitutions
        $this->part->substitutions()->sync($this->substitution_part_ids);

        session()->flash('success', 'Spare part updated successfully!');
        return redirect()->route('admin.spare-parts.index');
    }

    public function render()
    {
        $searchResults = [];
        $partResults = [];

        // Dynamic Vehicle Search Logic
        if (strlen($this->searchVehicle) >= 2) {
            $searchResults = Specification::with(['vehicleModel.brand', 'variant'])
                ->whereHas('vehicleModel', function($q) {
                    $q->where('model_name', 'like', "%{$this->searchVehicle}%")
                      ->orWhereHas('brand', fn($b) => $b->where('brand_name', 'like', "%{$this->searchVehicle}%"));
                })->limit(15)->get();
        }

        // Dynamic Substitution Part Search Logic
        if (strlen($this->searchPart) >= 2) {
            $partResults = Part::with('partBrand')
                ->where('id', '!=', $this->part->id) // Exclude current part
                ->where(function($q) {
                    $q->where('part_name', 'like', "%{$this->searchPart}%")
                      ->orWhere('part_number', 'like', "%{$this->searchPart}%");
                })
                ->limit(10)->get();
        }

        return view('livewire.admin.spareparts.edit-component', [
            'searchResults'         => $searchResults,
            'partResults'           => $partResults,
            'displaySpecs'          => Specification::whereIn('id', $this->selectedSpecs)->with('vehicleModel.brand')->get(),
            'parentCategories'      => Category::whereNull('parent_id')->orderBy('category_name')->get(),
            'childCategories'       => Category::where('parent_id', $this->parentCategoryId)->orderBy('category_name')->get(),
            'brands'                => PartBrand::orderBy('name')->get(),
            'selectedSubstitutions' => Part::whereIn('id', $this->substitution_part_ids)->with('partBrand')->get(),
        ]);
    }
}