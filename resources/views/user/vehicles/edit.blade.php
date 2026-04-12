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
            <li class="breadcrumb-item active small fw-bold" aria-current="page">Edit {{ $vehicle->brand->brand_name }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h2 class="h4 fw-bold text-dark mb-1">Edit Vehicle Details</h2>
                    <p class="text-muted small">Update your vehicle specifications for better part accuracy.</p>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h6 class="text-primary fw-bold mb-3">Basic Information</h6>
                        <div class="row">
                            {{-- Brand --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Vehicle Brand</label>
                                <select name="brand_id" x-model="selectedBrand" @change="selectedModel = ''"
                                        class="form-select border-0 bg-light rounded-3 @error('brand_id') is-invalid @enderror" required>
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
                                       class="form-control border-0 bg-light rounded-3" required>
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">
                        
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
                                <select name="body_type_id" class="form-select border-0 bg-light rounded-3" required>
                                    @foreach($bodyTypes as $type)
                                        <option value="{{ $type->id }}" {{ $vehicle->body_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Fuel Type --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Fuel Type</label>
                                <select name="engine_type_id" class="form-select border-0 bg-light rounded-3" required>
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

                        <hr class="my-4 opacity-25">

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Displacement</label>
                                <input type="text" name="displacement" value="{{ old('displacement', $vehicle->displacement) }}" class="form-control border-0 bg-light rounded-3">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Horsepower</label>
                                <input type="text" name="horsepower" value="{{ old('horsepower', $vehicle->horsepower) }}" class="form-control border-0 bg-light rounded-3">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">Steering</label>
                                <select name="steering_position" class="form-select border-0 bg-light rounded-3">
                                    <option value="LHD" {{ $vehicle->steering_position == 'LHD' ? 'selected' : '' }}>LHD</option>
                                    <option value="RHD" {{ $vehicle->steering_position == 'RHD' ? 'selected' : '' }}>RHD</option>
                                </select>
                            </div>
                        </div>

                        <div class="row align-items-end">
                            <div class="col-md-8 mb-3">
                                <label class="form-label small fw-bold text-muted text-uppercase">VIN</label>
                                <input type="text" name="vin" value="{{ old('vin', $vehicle->vin) }}" class="form-control border-0 bg-light rounded-3" maxlength="17">
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_primary" id="is_primary" value="1" {{ $vehicle->is_primary ? 'checked' : '' }}>
                                    <label class="form-check-label small fw-bold" for="is_primary">Primary Vehicle</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-2">
                            <a href="{{ route('vehicles.index') }}" class="btn btn-link text-muted text-decoration-none px-0">Cancel</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                Update Vehicle <i class="fas fa-check-circle ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection