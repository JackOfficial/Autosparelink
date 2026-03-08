<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Part;
use App\Models\VehicleModel;
use App\Models\Variant;
use Illuminate\Support\Facades\Storage;

class PartFitmentForm extends Component
{
    public $parts;
    public $vehicleModels;
    public $variants = [];
    
    public $part_id;
    public $vehicle_model_id;
    public $variant_id;
    public $status = 'active';
    public $year_start;
    public $year_end;
    public $photos = [];

    public function mount()
    {
        $this->parts = Part::all();
        $this->vehicleModels = VehicleModel::all();
    }

    public function updatedVehicleModelId($value)
    {
        $this->variants = Variant::where('vehicle_model_id', $value)->get();
        $this->variant_id = null; // reset variant when model changes
    }

    public function save()
    {
        $this->validate([
            'part_id'           => 'required|exists:parts,id',
            'vehicle_model_id'  => 'required|exists:vehicle_models,id',
            'variant_id'        => 'nullable|exists:variants,id',
            'status'            => 'required|in:active,inactive',
            'year_start'        => 'nullable|integer',
            'year_end'          => 'nullable|integer|gte:year_start',
            'photos.*'          => 'nullable|image|max:2048',
        ]);

        $fitment = \App\Models\PartFitment::create([
            'part_id'           => $this->part_id,
            'vehicle_model_id'  => $this->vehicle_model_id,
            'variant_id'        => $this->variant_id,
            'status'            => $this->status,
            'year_start'        => $this->year_start,
            'year_end'          => $this->year_end,
        ]);

        if ($this->photos) {
            foreach ($this->photos as $photo) {
                $path = $photo->store('fitments', 'public');
                $fitment->photos()->create(['photo' => $path]); // assuming you have a photos() relation
            }
        }

        session()->flash('success', 'Fitment saved successfully.');
        return redirect()->route('admin.fitments.index');
    }

    public function render()
    {
        return view('livewire.admin.part-fitment-form');
    }
}
