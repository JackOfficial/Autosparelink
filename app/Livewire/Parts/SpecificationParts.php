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
    
    public $condition = '';      
    public $maxPrice = 2000000;  
    public $sortBy = 'latest';   
    
    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'category_id' => ['except' => null],
        'condition' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
    ];

    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategoryId() { $this->resetPage(); }
    public function updatedInStockOnly() { $this->resetPage(); }
    public function updatedCondition() { $this->resetPage(); }
    public function updatedMaxPrice() { $this->resetPage(); }
    public function updatedSortBy() { $this->resetPage(); }

    public function mount($specificationId)
    {
        $this->specificationId = $specificationId;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'category_id', 'inStockOnly', 'condition', 'maxPrice', 'sortBy']);
    }

    public function render()
    {
        $specification = Specification::with(['vehicleModel.brand', 'destinations'])
            ->findOrFail($this->specificationId);

        // 1. Get IDs of all categories that actually contain parts for this vehicle
        // This prevents showing empty categories.
        $activeCategoryIds = Category::whereHas('parts', function($q) {
            $q->whereHas('specifications', function($s) {
                $s->where('specifications.id', $this->specificationId);
            });
        })->pluck('id')->toArray();

        // 2. Fetch Categories for Sidebar
        $categories = Category::query()
            // Handle Top-level (null or 0) vs Sub-category drill-down
            ->where(function($q) {
                if (is_null($this->category_id)) {
                    $q->whereNull('parent_id')->orWhere('parent_id', 0);
                } else {
                    $q->where('parent_id', $this->category_id);
                }
            })
            // SHOW category if it has parts OR if any of its children have parts
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

        // 3. Build the Parts Query
        $partsQuery = Part::whereHas('specifications', function($q) {
            $q->where('specifications.id', $this->specificationId);
        })
        ->when($this->category_id, function($q) {
            // Recursive: Show parts in selected category AND its children
            $categoryAndChildren = Category::where('id', $this->category_id)
                ->orWhere('parent_id', $this->category_id)
                ->pluck('id');
            $q->whereIn('category_id', $categoryAndChildren);
        })
        ->when($this->condition, fn($q) => $q->where('condition', $this->condition))
        ->when($this->inStockOnly, fn($q) => $q->where('stock', '>', 0))
        ->where(function($q) {
            $q->where('part_name', 'like', '%' . $this->search . '%')
              ->orWhere('part_number', 'like', '%' . $this->search . '%');
        });

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