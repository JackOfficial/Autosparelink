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
        $specification = Specification::with(['vehicleModel.brand', 'destinations'])
            ->findOrFail($this->specificationId);

        // 1. Fetch active categories (Direct check against specifications relationship)
        $activeCategoryIds = Category::whereHas('parts.specifications', function($q) {
            $q->where('specifications.id', $this->specificationId);
        })->pluck('id')->toArray();

        // 2. Fetch Sidebar Categories
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
                $q->whereHas('specifications', function($s) {
                    $s->where('specifications.id', $this->specificationId);
                });
            }])
            ->withCount('children')
            ->get();

        // 3. Build the Parts Query (Direct Relationship Query)
        $partsQuery = Part::whereHas('specifications', function($q) {
            $q->where('specifications.id', $this->specificationId);
        })
        ->with(['partBrand', 'photos']) 
        ->when($this->category_id, function($q) {
            $categoryIds = Category::where('id', $this->category_id)
                ->orWhere('parent_id', $this->category_id)
                ->pluck('id');
            $q->whereIn('category_id', $categoryIds);
        })
        ->when($this->inStockOnly, function($q) {
            $q->where('stock_quantity', '>', 0);
        })
        ->where(function($q) {
            $searchTerm = '%' . $this->search . '%';
            $q->where('part_name', 'like', $searchTerm)
              ->orWhere('part_number', 'like', $searchTerm)
              ->orWhere('sku', 'like', $searchTerm);
        });

        // 4. Price and Sorting
        if ($this->maxPrice) {
            $partsQuery->where('price', '<=', $this->maxPrice);
        }

        switch ($this->sortBy) {
            case 'price_low': $partsQuery->orderBy('price', 'asc'); break;
            case 'price_high': $partsQuery->orderBy('price', 'desc'); break;
            default: $partsQuery->latest(); break;
        }

        return view('livewire.parts.specification-parts', [
            'specification' => $specification,
            'categories' => $categories,
            'parts' => $partsQuery->paginate(12)
        ]);
    }
}