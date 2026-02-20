<?php

namespace App\Livewire\Parts;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Part;
use App\Models\VehicleModel;
use App\Models\Variant;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class PartsCatalog extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $vinData = [];

    #[Url(except: '')] public $search = '';
    #[Url(except: '')] public $category = '';
    #[Url(except: '')] public $brand = '';
    #[Url(except: '')] public $model = '';
    #[Url(except: '')] public $year = '';
    #[Url(except: '')] public $variant = '';
    #[Url(except: '')] public $oem = '';
    #[Url] public $min_price;
    #[Url] public $max_price;
    #[Url] public $in_stock = false;
    #[Url] public $sort = 'latest';

    // IMPORTANT: Initialize as empty arrays
    public $models = [];
    public $variants = [];

    public function mount($brand = null, $model = null, $variant = null, $vinData = [])
    {
        // Use the values passed from the Controller/Blade
        $this->brand = $brand ?? '';
        $this->model = $model ?? '';
        $this->variant = $variant ?? '';
        $this->vinData = $vinData;

        // Populate dropdowns - Use toArray() to avoid 500 serialization errors
        if ($this->brand) {
            $this->models = VehicleModel::where('brand_id', $this->brand)
                ->orderBy('model_name')
                ->get();
        }

        if ($this->model) {
            $this->variants = Variant::where('vehicle_model_id', $this->model)
                ->orderBy('name')
                ->get();
        }
    }

    // ... updatedBrand and updatedModel methods ...

    public function render()
    {
        // Check for common errors: ensure relationships exist in Part model
        // with('photos', 'partBrand', 'vehicleModels', 'variants')
        $query = Part::with(['photos', 'partBrand']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('part_name', 'like', '%'.$this->search.'%')
                  ->orWhere('part_number', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->category) $query->where('category_id', $this->category);

        // Fixed query logic for vehicle matching
        if ($this->variant) {
            $query->whereHas('variants', fn($q) => $q->where('variants.id', $this->variant));
        } elseif ($this->model) {
            $query->whereHas('vehicleModels', fn($q) => $q->where('vehicle_models.id', $this->model));
        }

        // Price, Stock, Sorting filters...
        
        return view('livewire.parts.parts-catalog', [
            'parts' => $query->paginate(12),
            'categories' => Category::whereNull('parent_id')->withCount('parts')->get(),
            'brands' => Brand::orderBy('brand_name')->get(),
        ]);
    }
}