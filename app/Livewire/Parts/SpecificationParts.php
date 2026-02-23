<?php

namespace App\Livewire\Parts;

use Livewire\Component;
use App\Models\Specification;
use App\Models\Part;
use Livewire\WithPagination;

class SpecificationParts extends Component
{
    use WithPagination;

    public $specificationId;
    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function mount($specificationId)
    {
        $this->specificationId = $specificationId;
    }

    public function render()
    {
        $specification = Specification::with(['vehicleModel.brand'])->findOrFail($this->specificationId);

        // Fetch parts associated with this spec
        $parts = Part::whereHas('specifications', function($q) {
            $q->where('specifications.id', $this->specificationId);
        })
        ->where(function($q) {
            $q->where('part_name', 'like', '%' . $this->search . '%')
              ->orWhere('part_number', 'like', '%' . $this->search . '%');
        })
        ->paginate(15);

        return view('livewire.parts.specification-parts', [
            'specification' => $specification,
            'parts' => $parts
        ]);
    }
}