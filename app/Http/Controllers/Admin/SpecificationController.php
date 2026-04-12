@extends('layouts.dashboard')

@section('title', 'Add New Vehicle')

@section('content')
<div class="container py-4 py-lg-5" x-data="{ 
    selectedBrandId: '', 
    selectedModelId: '',
    selectedVariantId: ''
}">
    
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
                    <p class="text-muted small">Select your vehicle details to ensure part compatibility.</p>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('vehicles.store') }}" method="POST">
                        @csrf

                        <h6 class="text-primary fw-bold mb-3 small text-uppercase">Identification</h6>
                        <div class="row">
                            {{-- Brand --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted">Vehicle Brand</label>
                                <select name="brand_id" x-model="selectedBrandId" @change="selectedModelId = ''; selectedVariantId = ''"
                                        class="form-select border-0 bg-light rounded-3 shadow-none">
                                    <option value="">Choose Brand...</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Model --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted">Vehicle Model</label>
                                <select name="vehicle_model_id" x-model="selectedModelId" :disabled="!selectedBrandId" @change="selectedVariantId = ''"
                                        class="form-select border-0 bg-light rounded-3 shadow-none @error('vehicle_model_id') is-invalid @enderror">
                                    <option value="">Choose Model...</option>
                                    @foreach($models as $model)
                                        <template x-if="selectedBrandId == '{{ $model->brand_id }}'">
                                            <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                                        </template>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Variant / Trim Level --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted">Trim Level (Variant)</label>
                                <select name="variant_id" x-model="selectedVariantId" :disabled="!selectedModelId"
                                        class="form-select border-0 bg-light rounded-3 shadow-none">
                                    <option value="">Choose Trim...</option>
                                    @foreach($variants as $variant)
                                        <template x-if="selectedModelId == '{{ $variant->vehicle_model_id }}'">
                                            <option value="{{ $variant->id }}">{{ $variant->trim_level }}</option>
                                        </template>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">
                        <h6 class="text-primary fw-bold mb-3 small text-uppercase">Technical Specifications</h6>
                        
                        <div class="row">
                            {{-- Body Type --}}
                            <div class="col-md-4 mb-3">
                                <label for="body_type_id" class="form-label small fw-bold text-muted">Body Type</label>
                                <select name="body_type_id" id="body_type_id" class="form-select border-0 bg-light rounded-3 shadow-none" required>
                                    <option value="">Select Body...</option>
                                    @foreach($bodyTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Transmission --}}
                            <div class="col-md-4 mb-3">
                                <label for="transmission_type_id" class="form-label small fw-bold text-muted">Transmission</label>
                                <select name="transmission_type_id" id="transmission_type_id" class="form-select border-0 bg-light rounded-3 shadow-none" required>
                                    <option value="">Select Gearbox...</option>
                                    @foreach($transmissionTypes as $trans)
                                        <option value="{{ $trans->id }}">{{ $trans->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Drive Type --}}
                            <div class="col-md-4 mb-3">
                                <label for="drive_type_id" class="form-label small fw-bold text-muted">Drive Type</label>
                                <select name="drive_type_id" id="drive_type_id" class="form-select border-0 bg-light rounded-3 shadow-none">
                                    <option value="">Select Drive...</option>
                                    @foreach($driveTypes as $drive)
                                        <option value="{{ $drive->id }}">{{ $drive->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Engine Type (Fuel Type) --}}
                            <div class="col-md-4 mb-3">
                                <label for="engine_type_id" class="form-label small fw-bold text-muted">Fuel / Engine Type</label>
                                <select name="engine_type_id" id="engine_type_id" class="form-select border-0 bg-light rounded-3 shadow-none" required>
                                    <option value="">Select Fuel...</option>
                                    @foreach($engineTypes as $engine)
                                        <option value="{{ $engine->id }}">{{ $engine->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Production Year --}}
                            <div class="col-md-4 mb-3">
                                <label for="production_start" class="form-label small fw-bold text-muted">Year</label>
                                <input type="number" name="production_start" id="production_start" value="{{ date('Y') }}" 
                                       class="form-control border-0 bg-light rounded-3 shadow-none" required>
                            </div>

                            {{-- Steering --}}
                            <div class="col-md-4 mb-3">
                                <label for="steering_position" class="form-label small fw-bold text-muted">Steering Position</label>
                                <select name="steering_position" id="steering_position" class="form-select border-0 bg-light rounded-3 shadow-none">
                                    <option value="LHD">Left Hand Drive (LHD)</option>
                                    <option value="RHD">Right Hand Drive (RHD)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Horsepower --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label small fw-bold text-muted">Horsepower</label>
                                <input type="text" name="horsepower" class="form-control border-0 bg-light rounded-3 shadow-none" placeholder="e.g. 150 hp">
                            </div>
                            {{-- Torque --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label small fw-bold text-muted">Torque</label>
                                <input type="text" name="torque" class="form-control border-0 bg-light rounded-3 shadow-none" placeholder="e.g. 250 Nm">
                            </div>
                            {{-- Color --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Primary Color</label>
                                <input type="text" name="color" class="form-control border-0 bg-light rounded-3 shadow-none" placeholder="e.g. Black Metallic">
                            </div>
                        </div>

                        {{-- VIN --}}
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">VIN (17 Characters)</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light rounded-start-3"><i class="fas fa-fingerprint text-primary"></i></span>
                                <input type="text" name="vin" class="form-control border-0 bg-light rounded-end-3 shadow-none" placeholder="Enter VIN" maxlength="17">
                            </div>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_primary" id="is_primary" checked>
                            <label class="form-check-label small fw-bold text-dark" for="is_primary">Set as primary vehicle</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="{{ route('vehicles.index') }}" class="btn btn-link text-muted text-decoration-none px-0">Cancel</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Save Vehicle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-select, .form-control { padding: 0.75rem 1rem; }
    .form-select:focus, .form-control:focus {
        background-color: #fff !important;
        border: 1px solid #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1) !important;
    }
</style>
@endsection