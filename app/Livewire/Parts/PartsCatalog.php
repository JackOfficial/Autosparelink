<?php

namespace App\Livewire\Parts;

use Livewire\Component;
use App\Models\Part;
use App\Models\Category;
use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\Specification;
use App\Models\Variant;
use Livewire\WithPagination;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Url;

#[Lazy]
class PartsCatalog extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $brand = null;

    #[Url(history: true)]
    public $model = null;

    #[Url(history: true)]
    public $variant = null; 

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

    #[Url(history: true)]
    public $grid = true;

    public $vinData = null; 

    protected $paginationTheme = 'bootstrap';

    /**
     * Handle initial data and URL parameters
     */
public function mount($brand = null, $model = null, $variant = null, $vinData = null, $search = null, $category = null)
{
    if ($vinData) {
        $this->vinData = $vinData;
        $this->brand = $brand;
        $this->model = $model;
        $this->variant = $variant;
        $this->search = ''; 
    } else {
        // Fallback to URL query params if no direct VIN data was injected
        $this->brand = $brand ?? request()->query('brand');
        $this->model = $model ?? request()->query('model');
        $this->variant = $variant ?? request()->query('variant');
        $this->search = $search ?? request()->query('search');
    }
}

    // --- Filter Lifecycle Hooks ---
    public function updatedBrand() { $this->model = null; $this->variant = null; $this->resetPage(); }
    public function updatedModel() { $this->variant = null; $this->resetPage(); }
    public function updatedVariant() { $this->resetPage(); }
    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategory() { $this->resetPage(); }
    public function updatedInStock() { $this->resetPage(); }
    public function updatedMinPrice() { $this->resetPage(); }
    public function updatedMaxPrice() { $this->resetPage(); }

    public function clearFilters()
    {
        $this->reset(['brand', 'model', 'variant', 'search', 'vinData', 'category', 'min_price', 'max_price', 'in_stock']);
        $this->resetPage();
    }

    public function placeholder()
    {
        return view('livewire.parts.skeleton');
    }

    public function setToggle($isGrid)
    {
        $this->grid = $isGrid;
    }

    public function render()
    {
        // 1. Fetch Sidebar Collections based on your Model -> Variant hierarchy
        $brands = Brand::orderBy('brand_name')->get();
        
        $models = $this->brand 
            ? VehicleModel::where('brand_id', $this->brand)->orderBy('model_name')->get() 
            : collect();

        // Updated to use the Variant model as per your requested logic
        $variants = $this->model 
            ? Variant::where('vehicle_model_id', $this->model)->orderBy('name')->get() 
            : collect();

        // While not displayed in a dropdown, we fetch specs if a variant is selected 
        // for internal logic or compatibility badges
        $specifications = $this->variant 
            ? Specification::where('variant_id', $this->variant)->get() 
            : collect();    

        $categories = Category::withCount('parts')->orderBy('category_name')->get();

        // 2. Build Query using part_fitment relationship (via specifications)
        $partsQuery = Part::query()
            ->with(['photos', 'partBrand', 'specifications.variant', 'specifications.vehicleModel.brand'])
            
            // Filter by selected Variant
            ->when($this->variant, function($q) {
                $q->whereHas('specifications', fn($query) => $query->where('variant_id', $this->variant));
            }) 
            // Filter by Model if no specific Variant is selected
            ->when($this->model && !$this->variant, function($q) {
                $q->whereHas('specifications', fn($query) => $query->where('vehicle_model_id', $this->model));
            })
            // Filter by Brand if no specific Model is selected
            ->when($this->brand && !$this->model, function($q) {
                $q->whereHas('specifications.vehicleModel', fn($query) => $query->where('brand_id', $this->brand));
            })

            ->when($this->category, fn($q) => $q->where('category_id', $this->category))
            ->when($this->in_stock, fn($q) => $q->where('stock_quantity', '>', 0))
            ->when($this->min_price, fn($q) => $q->where('price', '>=', $this->min_price))
            ->when($this->max_price, fn($q) => $q->where('price', '<=', $this->max_price))

            ->when(filled($this->search), function($q) {
                $q->where(function($sub) {
                    $term = '%' . $this->search . '%';
                    $sub->where('part_name', 'like', $term)
                        ->orWhere('part_number', 'like', $term)
                        ->orWhere('oem_number', 'like', $term)
                        ->orWhere('sku', 'like', $term);
                });
            });

        // 3. Sorting
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
            'currentSpecs' => $specifications // Optional: Pass to view if you want to show tech specs
        ]);
    }
}