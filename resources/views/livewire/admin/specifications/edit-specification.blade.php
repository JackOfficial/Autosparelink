<div>
    <section class="content">
        <form wire:submit.prevent="save">
            <div class="row">
                {{-- Left Column --}}
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

                            {{-- Vehicle Selection --}}
                            <div class="bg-light p-3 rounded mb-4 border">
                                <h6 class="text-uppercase text-muted font-weight-bold small mb-3">Vehicle Selection</h6>
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
                                        <input type="text" wire:model="trim_level" class="form-control" placeholder="XL, Premium...">
                                    </div>
                                </div>
                            </div>

                            {{-- Technical Grid --}}
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
                                        <label class="small font-weight-bold">Fuel Type</label>
                                        <select wire:model="engine_type_id" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($engineTypes as $item)
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
                                </div>
                            </div>

                            {{-- Production Dates --}}
                            <div class="bg-light p-3 rounded border mb-4">
                                <h6 class="text-uppercase text-muted font-weight-bold small mb-3">Production Lifecycle</h6>
                                <div class="row">
                                    <div class="col-md-6 border-right">
                                        <label class="small font-weight-bold">Start Date (Year / Month)</label>
                                        <div class="d-flex">
                                            <input type="number" wire:model="start_year" class="form-control mr-2" placeholder="YYYY">
                                            <select wire:model="start_month" class="form-control">
                                                <option value="">Month</option>
                                                @for($m=1; $m<=12; $m++) <option value="{{ $m }}">{{ date('F', mktime(0,0,0,$m,1)) }}</option> @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small font-weight-bold">End Date (Leave Year blank for "Present")</label>
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

                {{-- Right Column Preview --}}
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
                                <span class="badge badge-secondary">{{ $trim_level ?: 'Base' }}</span>
                                <hr>
                                <div class="row small text-left">
                                    <div class="col-6 text-muted">Variant Year:</div>
                                    <div class="col-6 font-weight-bold">{{ $production_year }}</div>
                                    <div class="col-6 text-muted">Lifecycle:</div>
                                    <div class="col-6 font-weight-bold">{{ $start_year }} - {{ $end_year ?: 'Present' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>