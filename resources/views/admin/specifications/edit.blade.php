@extends('admin.layouts.app')

@section('title', 'Edit Specification')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Specification</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.specifications.index') }}">Specifications</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="row">
<div class="col-md-10">

<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Vehicle Specification (Trim)</h3>
    </div>

    <form action="{{ route('admin.specifications.update', $specification->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- VEHICLE CONTEXT --}}
            <fieldset class="border p-3 mb-3">
                <legend class="w-auto">Vehicle Context</legend>

                <div class="row">
                    <div class="col-md-6">
                        <label>Vehicle Model <span class="text-danger">*</span></label>
                        <select name="vehicle_model_id" class="form-control" required>
                            @foreach($vehicleModels as $model)
                                <option value="{{ $model->id }}"
                                    {{ $specification->vehicle_model_id == $model->id ? 'selected' : '' }}>
                                    {{ $model->model_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Variant (Optional)</label>
                        <select name="variant_id" class="form-control">
                            <option value="">— Model Level Spec —</option>
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}"
                                    {{ $specification->variant_id == $variant->id ? 'selected' : '' }}>
                                    {{ $variant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </fieldset>

            {{-- ENGINE & DRIVETRAIN --}}
            <fieldset class="border p-3 mb-3">
                <legend class="w-auto">Engine & Drivetrain</legend>

                <div class="row">
                    <div class="col-md-4">
                        <label>Engine Type <span class="text-danger">*</span></label>
                        <select name="engine_type_id" class="form-control" required>
                            @foreach($engineTypes as $engine)
                                <option value="{{ $engine->id }}"
                                    {{ $specification->engine_type_id == $engine->id ? 'selected' : '' }}>
                                    {{ $engine->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Transmission <span class="text-danger">*</span></label>
                        <select name="transmission_type_id" class="form-control" required>
                            @foreach($transmissionTypes as $trans)
                                <option value="{{ $trans->id }}"
                                    {{ $specification->transmission_type_id == $trans->id ? 'selected' : '' }}>
                                    {{ $trans->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Drive Type</label>
                        <select name="drive_type_id" class="form-control">
                            <option value="">N/A</option>
                            @foreach($driveTypes as $drive)
                                <option value="{{ $drive->id }}"
                                    {{ $specification->drive_type_id == $drive->id ? 'selected' : '' }}>
                                    {{ $drive->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </fieldset>

            {{-- BODY & PERFORMANCE --}}
            <fieldset class="border p-3 mb-3">
                <legend class="w-auto">Body & Performance</legend>

                <div class="row">
                    <div class="col-md-3">
                        <label>Body Type</label>
                        <select name="body_type_id" class="form-control">
                            @foreach($bodyTypes as $body)
                                <option value="{{ $body->id }}"
                                    {{ $specification->body_type_id == $body->id ? 'selected' : '' }}>
                                    {{ $body->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Seats</label>
                        <input type="number" name="seats" class="form-control"
                               value="{{ old('seats', $specification->seats) }}">
                    </div>

                    <div class="col-md-3">
                        <label>Doors</label>
                        <input type="number" name="doors" class="form-control"
                               value="{{ old('doors', $specification->doors) }}">
                    </div>

                    <div class="col-md-3">
                        <label>Steering Position</label>
                        <select name="steering_position" class="form-control">
                            <option value="">Select</option>
                            <option value="LEFT" {{ $specification->steering_position == 'LEFT' ? 'selected' : '' }}>Left</option>
                            <option value="RIGHT" {{ $specification->steering_position == 'RIGHT' ? 'selected' : '' }}>Right</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            {{-- PRODUCTION --}}
            <fieldset class="border p-3 mb-3">
                <legend class="w-auto">Production</legend>

                <div class="row">
                    <div class="col-md-4">
                        <label>Production Start</label>
                        <input type="number" name="production_start" class="form-control"
                               value="{{ old('production_start', $specification->production_start) }}">
                    </div>

                    <div class="col-md-4">
                        <label>Production End</label>
                        <input type="number" name="production_end" class="form-control"
                               value="{{ old('production_end', $specification->production_end) }}">
                    </div>

                    <div class="col-md-4">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $specification->status ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$specification->status ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </fieldset>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-warning">
                <i class="fa fa-save"></i> Update Specification
            </button>
            <a href="{{ route('admin.specifications.index') }}" class="btn btn-secondary float-right">
                Cancel
            </a>
        </div>

    </form>
</div>

</div>
</div>
</section>

@endsection
