@extends('layouts.dashboard')

@section('title', 'Edit Vehicle')

@section('content')
{{-- Added photoPreview and file handling to x-data --}}
<div class="container py-4 py-lg-5" x-data="{ 
    selectedBrand: '{{ old('brand_id', $vehicle->brand_id) }}', 
    selectedModel: '{{ old('vehicle_model_id', $vehicle->vehicle_model_id) }}',
    photoPreview: '{{ $vehicle->vehicle_photo ? asset('storage/' . $vehicle->vehicle_photo) : null }}',
    previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            this.photoPreview = URL.createObjectURL(file);
        }
    }
}">
    
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('user.vehicles.index') }}" class="text-decoration-none text-muted small">Garage</a></li>
            <li class="breadcrumb-item active small fw-bold" aria-current="page">Edit {{ $vehicle->brand?->brand_name ?? 'Vehicle' }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                            <i class="fas fa-edit fa-lg"></i>
                        </div>
                        <div>
                            <h2 class="h4 fw-bold text-dark mb-0">Edit Vehicle</h2>
                            <p class="text-muted small mb-0">Update your {{ $vehicle->vehicleModel?->model_name }} details below.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {{-- Added enctype for handling the photo file --}}
                    <form action="{{ route('user.vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Section: Vehicle Photo (New) --}}
                        <div class="mb-5">
                            <h6 class="text-primary fw-bold mb-4 d-flex align-items-center">
                                <span class="badge rounded-pill bg-primary me-2">1</span> 
                                Vehicle Photo
                            </h6>
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="upload-container border-dashed rounded-4 text-center bg-light position-relative overflow-hidden" 
                                         style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                        
                                        <input type="file" name="vehicle_photo" id="vehicle_photo" 
                                               class="d-none" accept="image/*" @change="previewImage">
                                        
                                        <label for="vehicle_photo" class="mb-0 cursor-pointer w-100 h-100 d-block p-2">
                                            <template x-if="!photoPreview">
                                                <div class="py-5">
                                                    <i class="fas fa-camera fa-3x text-primary mb-3 opacity-50"></i>
                                                    <h6 class="fw-bold text-dark">Add a Photo</h6>
                                                    <p class="text-muted small mb-0">Recommended for better tracking</p>
                                                </div>
                                            </template>
                                            
                                            <template x-if="photoPreview">
                                                <div class="position-relative">
                                                    <img :src="photoPreview" class="rounded-4 img-fluid shadow-sm" style="max-height: 280px; width: 100%; object-fit: cover;">
                                                    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3">
                                                        <span class="btn btn-dark btn-sm rounded-pill px-4 shadow-lg">
                                                            <i class="fas fa-sync-alt me-1"></i> Replace Photo
                                                        </span>
                                                    </div>
                                                </div>
                                            </template>
                                        </label>
                                    </div>
                                    @error('vehicle_photo') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section 2: Basic Identity --}}
                        <div class="mb-5">
                            <h6 class="text-primary fw-bold mb-4 d-flex align-items-center">
                                <span class="badge rounded-pill bg-primary me-2">2</span> 
                                Basic Identity
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="brand_id" class="form-label small fw-bold text-muted text-uppercase">Brand</label>
                                    <select name="brand_id" id="brand_id" x-model="selectedBrand" @change="selectedModel = ''; $nextTick(() => { document.getElementById('trim_level').value = '' })"
                                            class="form-select border-0 bg-light rounded-3 py-2 @error('brand_id') is-invalid @enderror" required>
                                        <option value="">Choose Brand...</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="vehicle_model_id" class="form-label small fw-bold text-muted text-uppercase">Model</label>
                                    <select name="vehicle_model_id" id="vehicle_model_id" x-model="selectedModel" :disabled="!selectedBrand"
                                            class="form-select border-0 bg-light rounded-3 py-2 @error('vehicle_model_id') is-invalid @enderror" required>
                                        <option value="">Choose Model...</option>
                                        @foreach($models as $model)
                                            <template x-if="selectedBrand == '{{ $model->brand_id }}'">
                                                <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                                            </template>
                                        @endforeach
                                    </select>
                                    @error('vehicle_model_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="production_start" class="form-label small fw-bold text-muted text-uppercase">Year</label>
                                    <input type="number" name="production_start" id="production_start" value="{{ old('production_start', $vehicle->production_start) }}" 
                                           class="form-control border-0 bg-light rounded-3 py-2 @error('production_start') is-invalid @enderror" 
                                           min="1900" max="{{ date('Y') + 1 }}" required>
                                    @error('production_start') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section 3: Specifications --}}
                        <div class="mb-5">
                            <h6 class="text-primary fw-bold mb-4 d-flex align-items-center">
                                <span class="badge rounded-pill bg-primary me-2">3</span> 
                                Specifications
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="trim_level" class="form-label small fw-bold text-muted text-uppercase">Trim Level</label>
                                    <input type="text" name="trim_level" id="trim_level" list="trimOptions"
                                           value="{{ old('trim_level', $vehicle->trim_level) }}"
                                           class="form-control border-0 bg-light rounded-3 py-2 @error('trim_level') is-invalid @enderror" 
                                           placeholder="e.g. LE, Sport, Touring"
                                           :disabled="!selectedModel">
                                    <datalist id="trimOptions">
                                        @foreach($variants as $variant)
                                            <template x-if="selectedModel == '{{ $variant->vehicle_model_id }}'">
                                                <option value="{{ $variant->trim_level }}">
                                            </template>
                                        @endforeach
                                    </datalist>
                                </div>

                                <div class="col-md-4">
                                    <label for="body_type_id" class="form-label small fw-bold text-muted text-uppercase">Body Type</label>
                                    <select name="body_type_id" id="body_type_id" class="form-select border-0 bg-light rounded-3 py-2" required>
                                        @foreach($bodyTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('body_type_id', $vehicle->body_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="engine_type_id" class="form-label small fw-bold text-muted text-uppercase">Fuel Type</label>
                                    <select name="engine_type_id" id="engine_type_id" class="form-select border-0 bg-light rounded-3 py-2" required>
                                        @foreach($engineTypes as $fuel)
                                            <option value="{{ $fuel->id }}" {{ old('engine_type_id', $vehicle->engine_type_id) == $fuel->id ? 'selected' : '' }}>{{ $fuel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="transmission_type_id" class="form-label small fw-bold text-muted text-uppercase">Transmission</label>
                                    <select name="transmission_type_id" id="transmission_type_id" class="form-select border-0 bg-light rounded-3 py-2" required>
                                        @foreach($transmissionTypes as $trans)
                                            <option value="{{ $trans->id }}" {{ old('transmission_type_id', $vehicle->transmission_type_id) == $trans->id ? 'selected' : '' }}>{{ $trans->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="displacement" class="form-label small fw-bold text-muted text-uppercase">Engine Size</label>
                                    <input type="text" name="displacement" id="displacement" value="{{ old('displacement', $vehicle->displacement) }}" 
                                           class="form-control border-0 bg-light rounded-3 py-2" placeholder="e.g. 2.0L">
                                </div>

                                <div class="col-md-4">
                                    <label for="steering_position" class="form-label small fw-bold text-muted text-uppercase">Steering</label>
                                    <select name="steering_position" id="steering_position" class="form-select border-0 bg-light rounded-3 py-2">
                                        <option value="LHD" {{ $vehicle->steering_position == 'LHD' ? 'selected' : '' }}>Left Hand (LHD)</option>
                                        <option value="RHD" {{ $vehicle->steering_position == 'RHD' ? 'selected' : '' }}>Right Hand (RHD)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Section 4: Extra Info --}}
                        <div class="bg-light rounded-4 p-4 mb-4 border border-opacity-10">
                            <div class="row align-items-center g-3">
                                <div class="col-md-7">
                                    <label for="vin" class="form-label small fw-bold text-muted text-uppercase">VIN (Chassis Number)</label>
                                    <input type="text" name="vin" id="vin" value="{{ old('vin', $vehicle->vin) }}" 
                                           class="form-control border-white shadow-sm rounded-3 py-2" maxlength="17" placeholder="17-character VIN">
                                </div>
                                <div class="col-md-5">
                                    <div class="form-check form-switch p-3 bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-between">
                                        <label class="form-check-label small fw-bold text-dark mb-0 ms-2" for="is_primary">Set as Primary Vehicle</label>
                                        <input class="form-check-input me-2" type="checkbox" name="is_primary" id="is_primary" value="1" {{ $vehicle->is_primary ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('user.vehicles.index') }}" class="btn btn-link text-muted text-decoration-none fw-semibold px-0">
                                <i class="fas fa-arrow-left me-1"></i> Back to Garage
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow">
                                Update Vehicle <i class="fas fa-save ms-2"></i>
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
    .cursor-pointer { cursor: pointer; }
    .border-dashed { border: 2px dashed #dee2e6 !important; transition: all 0.3s ease; }
    .border-dashed:hover { border-color: #0d6efd !important; background-color: #f1f7ff !important; }

    .form-select, .form-control { transition: all 0.2s; border: 1px solid transparent !important; }
    .form-select:focus, .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02) !important;
        border: 1px solid #0d6efd !important;
    }
    .badge.bg-primary { width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
</style>
@endsection