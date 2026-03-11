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
use Livewire\Attributes\Url;

#[Lazy]
class PartsCatalog extends Component
{
    use WithPagination;

    // Filter Properties with URL persistence
    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $brand = null;

    #[Url(history: true)]
    public $model = null;

    #[Url(history: true)]
    public $variant = null; // Represents the Specification ID in the UI

    #[Url(history: true)]
    public $category = null;

    #[Url(history: true)]
    public $min_price = null;

    #[Url(history: true)]
    public $max_price = null;

    #[Url(history: true)]
    public $in_stock = false;

    #[Url(history: true)]
    public $sort = 'latest';

    public $selectedVariantModel;

    public $vinData = null; 

    protected $paginationTheme = 'bootstrap';

    /**
     * Handle initial data and URL parameters
     */
    public function mount($brand = null, $model = null, $variant = null, $vinData = null, $category = null)
    {
        $this->brand = $brand ?? request()->query('brand', $this->brand);
        $this->model = $model ?? request()->query('model', $this->model);
        $this->variant = $variant ?? request()->query('variant', $this->variant);
        $this->category = $category ?? request()->query('category', $this->category);
        $this->vinData = $vinData;
    }

//     public function updatedVariant($value)
//    {
//     // Retrieve the full model from DB to get descriptive names
//     $this->selectedVariantModel = \App\Models\Specification::with('vehicleModel.brand')->find($value);
//    }

    // --- Filter Lifecycle Hooks ---
    // These reset dependent dropdowns and pagination when a parent filter changes
    public function updatedBrand() { $this->model = null; $this->variant = null; $this->resetPage(); }
    public function updatedModel() { $this->variant = null; $this->resetPage(); }
    public function updatedVariant() { $this->resetPage(); }
    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategory() { $this->resetPage(); }
    public function updatedInStock() { $this->resetPage(); }
    public function updatedMinPrice() { $this->resetPage(); }
    public function updatedMaxPrice() { $this->resetPage(); }

    /**
     * Reset all filters to default
     */
    public function clearFilters()
    {
        $this->reset(['brand', 'model', 'variant', 'search', 'vinData', 'category', 'min_price', 'max_price', 'in_stock']);
        $this->resetPage();
    }

    public function placeholder()
    {
        return view('livewire.parts.skeleton');
    }

    public function render()
    {
        // 1. Fetch Sidebar Collections
        $brands = Brand::orderBy('brand_name')->get();
        
        $models = $this->brand 
            ? VehicleModel::where('brand_id', $this->brand)->orderBy('model_name')->get() 
            : collect();

        // Loading Specifications tied to the selected Model
        $variants = $this->model 
            ? Specification::where('vehicle_model_id', $this->model)->get() 
            : collect();

        $categories = Category::withCount('parts')->orderBy('category_name')->get();

        // 2. Build Query
        $partsQuery = Part::query()
            ->with(['photos', 'partBrand', 'specifications.vehicleModel.brand'])
            
            // --- VEHICLE HIERARCHY FILTERS ---
            // Priority 1: Specific Specification (Variant Select)
            ->when($this->variant, function($q) {
                $q->whereHas('specifications', fn($query) => $query->where('specifications.id', $this->variant));
            }) 
            // Priority 2: Model Level (if no specific specification selected)
            ->when($this->model && !$this->variant, function($q) {
                $q->whereHas('specifications', fn($query) => $query->where('vehicle_model_id', $this->model));
            })
            // Priority 3: Brand Level (if no model selected)
            ->when($this->brand && !$this->model, function($q) {
                $q->whereHas('specifications.vehicleModel', fn($query) => $query->where('brand_id', $this->brand));
            })

            // --- ATTRIBUTE FILTERS ---
            ->when($this->category, fn($q) => $q->where('category_id', $this->category))
            ->when($this->in_stock, fn($q) => $q->where('stock_quantity', '>', 0))
            ->when($this->min_price, fn($q) => $q->where('price', '>=', $this->min_price))
            ->when($this->max_price, fn($q) => $q->where('price', '<=', $this->max_price))

            // --- SEARCH ---
            ->when($this->search, function($q) {
                $q->where(function($sub) {
                    $term = '%' . $this->search . '%';
                    $sub->where('part_name', 'like', $term)
                        ->orWhere('part_number', 'like', $term)
                        ->orWhere('oem_number', 'like', $term)
                        ->orWhere('sku', 'like', $term);
                });
            });

        // 3. Sorting logic
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