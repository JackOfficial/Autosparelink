@extends('layouts.dashboard')

@section('title', 'Add New Vehicle')

@section('content')
<div class="container py-4 py-lg-5" x-data="{ 
    selectedBrand: '{{ old('brand_id') }}', 
    selectedModel: '{{ old('vehicle_model_id') }}' 
}">
    
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('vehicles.index') }}" class="text-decoration-none text-muted small">Garage</a></li>
            <li class="breadcrumb-item active small fw-bold" aria-current="page">Add Vehicle</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h2 class="h4 fw-bold text-dark mb-1">Add to Garage</h2>
                    <p class="text-muted small">Provide detailed specifications for accurate part matching.</p>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('vehicles.store') }}" method="POST">
                        @csrf

                        <h6 class="text-primary fw-bold mb-3">Basic Information</h6>
                        <div class="row">
                            {{-- Brand --}}
                            <div class="col-md-4 mb-3">
                                <label for="brand_id" class="form-label small fw-bold text-muted text-uppercase">Vehicle Brand</label>
                                <select name="brand_id" id="brand_id" x-model="selectedBrand" @change="selectedModel = ''"
                                        class="form-select border-0 bg-light rounded-3 @error('brand_id') is-invalid @enderror" required>
                                    <option value="">Choose Brand...</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Model --}}
                            <div class="col-md-4 mb-3">
                                <label for="vehicle_model_id" class="form-label small fw-bold text-muted text-uppercase">Vehicle Model</label>
                                <select name="vehicle_model_id" id="vehicle_model_id" x-model="selectedModel" :disabled="!selectedBrand"
                                        class="form-select border-0 bg-light rounded-3 @error('vehicle_model_id') is-invalid @enderror" required>
                                    <option value="">Choose Model...</option>
                                    @foreach($models as $model)
                                        <template x-if="selectedBrand == '{{ $model->brand_id }}'">
                                            <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                                        </template>
                                    @endforeach
                                </select>
                                @error('vehicle_model_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Year --}}
                            <div class="col-md-4 mb-3">
                                <label for="production_start" class="form-label small fw-bold text-muted text-uppercase">Manufacturing Year</label>
                                <input type="number" name="production_start" id="production_start" value="{{ old('production_start', date('Y')) }}" 
                                       class="form-control border-0 bg-light rounded-3 @error('production_start') is-invalid @enderror" 
                                       min="1900" max="{{ date('Y') + 1 }}" required>
                                @error('production_start') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">
                        <h6 class="text-primary fw-bold mb-3">Specifications & Market</h6>
                        
                        <div class="row">
                            {{-- Variant (Trim) --}}
                            <div class="col-md-4 mb-3">
                                <label for="variant_id" class="form-label small fw-bold text-muted text-uppercase">Trim Level</label>
                                <select name="variant_id" id="variant_id" class="form-select border-0 bg-light rounded-3" :disabled="!selectedModel">
                                    <option value="">Select Trim...</option>
                                    @foreach($variants as $variant)
                                        <template x-if="selectedModel == '{{ $variant->vehicle_model_id }}'">
                                            <option value="{{ $variant->id }}">{{ $variant->trim_level }}</option>
                                        </template>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Body Type --}}
                            <div class="col-md-4 mb-3">
                                <label for="body_type_id" class="form-label small fw-bold text-muted text-uppercase">Body Type</label>
                                <select name="body_type_id" id="body_type_id" class="form-select border-0 bg-light rounded-3" required>
                                    <option value="">Select Body...</option>
                                    @foreach($bodyTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('body_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Fuel Type (Engine Type) --}}
                            <div class="col-md-4 mb-3">
                                <label for="engine_type_id" class="form-label small fw-bold text-muted text-uppercase">Fuel Type</label>
                                <select name="engine_type_id" id="engine_type_id" class="form-select border-0 bg-light rounded-3" required>
                                    <option value="">Select Fuel...</option>
                                    @foreach($engineTypes as $fuel)
                                        <option value="{{ $fuel->id }}" {{ old('engine_type_id') == $fuel->id ? 'selected' : '' }}>{{ $fuel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Transmission --}}
                            <div class="col-md-6 mb-3">
                                <label for="transmission_type_id" class="form-label small fw-bold text-muted text-uppercase">Transmission</label>
                                <select name="transmission_type_id" id="transmission_type_id" class="form-select border-0 bg-light rounded-3" required>
                                    @foreach($transmissionTypes as $trans)
                                        <option value="{{ $trans->id }}">{{ $trans->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Drive Type --}}
                            <div class="col-md-6 mb-3">
                                <label for="drive_type_id" class="form-label small fw-bold text-muted text-uppercase">Drive Type</label>
                                <select name="drive_type_id" id="drive_type_id" class="form-select border-0 bg-light rounded-3">
                                    <option value="">Choose Drive...</option>
                                    @foreach($driveTypes as $drive)
                                        <option value="{{ $drive->id }}">{{ $drive->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">
                        <h6 class="text-primary fw-bold mb-3">Engine & Performance</h6>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="displacement" class="form-label small fw-bold text-muted text-uppercase">Displacement</label>
                                <input type="text" name="displacement" id="displacement" class="form-control border-0 bg-light rounded-3" placeholder="e.g. 2.0L">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="horsepower" class="form-label small fw-bold text-muted text-uppercase">Horsepower</label>
                                <input type="text" name="horsepower" id="horsepower" class="form-control border-0 bg-light rounded-3" placeholder="e.g. 150 hp">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="steering_position" class="form-label small fw-bold text-muted text-uppercase">Steering</label>
                                <select name="steering_position" id="steering_position" class="form-select border-0 bg-light rounded-3">
                                    <option value="LHD">Left Hand (LHD)</option>
                                    <option value="RHD">Right Hand (RHD)</option>
                                </select>
                            </div>
                        </div>

                        {{-- VIN & Primary Toggle --}}
                        <div class="row align-items-end">
                            <div class="col-md-8 mb-3">
                                <label for="vin" class="form-label small fw-bold text-muted text-uppercase">VIN (Optional)</label>
                                <input type="text" name="vin" id="vin" class="form-control border-0 bg-light rounded-3" maxlength="17" placeholder="17-character VIN">
                                @error('vin') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_primary" id="is_primary" value="1" checked>
                                    <label class="form-check-label small fw-bold" for="is_primary">Set as Primary</label>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-2">
                            <a href="{{ route('vehicles.index') }}" class="btn btn-link text-muted text-decoration-none px-0">Cancel</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                Save Vehicle <i class="fas fa-save ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light { background-color: #f8f9fa !important; }
    .rounded-4 { border-radius: 1rem !important; }
    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        border: 1px solid #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
</style>
@endsection