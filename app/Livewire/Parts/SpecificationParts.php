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
    
    // New Filters
    public $condition = '';      // 'new' or 'used'
    public $maxPrice = 2000000;  // Adjust default max based on your currency
    public $sortBy = 'latest';   // 'latest', 'price_low', 'price_high'
    
    protected $paginationTheme = 'bootstrap';

    // Query String keeps the URL updated so users can share filtered results
    protected $queryString = [
        'search' => ['except' => ''],
        'category_id' => ['except' => null],
        'condition' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
    ];

    // Reset pagination when any filter property is updated
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
        // 1. Fetch Specification Details
        $specification = Specification::with(['vehicleModel.brand', 'destinations'])
            ->findOrFail($this->specificationId);

        // 2. Fetch Categories scoped to this vehicle spec (for sidebar count)
        $categories = Category::whereHas('parts', function($q) {
            $q->whereHas('specifications', function($s) {
                $s->where('specifications.id', $this->specificationId);
            });
        })->withCount(['parts' => function($q) {
            $q->whereHas('specifications', function($s) {
                $s->where('specifications.id', $this->specificationId);
            });
        }])->get();

        // 3. Build the Parts Query
        $partsQuery = Part::whereHas('specifications', function($q) {
            $q->where('specifications.id', $this->specificationId);
        })
        ->when($this->category_id, function($q) {
            $q->where('category_id', $this->category_id);
        })
        ->when($this->condition, function($q) {
            $q->where('condition', $this->condition);
        })
        ->when($this->inStockOnly, function($q) {
            $q->where('stock', '>', 0);
        })
        ->where(function($q) {
            $q->where('part_name', 'like', '%' . $this->search . '%')
              ->orWhere('part_number', 'like', '%' . $this->search . '%');
        });

        // 4. Apply Price Filter (assuming you have a 'price' column)
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
            case 'latest':
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