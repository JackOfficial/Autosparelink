@extends('admin.layouts.app')

@section('title', 'Edit Variant')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Variant</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.variants.index') }}">Variants</a>
                    </li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">

    <div class="row">
        <div class="col-md-10">

            <!-- Alpine initialized -->
            <div class="card card-warning"
                 x-data="{
                    photoPreview: '{{ $variant->photo ? asset('storage/'.$variant->photo) : null }}'
                 }">

                <div class="card-header">
                    <h3 class="card-title">Edit Variant Details</h3>
                </div>

                <form action="{{ route('admin.variants.update', $variant->id) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        {{-- ERRORS --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- GENERAL --}}
                        <fieldset class="border p-3 mb-4">
                            <legend class="w-auto px-2">General Information</legend>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>
                                        Vehicle Model <span class="text-danger">*</span>
                                    </label>
                                    <select name="vehicle_model_id"
                                            class="form-control"
                                            required>
                                        @foreach($vehicleModels as $model)
                                            <option value="{{ $model->id }}"
                                                {{ old('vehicle_model_id', $variant->vehicle_model_id) == $model->id ? 'selected' : '' }}>
                                                {{ $model->model_name }}
                                                ({{ $model->brand->brand_name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>Variant Name (Auto Generated)</label>
                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           value="{{ old('name', $variant->name) }}"
                                           placeholder="e.g. Corolla 1.8L Sedan" readonly>
                                </div>
                            </div>
                        </fieldset>

                        {{-- IDENTIFIERS --}}
                        <fieldset class="border p-3 mb-4">
                            <legend class="w-auto px-2">Identifiers</legend>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>Chassis Code</label>
                                    <input type="text"
                                           name="chassis_code"
                                           class="form-control"
                                           value="{{ old('chassis_code', $variant->chassis_code) }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Model Code</label>
                                    <input type="text"
                                           name="model_code"
                                           class="form-control"
                                           value="{{ old('model_code', $variant->model_code) }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Trim Level</label>
                                    <input type="text"
                                           name="trim_level"
                                           class="form-control"
                                           value="{{ old('trim_level', $variant->trim_level) }}">
                                </div>
                            </div>
                        </fieldset>

                        {{-- MEDIA --}}
                        <fieldset class="border p-3 mb-4">
                            <legend class="w-auto px-2">Media</legend>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Variant Photo</label>

                                    <input type="file"
                                           name="photo"
                                           class="form-control"
                                           accept="image/*"
                                           @change="
                                                const file = $event.target.files[0];
                                                if (file) {
                                                    if (photoPreview) {
                                                        URL.revokeObjectURL(photoPreview);
                                                    }
                                                    photoPreview = URL.createObjectURL(file);
                                                }
                                           ">

                                    <template x-if="photoPreview">
                                        <div class="mt-2">
                                            <img :src="photoPreview"
                                                 class="img-thumbnail"
                                                 style="max-width:160px;">
                                        </div>
                                    </template>

                                    <small class="text-muted">
                                        Uploading a new image will replace the existing one.
                                    </small>
                                </div>

                                <div class="col-md-6">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1"
                                            {{ old('status', $variant->status) == 1 ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="0"
                                            {{ old('status', $variant->status) == 0 ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fa fa-save"></i> Update Variant
                        </button>

                        <a href="{{ route('admin.variants.index') }}"
                           class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>

        </div>
    </div>

</div>
</section>

@endsection
