<div>
    <form wire:submit.prevent="save">
        <div class="row">
            {{-- Left Column: Form Fields --}}
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold">Edit Technical Specifications</h3>
                    </div>
                    <div class="card-body">
                        
                        {{-- 1. Vehicle Selection --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Brand</label>
                                <select wire:model.live="brand_id" class="form-select">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Model</label>
                                <select wire:model.live="vehicle_model_id" class="form-select @error('vehicle_model_id') is-invalid @enderror">
                                    <option value="">Select Model</option>
                                    @foreach($vehicleModels as $model)
                                        <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Variant (Optional)</label>
                                <select wire:model.live="variant_id" class="form-select">
                                    <option value="">Base Configuration</option>
                                    @foreach($filteredVariants as $variant)
                                        <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr>

                        {{-- 2. Core Specs --}}
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Body Type</label>
                                <select wire:model="body_type_id" class="form-select">
                                    <option value="">Select...</option>
                                    @foreach($bodyTypes as $type) <option value="{{ $type->id }}">{{ $type->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Transmission</label>
                                <select wire:model="transmission_type_id" class="form-select">
                                    <option value="">Select...</option>
                                    @foreach($transmissionTypes as $type) <option value="{{ $type->id }}">{{ $type->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fuel Type</label>
                                <select wire:model="engine_type_id" class="form-select">
                                    <option value="">Select...</option>
                                    @foreach($engineTypes as $type) <option value="{{ $type->id }}">{{ $type->name }}</option> @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- 3. Performance --}}
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <label class="form-label">Horsepower (HP)</label>
                                <input type="number" wire:model="horsepower" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Torque (Nm)</label>
                                <input type="number" wire:model="torque" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Seats</label>
                                <input type="number" wire:model="seats" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Color Hex</label>
                                <div class="input-group">
                                    <input type="color" wire:model.live="color" class="form-control form-control-color w-25">
                                    <input type="text" wire:model="color" class="form-control w-75">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-light d-flex justify-content-between">
                        <a href="{{ route('admin.specifications.index') }}" class="btn btn-link text-muted">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fa fa-save me-2"></i> Update Specification
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right Column: Identity Preview --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-left-primary sticky-top" style="top: 20px;">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-muted small text-uppercase">Live Preview</h3>
                    </div>
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <div class="rounded-circle mx-auto border shadow-sm" 
                                 style="width: 60px; height: 60px; background-color: {{ $color ?? '#eee' }};">
                            </div>
                        </div>
                        
                        <h4 class="fw-bold mb-1">
                            {{ $brand_id ? $brands->firstWhere('id', $brand_id)->brand_name : 'Select Brand' }}
                        </h4>
                        <h5 class="text-primary mb-0">
                            {{ $vehicle_model_id ? (\App\Models\VehicleModel::find($vehicle_model_id)->model_name ?? '') : 'Select Model' }}
                        </h5>
                        <p class="text-muted">
                            {{ $variant_id ? (\App\Models\Variant::find($variant_id)->name ?? '') : 'Base Variant' }}
                        </p>

                        <div class="row mt-4 g-2">
                            <div class="col-6">
                                <div class="p-2 border rounded bg-light">
                                    <small class="text-muted d-block">Power</small>
                                    <strong>{{ $horsepower ?: '--' }} HP</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 border rounded bg-light">
                                    <small class="text-muted d-block">Seats</small>
                                    <strong>{{ $seats ?: '--' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" wire:model="status" id="statusSwitch">
                            <label class="form-check-label" for="statusSwitch">Visible on Website</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>