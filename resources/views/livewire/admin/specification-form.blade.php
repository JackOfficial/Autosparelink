<div class="card card-primary shadow-lg border-0">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title font-weight-bold">Vehicle Specification Details</h3>
    </div>
    <div class="card-body bg-white">

        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Message --}}
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3 shadow-sm" role="alert">
                <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Contextual Info Header --}}
        @if($vehicle_model_id && $hideBrandModel)
            @php
                $displayModel = \App\Models\VehicleModel::with('brand')->find($vehicle_model_id);
            @endphp
            @if($displayModel)
            <div class="alert alert-light border shadow-sm mb-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center border-right">
                        @if($displayModel->photo)
                            <img src="{{ asset('storage/' . $displayModel->photo) }}" class="img-fluid rounded shadow-sm" style="max-height: 80px;" alt="Model Photo">
                        @else
                            <i class="fa fa-car fa-3x text-muted"></i>
                        @endif
                    </div>
                    <div class="col-md-10 pl-4">
                        <h5 class="mb-1 font-weight-bold text-dark">{{ $displayModel->brand->brand_name ?? 'N/A' }} {{ $displayModel->model_name ?? 'N/A' }}</h5>
                        <p class="text-muted small mb-0">Defining technical data for this variant. Specific market and production details are required below.</p>
                    </div>
                </div>
            </div>
            @endif
        @endif

        <form wire:submit.prevent="save">

            {{-- ================= Identity Section ================= --}}
            <fieldset class="border p-4 mb-4 rounded shadow-sm">
                <legend class="w-auto px-3 font-weight-bold text-primary small text-uppercase" style="letter-spacing: 1px;">Identity & Lifecycle</legend>
                <div class="row">
                    @if(!$hideBrandModel)
                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold">Brand <span class="text-danger">*</span></label>
                            <select wire:model.live="brand_id" class="form-control rounded-pill shadow-sm">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="small font-weight-bold">Vehicle Model <span class="text-danger">*</span></label>
                            <select wire:model.live="vehicle_model_id" class="form-control rounded-pill shadow-sm" @disabled(!$brand_id)>
                                <option value="">Select Model</option>
                                @foreach($vehicleModels as $model)
                                    <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                                @endforeach
                            </select>
                            @error('vehicle_model_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Trim Level <span class="text-danger">*</span></label>
                        <input type="text" wire:model.live="trim_level" class="form-control shadow-sm" placeholder="e.g. S, XLE, AMG Line">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Chassis/Model Code</label>
                        <input type="text" wire:model.live="chassis_code" class="form-control shadow-sm" placeholder="e.g. W213, ZVW50">
                    </div>

                    {{-- NEW: Production Range --}}
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Prod. Start Year <span class="text-danger">*</span></label>
                        <input type="number" wire:model="production_year_start" class="form-control shadow-sm" placeholder="YYYY">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Prod. End Year</label>
                        <input type="number" wire:model="production_year_end" class="form-control shadow-sm" placeholder="YYYY (Leave blank if Now)">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Market Destination</label>
                        <select wire:model="destination_id" class="form-control shadow-sm">
                            <option value="">Select Market</option>
                            @foreach($destinations as $dest)
                                <option value="{{ $dest->id }}">{{ $dest->code }} - {{ $dest->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-center mt-3">
                        <div class="custom-control custom-switch custom-switch-md">
                            <input type="checkbox" class="custom-control-input" id="isDefaultSwitch" wire:model="is_default">
                            <label class="custom-control-label font-weight-bold" for="isDefaultSwitch">Set as Default Variant</label>
                        </div>
                    </div>
                </div>
            </fieldset>

            {{-- ================= Performance & Efficiency ================= --}}
            <div class="row">
                <div class="col-md-6">
                    <fieldset class="border p-3 mb-4 rounded h-100 bg-light shadow-sm">
                        <legend class="w-auto px-2 font-weight-bold text-primary small text-uppercase">Performance & Efficiency</legend>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Fuel Efficiency (L/100km)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fa fa-leaf text-success"></i></span>
                                    </div>
                                    <input type="number" wire:model="fuel_efficiency" class="form-control border-left-0" step="0.1" placeholder="e.g. 5.2">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Horsepower (HP)</label>
                                <input type="number" wire:model="horsepower" class="form-control shadow-sm">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Drive Type</label>
                                <select wire:model="drive_type_id" class="form-control shadow-sm">
                                    <option value="">Select</option>
                                    @foreach($driveTypes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Fuel Type <span class="text-danger">*</span></label>
                                <select wire:model.live="engine_type_id" class="form-control shadow-sm">
                                    <option value="">Select</option>
                                    @foreach($engineTypes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-md-6">
                    <fieldset class="border p-3 mb-4 rounded h-100 bg-light shadow-sm">
                        <legend class="w-auto px-2 font-weight-bold text-primary small text-uppercase">Transmission & Body</legend>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Transmission <span class="text-danger">*</span></label>
                                <select wire:model.live="transmission_type_id" class="form-control shadow-sm">
                                    <option value="">Select</option>
                                    @foreach($transmissionTypes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">Body Type <span class="text-danger">*</span></label>
                                <select wire:model.live="body_type_id" class="form-control shadow-sm">
                                    <option value="">Select</option>
                                    @foreach($bodyTypes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="small font-weight-bold">Steering Position</label>
                                <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                    <label class="btn btn-outline-secondary w-100 {{ $steering_position === 'LEFT' ? 'active' : '' }}" wire:click="$set('steering_position', 'LEFT')">
                                        <input type="radio" name="steering" value="LEFT"> LHD
                                    </label>
                                    <label class="btn btn-outline-secondary w-100 {{ $steering_position === 'RIGHT' ? 'active' : '' }}" wire:click="$set('steering_position', 'RIGHT')">
                                        <input type="radio" name="steering" value="RIGHT"> RHD
                                    </label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            {{-- Live Preview Area --}}
            <div class="alert alert-dark border-0 mb-4 text-center py-4 shadow-sm" style="background: #1e1e2d; color: #fff;">
                <label class="small text-uppercase text-muted d-block mb-1" style="letter-spacing: 2px;">Variant Identification Tag:</label>
                <h4 class="mb-0 font-weight-bold text-warning">
                    <i class="fa fa-barcode me-2"></i>
                    {{ $brand_id ? ($brands->firstWhere('id', $brand_id)->brand_name ?? '') : 'BRAND' }}
                    {{ $vehicle_model_id ? ($vehicleModels->firstWhere('id', $vehicle_model_id)->model_name ?? '') : 'MODEL' }}
                    {{ $trim_level ?: '' }}
                    [{{ $production_year_start ?: 'YYYY' }} - {{ $production_year_end ?: 'Now' }}]
                    <span class="text-white opacity-50 font-weight-light">| {{ $destination_id ? ($destinations->firstWhere('id', $destination_id)->code ?? '') : 'REGION' }}</span>
                </h4>
            </div>

            <div class="text-right mt-3">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill">
                    <i class="fa fa-save mr-2"></i> Create Vehicle Variant
                </button>
            </div>

        </form>
    </div>
</div>