<?php

namespace App\Livewire\Parts;

use Livewire\Component;
use App\Models\Specification;
use App\Models\Part;
use App\Models\Category;
use Livewire\WithPagination;

class SpecificationParts extends Component
{
    use WithPagination;

    public $specificationId;
    public $search = '';
    public $category_id = null;
    public $inStockOnly = false;
    
    public $maxPrice = 2000000;  
    public $sortBy = 'latest';   
    
    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'category_id' => ['except' => null],
        'sortBy' => ['except' => 'latest'],
    ];

    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategoryId() { $this->resetPage(); }
    public function updatedInStockOnly() { $this->resetPage(); }
    public function updatedMaxPrice() { $this->resetPage(); }
    public function updatedSortBy() { $this->resetPage(); }

    public function mount($specificationId)
    {
        $this->specificationId = $specificationId;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'category_id', 'inStockOnly', 'maxPrice', 'sortBy']);
    }

    public function render()
    {
        // 1. Fetch Specification Details
        $specification = Specification::with(['vehicleModel.brand', 'destinations'])
            ->findOrFail($this->specificationId);

        // 2. Identify Categories that have parts for this vehicle
        $activeCategoryIds = Category::whereHas('parts', function($q) {
            $q->forSpecification($this->specificationId); // Using your model scope
        })->pluck('id')->toArray();

        // 3. Fetch Sidebar Categories (Dynamic Drill-down)
        $categories = Category::query()
            ->where(function($q) {
                if (is_null($this->category_id)) {
                    $q->whereNull('parent_id')->orWhere('parent_id', 0);
                } else {
                    $q->where('parent_id', $this->category_id);
                }
            })
            ->where(function($q) use ($activeCategoryIds) {
                $q->whereIn('id', $activeCategoryIds)
                  ->orWhereHas('children', function($child) use ($activeCategoryIds) {
                      $child->whereIn('id', $activeCategoryIds);
                  });
            })
            ->withCount(['parts' => function($q) {
                $q->forSpecification($this->specificationId);
            }])
            ->withCount('children')
            ->get();

        // 4. Build the Parts Query
        $partsQuery = Part::query()
            ->forSpecification($this->specificationId) // Model Scope
            ->with(['partBrand', 'photos', 'category']) // Eager Load
            ->where('status', 'active') // Ensure parts are active
            ->when($this->category_id, function($q) {
                // Recursive: Show parts in the selected category AND its subcategories
                $categoryIds = Category::where('id', $this->category_id)
                    ->orWhere('parent_id', $this->category_id)
                    ->pluck('id');
                $q->whereIn('category_id', $categoryIds);
            })
            ->when($this->inStockOnly, function($q) {
                $q->where('stock_quantity', '>', 0); // Corrected to match your Model
            })
            ->where(function($q) {
                $q->where('part_name', 'like', '%' . $this->search . '%')
                  ->orWhere('part_number', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });

        if ($this->maxPrice) {
            $partsQuery->where('price', '<=', $this->maxPrice);
        }

        // 5. Apply Sorting
        switch ($this->sortBy) {
            case 'price_low': 
                $partsQuery->orderBy('price', 'asc'); 
                break;
            case 'price_high': 
                $partsQuery->orderBy('price', 'desc'); 
                break;
            default: 
                $partsQuery->latest(); 
                break;
        }

        return view('livewire.parts.specification-parts', [
            'specification' => $specification,
            'categories' => $categories,
            'parts' => $partsQuery->paginate(12)
        ]);
    }
}