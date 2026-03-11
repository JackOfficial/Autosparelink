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
    public function mount($brand = null, $model = null, $variant = null, $vinData = null, $category = null)
    {
        // 1. Priority 1: VIN data from a search session
        if ($vinData) {
            $this->vinData = $vinData;
            $this->brand = $vinData['brand_id'] ?? null;
            $this->model = $vinData['model_id'] ?? null;
            $this->variant = $variant; 

        } else {
            // 2. Priority 2: Direct URL queries or Parameters (View All / Filtered Links)
            // We use request()->query() to ensure that even if $brand is null, we check the URL.
            $this->brand = request()->query('brand', $brand ?? $this->brand);
            $this->model = request()->query('model', $model ?? $this->model);
            $this->variant = request()->query('variant', $variant ?? $this->variant);
            $this->category = request()->query('category', $category ?? $this->category);
        }

        // Global search bar handling
        if(request()->has('search_query')) {
            $this->search = request()->query('search_query');
        }
    }

    // --- Filter Lifecycle Hooks ---
    // When a parent changes, we MUST nullify children to reset the chain
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
        // 1. Fetch Sidebar Collections
        // These will react automatically to $this->brand and $this->model being set in mount()
        $brands = Brand::orderBy('brand_name')->get();
        
        $models = $this->brand 
            ? VehicleModel::where('brand_id', $this->brand)->orderBy('model_name')->get() 
            : collect();

        $variants = $this->model 
            ? Specification::where('vehicle_model_id', $this->model)->with('variant')->get() 
            : collect();

        $categories = Category::withCount('parts')->orderBy('category_name')->get();

        // 2. Build Query
        $partsQuery = Part::query()
            ->with(['photos', 'partBrand', 'specifications.vehicleModel.brand'])
            
            ->when($this->variant, function($q) {
                $q->whereHas('specifications', fn($query) => $query->where('variant_id', $this->variant));
            }) 
            ->when($this->model && !$this->variant, function($q) {
                $q->whereHas('specifications', fn($query) => $query->where('vehicle_model_id', $this->model));
            })
            ->when($this->brand && !$this->model, function($q) {
                $q->whereHas('specifications.vehicleModel', fn($query) => $query->where('brand_id', $this->brand));
            })

            ->when($this->category, fn($q) => $q->where('category_id', $this->category))
            ->when($this->in_stock, fn($q) => $q->where('stock_quantity', '>', 0))
            ->when($this->min_price, fn($q) => $q->where('price', '>=', $this->min_price))
            ->when($this->max_price, fn($q) => $q->where('price', '<=', $this->max_price))

            ->when($this->search, function($q) {
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
        ]);
    }
}