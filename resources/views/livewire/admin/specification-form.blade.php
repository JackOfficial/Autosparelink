<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Specification Details</h3></div>
    <div class="card-body">

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form wire:submit.prevent="save">

            {{-- Brand → Model → Variant --}}
            @if(!$hideBrandModel)
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Vehicle Selection <span class="text-danger">*</span></legend>
                <div class="row">
                    {{-- Brand --}}
                    <div class="col-md-4">
                        <label>Brand</label>
                        <select wire:model.live="brand_id" class="form-control">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    {{-- Vehicle Model --}}
                    <div class="col-md-4">
                        <label>Vehicle Model</label>
                        <select wire:model.live="vehicle_model_id" class="form-control" @if(!$vehicleModels->count()) disabled @endif>
                            <option value="">Select Model</option>
                            @foreach($vehicleModels as $model)
                                <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_model_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    {{-- Variant --}}
                    <div class="col-md-4">
                        <label>Variant</label>
                        <select wire:model.live="variant_id" class="form-control" @if(!$filteredVariants->count()) disabled @endif>
                            <option value="">Select Variant (optional)</option>
                            @foreach($filteredVariants as $variant)
                                <option value="{{ $variant->id }}">{{ $variant->name ?? 'Unnamed Variant' }}</option>
                            @endforeach
                        </select>
                        @error('variant_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <small class="text-muted">Select brand first, then model, then variant. Variant is optional.</small>
            </fieldset>
            @endif

            {{-- Core Specs --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Core Specifications</legend>
                <div class="row">
                    <div class="col-md-3">
                        <label>Body Type</label>
                        <select wire:model="body_type_id" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($bodyTypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('body_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Engine Type</label>
                        <select wire:model="engine_type_id" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($engineTypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('engine_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Transmission</label>
                        <select wire:model="transmission_type_id" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($transmissionTypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('transmission_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Drive Type</label>
                        <select wire:model="drive_type_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($driveTypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('drive_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Performance --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Performance & Capacity</legend>
                <div class="row">
                    <div class="col-md-3">
                        <label>Horsepower (HP)</label>
                        <input type="number" wire:model="horsepower" class="form-control" min="0" placeholder="e.g. 150">
                        @error('horsepower') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Torque (Nm)</label>
                        <input type="number" wire:model="torque" class="form-control" min="0" placeholder="e.g. 320">
                        @error('torque') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Fuel Capacity (Liters)</label>
                        <input type="number" wire:model="fuel_capacity" class="form-control" min="0" step="0.1" placeholder="e.g. 55">
                        @error('fuel_capacity') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Fuel Efficiency (km/L)</label>
                        <input type="number" wire:model="fuel_efficiency" class="form-control" min="0" step="0.1" placeholder="e.g. 14.5">
                        @error('fuel_efficiency') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Interior --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Interior & Layout</legend>
                <div class="row">
                    <div class="col-md-2">
                        <label>Seats</label>
                        <input type="number" wire:model="seats" class="form-control" min="1" max="20">
                        @error('seats') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-2">
                        <label>Doors</label>
                        <input type="number" wire:model="doors" class="form-control" min="1" max="6">
                        @error('doors') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label>Steering Position</label>
                        <select wire:model="steering_position" class="form-control">
                            <option value="">Select</option>
                            <option value="LEFT">Left-Hand Drive</option>
                            <option value="RIGHT">Right-Hand Drive</option>
                        </select>
                        @error('steering_position') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label>Color</label>
                        <div class="input-group my-colorpicker2">
                            <input type="text" wire:model="color" class="form-control" placeholder="Pick color (HEX)">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-square"></i></span>
                            </div>
                        </div>
                        @error('color') <span class="text-danger">{{ $message }}</span> @enderror
                        <small class="text-muted">Example: Black, Pearl White, Metallic Blue</small>
                    </div>
                </div>
            </fieldset>

            {{-- Production --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Production</legend>
                <div class="row">
                    <div class="col-md-3">
                        <label>Production Start Year</label>
                        <input type="number" wire:model="production_start" class="form-control" min="1950" max="{{ date('Y') }}">
                        @error('production_start') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Production End Year</label>
                        <input type="number" wire:model="production_end" class="form-control" min="1950" max="{{ date('Y') + 2 }}">
                        @error('production_end') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </fieldset>

            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Save Specification
            </button>
        </form>
    </div>
</div>
