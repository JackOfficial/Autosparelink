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
    public $category_id = null; // Consolidated to snake_case
    public $inStockOnly = false;
    
    protected $paginationTheme = 'bootstrap';

    // Reset pagination when any filter property is updated
    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategoryId() { $this->resetPage(); }
    public function updatedInStockOnly() { $this->resetPage(); }

    public function mount($specificationId)
    {
        $this->specificationId = $specificationId;
    }

    public function render()
    {
        // 1. Fetch Specification Details
        $specification = Specification::with(['vehicleModel.brand', 'destinations'])
            ->findOrFail($this->specificationId);

        // 2. Fetch Categories scoped to this vehicle spec
        $categories = Category::whereHas('parts', function($q) {
            $q->whereHas('specifications', function($s) {
                $s->where('specifications.id', $this->specificationId);
            });
        })->withCount(['parts' => function($q) {
            $q->whereHas('specifications', function($s) {
                $s->where('specifications.id', $this->specificationId);
            });
        }])->get();

        // 3. Build the Parts Query using $this->category_id
        $partsQuery = Part::whereHas('specifications', function($q) {
            $q->where('specifications.id', $this->specificationId);
        })
        ->when($this->category_id, function($q) {
            $q->where('category_id', $this->category_id);
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
            'parts' => $partsQuery->paginate(12)
        ]);
    }
}