<div>
    <form wire:submit.prevent="save">
        <div class="row">
            {{-- Left Column: Comprehensive Form --}}
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-primary"><i class="fa fa-edit me-2"></i>Edit Technical Specifications</h5>
                    </div>
                    <div class="card-body">
                        
                        {{-- 1. Vehicle Identity Section --}}
                        <div class="bg-light p-3 rounded mb-4">
                            <h6 class="fw-bold mb-3 text-uppercase small text-muted">Vehicle Identity</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Brand</label>
                                    <select wire:model.live="brand_id" class="form-select shadow-sm">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Model</label>
                                    <select wire:model.live="vehicle_model_id" class="form-select shadow-sm @error('vehicle_model_id') is-invalid @enderror">
                                        <option value="">Select Model</option>
                                        @foreach($vehicleModels as $model)
                                            <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Variant (Optional)</label>
                                    <select wire:model.live="variant_id" class="form-select shadow-sm">
                                        <option value="">Base Configuration</option>
                                        @foreach($filteredVariants as $variant)
                                            <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Core Technical Specs --}}
                        <h6 class="fw-bold mb-3 text-uppercase small text-muted">Core Engine & Drivetrain</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label small">Body Type</label>
                                <select wire:model="body_type_id" class="form-select shadow-sm">
                                    <option value="">Select...</option>
                                    @foreach($bodyTypes as $type) <option value="{{ $type->id }}">{{ $type->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Transmission</label>
                                <select wire:model="transmission_type_id" class="form-select shadow-sm">
                                    <option value="">Select...</option>
                                    @foreach($transmissionTypes as $type) <option value="{{ $type->id }}">{{ $type->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Fuel Type</label>
                                <select wire:model="engine_type_id" class="form-select shadow-sm">
                                    <option value="">Select...</option>
                                    @foreach($engineTypes as $type) <option value="{{ $type->id }}">{{ $type->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Drive Type (AWD/FWD/RWD)</label>
                                <select wire:model="drive_type_id" class="form-select shadow-sm">
                                    <option value="">Select...</option>
                                    @foreach($driveTypes as $type) <option value="{{ $type->id }}">{{ $type->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Engine Displacement</label>
                                <select wire:model="engine_displacement_id" class="form-select shadow-sm">
                                    <option value="">Select...</option>
                                    @foreach($engineDisplacements as $ed) <option value="{{ $ed->id }}">{{ $ed->name }}</option> @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- 3. Performance & Efficiency --}}
                        <h6 class="fw-bold mb-3 text-uppercase small text-muted">Performance & Capacity</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label small">Horsepower</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" wire:model="horsepower" class="form-control shadow-sm">
                                    <span class="input-group-text">HP</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Torque</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" wire:model="torque" class="form-control shadow-sm">
                                    <span class="input-group-text">Nm</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Fuel Capacity</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" wire:model="fuel_capacity" step="0.1" class="form-control shadow-sm">
                                    <span class="input-group-text">L</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Efficiency</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" wire:model="fuel_efficiency" step="0.1" class="form-control shadow-sm">
                                    <span class="input-group-text">km/L</span>
                                </div>
                            </div>
                        </div>

                        {{-- 4. Layout & Production --}}
                        <h6 class="fw-bold mb-3 text-uppercase small text-muted">Interior & Production</h6>
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label small">Seats</label>
                                <input type="number" wire:model="seats" class="form-control form-control-sm shadow-sm">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">Doors</label>
                                <input type="number" wire:model="doors" class="form-control form-control-sm shadow-sm">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Steering</label>
                                <select wire:model="steering_position" class="form-select form-select-sm shadow-sm">
                                    <option value="LEFT">Left-Hand Drive</option>
                                    <option value="RIGHT">Right-Hand Drive</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Prod. Year</label>
                                <input type="number" wire:model="production_year" class="form-control form-control-sm shadow-sm">
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-white py-3 d-flex justify-content-between">
                        <a href="{{ route('admin.specifications.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow">
                            <i class="fa fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right Column: Preview (Unchanged from previous logic) --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header bg-dark text-white py-3">
                        <h6 class="mb-0 small text-uppercase fw-bold">Live Preview</h6>
                    </div>
                    <div class="card-body text-center py-4">
                        <div class="mb-3 position-relative d-inline-block">
                            <div class="rounded-circle border border-4 border-white shadow" 
                                 style="width: 80px; height: 80px; background-color: {{ $color ?: '#cccccc' }}; transition: 0.3s;">
                            </div>
                        </div>
                        
                        <h4 class="fw-bold mb-0 text-dark">
                            {{ $brand_id ? $brands->firstWhere('id', $brand_id)?->brand_name : 'Brand' }}
                        </h4>
                        <h5 class="text-primary fw-normal">
                            {{ $vehicle_model_id ? (\App\Models\VehicleModel::find($vehicle_model_id)?->model_name) : 'Model' }}
                        </h5>
                        <div class="badge bg-secondary mb-3">
                            {{ $variant_id ? (\App\Models\Variant::find($variant_id)?->name) : 'Base Config' }}
                        </div>

                        <div class="row g-2 text-start small">
                            <div class="col-6">
                                <div class="bg-light p-2 rounded">
                                    <span class="text-muted d-block small">Power</span>
                                    <span class="fw-bold">{{ $horsepower ?: '--' }} HP</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-2 rounded">
                                    <span class="text-muted d-block small">Layout</span>
                                    <span class="fw-bold">{{ $seats ?: '-' }}S / {{ $doors ?: '-' }}D</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input" type="checkbox" wire:model="status" id="statusSwitch">
                                <label class="form-check-label fw-bold" for="statusSwitch">Active Status</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>