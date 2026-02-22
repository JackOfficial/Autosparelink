<div>
    <section class="content">
        <form wire:submit.prevent="save">
            <div class="row">
                {{-- Left Column: Form Fields --}}
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h3 class="card-title font-weight-bold text-primary mb-0">
                                <i class="fas fa-edit mr-2"></i> Edit Technical Specification
                            </h3>
                            <a href="{{ route('admin.specifications.index') }}" class="btn btn-light btn-sm border">
                                <i class="fas fa-arrow-left mr-1"></i> Back
                            </a>
                        </div>

                        <div class="card-body">
                            @if (session()->has('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            {{-- 1. Vehicle & Identity --}}
                            <div class="bg-light p-3 rounded mb-4 border">
                                <h6 class="text-uppercase text-muted font-weight-bold small mb-3">Vehicle & Identity</h6>
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
                                        <input type="text" wire:model="chassis_code" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Model Code</label>
                                        <input type="text" wire:model="model_code" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Destination Market</label>
                                        <select wire:model="destination_id" class="form-control">
                                            <option value="">Select Region</option>
                                            @foreach($destinations as $dest)
                                                <option value="{{ $dest->id }}">{{ $dest->region_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. Engine & Drivetrain --}}
                            <div class="bg-white p-3 rounded mb-4 border shadow-sm">
                                <h6 class="text-uppercase text-primary font-weight-bold small mb-3">Engine & Drivetrain</h6>
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
                                        <select wire:model="engine_displacement_id" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($engineDisplacements as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Transmission</label>
                                        <select wire:model="transmission_type_id" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($transmissionTypes as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Drive System</label>
                                        <select wire:model="drive_type_id" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($driveTypes as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small font-weight-bold">Steering Position</label>
                                        <select wire:model="steering_position" class="form-control">
                                            <option value="LEFT">Left Hand Drive</option>
                                            <option value="RIGHT">Right Hand Drive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold">HP</label>
                                        <input type="number" wire:model="horsepower" class="form-control">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold">Torque (Nm)</label>
                                        <input type="number" wire:model="torque" class="form-control">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold">Fuel Capacity (L)</label>
                                        <input type="number" wire:model="fuel_capacity" class="form-control">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold">Efficiency</label>
                                        <input type="text" wire:model="fuel_efficiency" class="form-control" placeholder="e.g. 8.5L/100km">
                                    </div>
                                </div>
                            </div>

                            {{-- 3. Practicality & Appearance --}}
                            <div class="bg-light p-3 rounded mb-4 border">
                                <h6 class="text-uppercase text-muted font-weight-bold small mb-3">Practicality & Appearance</h6>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold">Seats</label>
                                        <input type="number" wire:model="seats" class="form-control">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold">Doors</label>
                                        <input type="number" wire:model="doors" class="form-control">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold">Exterior Color</label>
                                        <input type="text" wire:model="color" class="form-control" placeholder="e.g. #FFFFFF">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold">Visibility Status</label>
                                        <select wire:model="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Draft/Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- 4. Production Dates --}}
                            <div class="bg-white p-3 rounded border mb-4 shadow-sm">
                                <h6 class="text-uppercase text-muted font-weight-bold small mb-3">Production Lifecycle</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3 border-right">
                                        <label class="small font-weight-bold">Variant/Model Year *</label>
                                        <input type="number" wire:model="production_year" class="form-control @error('production_year') is-invalid @enderror" placeholder="e.g. 2026">
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
                                        <small class="text-muted">Empty Year = "Present"</small>
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
                            <div class="card-header bg-dark text-white">Live Preview</div>
                            <div class="card-body text-center py-4">
                                <div class="rounded-circle mx-auto mb-3 border shadow-sm" 
                                     style="width: 60px; height: 60px; background-color: {{ $color ?: '#eee' }}; border: 3px solid white !important;">
                                </div>
                                <h5 class="mb-0 font-weight-bold">
                                    {{ $brands->firstWhere('id', $brand_id)?->brand_name ?? 'Brand' }}
                                </h5>
                                <p class="text-primary mb-2">
                                    {{ collect($this->vehicleModels)->firstWhere('id', $vehicle_model_id)['model_name'] ?? 'Model' }}
                                </p>
                                <span class="badge badge-secondary">{{ $trim_level ?: 'Base Trim' }}</span>
                                <hr>
                                <div class="row small text-left px-3">
                                    <div class="col-6 text-muted mb-1">Model Year:</div>
                                    <div class="col-6 font-weight-bold mb-1 text-right">{{ $production_year ?: '----' }}</div>
                                    
                                    <div class="col-6 text-muted mb-1">Drivetrain:</div>
                                    <div class="col-6 font-weight-bold mb-1 text-right">
                                        {{ $driveTypes->firstWhere('id', $drive_type_id)?->name ?? '---' }}
                                    </div>

                                    <div class="col-6 text-muted mb-1">Power:</div>
                                    <div class="col-6 font-weight-bold mb-1 text-right">{{ $horsepower ? $horsepower . ' HP' : '---' }}</div>

                                    <div class="col-6 text-muted">Lifecycle:</div>
                                    <div class="col-6 font-weight-bold text-right">
                                        {{ $start_year ?: '----' }} - {{ $end_year ?: 'Present' }}
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