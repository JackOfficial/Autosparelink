@extends('admin.layouts.app')
@section('title', 'Edit Vehicle Model | ' . $vehicleModel->model_name)

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="font-weight-bold"><i class="fas fa-edit text-muted mr-2"></i>Edit Vehicle Model</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.vehicle-models.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.vehicle-models.update', $vehicleModel->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-outline card-primary shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">Model Information</h3>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5 class="small font-weight-bold"><i class="icon fas fa-ban"></i> Please fix the errors below:</h5>
                                    <ul class="mb-0 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Brand <span class="text-danger">*</span></label>
                                    <select name="brand_id" class="form-control shadow-none select2" required>
                                        <option value="">-- Select Brand --</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ (old('brand_id', $vehicleModel->brand_id) == $brand->id) ? 'selected' : '' }}>
                                                {{ $brand->brand_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Model Name <span class="text-danger">*</span></label>
                                    <input type="text" name="model_name" class="form-control shadow-none" value="{{ old('model_name', $vehicleModel->model_name) }}" placeholder="e.g. Corolla" required>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-muted">Description</label>
                                <textarea name="description" class="form-control shadow-none" rows="5" placeholder="Write a brief overview of this model series...">{{ old('description', $vehicleModel->description) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Production Start Year</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span></div>
                                        <input type="number" name="production_start_year" class="form-control shadow-none" value="{{ old('production_start_year', $vehicleModel->production_start_year) }}" min="1900" max="{{ date('Y')+1 }}">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Production End Year</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light"><i class="fas fa-history"></i></span></div>
                                        <input type="number" name="production_end_year" class="form-control shadow-none" value="{{ old('production_end_year', $vehicleModel->production_end_year) }}" min="1900" max="{{ date('Y')+10 }}" placeholder="Leave blank if current">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h3 class="card-title font-weight-bold small text-uppercase">Publishing</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="small font-weight-bold text-muted d-block">Status</label>
                                <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                    <label class="btn btn-outline-success flex-fill {{ old('status', $vehicleModel->status) == 1 ? 'active' : '' }}">
                                        <input type="radio" name="status" value="1" {{ old('status', $vehicleModel->status) == 1 ? 'checked' : '' }}> Active
                                    </label>
                                    <label class="btn btn-outline-secondary flex-fill {{ old('status', $vehicleModel->status) == 0 ? 'active' : '' }}">
                                        <input type="radio" name="status" value="0" {{ old('status', $vehicleModel->status) == 0 ? 'checked' : '' }}> Inactive
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary btn-block shadow-sm">
                                <i class="fa fa-save mr-1"></i> Save Changes
                            </button>
                        </div>
                    </div>

                    <div class="card shadow-sm" x-data="{ photoPreview: '{{ $vehicleModel->photo ? asset('storage/' . $vehicleModel->photo) : '' }}' }">
                        <div class="card-header bg-light">
                            <h3 class="card-title font-weight-bold small text-uppercase">Vehicle Photo</h3>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3 border rounded p-2 bg-light d-flex align-items-center justify-content-center" style="min-height: 150px;">
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                </template>
                                <template x-if="!photoPreview">
                                    <div class="text-muted small">
                                        <i class="fas fa-image fa-3x mb-2 d-block"></i>
                                        No image uploaded
                                    </div>
                                </template>
                            </div>
                            
                            <div class="custom-file">
                                <input type="file" name="photo" id="photo" class="custom-file-input" accept="image/*" @change="photoPreview = URL.createObjectURL($event.target.files[0])">
                                <label class="custom-file-label text-left" for="photo">Choose file</label>
                            </div>
                            <p class="text-xs text-muted mt-2 mb-0 italic">Recommended: 800x600px (JPG/PNG)</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection