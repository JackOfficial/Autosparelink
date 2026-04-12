@extends('layouts.dashboard')

@section('title', 'Edit Vehicle')

@section('content')
<div class="container py-4 py-lg-5" x-data="{ 
    selectedBrand: '{{ old('brand_id', $vehicle->brand_id) }}', 
    selectedModel: '{{ old('vehicle_model_id', $vehicle->vehicle_model_id) }}' 
}">
    
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('vehicles.index') }}" class="text-decoration-none text-muted small">Garage</a></li>
            <li class="breadcrumb-item active small fw-bold" aria-current="page">
                {{-- Null-safe check for the breadcrumb --}}
                Edit {{ $vehicle->brand?->brand_name ?? 'Vehicle' }}
            </li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3">
                            <i class="fas fa-edit fa-lg"></i>
                        </div>
                        <div>
                            <h2 class="h4 fw-bold text-dark mb-0">Edit Vehicle Details</h2>
                            <p class="text-muted small mb-0">Keep your specifications accurate for the best parts matching.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h6 class="text-primary fw-bold mb-3 d-flex align-items-center">
                            <span class="badge bg-primary me-2">1</span> Basic Information
                        </h6>
                        <div class="row">
                            {{-- Brand --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Vehicle Brand</label>
                                <select name="brand_id" x-model="selectedBrand" @change="selectedModel = ''"
                                        class="form-select border-0 bg-light rounded-3 @error('brand_id') is-invalid @enderror" required>
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Model --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Vehicle Model</label>
                                <select name="vehicle_model_id" x-model="selectedModel" :disabled="!selectedBrand"
                                        class="form-select border-0 bg-light rounded-3 @error('vehicle_model_id') is-invalid @enderror" required>
                                    <option value="">Select Model</option>
                                    @foreach($models as $model)
                                        <template x-if="selectedBrand == '{{ $model->brand_id }}'">
                                            <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                                        </template>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Year --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Manufacturing Year</label>
                                <input type="number" name="production_start" value="{{ old('production_start', $vehicle->production_start) }}" 
                                       class="form-control border-0 bg-light rounded-3 @error('production_start') is-invalid @enderror" required>
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">
                        <h6 class="text-primary fw-bold mb-3 d-flex align-items-center">
                            <span class="badge bg-primary me-2">2</span> Build & Engine
                        </h6>
                        
                        <div class="row">
                            {{-- Variant (Trim) --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Trim Level</label>
                                <select name="variant_id" class="form-select border-0 bg-light rounded-3">
                                    <option value="">Select Trim...</option>
                                    @foreach($variants as $variant)
                                        <template x-if="selectedModel == '{{ $variant->vehicle_model_id }}'">
                                            <option value="{{ $variant->id }}" {{ $vehicle->variant_id == $variant->id ? 'selected' : '' }}>
                                                {{ $variant->trim_level }}
                                            </option>
                                        </template>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Body Type --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Body Type</label>
                                <select name="body_type_id" class="form-select border-0 bg-light rounded-3 @error('body_type_id') is-invalid @enderror" required>
                                    @foreach($bodyTypes as $type)
                                        <option value="{{ $type->id }}" {{ $vehicle->body_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Fuel Type --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Fuel Type</label>
                                <select name="engine_type_id" class="form-select border-0 bg-light rounded-3 @error('engine_type_id') is-invalid @enderror" required>
                                    @foreach($engineTypes as $fuel)
                                        <option value="{{ $fuel->id }}" {{ $vehicle->engine_type_id == $fuel->id ? 'selected' : '' }}>{{ $fuel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Transmission</label>
                                <select name="transmission_type_id" class="form-select border-0 bg-light rounded-3">
                                    @foreach($transmissionTypes as $trans)
                                        <option value="{{ $trans->id }}" {{ $vehicle->transmission_type_id == $trans->id ? 'selected' : '' }}>{{ $trans->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Drive Type</label>
                                <select name="drive_type_id" class="form-select border-0 bg-light rounded-3">
                                    @foreach($driveTypes as $drive)
                                        <option value="{{ $drive->id }}" {{ $vehicle->drive_type_id == $drive->id ? 'selected' : '' }}>{{ $drive->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Displacement</label>
                                <input type="text" name="displacement" value="{{ old('displacement', $vehicle->displacement) }}" 
                                       class="form-control border-0 bg-light rounded-3" placeholder="e.g. 2.4L">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Horsepower</label>
                                <input type="text" name="horsepower" value="{{ old('horsepower', $vehicle->horsepower) }}" 
                                       class="form-control border-0 bg-light rounded-3" placeholder="e.g. 190hp">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Steering</label>
                                <select name="steering_position" class="form-select border-0 bg-light rounded-3">
                                    <option value="LHD" {{ $vehicle->steering_position == 'LHD' ? 'selected' : '' }}>LHD (Left Hand)</option>
                                    <option value="RHD" {{ $vehicle->steering_position == 'RHD' ? 'selected' : '' }}>RHD (Right Hand)</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">

                        <div class="row align-items-center">
                            <div class="col-md-8 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Vehicle Identification Number (VIN)</label>
                                <input type="text" name="vin" value="{{ old('vin', $vehicle->vin) }}" 
                                       class="form-control border-0 bg-light rounded-3 @error('vin') is-invalid @enderror" maxlength="17" placeholder="17-character VIN">
                                @error('vin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch p-3 bg-light rounded-3 border">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="is_primary" id="is_primary" value="1" {{ $vehicle->is_primary ? 'checked' : '' }}>
                                    <label class="form-check-label small fw-bold text-dark" for="is_primary">Set as Default</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4">
                            <a href="{{ route('vehicles.index') }}" class="btn btn-link text-muted text-decoration-none fw-semibold">
                                <i class="fas fa-arrow-left me-1"></i> Back to Garage
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                                Save Changes <i class="fas fa-save ms-2"></i>
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
    .form-select, .form-control { transition: all 0.2s; }
    .form-select:focus, .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1) !important;
        border: 1px solid #0d6efd !important;
    }
    .badge.bg-primary { padding: 0.5em 0.8em; border-radius: 0.5rem; }
</style>
@endsection