<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class CategoriesComponent extends Component
{
    public function render()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('livewire.categories-component', compact('categories'));
    }
}
