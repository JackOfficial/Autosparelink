@extends('admin.layouts.app')

@section('title', 'Edit Variant')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="fw-bold text-dark">Edit Variant</h1>
                <p class="text-muted mb-0">Update technical identifiers and media.</p>
            </div>
            <div class="col-sm-6 text-end">
                <a href="{{ route('admin.variants.show', $variant->id) }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="fas fa-eye me-1"></i> View Details
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-11">

                {{-- Initialize Alpine with current data and Spec constants --}}
                <div class="card border-0 shadow-sm" 
                     x-data="{ 
                        photoPreview: '{{ $variant->photo ? asset('storage/'.$variant->photo) : null }}',
                        brand: '{{ $variant->vehicleModel->brand->brand_name ?? '' }}',
                        model: '{{ $variant->vehicleModel->model_name ?? '' }}',
                        trim: '{{ old('trim_level', $variant->trim_level) }}',
                        year: '{{ old('production_year', $variant->production_year) }}',
                        {{-- Static spec parts that only change if they edit the Specification record --}}
                        specParts: '{{ trim(($variant->specifications->first()->bodyType->name ?? "") . " " . ($variant->specifications->first()->engineDisplacement->name ?? "") . " " . ($variant->specifications->first()->engineType->name ?? "") . " " . ($variant->specifications->first()->transmissionType->name ?? "")) }}',
                        
                        get liveName() {
                            return [this.brand, this.model, this.trim, this.specParts, this.year]
                                .filter(i => i && i.trim() !== '')
                                .join(' ');
                        }
                     }">

                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning-soft p-2 rounded me-3">
                                <i class="fas fa-car-side text-warning fa-lg"></i>
                            </div>
                            <div>
                                <h3 class="card-title fw-bold d-block mb-0">System Generated Name</h3>
                                <span class="text-primary fw-bold fs-5" x-text="liveName"></span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.variants.update', $variant->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div class="alert alert-danger border-0 shadow-sm">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                {{-- LEFT COLUMN: Details --}}
                                <div class="col-md-8">
                                    <div class="row g-4">
                                        {{-- Identifiers Section --}}
                                        <div class="col-12">
                                            <h6 class="text-uppercase text-muted fw-bold small mb-3">Core Information</h6>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Vehicle Model</label>
                                                    <input type="text" class="form-control bg-light" value="{{ $variant->vehicleModel->model_name }} ({{ $variant->vehicleModel->brand->brand_name }})" readonly>
                                                    <input type="hidden" name="vehicle_model_id" value="{{ $variant->vehicle_model_id }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Production Year <span class="text-danger">*</span></label>
                                                    <input type="number" name="production_year" x-model="year" class="form-control" placeholder="e.g. 2024" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Trim Level</label>
                                                    <input type="text" name="trim_level" x-model="trim" class="form-control" placeholder="e.g. SE, Limited, AMG">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Chassis Code</label>
                                                    <input type="text" name="chassis_code" class="form-control" value="{{ old('chassis_code', $variant->chassis_code) }}">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Model Code</label>
                                                    <input type="text" name="model_code" class="form-control" value="{{ old('model_code', $variant->model_code) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- RIGHT COLUMN: Media & Status --}}
                                <div class="col-md-4 border-start ps-4">
                                    <h6 class="text-uppercase text-muted fw-bold small mb-3">Media & Visibility</h6>
                                    
                                    <div class="mb-4">
                                        <label class="form-label d-block">Variant Image</label>
                                        <div class="position-relative mb-2">
                                            <template x-if="photoPreview">
                                                <img :src="photoPreview" class="img-thumbnail w-100 shadow-sm" style="height: 180px; object-fit: contain;">
                                            </template>
                                            <template x-if="!photoPreview">
                                                <div class="bg-light d-flex align-items-center justify-content-center border rounded" style="height: 180px;">
                                                    <i class="fas fa-image fa-3x text-muted opacity-25"></i>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="custom-file-input">
                                            <input type="file" name="photo" class="form-control form-control-sm" accept="image/*"
                                                @change="const file = $event.target.files[0]; if (file) { photoPreview = URL.createObjectURL(file); }">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Publishing Status</label>
                                        <select name="status" class="form-select shadow-sm">
                                            <option value="1" {{ old('status', $variant->status) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status', $variant->status) == 0 ? 'selected' : '' }}>Inactive / Draft</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light p-4 text-end">
                            <a href="{{ route('admin.variants.index') }}" class="btn btn-link text-muted me-3">Discard Changes</a>
                            <button type="submit" class="btn btn-warning px-5 fw-bold shadow-sm">
                                <i class="fas fa-sync-alt me-2"></i> Update & Re-Sync Name
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .bg-warning-soft { background-color: #fff3cd; }
    .form-label { font-weight: 600; font-size: 0.85rem; color: #4a5568; }
    .form-control:focus, .form-select:focus { border-color: #ffc107; box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.15); }
</style>
@endsection