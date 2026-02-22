<?php 

namespace App\Livewire\Specifications;

use App\Models\Variant;
use App\Models\DriveType;
use App\Models\Destination;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class Index extends Component
{
    public Variant $item;

    /** * Technical Filters - Focused only on attributes NOT in the variant name.
     */
    #[Url(history: true)]
    public $steering_position = '';

    #[Url(history: true)]
    public $drive = '';

    #[Url(history: true)]
    public $destination = '';

    #[Url(history: true)]
    public $search = '';

    public function mount(Variant $variant)
    {
        // Eager load everything needed for the locked header and table
        $this->item = $variant->load(['vehicleModel.brand', 'bodyType', 'engineType', 'transmissionType']);
    }

    public function resetFilters()
    {
        $this->reset(['steering_position', 'drive', 'destination', 'search']);
    }

    #[Computed]
    public function specifications()
    {
        return $this->item->specifications()
            ->with(['driveType', 'destinations'])
            ->when($this->steering_position, fn($q) => $q->where('steering_position', $this->steering_position))
            ->when($this->drive, fn($q) => $q->where('drive_type_id', $this->drive))
            ->when($this->destination, function($q) {
                $q->whereHas('destinations', fn($d) => $d->where('destinations.id', $this->destination));
            })
            ->when($this->search, function($q) {
                $q->where(fn($sub) => 
                    $sub->where('chassis_code', 'like', '%' . $this->search . '%')
                        ->orWhere('model_code', 'like', '%' . $this->search . '%')
                        ->orWhereHas('destinations', fn($d) => $d->where('region_name', 'like', '%' . $this->search . '%'))
                );
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.specifications.index', [
            'specifications' => $this->specifications,
            // Only show Drive Types and Destinations that exist for this specific Variant
            'driveTypes'     => DriveType::whereHas('specifications', fn($q) => $q->where('variant_id', $this->item->id))->get(),
            'destinations'   => Destination::whereHas('specifications', fn($q) => $q->where('variant_id', $this->item->id))->get(),
        ]);
    }
}