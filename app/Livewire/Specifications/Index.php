<?php 

namespace App\Livewire\Specifications;

use App\Models\Variant;
use App\Models\DriveType;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class Index extends Component
{
    public Variant $item;

    // Filter Properties - #[Url] keeps them in the browser's address bar
    #[Url(history: true)]
    public $steering_position = '';

    #[Url(history: true)]
    public $drive = '';

    #[Url(history: true)]
    public $search = '';

    public function mount(Variant $variant)
    {
        $this->item = $variant->load(['vehicleModel.brand']);
    }

    #[Computed]
    public function specifications()
    {
        return $this->item->specifications()
            ->with(['bodyType', 'engineType', 'transmissionType', 'driveType', 'destinations'])
            ->when($this->steering_position, fn($q) => $q->where('steering_position', $this->steering_position))
            ->when($this->drive, fn($q) => $q->where('drive_type_id', $this->drive))
            ->when($this->search, function($q) {
                $q->where(fn($sub) => 
                    $sub->where('chassis_code', 'like', '%' . $this->search . '%')
                        ->orWhere('model_code', 'like', '%' . $this->search . '%')
                        ->orWhereHas('destinations', fn($d) => $d->where('region_name', 'like', '%' . $this->search . '%'))
                );
            })
            ->get();
    }

    public function resetFilters()
{
    $this->reset(['steering_position', 'drive', 'search']);
}

    public function render()
    {
        return view('livewire.specifications.index', [
            'driveTypes' => DriveType::all(),
            'specifications' => $this->specifications, // Calls the computed property
        ]);
    }
}