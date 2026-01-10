<div>
<div class="card card-primary">
<div class="card-header">
    <h3 class="card-title">Model Information</h3>
</div>

<div class="card-body">

<form wire:submit.prevent="save">

{{-- Brand --}}
<div class="form-group">
    <label>Brand *</label> ({{ $brand_id }})
    <select wire:model.live="brand_id" class="form-control">
        <option value="">Select Brand</option>
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
        @endforeach
    </select>
</div>

{{-- Model Name --}}
<div class="form-group">
    <label>Model Name *</label> ({{ $model_name }})
    <input type="text" wire:model.live="model_name" class="form-control">
</div>

{{-- Has Variants --}}
<div class="form-group">
    <label>Does this model have variants?</label>
    <div>
        <label class="mr-3">
            <input type="radio" wire:model.live="has_variants" value="1"> Yes
        </label>
        <label>
            <input type="radio" wire:model.live="has_variants" value="0"> No
        </label>
    </div>
</div>

{{-- Description --}}
<div class="form-group">
    <label>Description</label>
    <textarea wire:model="description" class="form-control"></textarea>
</div>

{{-- ================= SPECIFICATIONS ================= --}}
@if($has_variants == 0)
<hr>
<h4 class="text-primary">Specifications</h4>

<div class="row">
    <div class="col-md-3">
        <label>Body Type *</label>
        <select wire:model="spec.body_type_id" class="form-control">
            <option value="">Select</option>
            @foreach($bodyTypes as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label>Engine Type *</label>
        <select wire:model="spec.engine_type_id" class="form-control">
            <option value="">Select</option>
            @foreach($engineTypes as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label>Transmission *</label>
        <select wire:model="spec.transmission_type_id" class="form-control">
            <option value="">Select</option>
            @foreach($transmissionTypes as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label>Drive Type</label>
        <select wire:model="spec.drive_type_id" class="form-control">
            <option value="">Select</option>
            @foreach($driveTypes as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-3">
        <label>Horsepower</label>
        <input type="number" wire:model="spec.horsepower" class="form-control">
    </div>
    <div class="col-md-3">
        <label>Torque</label>
        <input type="number" wire:model="spec.torque" class="form-control">
    </div>
    <div class="col-md-3">
        <label>Fuel Capacity</label>
        <input type="number" step="0.1" wire:model="spec.fuel_capacity" class="form-control">
    </div>
    <div class="col-md-3">
        <label>Fuel Efficiency</label>
        <input type="number" step="0.1" wire:model="spec.fuel_efficiency" class="form-control">
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-2">
        <label>Seats</label>
        <input type="number" wire:model="spec.seats" class="form-control">
    </div>
    <div class="col-md-2">
        <label>Doors</label>
        <input type="number" wire:model="spec.doors" class="form-control">
    </div>
    <div class="col-md-4">
        <label>Steering</label>
        <select wire:model="spec.steering_position" class="form-control">
            <option value="">Select</option>
            <option value="LEFT">Left</option>
            <option value="RIGHT">Right</option>
        </select>
    </div>
    <div class="col-md-4">
        <label>Color</label>
        <input type="text" wire:model="spec.color" class="form-control">
    </div>
</div>
@endif
{{-- ================= END ================= --}}

<div class="mt-4">
    <button class="btn btn-primary">
        <i class="fa fa-save"></i> Save Model
    </button>
</div>

</form>
</div>
</div>
</div>
