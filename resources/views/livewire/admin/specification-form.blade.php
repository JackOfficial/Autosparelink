<div>
    {{-- Global Error Alerts --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm border-0 mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle mr-3 fa-2x"></i>
                <div>
                    <span class="font-weight-bold">Validation Errors:</span>
                    <ul class="mb-0 small">
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
                    <h6 class="text-uppercase mb-1" style="opacity: 0.8; letter-spacing: 1px;">Specification Management</h6>
                    <h3 class="font-weight-bold mb-0">
                        <i class="fas fa-car-side mr-2"></i> {{ $this->generatedName }}
                    </h3>
                </div>
                <div class="text-right">
                    <a href="/admin/specifications" class="btn btn-link text-white text-decoration-none">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body bg-light-gray p-4">
            @if (session()->has('error')) <div class="alert alert-danger shadow-sm border-0">{{ session('error') }}</div> @endif
            @if (session()->has('success')) <div class="alert alert-success shadow-sm border-0">{{ session('success') }}</div> @endif

            <form wire:submit.prevent="save">
                {{-- IDENTITY & TIMELINE SECTION --}}
                <div class="bg-white p-4 rounded shadow-sm mb-4 border-left border-primary" style="border-left-width: 5px !important;">
                    <h5 class="text-primary font-weight-bold mb-4 border-bottom pb-2">
                        <i class="fas fa-id-card mr-2"></i> Identity & Production Timeline
                    </h5>
                    
                    <div class="row">
                        @if(!$hideBrandModel)
                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold text-muted">Brand *</label>
                                <select wire:model.live="brand_id" class="form-control select-custom @error('brand_id') is-invalid @enderror">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand) <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option> @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold text-muted">Vehicle Model *</label>
                                <select wire:model.live="vehicle_model_id" class="form-control select-custom @error('vehicle_model_id') is-invalid @enderror" @disabled(!$brand_id)>
                                    <option value="">Select Model</option>
                                    @foreach($this->vehicleModels as $model) <option value="{{ $model->id }}">{{ $model->model_name }}</option> @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Trim Level *</label>
                            <input type="text" wire:model.live="trim_level" class="form-control @error('trim_level') is-invalid @enderror" placeholder="e.g. VXR, AMG Line">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Model Year *</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fas fa-calendar-alt text-primary"></i></span>
                                </div>
                                <input type="number" wire:model.live="production_year" class="form-control @error('production_year') is-invalid @enderror">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted">Production Start (Month & Year) *</label>
                            <div class="d-flex">
                                <select wire:model="start_month" class="form-control mr-2 @error('start_month') is-invalid @enderror" style="width: 45%;">
                                    <option value="">Month</option>
                                    @foreach($months as $num => $name) <option value="{{ $num }}">{{ $name }}</option> @endforeach
                                </select>
                                <input type="number" wire:model="start_year" class="form-control @error('start_year') is-invalid @enderror" placeholder="Year">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold text-muted">Production End (Leave blank for "Present")</label>
                            <div class="d-flex">
                                <select wire:model="end_month" class="form-control mr-2" style="width: 45%;">
                                    <option value="">Month</option>
                                    @foreach($months as $num => $name) <option value="{{ $num }}">{{ $name }}</option> @endforeach
                                </select>
                                <input type="number" wire:model="end_year" class="form-control" placeholder="Year">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-muted">Regional Market</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fas fa-globe-americas text-muted"></i></span>
                                </div>
                                <select wire:model="destination_id" class="form-control">
                                    <option value="">Select Market</option>
                                    @foreach($destinations as $dest) <option value="{{ $dest->id }}">{{ $dest->region_name }}</option> @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-muted">Chassis Code</label>
                            <input type="text" wire:model="chassis_code" class="form-control" placeholder="e.g. LC300">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-muted">Model Code</label>
                            <input type="text" wire:model="model_code" class="form-control" placeholder="e.g. VJA300L">
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- ENGINE SECTION --}}
                    <div class="col-md-6 mb-4">
                        <div class="bg-white p-4 rounded shadow-sm h-100 border-top border-info" style="border-top-width: 3px !important;">
                            <h5 class="text-info font-weight-bold mb-4 border-bottom pb-2">
                                <i class="fas fa-engine mr-2"></i> Engine & Power
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

                                {{-- Improved Input Groups with Suffixes --}}
                                <div class="col-md-4 mb-3">
                                    <label class="small font-weight-bold text-muted">Horse Power</label>
                                    <div class="input-group">
                                        <input type="number" wire:model="horsepower" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text font-weight-bold bg-light small">HP</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="small font-weight-bold text-muted">Torque</label>
                                    <div class="input-group">
                                        <input type="number" wire:model="torque" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text font-weight-bold bg-light small">Nm</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="small font-weight-bold text-muted">Fuel Efficiency</label>
                                    <div class="input-group">
                                        <input type="text" wire:model="fuel_efficiency" class="form-control" placeholder="10.5">
                                        <div class="input-group-append">
                                            <span class="input-group-text font-weight-bold bg-light small">L/100 Km</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DRIVETRAIN SECTION --}}
                    <div class="col-md-6 mb-4">
                        <div class="bg-white p-4 rounded shadow-sm h-100 border-top border-info" style="border-top-width: 3px !important;">
                            <h5 class="text-info font-weight-bold mb-4 border-bottom pb-2">
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
                                    <div class="btn-group w-100 shadow-sm rounded">
                                        <button type="button" wire:click="$set('steering_position', 'LEFT')" class="btn {{ $steering_position == 'LEFT' ? 'btn-primary' : 'btn-outline-primary' }} font-weight-bold py-2">
                                            <i class="fas fa-arrow-left mr-1"></i> Left Hand Drive
                                        </button>
                                        <button type="button" wire:click="$set('steering_position', 'RIGHT')" class="btn {{ $steering_position == 'RIGHT' ? 'btn-primary' : 'btn-outline-primary' }} font-weight-bold py-2">
                                            Right Hand Drive <i class="fas fa-arrow-right ml-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BODY & CAPACITY --}}
                <div class="bg-white p-4 rounded shadow-sm mb-4 border-top border-success" style="border-top-width: 3px !important;">
                    <h5 class="text-success font-weight-bold mb-4 border-bottom pb-2">
                        <i class="fas fa-vector-square mr-2"></i> Body & Interior
                    </h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold text-muted">Body Type *</label>
                            <select wire:model="body_type_id" class="form-control @error('body_type_id') is-invalid @enderror">
                                <option value="">Select</option>
                                @foreach($bodyTypes as $bt) <option value="{{ $bt->id }}">{{ $bt->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="small font-weight-bold text-muted">Doors</label>
                            <div class="input-group">
                                <input type="number" wire:model="doors" class="form-control">
                                <div class="input-group-append"><span class="input-group-text bg-light font-weight-bold">D</span></div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="small font-weight-bold text-muted">Seats</label>
                            <div class="input-group">
                                <input type="number" wire:model="seats" class="form-control">
                                <div class="input-group-append"><span class="input-group-text bg-light font-weight-bold">S</span></div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="small font-weight-bold text-muted">Fuel Tank</label>
                            <div class="input-group">
                                <input type="number" wire:model="tank_capacity" class="form-control">
                                <div class="input-group-append"><span class="input-group-text bg-light font-weight-bold text-primary">L</span></div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
    <label class="small font-weight-bold text-muted">Primary Color</label>
    {{-- We use x-data to sync the two inputs locally in the browser --}}
    <div class="input-group shadow-sm" x-data="{ colorName: @entangle('color') }">
        <div class="input-group-prepend">
            <span class="input-group-text bg-white">
                <i class="fas fa-palette text-muted"></i>
            </span>
        </div>
        
        {{-- Text Input --}}
        <input type="text" 
               x-model="colorName" 
               class="form-control" 
               placeholder="e.g. Midnight Black">
        
        {{-- Visual Picker --}}
        <div class="input-group-append">
            <div class="input-group-text bg-white p-1">
                <input type="color" 
                       x-model="colorName"
                       class="border-0 bg-transparent" 
                       style="width: 30px; height: 24px; cursor: pointer;"
                       title="Pick a color code">
            </div>
        </div>
    </div>
</div>
                    </div>
                </div>

                {{-- FINAL ACTIONS --}}
                <div class="bg-white p-4 rounded shadow-sm border-top border-primary">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="custom-control custom-switch custom-switch-lg">
                                <input type="checkbox" wire:model="status" class="custom-control-input" id="statusSwitch">
                                <label class="custom-control-label font-weight-bold ml-3" for="statusSwitch">
                                    Published & Visible to Public
                                </label>
                            </div>
                            <small class="text-muted">Unpublished specifications will only be visible to admins.</small>
                        </div>
                        <div class="col-md-6 text-md-right text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-lg rounded-pill">
                                <i class="fas fa-cloud-upload-alt mr-2"></i> Save Specification
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .bg-light-gray { background-color: #f8f9fa; }
        .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important; }
        
        /* Custom Styling for Input Groups */
        .input-group-text {
            border: 1px solid #ced4da;
            color: #495057;
        }
        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
        }

        /* Large Toggle Switch */
        .custom-switch-lg .custom-control-label::before { height: 1.5rem; width: 2.75rem; border-radius: 1rem; cursor: pointer; }
        .custom-switch-lg .custom-control-label::after { width: calc(1.5rem - 4px); height: calc(1.5rem - 4px); border-radius: 1rem; cursor: pointer; }
        .custom-switch-lg .custom-control-input:checked ~ .custom-control-label::after { transform: translateX(1.25rem); }
        
        .select-custom {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right .75rem center;
            background-size: 16px 12px;
            appearance: none;
        }
    </style>
</div>