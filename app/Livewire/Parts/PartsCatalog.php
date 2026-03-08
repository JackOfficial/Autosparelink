<?php 

namespace App\Livewire\Parts;

use Livewire\Component;
use App\Models\Part;
use App\Models\Category;
use App\Models\Brand;
use App\Models\VehicleModel; // Assuming your model names
use App\Models\Specification; // Assuming your variant/specification model
use Livewire\WithPagination;

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
    public $sort = 'latest';

    protected $paginationTheme = 'bootstrap';

    // Reset pagination on filter change
    public function updatedBrand() { $this->model = null; $this->variant = null; $this->resetPage(); }
    public function updatedModel() { $this->variant = null; $this->resetPage(); }
    public function updatedSearch() { $this->resetPage(); }

    public function render()
    {
        // 1. Fetch Sidebar Data
        $brands = Brand::orderBy('brand_name')->get();
        
        // Only fetch models if a brand is selected
        $models = $this->brand 
            ? VehicleModel::where('brand_id', $this->brand)->orderBy('model_name')->get() 
            : collect();

        // Only fetch variants if a model is selected
        $variants = $this->model 
            ? Specification::where('vehicle_model_id', $this->model)->get() 
            : collect();

        $categories = Category::withCount('parts')->get();

        // 2. Build the Parts Query
        $partsQuery = Part::query()
            ->when($this->brand, function($q) {
                $q->whereHas('specifications.vehicleModel', fn($query) => $query->where('brand_id', $this->brand));
            })
            ->when($this->model, function($q) {
                $q->whereHas('specifications', fn($query) => $query->where('vehicle_model_id', $this->model));
            })
            ->when($this->variant, function($q) {
                $q->whereHas('specifications', fn($query) => $query->where('specifications.id', $this->variant));
            })
            ->when($this->category, fn($q) => $q->where('category_id', $this->category))
            ->when($this->in_stock, fn($q) => $q->where('stock', '>', 0))
            ->when($this->min_price, fn($q) => $q->where('price', '>=', $this->min_price))
            ->when($this->max_price, fn($q) => $q->where('price', '<=', $this->max_price))
            ->where(function($q) {
                $q->where('part_name', 'like', '%' . $this->search . '%')
                  ->orWhere('part_number', 'like', '%' . $this->search . '%');
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