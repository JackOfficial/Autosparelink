<?php

namespace App\Livewire\Parts;

use Livewire\Component;
use App\Models\Part;
use App\Models\Category;
use Livewire\WithPagination;

class PartsCatalog extends Component
{
   use WithPagination;

    public $search = '';
    public $category_id = null;
    public $inStockOnly = false;
    protected $paginationTheme = 'bootstrap';

    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategoryId() { $this->resetPage(); }

    // ... updatedBrand and updatedModel methods ...

    public function render()
    {
        // 1. Fetch Categories with total global counts
        $categories = Category::withCount('parts')->get();

        // 2. Query all parts with filters
        $parts = Part::with(['category'])
            ->when($this->category_id, function($q) {
                $q->where('category_id', $this->category_id);
            })
            ->when($this->inStockOnly, function($q) {
                $q->where('stock', '>', 0);
            })
            ->where(function($q) {
                $q->where('part_name', 'like', '%' . $this->search . '%')
                  ->orWhere('part_number', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(12);
        
        return view('livewire.parts.parts-catalog', [
           'categories' => $categories,
            'parts' => $parts
        ]);
    }
}