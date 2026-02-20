<div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form wire:submit.prevent="save">
        <div class="row">
            {{-- Left Column: Organized Form --}}
            <div class="col-md-8">
                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-edit mr-2"></i>Edit Technical Specification
                        </h3>
                    </div>
                    <div class="card-body">

                        {{-- ================= Vehicle Selection ================= --}}
                        <fieldset class="border p-3 mb-4 rounded">
                            <legend class="w-auto px-2 text-primary font-weight-bold text-uppercase" style="font-size: 0.9rem;">
                                Vehicle Selection
                            </legend>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Brand <span class="text-danger">*</span></label>
                                    <select wire:model.live="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Vehicle Model <span class="text-danger">*</span></label>
                                    <select wire:model.live="vehicle_model_id" class="form-control @error('vehicle_model_id') is-invalid @enderror">
                                        <option value="">Select Model</option>
                                        @foreach($vehicleModels as $model)
                                            <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_model_id') <span class="error invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Trim Level</label>
                                    <input type="text" wire:model="trim_level" class="form-control" placeholder="e.g. XL, Premium, Sport">
                                    <small class="form-text text-muted">Example: <strong>Lariat, Premium, or SE</strong></small>
                                </div>
                            </div>
                        </fieldset>

                        {{-- ================= Core Specifications ================= --}}
                        <fieldset class="border p-3 mb-4 rounded">
                            <legend class="w-auto px-2 text-primary font-weight-bold text-uppercase" style="font-size: 0.9rem;">
                                Core Specifications
                            </legend>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Body Type</label>
                                    <select wire:model="body_type_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($bodyTypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Fuel Type</label>
                                    <select wire:model="engine_type_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($engineTypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Transmission</label>
                                    <select wire:model="transmission_type_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($transmissionTypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Drive Type</label>
                                    <select wire:model="drive_type_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($driveTypes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Engine Displacement</label>
                                    <select wire:model="engine_displacement_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($engineDisplacements as $ed)
                                            <option value="{{ $ed->id }}">{{ $ed->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        {{-- ================= Performance & Capacity ================= --}}
                        <fieldset class="border p-3 mb-4 rounded">
                            <legend class="w-auto px-2 text-primary font-weight-bold text-uppercase" style="font-size: 0.9rem;">
                                Performance & Capacity
                            </legend>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Horsepower (HP)</label>
                                    <input type="number" wire:model="horsepower" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Torque (Nm)</label>
                                    <input type="number" wire:model="torque" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Fuel Capacity (L)</label>
                                    <input type="number" wire:model="fuel_capacity" step="0.1" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Efficiency (km/L)</label>
                                    <input type="number" wire:model="fuel_efficiency" step="0.1" class="form-control">
                                </div>
                            </div>
                        </fieldset>

                        {{-- ================= Interior & Production ================= --}}
                        <fieldset class="border p-3 rounded shadow-sm">
                            <legend class="w-auto px-2 text-primary font-weight-bold text-uppercase" style="font-size: 0.9rem;">
                                Interior & Production
                            </legend>
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label>Seats</label>
                                    <input type="number" wire:model="seats" class="form-control">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Doors</label>
                                    <input type="number" wire:model="doors" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Steering</label>
                                    <select wire:model="steering_position" class="form-control">
                                        <option value="LEFT">Left-Hand Drive</option>
                                        <option value="RIGHT">Right-Hand Drive</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Color</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <input type="color" wire:model.live="color" class="form-control p-1" style="width: 45px; height: 38px;">
                                        </div>
                                        <input type="text" wire:model="color" class="form-control" placeholder="#000000">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="form-group col-md-4">
                                    <label>Prod. Year</label>
                                    <input type="number" wire:model="production_year" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Start Year</label>
                                    <input type="number" wire:model="production_start" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>End Year</label>
                                    <input type="number" wire:model="production_end" class="form-control">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="fas fa-save mr-1"></i> Update Specification
                        </button>
                        <a href="{{ route('admin.specifications.index') }}" class="btn btn-default ml-2">Cancel</a>
                    </div>
                </div>
            </div>

          {{-- Right Column: Preview --}}
<div class="col-md-4">
    <div class="sticky-top" style="top: 20px; z-index: 1020;">
        <div class="card card-dark shadow">
            <div class="card-header">
                <h3 class="card-title font-weight-bold small text-uppercase">Live Preview</h3>
            </div>
            <div class="card-body text-center py-4">
                <div class="rounded-circle mx-auto mb-3 border border-secondary shadow-sm" 
                     style="width: 70px; height: 70px; background-color: {{ $color ?: '#555' }}; transition: background-color 0.3s;">
                </div>
                
                <h5 class="font-weight-bold mb-1">
                    {{-- Safely find brand name --}}
                    @php $currentBrand = $brands->where('id', $brand_id)->first(); @endphp
                    {{ $currentBrand ? $currentBrand->brand_name : 'Select Brand' }}
                </h5>
                
                <h6 class="text-primary">
                    {{-- Safely find model name from the already loaded collection --}}
                    @php $currentModel = collect($vehicleModels)->where('id', $vehicle_model_id)->first(); @endphp
                    {{ $currentModel ? $currentModel['model_name'] : 'Select Model' }}
                </h6>

                <div class="badge badge-secondary px-3">
                    {{ $trim_level ?: 'Base Trim' }}
                </div>
                
                {{-- ... rest of your preview code ... --}}
            </div>
        </div>
    </div>
</div>
        </div>
    </form>
</div>