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
            <li class="breadcrumb-item active small fw-bold" aria-current="page">Add New Vehicle</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 me-3">
                            <i class="fas fa-plus-circle fa-lg"></i>
                        </div>
                        <div>
                            <h2 class="h4 fw-bold text-dark mb-0">Add to Garage</h2>
                            <p class="text-muted small mb-0">Provide detailed specifications for accurate part matching and service alerts.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('vehicles.store') }}" method="POST">
                        @csrf

                        {{-- Section 1: Identity --}}
                        <h6 class="text-primary fw-bold mb-3 d-flex align-items-center">
                            <span class="badge bg-primary me-2">1</span> Vehicle Identity
                        </h6>
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

                        {{-- Section 2: Technical Specs --}}
                        <h6 class="text-primary fw-bold mb-3 d-flex align-items-center">
                            <span class="badge bg-primary me-2">2</span> Technical Specifications
                        </h6>
                        
                        <div class="row">
                            {{-- Variant --}}
                            <div class="col-md-4 mb-3">
                                <label for="variant_id" class="form-label small fw-bold text-muted text-uppercase">Trim Level</label>
                                <select name="variant_id" id="variant_id" class="form-select border-0 bg-light rounded-3" :disabled="!selectedModel">
                                    <option value="">Select Trim...</option>
                                    @foreach($variants as $variant)
                                        <template x-if="selectedModel == '{{ $variant->vehicle_model_id }}'">
                                            <option value="{{ $variant->id }}" {{ old('variant_id') == $variant->id ? 'selected' : '' }}>
                                                {{ $variant->trim_level }}
                                            </option>
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

                            {{-- Fuel Type --}}
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
                            <div class="col-md-6 mb-3">
                                <label for="transmission_type_id" class="form-label small fw-bold text-muted text-uppercase">Transmission</label>
                                <select name="transmission_type_id" id="transmission_type_id" class="form-select border-0 bg-light rounded-3" required>
                                    <option value="">Choose Transmission...</option>
                                    @foreach($transmissionTypes as $trans)
                                        <option value="{{ $trans->id }}" {{ old('transmission_type_id') == $trans->id ? 'selected' : '' }}>{{ $trans->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="drive_type_id" class="form-label small fw-bold text-muted text-uppercase">Drive Type</label>
                                <select name="drive_type_id" id="drive_type_id" class="form-select border-0 bg-light rounded-3">
                                    <option value="">Choose Drive...</option>
                                    @foreach($driveTypes as $drive)
                                        <option value="{{ $drive->id }}" {{ old('drive_type_id') == $drive->id ? 'selected' : '' }}>{{ $drive->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">

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
                                    <option value="LHD">LHD (Left Hand Drive)</option>
                                    <option value="RHD">RHD (Right Hand Drive)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-md-8 mb-3">
                                <label for="vin" class="form-label small fw-bold text-muted text-uppercase">VIN (Optional)</label>
                                <input type="text" name="vin" id="vin" class="form-control border-0 bg-light rounded-3 @error('vin') is-invalid @enderror" maxlength="17" placeholder="Enter 17-digit VIN">
                                @error('vin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch p-3 bg-light rounded-3 border">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="is_primary" id="is_primary" value="1" checked>
                                    <label class="form-check-label small fw-bold text-dark" for="is_primary">Set as Default</label>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4">
                            <a href="{{ route('vehicles.index') }}" class="btn btn-link text-muted text-decoration-none fw-semibold">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                                Save to Garage <i class="fas fa-save ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1.25rem !important; }
    .bg-light { background-color: #f8f9fa !important; }
    .form-select, .form-control { transition: all 0.2s; border: 1px solid transparent !important; }
    .form-select:focus, .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1) !important;
        border: 1px solid #0d6efd !important;
    }
    .badge.bg-primary { padding: 0.5em 0.8em; border-radius: 0.5rem; }
    .breadcrumb-item + .breadcrumb-item::before { content: "›"; font-size: 1.2rem; vertical-align: middle; line-height: 1; }
</style>
@endsection