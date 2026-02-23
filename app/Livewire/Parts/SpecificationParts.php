<?php

namespace App\Livewire\Parts;

use Livewire\Component;
use App\Models\Specification;
use App\Models\Part;
use App\Models\Category; // Ensure you import your Category model
use Livewire\WithPagination;

class SpecificationParts extends Component
{
    use WithPagination;

    public $specificationId;
    public $search = '';
    public $category_id = null;
    public $inStockOnly = false; // Track stock filter
    
    protected $paginationTheme = 'bootstrap';

    // Reset pagination when search or filters change
    public function updatingSearch() { $this->resetPage(); }
    public function updatingCategoryId() { $this->resetPage(); }
    public function updatingInStockOnly() { $this->resetPage(); }

    public function mount($specificationId)
    {
        $this->specificationId = $specificationId;
    }

    public function render()
    {
        // 1. Fetch Specification Details
        $specification = Specification::with(['vehicleModel.brand', 'destinations'])
            ->findOrFail($this->specificationId);

        // 2. Fetch Categories that actually have parts for THIS specific car
        // This makes the sidebar intuitive by not showing empty categories
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
        ->when($this->categoryId, function($q) {
            $q->where('category_id', $this->categoryId);
        })
        ->when($this->inStockOnly, function($q) {
            $q->where('stock', '>', 0);
        })
        ->where(function($q) {
            $q->where('part_name', 'like', '%' . $this->search . '%')
              ->orWhere('part_number', 'like', '%' . $this->search . '%');
        });

        return view('livewire.parts.specification-parts', [
            'specification' => $specification,
            'categories' => $categories,
            'parts' => $partsQuery->paginate(12) // 12 works better for a 3-column grid
        ]);
    }
}