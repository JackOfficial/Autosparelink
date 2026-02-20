<?php

namespace App\Livewire\Parts;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Part;
use App\Models\VehicleModel;
use App\Models\Variant;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url; // Laravel 12 preferred way for query strings

class PartsCatalog extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Added to store the full API response for future specificity
    public $vinData = [];

    #[Url] public $search = '';
    #[Url] public $category = '';
    #[Url] public $brand = '';
    #[Url] public $model = '';
    #[Url] public $year = '';
    #[Url] public $variant = '';
    #[Url] public $oem = '';
    #[Url] public $min_price;
    #[Url] public $max_price;
    #[Url] public $in_stock = false;
    #[Url] public $sort = 'latest';

    public $models = [];
    public $variants = [];

    /**
     * Mount runs once when the component is created.
     * It catches the data passed from the Blade @livewire call.
     */
    public function mount($brand = '', $model = '', $variant = '', $vinData = [])
    {
        $this->brand = $brand;
        $this->model = $model;
        $this->variant = $variant;
        $this->vinData = $vinData;

        // If we have a brand/model from VIN, populate the dependent dropdowns immediately
        if ($this->brand) {
            $this->models = VehicleModel::where('brand_id', $this->brand)->orderBy('model_name')->get();
        }
        if ($this->model) {
            $this->variants = Variant::where('vehicle_model_id', $this->model)->orderBy('name')->get();
        }
        
        // Future: You can use $this->vinData['Vehicle Specification']['Fuel type'] 
        // to pre-set other filters here.
    }

    public function updating($field)
    {
        $this->resetPage();
    }

    public function updatedBrand($value)
    {
        $this->model = '';
        $this->variant = '';
        $this->models = $value ? VehicleModel::where('brand_id', $value)->orderBy('model_name')->get() : [];
        $this->variants = [];
    }

    public function updatedModel($value)
    {
        $this->variant = '';
        $this->variants = $value ? Variant::where('vehicle_model_id', $value)->orderBy('name')->get() : [];
    }

    public function render()
    {
        $query = Part::with('photos', 'partBrand', 'vehicleModels', 'variants');

        // Apply filters (Keeping your existing logic)
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('part_name', 'like', '%'.$this->search.'%')
                  ->orWhere('part_number', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->category) $query->where('category_id', $this->category);

        // Vehicle Compatibility Logic
        if ($this->variant) {
            $query->whereHas('variants', fn($q) => $q->where('variant_id', $this->variant));
        } elseif ($this->model) {
            $query->whereHas('vehicleModels', fn($q) => $q->where('vehicle_model_id', $this->model));
        }

        if ($this->year) {
            $query->whereHas('fitments', function ($q) {
                $q->where('year_start', '<=', $this->year)
                  ->where('year_end', '>=', $this->year);
            });
        }

        // Price, Stock, Sorting... (Remains the same as your original)
        if ($this->min_price) $query->where('price', '>=', $this->min_price);
        if ($this->max_price) $query->where('price', '<=', $this->max_price);
        if ($this->in_stock) $query->where('stock_quantity', '>', 0);

        match ($this->sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name_asc' => $query->orderBy('part_name'),
            default => $query->latest(),
        };

        return view('livewire.parts.parts-catalog', [
            'parts' => $query->paginate(12),
            'categories' => Category::whereNull('parent_id')->orderBy('category_name')->withCount('parts')->get(),
            'brands' => Brand::orderBy('brand_name')->get(),
        ]);
    }
}