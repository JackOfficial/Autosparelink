<div>
    <form wire:submit.prevent="save">
        <div class="row">
            {{-- Left Column: Organized Form --}}
            <div class="col-md-8">
                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title fw-bold"><i class="fas fa-edit me-2"></i>Edit Specification</h3>
                    </div>
                    <div class="card-body">

                        {{-- ================= Vehicle Selection ================= --}}
                        <fieldset class="border p-3 mb-4 rounded">
                            <legend class="w-auto px-2 small fw-bold text-primary text-uppercase">Vehicle Selection</legend>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Brand <span class="text-danger">*</span></label>
                                    <select wire:model.live="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Vehicle Model <span class="text-danger">*</span></label>
                                    <select wire:model.live="vehicle_model_id" class="form-select @error('vehicle_model_id') is-invalid @enderror">
                                        <option value="">Select Model</option>
                                        @foreach($vehicleModels as $model)
                                            <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_model_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Variant</label>
                                    <select wire:model.live="variant_id" class="form-select">
                                        <option value="">Select Variant (optional)</option>
                                        @foreach($filteredVariants as $variant)
                                            <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        {{-- ================= Core Specifications ================= --}}
                        <fieldset class="border p-3 mb-4 rounded">
                            <legend class="w-auto px-2 small fw-bold text-primary text-uppercase">Core Specifications</legend>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Body Type</label>
                                    <select wire:model="body_type_id" class="form-select @error('body_type_id') is-invalid @enderror">
                                        <option value="">Select</option>
                                        @foreach($bodyTypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Fuel Type</label>
                                    <select wire:model="engine_type_id" class="form-select @error('engine_type_id') is-invalid @enderror">
                                        <option value="">Select</option>
                                        @foreach($engineTypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Transmission</label>
                                    <select wire:model="transmission_type_id" class="form-select">
                                        <option value="">Select</option>
                                        @foreach($transmissionTypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Drive Type</label>
                                    <select wire:model="drive_type_id" class="form-select">
                                        <option value="">Select</option>
                                        @foreach($driveTypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Engine Displacement</label>
                                    <select wire:model="engine_displacement_id" class="form-select">
                                        <option value="">Select</option>
                                        @foreach($engineDisplacements as $ed)
                                            <option value="{{ $ed->id }}">{{ $ed->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        {{-- ================= Performance & Capacity ================= --}}
                        <fieldset class="border p-3 mb-4 rounded">
                            <legend class="w-auto px-2 small fw-bold text-primary text-uppercase">Performance & Capacity</legend>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Horsepower (HP)</label>
                                    <input type="number" wire:model="horsepower" class="form-control" placeholder="e.g. 150">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Torque (Nm)</label>
                                    <input type="number" wire:model="torque" class="form-control" placeholder="e.g. 320">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Fuel Capacity (L)</label>
                                    <input type="number" wire:model="fuel_capacity" step="0.1" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Efficiency (km/L)</label>
                                    <input type="number" wire:model="fuel_efficiency" step="0.1" class="form-control">
                                </div>
                            </div>
                        </fieldset>

                        {{-- ================= Interior & Production ================= --}}
                        <fieldset class="border p-3 rounded">
                            <legend class="w-auto px-2 small fw-bold text-primary text-uppercase">Interior & Production</legend>
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label">Seats</label>
                                    <input type="number" wire:model="seats" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Doors</label>
                                    <input type="number" wire:model="doors" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Steering Position</label>
                                    <select wire:model="steering_position" class="form-select">
                                        <option value="LEFT">Left-Hand Drive</option>
                                        <option value="RIGHT">Right-Hand Drive</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Color</label>
                                    <div class="input-group">
                                        <input type="color" wire:model.live="color" class="form-control form-control-color" style="width: 20%;">
                                        <input type="text" wire:model="color" class="form-control" style="width: 80%;">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Production Year</label>
                                    <input type="number" wire:model="production_year" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Start Year</label>
                                    <input type="number" wire:model="production_start" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">End Year</label>
                                    <input type="number" wire:model="production_end" class="form-control">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="card-footer bg-light">
                        <button type="submit" class="btn btn-primary shadow-sm">
                            <i class="fas fa-save me-1"></i> Update Specification
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right Column: Preview --}}
            <div class="col-md-4">
                <div class="sticky-top" style="top: 20px;">
                    <div class="card shadow-sm border-0 bg-dark text-white">
                        <div class="card-body text-center py-4">
                            <div class="rounded-circle mx-auto mb-3 border border-4 border-secondary shadow" 
                                 style="width: 70px; height: 70px; background-color: {{ $color ?: '#555' }};">
                            </div>
                            <h5 class="fw-bold text-uppercase mb-1">
                                {{ $brand_id ? $brands->firstWhere('id', $brand_id)->brand_name : 'Select Brand' }}
                            </h5>
                            <h6 class="text-info">
                                {{ $vehicle_model_id ? (\App\Models\VehicleModel::find($vehicle_model_id)?->model_name) : 'Model' }}
                            </h6>
                            <p class="small text-muted mb-3">
                                {{ $variant_id ? (\App\Models\Variant::find($variant_id)?->name) : 'Base' }}
                            </p>
                            <hr class="bg-secondary">
                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted d-block">Power</small>
                                    <span class="fw-bold">{{ $horsepower ?: '--' }} HP</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Seats</small>
                                    <span class="fw-bold">{{ $seats ?: '--' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>