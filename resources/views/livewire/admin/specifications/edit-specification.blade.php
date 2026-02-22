<div>
    <section class="content">
        <form wire:submit.prevent="save">
            <div class="row">
                {{-- Left Column: Refined Form --}}
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        {{-- Header --}}
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold text-primary mb-0">
                                <i class="fas fa-tools mr-2"></i> Edit Technical Specification
                            </h3>
                            <a href="{{ route('admin.specifications.index') }}" class="btn btn-light btn-sm border">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                        </div>

                        <div class="card-body">
                            {{-- Refined Error Alert --}}
                            @if ($errors->any())
                                <div class="alert alert-custom bg-danger-light text-danger border-0 mb-4 shadow-sm">
                                    <div class="d-flex">
                                        <i class="fas fa-exclamation-circle mt-1 mr-3"></i>
                                        <div>
                                            <span class="font-weight-bold">Validation Errors:</span>
                                            <ul class="mb-0 small ml-n3">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Vehicle Selection --}}
                            <div class="bg-light p-3 rounded mb-4 border shadow-none">
                                <h6 class="text-uppercase text-muted font-weight-bold small mb-3">Vehicle Selection</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold text-muted">Brand <span class="text-danger">*</span></label>
                                        <select wire:model.live="brand_id" class="form-control shadow-none @error('brand_id') is-invalid @enderror">
                                            <option value="">Select Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('brand_id') <span class="text-danger extra-small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold text-muted">Vehicle Model <span class="text-danger">*</span></label>
                                        <select wire:model.live="vehicle_model_id" wire:loading.attr="disabled" wire:target="brand_id" class="form-control shadow-none @error('vehicle_model_id') is-invalid @enderror">
                                            <option value="">Select Model</option>
                                            @foreach($this->vehicleModels as $model)
                                                <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('vehicle_model_id') <span class="text-danger extra-small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold text-muted">Trim Level</label>
                                        <input type="text" wire:model="trim_level" class="form-control shadow-none" placeholder="e.g. XL, Premium">
                                        <small class="text-muted extra-small">Example: <strong>Lariat or SE</strong></small>
                                    </div>
                                </div>
                            </div>

                            {{-- Core Specifications --}}
                            <div class="bg-white p-3 rounded mb-4 border shadow-sm">
                                <h6 class="text-uppercase text-primary font-weight-bold small mb-3">Core Specifications</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold text-muted">Body Type</label>
                                        <select wire:model="body_type_id" class="form-control shadow-none">
                                            <option value="">Select</option>
                                            @foreach($bodyTypes as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold text-muted">Fuel Type</label>
                                        <select wire:model="engine_type_id" class="form-control shadow-none">
                                            <option value="">Select</option>
                                            @foreach($engineTypes as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold text-muted">Transmission</label>
                                        <select wire:model="transmission_type_id" class="form-control shadow-none">
                                            <option value="">Select</option>
                                            @foreach($transmissionTypes as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Drive Type</label>
                                        <select wire:model="drive_type_id" class="form-control shadow-none">
                                            <option value="">Select</option>
                                            @foreach($driveTypes as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Engine Displacement</label>
                                        <select wire:model="engine_displacement_id" class="form-control shadow-none">
                                            <option value="">Select</option>
                                            @foreach($engineDisplacements as $ed)
                                                <option value="{{ $ed->id }}">{{ $ed->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Performance & Capacity --}}
                            <div class="bg-light p-3 rounded mb-4 border shadow-none">
                                <h6 class="text-uppercase text-muted font-weight-bold small mb-3">Performance & Capacity</h6>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold text-muted">Horsepower (HP)</label>
                                        <input type="number" wire:model="horsepower" class="form-control shadow-none">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold text-muted">Torque (Nm)</label>
                                        <input type="number" wire:model="torque" class="form-control shadow-none">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold text-muted">Fuel Capacity (L)</label>
                                        <input type="number" wire:model="fuel_capacity" step="0.1" class="form-control shadow-none">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold text-muted">Efficiency (km/L)</label>
                                        <input type="number" wire:model="fuel_efficiency" step="0.1" class="form-control shadow-none">
                                    </div>
                                </div>
                            </div>

                            {{-- Interior & Production --}}
                            <div class="bg-white p-3 rounded border shadow-sm mb-4">
                                <h6 class="text-uppercase text-primary font-weight-bold small mb-3">Interior & Production</h6>
                                <div class="row">
                                    <div class="col-md-2 mb-3">
                                        <label class="small font-weight-bold text-muted">Seats</label>
                                        <input type="number" wire:model="seats" class="form-control shadow-none">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="small font-weight-bold text-muted">Doors</label>
                                        <input type="number" wire:model="doors" class="form-control shadow-none">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold text-muted">Steering</label>
                                        <select wire:model="steering_position" class="form-control shadow-none">
                                            <option value="LEFT">Left-Hand Drive</option>
                                            <option value="RIGHT">Right-Hand Drive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold text-muted">Color</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <input type="color" wire:model.live="color" class="form-control p-1" style="width: 45px; height: 38px;">
                                            </div>
                                            <input type="text" wire:model="color" class="form-control shadow-none" placeholder="#000000">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold text-muted">Prod. Year</label>
                                        <input type="number" wire:model="production_year" class="form-control shadow-none">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold text-muted">Start Year</label>
                                        <input type="number" wire:model="production_year_start" class="form-control shadow-none">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold text-muted">End Year</label>
                                        <input type="number" wire:model="production_year_end" class="form-control shadow-none">
                                    </div>
                                </div>
                            </div>

                            {{-- Action Bar --}}
                            <div class="d-flex justify-content-end align-items-center bg-light p-3 rounded border">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm px-5" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="save">
                                        <i class="fas fa-save mr-2"></i> Update Specification
                                    </span>
                                    <span wire:loading wire:target="save">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> Processing...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Preview --}}
                <div class="col-md-4">
                    <div class="sticky-top" style="top: 20px; z-index: 10;">
                        <div class="card shadow border-0">
                            <div class="card-header bg-dark">
                                <h3 class="card-title font-weight-bold small text-uppercase mb-0">Live Preview</h3>
                            </div>
                            <div class="card-body text-center py-5">
                                <div class="rounded-circle mx-auto mb-4 border shadow-sm" 
                                     style="width: 80px; height: 80px; background-color: {{ $color ?: '#dee2e6' }}; transition: background-color 0.3s; border: 4px solid white !important;">
                                </div>
                                
                                <h4 class="font-weight-bold text-dark mb-1">
                                    @php $currentBrand = $brands->where('id', $brand_id)->first(); @endphp
                                    {{ $currentBrand ? $currentBrand->brand_name : 'Select Brand' }}
                                </h4>
                                
                                <h5 class="text-primary mb-3">
                                    @php $currentModel = collect($vehicleModels)->where('id', $vehicle_model_id)->first(); @endphp
                                    {{ $currentModel ? $currentModel['model_name'] : 'Select Model' }}
                                </h5>

                                <div class="badge badge-pill badge-light border px-4 py-2 text-uppercase mb-4" style="letter-spacing: 1px;">
                                    {{ $trim_level ?: 'Base Trim' }}
                                </div>

                                <hr>

                                <div class="row text-left small mt-4">
                                    <div class="col-6 mb-2">
                                        <span class="text-muted d-block text-uppercase extra-small">Transmission</span>
                                        <strong class="text-dark">
                                            @php $trans = $transmissionTypes->where('id', $transmission_type_id)->first(); @endphp
                                            {{ $trans ? $trans->name : 'N/A' }}
                                        </strong>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <span class="text-muted d-block text-uppercase extra-small">Fuel</span>
                                        <strong class="text-dark">
                                            @php $eng = $engineTypes->where('id', $engine_type_id)->first(); @endphp
                                            {{ $eng ? $eng->name : 'N/A' }}
                                        </strong>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block text-uppercase extra-small">Horsepower</span>
                                        <strong class="text-dark">{{ $horsepower ?: '0' }} HP</strong>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block text-uppercase extra-small">Year</span>
                                        <strong class="text-dark">{{ $production_year ?: 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top text-center">
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Preview updates as you type</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>