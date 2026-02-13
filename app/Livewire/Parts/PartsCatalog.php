<?php

namespace App\Livewire\Parts;

use App\Models\Category;
use App\Models\Part;
use Livewire\Component;
use Livewire\WithPagination;

class PartsCatalog extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $category = '';
    public $min_price;
    public $max_price;
    public $in_stock = false;
    public $sort = 'latest';

    protected $updatesQueryString = [
        'search',
        'category',
        'min_price',
        'max_price',
        'in_stock',
        'sort'
    ];

    public function updating($field)
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Part::query()->with('photos');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('part_name', 'like', '%' . $this->search . '%')
                  ->orWhere('part_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $query->where('category_id', $this->category);
        }

        if ($this->min_price) {
            $query->where('price', '>=', $this->min_price);
        }

        if ($this->max_price) {
            $query->where('price', '<=', $this->max_price);
        }

        if ($this->in_stock) {
            $query->where('stock_quantity', '>', 0);
        }

        // Sorting
        match ($this->sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name_asc' => $query->orderBy('part_name'),
            default => $query->latest()
        };

        return view('livewire.parts.parts-catalog', [
            'parts' => $query->paginate(12),
            'categories' => Category::withCount('parts')->get()
        ])->layout('layouts.app');
    }
}
