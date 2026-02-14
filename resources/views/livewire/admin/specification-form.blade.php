<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Specification Details</h3></div>
    <div class="card-body">

       {{-- Dismissable success message --}}
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

         {{-- ================= Show Redirected Model/Variant ================= --}}
        @if($vehicle_model_id || $variant_id)
    @php
        $displayVariant = $variant_id ? \App\Models\Variant::with('vehicleModel.brand', 'photos')->find($variant_id) : null;

        if ($variant_id) {
            $displayModel = $displayVariant->vehicleModel ?? null;
        } elseif ($vehicle_model_id) {
            $displayModel = \App\Models\VehicleModel::with('brand')->find($vehicle_model_id);
        } else {
            $displayModel = null;
        }
    @endphp

    <div class="card mb-4">
        <div class="row g-0">
            <div class="col-md-4">
                @if($displayVariant && $displayVariant->photos->count())
                    <img src="{{ asset('storage/' . $displayVariant->photos->first()->file_path) }}" class="img-fluid rounded-start" alt="Variant Photo">
                @elseif($displayModel && $displayModel->photo)
                    <img src="{{ asset('storage/' . $displayModel->photo) }}" class="img-fluid rounded-start" alt="Model Photo">
                @else
                    <img src="{{ asset('images/placeholder.png') }}" class="img-fluid rounded-start" alt="Placeholder">
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $displayVariant->name ?? $displayModel->model_name ?? 'N/A' }}</h5>
                    <p class="card-text">
                        Brand: {{ $displayModel->brand->brand_name ?? 'N/A' }} <br>
                        Model: {{ $displayModel->model_name ?? 'N/A' }} <br>
                        Variant: {{ $displayVariant->name ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif


        <form wire:submit.prevent="save">

            {{-- ================= Brand → Model → Variant ================= --}}
            @if(!$hideBrandModel)
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Vehicle Selection <span class="text-danger">*</span></legend>
                <div class="row">

                    {{-- Brand --}}
                    <div class="col-md-4">
                        <label>Brand</label>
                        <select wire:model.live="brand_id" class="form-control" @disabled($vehicle_model_id || $hideBrandModel)>
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" 
                                    @if($vehicle_model_id && $brand->id == optional($vehicleModels->firstWhere('id', $vehicle_model_id))->brand_id) selected @endif
                                >
                                    {{ $brand->brand_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    {{-- Vehicle Model --}}
                    <div class="col-md-4">
                        <label>Vehicle Model</label>
                        <select wire:model.live="vehicle_model_id" class="form-control" @disabled(!$vehicleModels->count() || $hideBrandModel)>
                            <option value="">Select Model</option>
                            @foreach($vehicleModels as $model)
                                <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_model_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    {{-- Variant --}}
                    @if(!$hideVariant)
                    <div class="col-md-4">
                        <label>Variant</label>
                        <select wire:model.live="variant_id" class="form-control" @disabled(!$filteredVariants->count())>
                            <option value="">Select Variant (optional)</option>
                            @foreach($filteredVariants as $variant)
                                <option value="{{ $variant->id }}">{{ $variant->name ?? 'Unnamed Variant' }}</option>
                            @endforeach
                        </select>
                        @error('variant_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>
                <small class="text-muted">Select brand first, then model. Variant is optional.</small>
            </fieldset>
            @endif

            {{-- ================= Core Specs ================= --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Core Specifications</legend>
                <div class="row">
                    <div class="col-md-4">
                        <label>Body Type</label>
                        <select wire:model="body_type_id" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($bodyTypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('body_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label>Fuel Type</label>
                        <select wire:model="engine_type_id" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($engineTypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('engine_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label>Transmission</label>
                        <select wire:model="transmission_type_id" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($transmissionTypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('transmission_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label>Drive Type</label>
                        <select wire:model="drive_type_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($driveTypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('drive_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Engine Displacement</label>
                        <select wire:model="engine_displacement_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($engineDisplacement as $ed)
                                <option value="{{ $ed->id }}">{{ $ed->name }}</option>
                            @endforeach
                        </select>
                        @error('engine_displacement_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                </div>
            </fieldset>

            {{-- ================= Performance & Capacity ================= --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Performance & Capacity</legend>
                <div class="row">
                    <div class="col-md-3">
                        <label>Horsepower (HP)</label>
                        <input type="number" wire:model="horsepower" class="form-control" min="0" placeholder="e.g. 150">
                        @error('horsepower') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Torque (Nm)</label>
                        <input type="number" wire:model="torque" class="form-control" min="0" placeholder="e.g. 320">
                        @error('torque') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Fuel Capacity (Liters)</label>
                        <input type="number" wire:model="fuel_capacity" class="form-control" min="0" step="0.1" placeholder="e.g. 55">
                        @error('fuel_capacity') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Fuel Efficiency (km/L)</label>
                        <input type="number" wire:model="fuel_efficiency" class="form-control" min="0" step="0.1" placeholder="e.g. 14.5">
                        @error('fuel_efficiency') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- ================= Interior & Layout ================= --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Interior & Layout</legend>
                <div class="row">
                    <div class="col-md-2">
                        <label>Seats</label>
                        <input type="number" wire:model="seats" class="form-control" min="1" max="20">
                        @error('seats') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-2">
                        <label>Doors</label>
                        <input type="number" wire:model="doors" class="form-control" min="1" max="6">
                        @error('doors') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label>Steering Position</label>
                        <select wire:model="steering_position" class="form-control">
                            <option value="">Select</option>
                            <option value="LEFT">Left-Hand Drive</option>
                            <option value="RIGHT">Right-Hand Drive</option>
                        </select>
                        @error('steering_position') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                  <div class="col-md-4" x-data="{ color: @entangle('color') }">
    <label class="form-label">Color</label>

    <div class="d-flex align-items-center gap-2">
        <!-- Color preview circle -->
        <div class="rounded-circle border" :style="'background-color: ' + color" style="width: 35px; height: 35px;"></div>

        <!-- Text input -->
        <input type="text" x-model="color" class="form-control" placeholder="e.g. Black, Pearl White, #ff0000">

        <!-- Color picker button -->
        <input type="color" x-model="color" class="form-control p-0" style="width: 50px; height: 35px; border:none; padding:0;">
    </div>

    @error('color') 
        <span class="text-danger d-block mt-1">{{ $message }}</span> 
    @enderror
    <small class="text-muted d-block mt-1">Pick a color or type a name/HEX (e.g. Black, #ff0000)</small>
</div>


                </div>
            </fieldset>

            {{-- ================= Production ================= --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto">Production</legend>
                <div class="row">
                    <div class="col-md-3">
                        <label>Production Start Year</label>
                        <input type="number" wire:model="production_start" class="form-control" min="1950" max="{{ date('Y') }}">
                        @error('production_start') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label>Production End Year</label>
                        <input type="number" wire:model="production_end" class="form-control" min="1950" max="{{ date('Y') + 2 }}">
                        @error('production_end') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </fieldset>

            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Save Specification
            </button>

        </form>
    </div>
</div>
