<div class="card card-primary shadow-lg border-0">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title font-weight-bold">Vehicle Specification Details</h3>
    </div>
    
    <div class="card-body bg-white">
        {{-- Flash Messages --}}
        @if (session()->has('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
        @if (session()->has('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

        <form wire:submit.prevent="save">
            {{-- Identity Section --}}
            <fieldset class="border p-4 mb-4 rounded shadow-sm">
                <legend class="w-auto px-3 font-weight-bold text-primary small text-uppercase">Identity & Lifecycle</legend>
                <div class="row">
                    @if(!$hideBrandModel)
                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold">Brand *</label>
                            <select wire:model.live="brand_id" class="form-control rounded-pill shadow-sm">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold">Vehicle Model *</label>
                            <select wire:model.live="vehicle_model_id" class="form-control rounded-pill shadow-sm" @disabled(!$brand_id)>
                                <option value="">Select Model</option>
                                @foreach($this->vehicleModels as $model)
                                    <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                                @endforeach
                            </select>
                            @error('vehicle_model_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Trim Level *</label>
                        <input type="text" wire:model="trim_level" class="form-control shadow-sm" placeholder="e.g. AMG Line">
                        @error('trim_level') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Marketing Year *</label>
                        <input type="number" wire:model="production_year" class="form-control shadow-sm" placeholder="e.g. 2024">
                        @error('production_year') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Chassis Code</label>
                        <input type="text" wire:model="chassis_code" class="form-control shadow-sm">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Prod. Start (Technical)</label>
                        <input type="number" wire:model="production_year_start" class="form-control shadow-sm" placeholder="YYYY">
                        @error('production_year_start') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Prod. End (Technical)</label>
                        <input type="number" wire:model="production_year_end" class="form-control shadow-sm" placeholder="Leave blank if active">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Market</label>
                        <select wire:model="destination_id" class="form-control shadow-sm">
                            <option value="">Select Region</option>
                            @foreach($destinations as $dest)
                                <option value="{{ $dest->id }}">{{ $dest->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </fieldset>

            {{-- Technical Section --}}
            <div class="row">
                <div class="col-md-6">
                    <fieldset class="border p-3 mb-4 rounded bg-light shadow-sm">
                        <legend class="w-auto px-2 font-weight-bold text-primary small text-uppercase">Engine & Performance</legend>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Displacement (CC) *</label>
                                <select wire:model="engine_displacement_id" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($engineDisplacements as $ed) <option value="{{ $ed->id }}">{{ $ed->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Fuel Type *</label>
                                <select wire:model="engine_type_id" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($engineTypes as $et) <option value="{{ $et->id }}">{{ $et->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">HP</label>
                                <input type="number" wire:model="horsepower" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Efficiency (L/100km)</label>
                                <input type="number" step="0.1" wire:model="fuel_efficiency" class="form-control">
                            </div>
                        </div>
                    </fieldset>
                </div>
                
                <div class="col-md-6">
                    <fieldset class="border p-3 mb-4 rounded bg-light shadow-sm">
                        <legend class="w-auto px-2 font-weight-bold text-primary small text-uppercase">Transmission & Body</legend>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Transmission *</label>
                                <select wire:model="transmission_type_id" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($transmissionTypes as $tt) <option value="{{ $tt->id }}">{{ $tt->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Body Type *</label>
                                <select wire:model="body_type_id" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($bodyTypes as $bt) <option value="{{ $bt->id }}">{{ $bt->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="small font-weight-bold">Steering</label>
                                <select wire:model="steering_position" class="form-control">
                                    <option value="LEFT">LHD (Left)</option>
                                    <option value="RIGHT">RHD (Right)</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill">
                    <i class="fa fa-save mr-2"></i> Save Variant
                </button>
            </div>
        </form>
    </div>
</div>