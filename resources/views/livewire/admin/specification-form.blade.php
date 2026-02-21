<div>
    {{-- resources/views/livewire/admin/specification-form.blade.php --}}
<div> {{-- <--- ADD THIS SINGLE ROOT WRAPPER --}}
    <div class="card shadow-lg border-0 overflow-hidden">
        {{-- Dynamic Header based on Auto-Generated Name --}}
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
            @if (session()->has('error')) <div class="alert alert-danger shadow-sm border-0"><i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}</div> @endif
            @if (session()->has('success')) <div class="alert alert-success shadow-sm border-0"><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</div> @endif

            <form wire:submit.prevent="save">
                {{-- IDENTITY SECTION --}}
                <div class="bg-white p-4 rounded shadow-sm mb-4">
                    <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">
                        <i class="fas fa-id-card mr-2"></i> Identity & Market
                    </h5>
                    
                    <div class="row">
                        @if(!$hideBrandModel)
                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold text-muted">Brand *</label>
                                <div class="input-group shadow-none">
                                    <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-copyright text-muted"></i></span></div>
                                    <select wire:model.live="brand_id" class="form-control border-left-0 shadow-none">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand) <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option> @endforeach
                                    </select>
                                </div>
                                @error('brand_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold text-muted">Vehicle Model *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-car text-muted"></i></span></div>
                                    <select wire:model.live="vehicle_model_id" class="form-control border-left-0 shadow-none" @disabled(!$brand_id)>
                                        <option value="">Select Model</option>
                                        @foreach($this->vehicleModels as $model) <option value="{{ $model->id }}">{{ $model->model_name }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-muted">Trim Level *</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-tags text-muted"></i></span></div>
                                <input type="text" wire:model.live="trim_level" class="form-control border-left-0 shadow-none" placeholder="e.g. AMG Line">
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Marketing Year *</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-calendar-check text-muted"></i></span></div>
                                <input type="number" wire:model.live="production_year" class="form-control border-left-0 shadow-none" placeholder="2024">
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Region</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-globe text-muted"></i></span></div>
                                <select wire:model="destination_id" class="form-control border-left-0 shadow-none">
                                    <option value="">Select Market</option>
                                    @foreach($destinations as $dest) <option value="{{ $dest->id }}">{{ $dest->region_name ?? $dest->name }}</option> @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Chassis Code</label>
                            <input type="text" wire:model="chassis_code" class="form-control shadow-none" placeholder="e.g. W213">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Tech. Timeline</label>
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
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Fuel Type</label>
                                    <select wire:model="engine_type_id" class="form-control shadow-none">
                                        <option value="">Select Fuel</option>
                                        @foreach($engineTypes as $et) <option value="{{ $et->id }}">{{ $et->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Displacement</label>
                                    <select wire:model="engine_displacement_id" class="form-control shadow-none">
                                        <option value="">Select CC</option>
                                        @foreach($engineDisplacements as $ed) <option value="{{ $ed->id }}">{{ $ed->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Horsepower (HP)</label>
                                    <input type="number" wire:model="horsepower" class="form-control shadow-none">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Efficiency (L/100km)</label>
                                    <input type="number" step="0.1" wire:model="fuel_efficiency" class="form-control shadow-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DRIVETRAIN SECTION --}}
                    <div class="col-md-6 mb-4">
                        <div class="bg-white p-4 rounded shadow-sm h-100">
                            <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">
                                <i class="fas fa-cogs mr-2"></i> Transmission & Body
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Transmission</label>
                                    <select wire:model="transmission_type_id" class="form-control shadow-none">
                                        <option value="">Select Type</option>
                                        @foreach($transmissionTypes as $tt) <option value="{{ $tt->id }}">{{ $tt->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Body Type</label>
                                    <select wire:model="body_type_id" class="form-control shadow-none">
                                        <option value="">Select Style</option>
                                        @foreach($bodyTypes as $bt) <option value="{{ $bt->id }}">{{ $bt->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="small font-weight-bold text-muted d-block">Steering Position</label>
                                    <div class="btn-group w-100">
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
                        <i class="fas fa-save mr-2"></i> Save Specification
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .bg-gradient-primary { background: linear-gradient(45deg, #4e73df 0%, #224abe 100%); }
        .bg-light-gray { background-color: #f8f9fc; }
        .input-group-text { border-color: #d1d3e2; border-radius: 0.35rem 0 0 0.35rem; }
        .form-control { border-color: #d1d3e2; }
    </style>
</div> {{-- <--- CLOSE SINGLE ROOT WRAPPER --}}