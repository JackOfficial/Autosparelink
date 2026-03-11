<?php

namespace App\Livewire\Parts;

use Livewire\Component;
use App\Models\Part;
use App\Models\Category;
use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\Specification;
use Livewire\WithPagination;
use Livewire\Attributes\Lazy;

#[Lazy]
class PartsCatalog extends Component
{
    use WithPagination;

    // Filter Properties
    public $search = '';
    public $brand = null;
    public $model = null;
    public $variant = null;
    public $category = null;
    public $min_price = null;
    public $max_price = null;
    public $in_stock = false;
    public $vinData = null; 
    public $sort = 'latest';

    protected $paginationTheme = 'bootstrap';

    /**
     * Mount data passed from the Controller (VIN Search results)
     */
    public function mount($brand = null, $model = null, $variant = null, $vinData = null)
    {
        $this->brand = $brand;
        $this->model = $model;
        $this->variant = $variant;
        $this->vinData = $vinData;
    }

    // Reset pagination on filter change
    public function updatedBrand() { $this->model = null; $this->variant = null; $this->resetPage(); }
    public function updatedModel() { $this->variant = null; $this->resetPage(); }
    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategory() { $this->resetPage(); }

    /**
     * Clear all active filters and VIN data
     */
    public function clearFilters()
    {
        $this->reset(['brand', 'model', 'variant', 'search', 'vinData', 'category', 'min_price', 'max_price', 'in_stock']);
        $this->resetPage();
    }

    /**
     * Shimmer effect shown while the query runs
     */
    public function placeholder()
    {
        return view('livewire.parts.skeleton');
    }

    public function render()
    {
        // 1. Fetch Sidebar Data
        $brands = Brand::orderBy('brand_name')->get();
        
        $models = $this->brand 
            ? VehicleModel::where('brand_id', $this->brand)->orderBy('model_name')->get() 
            : collect();

        $variants = $this->model 
            ? Specification::where('vehicle_model_id', $this->model)->get() 
            : collect();

        $categories = Category::withCount('parts')->get();

        // 2. Build the Unified Parts Query
        $partsQuery = Part::query()
            // --- VEHICLE FILTERING ---
            // If we have a specific Variant (from VIN or Manual Select)
            ->when($this->variant, function($q) {
                $q->whereHas('fitments', function($query) {
                    $query->where('specification_id', $this->variant);
                });
            })
            // Otherwise, filter by Model if selected
            ->when($this->model && !$this->variant, function($q) {
                $q->whereHas('specifications', fn($query) => $query->where('vehicle_model_id', $this->model));
            })
            // Otherwise, filter by Brand if selected
            ->when($this->brand && !$this->model, function($q) {
                $q->whereHas('specifications.vehicleModel', fn($query) => $query->where('brand_id', $this->brand));
            })

            // --- GENERAL ATTRIBUTE FILTERS ---
            ->when($this->category, fn($q) => $q->where('category_id', $this->category))
            ->when($this->in_stock, fn($q) => $q->where('stock', '>', 0))
            ->when($this->min_price, fn($q) => $q->where('price', '>=', $this->min_price))
            ->when($this->max_price, fn($q) => $q->where('price', '<=', $this->max_price))

            // --- TEXT SEARCH ---
            ->where(function($q) {
                $q->where('part_name', 'like', '%' . $this->search . '%')
                  ->orWhere('part_number', 'like', '%' . $this->search . '%');
            });

        // 3. Apply Sorting
        switch ($this->sort) {
            case 'price_asc': $partsQuery->orderBy('price', 'asc'); break;
            case 'price_desc': $partsQuery->orderBy('price', 'desc'); break;
            case 'name_asc': $partsQuery->orderBy('part_name', 'asc'); break;
            default: $partsQuery->latest(); break;
        }

        return view('livewire.parts.parts-catalog', [
            'parts' => $partsQuery->paginate(12),
            'brands' => $brands,
            'models' => $models,
            'variants' => $variants,
            'categories' => $categories,
        ]);
    }
}