<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Vehicle Specification Details</h3>
    </div>
    <div class="card-body">

        {{-- Success Message --}}
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Contextual Info Header (Visible when model is pre-selected) --}}
        @if($vehicle_model_id && $hideBrandModel)
            @php
                $displayModel = \App\Models\VehicleModel::with('brand')->find($vehicle_model_id);
            @endphp
            @if($displayModel)
            <div class="alert alert-light border shadow-sm mb-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        @if($displayModel->photo)
                            <img src="{{ asset('storage/' . $displayModel->photo) }}" class="img-fluid rounded border" style="max-height: 80px;" alt="Model Photo">
                        @else
                            <i class="fa fa-car fa-3x text-muted"></i>
                        @endif
                    </div>
                    <div class="col-md-10">
                        <h5 class="mb-1">{{ $displayModel->brand->brand_name ?? 'N/A' }} {{ $displayModel->model_name ?? 'N/A' }}</h5>
                        <p class="text-muted small mb-0">Adding specification for this specific model. The trim and technical data below will define the variant.</p>
                    </div>
                </div>
            </div>
            @endif
        @endif

        <form wire:submit.prevent="save">

            {{-- ================= Identity Section ================= --}}
            <fieldset class="border p-3 mb-4 rounded shadow-sm">
                <legend class="w-auto px-2 font-weight-bold text-primary">Identity</legend>
                <div class="row">
                    @if(!$hideBrandModel)
                        {{-- Brand Selection --}}
                        <div class="col-md-4">
                            <label>Brand <span class="text-danger">*</span></label>
                            <select wire:model.live="brand_id" class="form-control">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- Model Selection --}}
                        <div class="col-md-4">
                            <label>Vehicle Model <span class="text-danger">*</span></label>
                            <select wire:model.live="vehicle_model_id" class="form-control" @disabled(!$brand_id)>
                                <option value="">Select Model</option>
                                @foreach($vehicleModels as $model)
                                    <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                                @endforeach
                            </select>
                            @error('vehicle_model_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    {{-- Trim Level - MOVED OUTSIDE so it's always visible --}}
                    <div class="{{ $hideBrandModel ? 'col-md-12' : 'col-md-4' }}">
                        <label>Trim Level <span class="text-danger">*</span></label>
                        <input type="text" wire:model.live="trim_level" class="form-control" placeholder="e.g. S, XLE, AMG Line, Premium">
                        @error('trim_level') <span class="text-danger small">{{ $message }}</span> @enderror
                        <small class="text-muted">The marketing name for this specific version.</small>
                    </div>
                </div>
            </fieldset>

            {{-- ================= Core Specs ================= --}}
            <fieldset class="border p-3 mb-4 rounded shadow-sm bg-light">
                <legend class="w-auto px-2 font-weight-bold text-primary">Core Configuration</legend>
                <div class="row">
                    <div class="col-md-3">
                        <label>Body Type <span class="text-danger">*</span></label>
                        <select wire:model.live="body_type_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($bodyTypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('body_type_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label>Prod. Year <span class="text-danger">*</span></label>
                        <input type="number" wire:model.live="production_year" class="form-control" min="1950" max="{{ date('Y') + 1 }}">
                        @error('production_year') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-2">
                        <label>Displacement <span class="text-danger">*</span></label>
                        <select wire:model.live="engine_displacement_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($engineDisplacements as $ed)
                                <option value="{{ $ed->id }}">{{ $ed->name }}</option>
                            @endforeach
                        </select>
                        @error('engine_displacement_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-2">
                        <label>Fuel Type <span class="text-danger">*</span></label>
                        <select wire:model.live="engine_type_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($engineTypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('engine_type_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-2">
                        <label>Transmission <span class="text-danger">*</span></label>
                        <select wire:model.live="transmission_type_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($transmissionTypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('transmission_type_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Live Preview Area --}}
            <div class="alert alert-primary border-dashed mb-4 text-center py-3">
                <label class="small text-uppercase text-muted d-block mb-1">Generated System Name Preview:</label>
                <h4 class="mb-0 font-weight-bold">
                    <i class="fa fa-tag me-2"></i>
                    {{ $brand_id ? ($brands->firstWhere('id', $brand_id)->brand_name ?? '') : 'Brand' }}
                    {{ $vehicle_model_id ? ($vehicleModels->firstWhere('id', $vehicle_model_id)->model_name ?? '') : 'Model' }}
                    {{ $trim_level ?: '[Trim]' }}
                    {{ $body_type_id ? ($bodyTypes->firstWhere('id', $body_type_id)->name ?? '') : '[Body]' }}
                    {{ $production_year ?: '[Year]' }}
                    {{ $engine_displacement_id ? ($engineDisplacements->firstWhere('id', $engine_displacement_id)->name ?? '') : '[Displ]' }}
                    {{ $engine_type_id ? ($engineTypes->firstWhere('id', $engine_type_id)->name ?? '') : '[Fuel]' }}
                    {{ $transmission_type_id ? ($transmissionTypes->firstWhere('id', $transmission_type_id)->name ?? '') : '[Gearbox]' }}
                </h4>
            </div>

            {{-- ================= Technical Details ================= --}}
            <div class="row">
                <div class="col-md-6">
                    <fieldset class="border p-3 mb-4 rounded">
                        <legend class="w-auto px-2 font-weight-bold text-primary">Performance</legend>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Drive Type</label>
                                <select wire:model="drive_type_id" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($driveTypes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Horsepower (HP)</label>
                                <input type="number" wire:model="horsepower" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>Torque (Nm)</label>
                                <input type="number" wire:model="torque" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>Fuel Capacity (L)</label>
                                <input type="number" wire:model="fuel_capacity" class="form-control" step="0.1">
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-md-6">
                    <fieldset class="border p-3 mb-4 rounded">
                        <legend class="w-auto px-2 font-weight-bold text-primary">Interior & Exterior</legend>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label>Seats</label>
                                <input type="number" wire:model="seats" class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label>Doors</label>
                                <input type="number" wire:model="doors" class="form-control">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label>Steering</label>
                                <select wire:model="steering_position" class="form-control">
                                    <option value="LEFT">LHD</option>
                                    <option value="RIGHT">RHD</option>
                                </select>
                            </div>
                            <div class="col-md-12" x-data="{ color: @entangle('color') }">
                                <label>Color</label>
                                <div class="d-flex gap-2">
                                    <div class="rounded-circle border" :style="'background-color: ' + color" style="width: 38px; height: 38px; flex-shrink: 0;"></div>
                                    <input type="text" x-model="color" class="form-control" placeholder="e.g. Pearl White">
                                    <input type="color" x-model="color" class="form-control p-0 border-0" style="width: 40px;">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="text-right mt-3">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                    <i class="fa fa-save mr-2"></i> Save & Generate Variant
                </button>
            </div>

        </form>
    </div>
</div>