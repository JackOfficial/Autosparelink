<div>
    {{-- Global Error Alerts --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm border-0 mb-4">
            <div class="d-flex">
                <i class="fas fa-exclamation-circle mt-1 mr-3"></i>
                <div>
                    <span class="font-weight-bold">Please correct the following:</span>
                    <ul class="mb-0 mt-1 small">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="card shadow-lg border-0 overflow-hidden">
        {{-- Header --}}
        <div class="card-header bg-gradient-primary text-white p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1" style="opacity: 0.8; letter-spacing: 1px;">New Specification Entry</h6>
                    <h3 class="font-weight-bold mb-0">
                        <i class="fas fa-car-side mr-2"></i> {{ $this->generatedName ?: 'Vehicle Specification' }}
                    </h3>
                </div>
                <div class="text-right">
                    <span class="badge badge-light px-3 py-2 shadow-sm rounded-pill text-primary">
                        <i class="fas fa-sync-alt fa-spin mr-1"></i> Live Preview
                    </span>
                </div>
            </div>
        </div>
        
        <div class="card-body bg-light-gray p-4">
            @if (session()->has('error')) <div class="alert alert-danger shadow-sm border-0">{{ session('error') }}</div> @endif
            @if (session()->has('success')) <div class="alert alert-success shadow-sm border-0">{{ session('success') }}</div> @endif

            <form wire:submit.prevent="save">
                {{-- IDENTITY SECTION --}}
                <div class="bg-white p-4 rounded shadow-sm mb-4">
                    <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">
                        <i class="fas fa-id-card mr-2"></i> Identity & Market
                    </h5>
                    
                    <div class="row">
                        @if(!$hideBrandModel)
                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold text-muted">Brand *</label>
                                <select wire:model.live="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand) <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option> @endforeach
                                </select>
                                @error('brand_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold text-muted">Vehicle Model *</label>
                                <select wire:model.live="vehicle_model_id" class="form-control @error('vehicle_model_id') is-invalid @enderror" @disabled(!$brand_id)>
                                    <option value="">Select Model</option>
                                    @foreach($this->vehicleModels as $model) <option value="{{ $model->id }}">{{ $model->model_name }}</option> @endforeach
                                </select>
                                @error('vehicle_model_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Trim Level *</label>
                            <input type="text" wire:model.live="trim_level" class="form-control @error('trim_level') is-invalid @enderror" placeholder="e.g. AMG Line">
                            @error('trim_level') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Base Production Year *</label>
                            <input type="number" wire:model.live="production_year" class="form-control @error('production_year') is-invalid @enderror">
                            @error('production_year') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Regional Market</label>
                            <select wire:model="destination_id" class="form-control">
                                <option value="">Select Market</option>
                                @foreach($destinations as $dest) <option value="{{ $dest->id }}">{{ $dest->region_name ?? $dest->name }}</option> @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Chassis Code</label>
                            <input type="text" wire:model="chassis_code" class="form-control" placeholder="e.g. W213">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Model Code</label>
                            <input type="text" wire:model="model_code" class="form-control" placeholder="e.g. ZVW30">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Production Timeline</label>
                            <div class="d-flex">
                                <input type="number" wire:model="production_year_start" class="form-control mr-1" placeholder="Start">
                                <input type="text" wire:model="production_year_end" class="form-control" placeholder="End/Present">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- ENGINE SECTION --}}
                    <div class="col-md-6 mb-4">
                        <div class="bg-white p-4 rounded shadow-sm h-100">
                            <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">
                                <i class="fas fa-microchip mr-2"></i> Engine & Power
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Fuel Type *</label>
                                    <select wire:model.live="engine_type_id" class="form-control @error('engine_type_id') is-invalid @enderror">
                                        <option value="">Select Fuel</option>
                                        @foreach($engineTypes as $et) <option value="{{ $et->id }}">{{ $et->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Displacement *</label>
                                    <select wire:model.live="engine_displacement_id" class="form-control @error('engine_displacement_id') is-invalid @enderror">
                                        <option value="">Select CC</option>
                                        @foreach($engineDisplacements as $ed) <option value="{{ $ed->id }}">{{ $ed->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="small font-weight-bold text-muted">Power (HP)</label>
                                    <input type="number" wire:model="horsepower" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="small font-weight-bold text-muted">Torque (Nm)</label>
                                    <input type="number" wire:model="torque" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="small font-weight-bold text-muted">Efficiency (L/100k)</label>
                                    <input type="number" step="0.1" wire:model="fuel_efficiency" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DRIVETRAIN SECTION --}}
                    <div class="col-md-6 mb-4">
                        <div class="bg-white p-4 rounded shadow-sm h-100">
                            <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">
                                <i class="fas fa-cogs mr-2"></i> Drivetrain & Layout
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Transmission *</label>
                                    <select wire:model.live="transmission_type_id" class="form-control @error('transmission_type_id') is-invalid @enderror">
                                        <option value="">Select Type</option>
                                        @foreach($transmissionTypes as $tt) <option value="{{ $tt->id }}">{{ $tt->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Drive Type *</label>
                                    <select wire:model="drive_type_id" class="form-control @error('drive_type_id') is-invalid @enderror">
                                        <option value="">Select Drive</option>
                                        @foreach($driveTypes as $dt) <option value="{{ $dt->id }}">{{ $dt->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="small font-weight-bold text-muted d-block">Steering Position</label>
                                    <div class="btn-group w-100 shadow-sm">
                                        <button type="button" wire:click="$set('steering_position', 'LEFT')" class="btn {{ $steering_position == 'LEFT' ? 'btn-primary' : 'btn-outline-primary' }} w-50">LHD</button>
                                        <button type="button" wire:click="$set('steering_position', 'RIGHT')" class="btn {{ $steering_position == 'RIGHT' ? 'btn-primary' : 'btn-outline-primary' }} w-50">RHD</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- BODY & DIMENSIONS --}}
                    <div class="col-12 mb-4">
                        <div class="bg-white p-4 rounded shadow-sm">
                            <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">
                                <i class="fas fa-ruler-combined mr-2"></i> Body & Capacity
                            </h5>
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="small font-weight-bold text-muted">Body Type</label>
                                    <select wire:model="body_type_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($bodyTypes as $bt) <option value="{{ $bt->id }}">{{ $bt->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="small font-weight-bold text-muted">Doors</label>
                                    <input type="number" wire:model="doors" class="form-control">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="small font-weight-bold text-muted">Seats</label>
                                    <input type="number" wire:model="seats" class="form-control">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="small font-weight-bold text-muted">Weight (kg)</label>
                                    <input type="number" wire:model="curb_weight" class="form-control">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="small font-weight-bold text-muted">Fuel Tank (L)</label>
                                    <input type="number" wire:model="tank_capacity" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PUBLISHING CONTROLS --}}
                <div class="bg-white p-4 rounded shadow-sm mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="custom-control custom-switch custom-switch-lg">
                                <input type="checkbox" class="custom-control-input" id="statusSwitch" wire:model="status">
                                <label class="custom-control-label font-weight-bold" for="statusSwitch">
                                    {{ $status ? 'Active / Visible' : 'Draft / Hidden' }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="defaultSwitch" wire:model="is_default">
                                <label class="custom-control-label" for="defaultSwitch">Set as Default Variation for this Model</label>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill">
                                <i class="fas fa-save mr-2"></i> Save Specification
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .bg-light-gray { background-color: #f8f9fa; }
        .bg-gradient-primary { background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important; }
        .custom-switch-lg .custom-control-label::before { height: 1.5rem; width: 2.5rem; border-radius: 1rem; }
        .custom-switch-lg .custom-control-label::after { width: calc(1.5rem - 4px); height: calc(1.5rem - 4px); border-radius: 1rem; }
        .custom-switch-lg .custom-control-input:checked ~ .custom-control-label::after { transform: translateX(1rem); }
    </style>
</div>