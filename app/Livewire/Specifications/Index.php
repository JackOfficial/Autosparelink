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
    public array $lockedInfo = [];

    /** * Technical Filters 
     * We exclude Body, Engine, and Transmission as they define the Variant itself.
     */
    #[Url(history: true)] public $steering_position = '';
    #[Url(history: true)] public $drive = '';
    #[Url(history: true)] public $destination = '';
    #[Url(history: true)] public $search = '';

    public function mount(Variant $variant)
    {
        // Load the variant and its brand
        $this->item = $variant->load(['vehicleModel.brand']);

        // Since the Variant name is generated from spec data, 
        // we grab the first spec to extract the "Locked" info for the header.
        $firstSpec = $this->item->specifications()
            ->with(['bodyType', 'engineType', 'transmissionType'])
            ->first();

        if ($firstSpec) {
            $this->lockedInfo = [
                'body'   => $firstSpec->bodyType->name ?? 'N/A',
                'engine' => $firstSpec->engineType->name ?? 'N/A',
                'trans'  => $firstSpec->transmissionType->name ?? 'N/A',
            ];
        }
    }

    public function resetFilters()
    {
        $this->reset(['steering_position', 'drive', 'destination', 'search']);
    }

    #[Computed]
    public function specifications()
    {
        return $this->item->specifications()
            ->with(['driveType', 'destinations', 'bodyType', 'engineType', 'transmissionType'])
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
            'driveTypes'     => DriveType::whereHas('specifications', fn($q) => $q->where('variant_id', $this->item->id))->get(),
            'destinations'   => Destination::whereHas('specifications', fn($q) => $q->where('variant_id', $this->item->id))->get(),
        ]);
    }
}