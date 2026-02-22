<div>
    <section class="content">
        <form wire:submit.prevent="save">
            <div class="row">
                {{-- Left Column: Form Fields --}}
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold text-primary mb-0">
                                <i class="fas fa-car mr-2"></i> Edit Technical Specification
                            </h3>
                            <a href="{{ route('admin.specifications.index') }}" class="btn btn-light btn-sm border">
                                <i class="fas fa-arrow-left mr-1"></i> Back
                            </a>
                        </div>

                        <div class="card-body">
                            @if (session()->has('error'))
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                                </div>
                            @endif

                            {{-- 1. Vehicle & Identity --}}
                            <div class="bg-light p-3 rounded mb-4 border">
                                <h6 class="text-uppercase text-muted font-weight-bold small mb-3">
                                    <i class="fas fa-id-card mr-1"></i> Vehicle & Identity
                                </h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Brand *</label>
                                        <select wire:model.live="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                                            <option value="">Select Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Model *</label>
                                        <select wire:model.live="vehicle_model_id" class="form-control @error('vehicle_model_id') is-invalid @enderror">
                                            <option value="">Select Model</option>
                                            @foreach($this->vehicleModels as $model)
                                                <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Trim Level</label>
                                        <input type="text" wire:model="trim_level" class="form-control" placeholder="e.g. XL, Premium">
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Chassis Code</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white text-muted">#</span>
                                            </div>
                                            <input type="text" wire:model="chassis_code" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Model Code</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white text-muted">#</span>
                                            </div>
                                            <input type="text" wire:model="model_code" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Destination Market</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white"><i class="fas fa-globe text-muted"></i></span>
                                            </div>
                                            <select wire:model="destination_id" class="form-control">
                                                <option value="">Select Region</option>
                                                @foreach($destinations as $dest)
                                                    <option value="{{ $dest->id }}">{{ $dest->region_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. Engine & Drivetrain --}}
                            <div class="bg-white p-3 rounded mb-4 border shadow-sm">
                                <h6 class="text-uppercase text-primary font-weight-bold small mb-3">
                                    <i class="fas fa-cogs mr-1"></i> Engine & Drivetrain
                                </h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Body Type</label>
                                        <select wire:model="body_type_id" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($bodyTypes as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Engine Type (Fuel)</label>
                                        <select wire:model="engine_type_id" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($engineTypes as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Displacement</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white"><i class="fas fa-arrows-alt-h text-muted small"></i></span>
                                            </div>
                                            <select wire:model="engine_displacement_id" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($engineDisplacements as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold text-primary">Horsepower</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white"><i class="fas fa-tachometer-alt text-warning small"></i></span>
                                            </div>
                                            <input type="number" wire:model="horsepower" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold text-primary">Torque</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white"><i class="fas fa-redo text-muted small"></i></span>
                                            </div>
                                            <input type="number" wire:model="torque" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold">Fuel Capacity</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white"><i class="fas fa-gas-pump text-muted small"></i></span>
                                            </div>
                                            <input type="number" wire:model="fuel_capacity" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold">Efficiency</label>
                                        <div class="input-group">
                                            <input type="text" wire:model="fuel_efficiency" class="form-control">
                                            <div class="input-group-append">
                                                <span class="input-group-text bg-light font-weight-bold small">L/100km</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. Practicality --}}
                            <div class="bg-light p-3 rounded mb-4 border">
                                <h6 class="text-uppercase text-muted font-weight-bold small mb-3">
                                    <i class="fas fa-users mr-1"></i> Practicality
                                </h6>
                                <div class="row align-items-end">
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold">Seats <i class="fa fa-chair small"></i></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white"><i class="fas fa-chair text-muted small"></i></span>
                                            </div>
                                            <input type="number" wire:model="seats" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold">Doors</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white"><i class="fas fa-door-closed text-muted small"></i></span>
                                            </div>
                                            <input type="number" wire:model="doors" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold">Visibility Status</label>
                                        <select wire:model="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Draft</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- 4. Production Dates --}}
                            <div class="bg-white p-3 rounded border mb-4 shadow-sm">
                                <h6 class="text-uppercase text-muted font-weight-bold small mb-3">
                                    <i class="fas fa-calendar-alt mr-1"></i> Production Lifecycle
                                </h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3 border-right">
                                        <label class="small font-weight-bold">Variant/Model Year *</label>
                                        <input type="number" wire:model="production_year" class="form-control @error('production_year') is-invalid @enderror">
                                    </div>
                                    <div class="col-md-4 mb-3 border-right">
                                        <label class="small font-weight-bold">Production Start</label>
                                        <div class="d-flex">
                                            <input type="number" wire:model="start_year" class="form-control mr-2 @error('start_year') is-invalid @enderror" placeholder="YYYY">
                                            <select wire:model="start_month" class="form-control">
                                                <option value="">Month</option>
                                                @for($m=1; $m<=12; $m++) <option value="{{ $m }}">{{ date('F', mktime(0,0,0,$m,1)) }}</option> @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Production End</label>
                                        <div class="d-flex">
                                            <input type="number" wire:model="end_year" class="form-control mr-2" placeholder="YYYY">
                                            <select wire:model="end_month" class="form-control">
                                                <option value="">Month</option>
                                                @for($m=1; $m<=12; $m++) <option value="{{ $m }}">{{ date('F', mktime(0,0,0,$m,1)) }}</option> @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm" wire:loading.attr="disabled">
                                    <span wire:loading.remove><i class="fas fa-save mr-2"></i> Update Specification</span>
                                    <span wire:loading><i class="fas fa-spinner fa-spin mr-2"></i> Saving...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Live Preview --}}
                <div class="col-md-4">
                    <div class="sticky-top" style="top: 20px;">
                        <div class="card shadow border-0">
                            <div class="card-header bg-dark text-white font-weight-bold">
                                <i class="fas fa-eye mr-2"></i> Live Preview
                            </div>
                            <div class="card-body text-center py-4">
                                <div class="rounded-circle mx-auto mb-3 border shadow-sm" 
                                     style="width: 70px; height: 70px; background-color: {{ $color ?: '#eee' }}; border: 4px solid white !important;">
                                </div>
                                <h5 class="mb-0 font-weight-bold">
                                    {{ $brands->firstWhere('id', $brand_id)?->brand_name ?? 'Brand' }}
                                </h5>
                                <p class="text-primary mb-2">
                                    {{ collect($this->vehicleModels)->firstWhere('id', $vehicle_model_id)['model_name'] ?? 'Model' }}
                                </p>
                                <span class="badge badge-secondary mb-3">{{ $trim_level ?: 'Base Trim' }}</span>
                                
                                <div class="bg-light rounded p-3 text-left">
                                    <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                        <span class="text-muted small"><i class="fas fa-leaf mr-1"></i> Efficiency</span>
                                        <span class="font-weight-bold small text-success">{{ $fuel_efficiency ? $fuel_efficiency . ' L/100km' : '---' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                        <span class="text-muted small"><i class="fas fa-tachometer-alt mr-1"></i> Power</span>
                                        <span class="font-weight-bold small">{{ $horsepower ? $horsepower . ' HP' : '---' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                        <span class="text-muted small"><i class="fas fa-redo mr-1"></i> Torque</span>
                                        <span class="font-weight-bold small">{{ $torque ? $torque . ' Nm' : '---' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted small"><i class="fas fa-calendar mr-1"></i> Year</span>
                                        <span class="font-weight-bold small">{{ $production_year ?: '----' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>