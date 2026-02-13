<?php

namespace App\Livewire\Parts;

use App\Models\Category;
use App\Models\Part;
use App\Models\PartBrand;
use App\Models\VehicleModel;
use App\Models\Variant;
use Livewire\Component;
use Livewire\WithPagination;

class PartsCatalog extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $category = '';
    public $brand = '';
    public $model = '';
    public $year = '';
    public $variant = '';
    public $oem = '';
    public $min_price;
    public $max_price;
    public $in_stock = false;
    public $sort = 'latest';

    // For dependent dropdowns
    public $models = [];
    public $variants = [];

    protected $updatesQueryString = [
        'search', 'category', 'brand', 'model', 'year', 'variant', 'oem', 'min_price', 'max_price', 'in_stock', 'sort'
    ];

    // Reset pagination on filter change
    public function updating($field)
    {
        $this->resetPage();
    }

    // Update model dropdown when brand changes
    public function updatedBrand($value)
    {
        $this->model = '';
        $this->variant = '';
        $this->models = VehicleModel::where('brand_id', $value)->orderBy('model_name')->get();
        $this->variants = [];
    }

    // Update variant dropdown when model changes
    public function updatedModel($value)
    {
        $this->variant = '';
        $this->variants = Variant::where('model_id', $value)->orderBy('name')->get();
    }

    public function render()
    {
        $query = Part::with('photos', 'partBrand', 'vehicleModels', 'variants');

        // Search by name or part number
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('part_name', 'like', '%'.$this->search.'%')
                  ->orWhere('part_number', 'like', '%'.$this->search.'%');
            });
        }

        // Category filter
        if ($this->category) {
            $query->where('category_id', $this->category);
        }

        // Part Brand filter
        if ($this->brand) {
            $query->where('part_brand_id', $this->brand);
        }

        // Vehicle Model filter via pivot
        if ($this->model) {
            $query->whereHas('vehicleModels', function ($q) {
                $q->where('vehicle_model_id', $this->model);
            });
        }

        // Variant filter via pivot
        if ($this->variant) {
            $query->whereHas('variants', function ($q) {
                $q->where('variant_id', $this->variant);
            });
        }

        // Year filter via fitments pivot
        if ($this->year) {
            $query->whereHas('fitments', function ($q) {
                $q->where('start_year', '<=', $this->year)
                  ->where('end_year', '>=', $this->year);
            });
        }

        // OEM filter
        if ($this->oem) {
            if ($this->oem === 'OEM') {
                $query->whereNotNull('oem_number');
            } else { // Aftermarket
                $query->whereNull('oem_number');
            }
        }

        // Price range filter
        if ($this->min_price) $query->where('price', '>=', $this->min_price);
        if ($this->max_price) $query->where('price', '<=', $this->max_price);

        // Stock filter
        if ($this->in_stock) $query->where('stock_quantity', '>', 0);

        // Sorting
        match ($this->sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name_asc' => $query->orderBy('part_name'),
            default => $query->latest(),
        };

        return view('livewire.parts.parts-catalog', [
            'parts' => $query->paginate(12),
            'categories' => Category::whereNull('parent_id')->orderBy('category_name')->withCount('parts')->get(),
            'brands' => PartBrand::orderBy('name')->get(),
        ]);
    }
}