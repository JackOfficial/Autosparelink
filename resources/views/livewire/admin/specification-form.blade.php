<div>
    {{-- Global Error Alerts --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-triangle mr-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-lg border-0 overflow-hidden">
        {{-- Header --}}
        <div class="card-header bg-gradient-primary text-white p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1" style="opacity: 0.8; letter-spacing: 1px;">New Specification Entry</h6>
                    <h3 class="font-weight-bold mb-0">
                        <i class="fas fa-car-side mr-2"></i> {{ $this->generatedName }}
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
            {{-- Flash Messages --}}
            @if (session()->has('error')) <div class="alert alert-danger shadow-sm border-0">{{ session('error') }}</div> @endif
            @if (session()->has('success')) <div class="alert alert-success shadow-sm border-0">{{ session('success') }}</div> @endif

            <form wire:submit.prevent="save">
                {{-- IDENTITY SECTION (Brand, Model, Year, Trim, Region, Chassis, Timeline) --}}
                <div class="bg-white p-4 rounded shadow-sm mb-4">
                    <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">
                        <i class="fas fa-id-card mr-2"></i> Identity & Market
                    </h5>
                    
                    <div class="row">
                        @if(!$hideBrandModel)
                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold text-muted">Brand *</label>
                                <select wire:model.live="brand_id" class="form-control shadow-none">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand) <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option> @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold text-muted">Vehicle Model *</label>
                                <select wire:model.live="vehicle_model_id" class="form-control shadow-none" @disabled(!$brand_id)>
                                    <option value="">Select Model</option>
                                    @foreach($this->vehicleModels as $model) <option value="{{ $model->id }}">{{ $model->model_name }}</option> @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Trim Level *</label>
                            <input type="text" wire:model.live="trim_level" class="form-control shadow-none" placeholder="e.g. AMG Line">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Production Year *</label>
                            <input type="number" wire:model.live="production_year" class="form-control shadow-none">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Regional Market</label>
                            <select wire:model="destination_id" class="form-control shadow-none">
                                <option value="">Select Market</option>
                                @foreach($destinations as $dest) <option value="{{ $dest->id }}">{{ $dest->region_name ?? $dest->name }}</option> @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Chassis Code</label>
                            <input type="text" wire:model="chassis_code" class="form-control shadow-none" placeholder="e.g. W213">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Model Code</label>
                            <input type="text" wire:model="model_code" class="form-control shadow-none" placeholder="e.g. ZVW30">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Production Timeline (Start / End)</label>
                            <div class="d-flex">
                                <input type="number" wire:model="production_year_start" class="form-control shadow-none mr-1" placeholder="Start">
                                <input type="number" wire:model="production_year_end" class="form-control shadow-none" placeholder="End">
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
            {{-- Fuel Type --}}
            <div class="col-md-6 mb-3">
                <label class="small font-weight-bold text-muted">Fuel Type *</label>
                <select wire:model.live="engine_type_id" class="form-control shadow-none">
                    <option value="">Select Fuel</option>
                    @foreach($engineTypes as $et) <option value="{{ $et->id }}">{{ $et->name }}</option> @endforeach
                </select>
            </div>

            {{-- Displacement --}}
            <div class="col-md-6 mb-3">
                <label class="small font-weight-bold text-muted">Displacement *</label>
                <select wire:model.live="engine_displacement_id" class="form-control shadow-none">
                    <option value="">Select CC</option>
                    @foreach($engineDisplacements as $ed) <option value="{{ $ed->id }}">{{ $ed->name }}</option> @endforeach
                </select>
            </div>

            {{-- Horse Power --}}
            <div class="col-md-4 mb-3">
                <label class="small font-weight-bold text-muted">Power</label>
                <div class="input-group">
                    <input type="number" wire:model="horsepower" class="form-control shadow-none">
                    <div class="input-group-append">
                        <span class="input-group-text bg-light small text-muted">HP</span>
                    </div>
                </div>
            </div>

            {{-- Torque --}}
            <div class="col-md-4 mb-3">
                <label class="small font-weight-bold text-muted">Torque</label>
                <div class="input-group">
                    <input type="number" wire:model="torque" class="form-control shadow-none">
                    <div class="input-group-append">
                        <span class="input-group-text bg-light small text-muted">Nm</span>
                    </div>
                </div>
            </div>

            {{-- Efficiency --}}
            <div class="col-md-4 mb-3">
                <label class="small font-weight-bold text-muted">Efficiency</label>
                <div class="input-group">
                    <input type="number" step="0.1" wire:model="fuel_efficiency" class="form-control shadow-none" placeholder="0.0">
                    <div class="input-group-append">
                        <span class="input-group-text bg-light small text-muted">L/100k</span>
                    </div>
                </div>
            </div>

            {{-- Fuel Tank --}}
            <div class="col-md-12">
                <label class="small font-weight-bold text-muted">Fuel Tank Capacity</label>
                <div class="input-group">
                    <input type="number" wire:model="fuel_capacity" class="form-control shadow-none">
                    <div class="input-group-append">
                        <span class="input-group-text bg-light small text-muted">Liters</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

                    {{-- DRIVETRAIN & INTERIOR --}}
                    <div class="col-md-6 mb-4">
                        <div class="bg-white p-4 rounded shadow-sm h-100">
                            <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">
                                <i class="fas fa-cogs mr-2"></i> Drivetrain & Layout
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Transmission *</label>
                                    <select wire:model.live="transmission_type_id" class="form-control shadow-none">
                                        <option value="">Select Type</option>
                                        @foreach($transmissionTypes as $tt) <option value="{{ $tt->id }}">{{ $tt->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Drive Type (AWD/RWD) *</label>
                                    <select wire:model="drive_type_id" class="form-control shadow-none">
                                        <option value="">Select Drive</option>
                                        @foreach($driveTypes as $dt) <option value="{{ $dt->id }}">{{ $dt->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Body Style *</label>
                                    <select wire:model.live="body_type_id" class="form-control shadow-none">
                                        <option value="">Select Style</option>
                                        @foreach($bodyTypes as $bt) <option value="{{ $bt->id }}">{{ $bt->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="small font-weight-bold text-muted">Seats</label>
                                    <input type="number" wire:model="seats" class="form-control shadow-none">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="small font-weight-bold text-muted">Doors</label>
                                    <input type="number" wire:model="doors" class="form-control shadow-none">
                                </div>
                                <div class="col-md-12">
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

                <div class="text-right">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill">
                        <i class="fas fa-save mr-2"></i> Save Full Specification
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .bg-gradient-primary { background: linear-gradient(45deg, #4e73df 0%, #224abe 100%); }
        .bg-light-gray { background-color: #f8f9fc; }
        .form-control { border-color: #d1d3e2; font-size: 0.9rem; }
        .form-control:focus { border-color: #4e73df; box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1); }
        .alert { border-radius: 0.75rem; }
    </style>
</div>