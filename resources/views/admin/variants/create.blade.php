@extends('admin.layouts.app')

@section('title', 'Add Variant')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Variant</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.variants.index') }}">Variants</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">

    <div class="row">
        <div class="col-md-10">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Variant Details</h3>
                </div>

                <form action="{{ route('admin.variants.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf

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
                                    <label>Vehicle Model <span class="text-danger">*</span></label>
                                    <select name="vehicle_model_id"
                                            class="form-control"
                                            required>
                                        <option value="">Select Model</option>
                                        @foreach($vehicleModels as $model)
                                            <option value="{{ $model->id }}"
                                                {{ old('vehicle_model_id') == $model->id ? 'selected' : '' }}>
                                                {{ $model->model_name }}
                                                ({{ $model->brand->brand_name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>Variant Name</label>
                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           value="{{ old('name') }}"
                                           placeholder="e.g. Corolla 1.8L Sedan">
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
                                           value="{{ old('chassis_code') }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Model Code</label>
                                    <input type="text"
                                           name="model_code"
                                           class="form-control"
                                           value="{{ old('model_code') }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Trim Level</label>
                                    <input type="text"
                                           name="trim_level"
                                           class="form-control"
                                           value="{{ old('trim_level') }}"
                                           placeholder="LE, SE, GX, Sport">
                                </div>
                            </div>

                        </fieldset>

                        {{-- MEDIA --}}
                        <fieldset class="border p-3 mb-4">
                            <legend class="w-auto px-2">Media</legend>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Default Photo</label>
                                    <input type="file"
                                           name="photo"
                                           class="form-control"
                                           accept="image/*">
                                    <small class="text-muted">
                                        Optional. Used as default variant image.
                                    </small>
                                </div>

                                <div class="col-md-6">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </fieldset>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Save Variant
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
