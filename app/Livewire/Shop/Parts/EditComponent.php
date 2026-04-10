<?php

namespace App\Livewire\Shop\Parts;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\{Part, Category, PartBrand, Specification, PartFitment};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EditComponent extends Component
{
    use WithFileUploads;

    public Part $part; // Route Model Binding

    // Form Properties
    public $part_name, $part_number, $oem_number, $description;
    public $parentCategoryId, $category_id, $part_brand_id;
    public $price, $stock_quantity;
    
    // Selection & Search State
    public $selectedSpecs = []; 
    public $substitution_part_ids = []; 
    public $searchVehicle = '';
    public $searchPart = '';
    
    // Image State
    public $photos = []; // New uploads
    public $existingPhotos = []; // Current records

    /**
     * Initialize the component with existing Part data
     */
    public function mount(Part $part)
    {
        $this->part = $part;
        
        // Basic Info
        $this->part_name = $part->part_name;
        $this->part_number = $part->part_number;
        $this->oem_number = $part->oem_number;
        $this->description = $part->description;
        $this->price = $part->price;
        $this->stock_quantity = $part->stock_quantity;
        $this->part_brand_id = $part->part_brand_id;
        $this->category_id = $part->category_id;
        
        // Logic for dropdowns
        $this->parentCategoryId = $part->category?->parent_id;

        // Load Relationships
        $this->selectedSpecs = $part->fitments()->pluck('specification_id')->toArray();
        $this->substitution_part_ids = $part->substitutions()->pluck('substitution_part_id')->toArray();
        $this->existingPhotos = $part->photos;
    }

    public function updatedParentCategoryId($value)
    {
        $this->category_id = null; 
    }

    public function toggleVehicle($specId)
    {
        if (in_array($specId, $this->selectedSpecs)) {
            $this->selectedSpecs = array_diff($this->selectedSpecs, [$specId]);
        } else {
            $this->selectedSpecs[] = $specId;
        }
        $this->searchVehicle = ''; 
    }

    public function toggleSubstitution($partId)
    {
        if (in_array($partId, $this->substitution_part_ids)) {
            $this->substitution_part_ids = array_diff($this->substitution_part_ids, [$partId]);
        } else {
            $this->substitution_part_ids[] = $partId;
        }
        $this->searchPart = ''; 
    }

    public function deletePhoto($photoId)
    {
        $photo = $this->part->photos()->find($photoId);
        if ($photo) {
            Storage::disk('public')->delete($photo->file_path);
            $photo->delete();
            $this->existingPhotos = $this->part->photos()->get();
        }
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

        // 1. Update Core Part Data
        $this->part->update([
            'part_name'      => $this->part_name,
            'part_number'    => $this->part_number,
            'oem_number'     => $this->oem_number,
            'category_id'    => $this->category_id,
            'part_brand_id'  => $this->part_brand_id,
            'price'          => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'description'    => $this->description,
        ]);

        // 2. Refresh Fitments
        $this->part->fitments()->delete();
        foreach ($this->selectedSpecs as $id) {
            $spec = Specification::find($id);
            if ($spec) {
                PartFitment::create([
                    'part_id' => $this->part->id,
                    'specification_id' => $spec->id,
                    'vehicle_model_id' => $spec->vehicle_model_id,
                    'variant_id' => $spec->variant_id,
                    'start_year' => $spec->production_start,
                    'status' => 'active'
                ]);
            }
        }

        // 3. Sync Substitutions (Alternatives)
        $this->part->substitutions()->sync($this->substitution_part_ids);

        // 4. Handle New Photo Uploads
        foreach ($this->photos as $photo) {
            $path = $photo->store('parts/' . Str::slug($brand->name ?? 'general'), 'public');
            $this->part->photos()->create(['file_path' => $path]);
        }

        session()->flash('success', 'Part updated successfully!');
        return redirect()->route('admin.spare-parts.index');
    }

    public function render()
    {
        // Dynamic search results for vehicles
        $searchResults = strlen($this->searchVehicle) >= 2 
            ? Specification::with(['vehicleModel.brand', 'variant'])
                ->whereHas('vehicleModel', function($q) {
                    $q->where('model_name', 'like', "%{$this->searchVehicle}%")
                      ->orWhereHas('brand', fn($b) => $b->where('brand_name', 'like', "%{$this->searchVehicle}%"));
                })->limit(10)->get()
            : [];

        // Dynamic search results for other parts (excluding current part)
        $partResults = strlen($this->searchPart) >= 2
            ? Part::with('partBrand')
                ->where('id', '!=', $this->part->id)
                ->where(function($q) {
                    $q->where('part_name', 'like', "%{$this->searchPart}%")
                      ->orWhere('part_number', 'like', "%{$this->searchPart}%");
                })->limit(10)->get()
            : [];

        return view('livewire.shop.parts.edit-component', [
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