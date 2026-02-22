<?php 

namespace App\Livewire\Specifications;

use App\Models\Variant;
use App\Models\DriveType;
use App\Models\BodyType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\Destination;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class Index extends Component
{
    public Variant $item;

    /** * Technical Filters 
     * Using #[Url(history: true)] ensures filters persist on page refresh 
     * and can be shared via the URL.
     */
    #[Url(history: true)]
    public $steering_position = '';

    #[Url(history: true)]
    public $drive = '';

    #[Url(history: true)]
    public $body_type = '';

    #[Url(history: true)]
    public $engine_type = '';

    #[Url(history: true)]
    public $transmission_type = '';

    #[Url(history: true)]
    public $destination = '';

    #[Url(history: true)]
    public $search = '';

    public function mount(Variant $variant)
    {
        // Eager load the hierarchy for the breadcrumbs/header
        $this->item = $variant->load(['vehicleModel.brand']);
    }

    /**
     * Reset all filters to their default state
     */
    public function resetFilters()
    {
        $this->reset([
            'steering_position', 
            'drive', 
            'body_type', 
            'engine_type', 
            'transmission_type', 
            'destination', 
            'search'
        ]);
    }

    /**
     * Computed property for the specifications table.
     * Re-evaluates automatically when any wire:model property changes.
     */
    #[Computed]
    public function specifications()
    {
        return $this->item->specifications()
            ->with(['bodyType', 'engineType', 'transmissionType', 'driveType', 'destinations'])
            ->when($this->steering_position, fn($q) => $q->where('steering_position', $this->steering_position))
            ->when($this->drive, fn($q) => $q->where('drive_type_id', $this->drive))
            ->when($this->body_type, fn($q) => $q->where('body_type_id', $this->body_type))
            ->when($this->engine_type, fn($q) => $q->where('engine_type_id', $this->engine_type))
            ->when($this->transmission_type, fn($q) => $q->where('transmission_type_id', $this->transmission_type))
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
            'specifications'    => $this->specifications,
            'driveTypes'        => DriveType::all(),
            'bodyTypes'         => BodyType::all(),
            'engineTypes'       => EngineType::all(),
            'transmissionTypes' => TransmissionType::all(),
            'destinations'      => Destination::all(),
        ]);
    }
}