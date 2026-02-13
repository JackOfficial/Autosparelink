<?php

namespace App\Livewire\Parts;

use App\Models\Category;
use App\Models\Part;
use App\Models\VehicleModel;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SparePart;
use App\Models\Brand;
use App\Models\Variant;
use App\Models\VehicleVariant;

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

    public function updating($field)
    {
        $this->resetPage();
    }

    public function updatedBrand($value)
    {
        $this->model = '';
        $this->variant = '';
        $this->models = VehicleModel::where('brand_id', $value)->get();
        $this->variants = [];
    }

    public function updatedModel($value)
    {
        $this->variant = '';
        $this->variants = Variant::where('model_id', $value)->get();
    }

    public function render()
    {
        $query = Part::query()->with('photos');

        // Search
        if ($this->search) {
            $query->where(function($q){
                $q->where('part_name', 'like', '%'.$this->search.'%')
                  ->orWhere('part_number', 'like', '%'.$this->search.'%');
            });
        }

        // Filters
        if ($this->category) $query->where('category_id', $this->category);
        if ($this->brand) $query->where('brand_id', $this->brand);
        if ($this->model) $query->where('model_id', $this->model);
        if ($this->variant) $query->where('variant_id', $this->variant);
        if ($this->year) $query->where('year', $this->year);
        if ($this->oem) $query->where('oem', $this->oem); // 'OEM' or 'Aftermarket'
        if ($this->min_price) $query->where('price', '>=', $this->min_price);
        if ($this->max_price) $query->where('price', '<=', $this->max_price);
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
            'brands' => Brand::all(),
        ]);
    }
}
