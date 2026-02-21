<?php

namespace App\Livewire\Admin\Spareparts;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\{Part, Category, PartBrand, Specification, PartFitment};
use Illuminate\Support\Str;

class CreateComponent extends Component
{
    use WithFileUploads;

    // Form fields
    public $part_name, $part_number, $price, $stock_quantity, $category_id, $part_brand_id;
    public $description, $oem_number;
    
    // Selection & Search
    public $selectedSpecs = []; 
    public $searchVehicle = '';

    public function toggleVehicle($specId)
    {
        if (in_array($specId, $this->selectedSpecs)) {
            $this->selectedSpecs = array_diff($this->selectedSpecs, [$specId]);
        } else {
            $this->selectedSpecs[] = $specId;
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
        $category = Category::find($this->category_id);

        $part = Part::create([
            'part_name' => $this->part_name,
            'part_number' => $this->part_number,
            'oem_number' => $this->oem_number,
            'category_id' => $this->category_id,
            'part_brand_id' => $this->part_brand_id,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'description' => $this->description,
            'status' => 1,
            'sku' => Part::generateSku($brand->name, $category->category_name, $this->part_name),
        ]);

        // Save Fitments
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

        session()->flash('success', 'Spare part created successfully!');
        return redirect()->route('admin.spare-parts.index');
    }

    public function render()
    {
        $searchResults = [];
        if (strlen($this->searchVehicle) >= 2) {
            $searchResults = Specification::with(['vehicleModel.brand', 'variant'])
                ->whereHas('vehicleModel', function($q) {
                    $q->where('model_name', 'like', "%{$this->searchVehicle}%")
                      ->orWhereHas('brand', fn($b) => $b->where('brand_name', 'like', "%{$this->searchVehicle}%"));
                })
                ->limit(10)
                ->get();
        }

        return view('livewire.admin.spareparts.create-component', [
            'searchResults' => $searchResults,
            'displaySpecs' => Specification::whereIn('id', $this->selectedSpecs)->with('vehicleModel.brand')->get(),
            'brands' => PartBrand::orderBy('name')->get(),
            'categories' => Category::whereNotNull('parent_id')->get(),
        ]);
    }
}