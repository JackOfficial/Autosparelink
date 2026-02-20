<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Create Vehicle Specification</h3></div>
    <div class="card-body">
        
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ================= Info Header ================= --}}
        @if($vehicle_model_id)
            @php $displayModel = \App\Models\VehicleModel::with('brand')->find($vehicle_model_id); @endphp
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center">
                <i class="fa fa-info-circle me-3 fa-2x"></i>
                <div>
                    <strong>Selected Model:</strong> {{ $displayModel->brand->brand_name }} {{ $displayModel->model_name }}<br>
                    <small>Fill out the specifications below. The system will automatically generate a searchable Variant.</small>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="save">
            {{-- ================= Vehicle Selection ================= --}}
            @if(!$hideBrandModel)
            <fieldset class="border p-3 mb-4 rounded">
                <legend class="w-auto px-2">Vehicle Identity</legend>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Brand <span class="text-danger">*</span></label>
                        <select wire:model.live="brand_id" class="form-control">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Vehicle Model <span class="text-danger">*</span></label>
                        <select wire:model.live="vehicle_model_id" class="form-control" @disabled(!$brand_id)>
                            <option value="">Select Model</option>
                            @foreach($vehicleModels as $model)
                                <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_model_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- TRIM LEVEL INPUT (REPLACED VARIANT DROPDOWN) --}}
                    <div class="col-md-4">
                        <label class="form-label">Trim Level</label>
                        <input type="text" wire:model="trim_level" class="form-control" placeholder="e.g. S, XLE, SE, Sport">
                        <small class="text-muted">Marketing name for this specific version.</small>
                        @error('trim_level') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
            </fieldset>
            @endif

            {{-- ... keep Core Specs, Performance, Interior, and Production fieldsets as they were ... --}}
            {{-- Just ensure wire:model names match the properties in the component --}}

            <div class="mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fa fa-magic me-2"></i> Generate Variant & Save Specs
                </button>
            </div>
        </form>
    </div>

    {{-- Add this just above the Save button in your Blade --}}
<div class="alert alert-secondary border-dashed mt-4">
    <label class="small text-uppercase text-muted d-block">Generated Variant Name Preview:</label>
    <h4 class="mb-0 text-primary">
        <i class="fa fa-car me-2"></i>
        {{ $brand_id ? $brands->find($brand_id)->brand_name : 'Brand' }}
        {{ $vehicle_model_id ? $vehicleModels->find($vehicle_model_id)->model_name : 'Model' }}
        {{ $trim_level ?: '[Trim]' }}
        {{ $body_type_id ? $bodyTypes->find($body_type_id)->name : '[Body]' }}
        {{ $production_year ?: '[Year]' }}
        ...
    </h4>
</div>

</div>