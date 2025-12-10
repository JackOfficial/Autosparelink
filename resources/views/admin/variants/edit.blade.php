@extends('admin.layouts.app')
@section('title', 'Edit Variant')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Edit Variant</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Edit Variant</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-10">
            <div class="card card-primary" x-data="{ photoPreview: '{{ $variant->photo ? asset('storage/'.$variant->photo) : '' }}' }">
                <div class="card-header"><h3 class="card-title">Edit Variant</h3></div>
                <div class="card-body">

                    @if($errors->any())
                        <div class="alert alert-danger"><ul class="mb-0">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul></div>
                    @endif

                    <form action="{{ route('admin.variants.update', $variant->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- General Info -->
                        <fieldset class="border p-3 mb-3">
                            <legend class="w-auto">General Info</legend>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="name">Variant Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           value="{{ old('name', $variant->name) }}" placeholder="Optional">
                                </div>
                                <div class="col-md-4">
                                    <label for="model_code">Model Code</label>
                                    <input type="text" name="model_code" id="model_code" class="form-control"
                                           value="{{ old('model_code', $variant->model_code) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="chassis_code">Chassis Code</label>
                                    <input type="text" name="chassis_code" id="chassis_code" class="form-control"
                                           value="{{ old('chassis_code', $variant->chassis_code) }}">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="vehicle_model_id">Vehicle Model <span class="text-danger">*</span></label>
                                    <select name="vehicle_model_id" id="vehicle_model_id" class="form-control" required>
                                        <option value="">Select Model</option>
                                        @foreach($vehicleModels as $model)
                                            <option value="{{ $model->id }}" {{ old('vehicle_model_id', $variant->vehicle_model_id) == $model->id ? 'selected' : '' }}>
                                                {{ $model->model_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="body_type_id">Body Type <span class="text-danger">*</span></label>
                                    <select name="body_type_id" id="body_type_id" class="form-control" required>
                                        <option value="">Select Body Type</option>
                                        @foreach($bodyTypes as $body)
                                            <option value="{{ $body->id }}" {{ old('body_type_id', $variant->body_type_id) == $body->id ? 'selected' : '' }}>
                                                {{ $body->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Engine & Transmission -->
                        <fieldset class="border p-3 mb-3">
                            <legend class="w-auto">Engine & Transmission</legend>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="engine_type_id">Engine Type <span class="text-danger">*</span></label>
                                    <select name="engine_type_id" id="engine_type_id" class="form-control" required>
                                        <option value="">Select Engine</option>
                                        @foreach($engineTypes as $engine)
                                            <option value="{{ $engine->id }}" {{ old('engine_type_id', $variant->engine_type_id) == $engine->id ? 'selected' : '' }}>
                                                {{ $engine->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="transmission_type_id">Transmission <span class="text-danger">*</span></label>
                                    <select name="transmission_type_id" id="transmission_type_id" class="form-control" required>
                                        <option value="">Select Transmission</option>
                                        @foreach($transmissionTypes as $trans)
                                            <option value="{{ $trans->id }}" {{ old('transmission_type_id', $variant->transmission_type_id) == $trans->id ? 'selected' : '' }}>
                                                {{ $trans->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="drive_type_id">Drive Type</label>
                                    <select name="drive_type_id" id="drive_type_id" class="form-control">
                                        <option value="">Select Drive Type</option>
                                        @foreach($driveTypes as $drive)
                                            <option value="{{ $drive->id }}" {{ old('drive_type_id', $variant->drive_type_id) == $drive->id ? 'selected' : '' }}>
                                                {{ $drive->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Performance & Specs -->
                        <fieldset class="border p-3 mb-3">
                            <legend class="w-auto">Performance & Specs</legend>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="seats">Seats</label>
                                    <input type="number" name="seats" id="seats" class="form-control"
                                           value="{{ old('seats', $variant->seats) }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="doors">Doors</label>
                                    <input type="number" name="doors" id="doors" class="form-control"
                                           value="{{ old('doors', $variant->doors) }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="horsepower">Horsepower</label>
                                    <input type="text" name="horsepower" id="horsepower" class="form-control"
                                           value="{{ old('horsepower', $variant->horsepower) }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="torque">Torque</label>
                                    <input type="text" name="torque" id="torque" class="form-control"
                                           value="{{ old('torque', $variant->torque) }}">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label for="fuel_capacity">Fuel Capacity</label>
                                    <input type="text" name="fuel_capacity" id="fuel_capacity" class="form-control"
                                           value="{{ old('fuel_capacity', $variant->fuel_capacity) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="fuel_efficiency">Fuel Efficiency</label>
                                    <input type="text" name="fuel_efficiency" id="fuel_efficiency" class="form-control"
                                           value="{{ old('fuel_efficiency', $variant->fuel_efficiency) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="steering_position">Steering Position</label>
                                    <select name="steering_position" id="steering_position" class="form-control">
                                        <option value="">Select Position</option>
                                        <option value="LEFT" {{ old('steering_position', $variant->steering_position) == 'LEFT' ? 'selected' : '' }}>Left-Hand Drive</option>
                                        <option value="RIGHT" {{ old('steering_position', $variant->steering_position) == 'RIGHT' ? 'selected' : '' }}>Right-Hand Drive</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Appearance & Production -->
                        <fieldset class="border p-3 mb-3">
                            <legend class="w-auto">Appearance & Production</legend>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="trim_level">Trim Level</label>
                                    <input type="text" name="trim_level" id="trim_level" class="form-control"
                                           value="{{ old('trim_level', $variant->trim_level) }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="color">Color</label>
                                    <input type="text" name="color" id="color" class="form-control"
                                           value="{{ old('color', $variant->color) }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="production_start">Production Start</label>
                                    <input type="text" name="production_start" id="production_start" class="form-control"
                                           value="{{ old('production_start', $variant->production_start) }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="production_end">Production End</label>
                                    <input type="text" name="production_end" id="production_end" class="form-control"
                                           value="{{ old('production_end', $variant->production_end) }}">
                                </div>
                            </div>
                        </fieldset>

                        <!-- Photo -->
                        <fieldset class="border p-3 mb-3">
                            <legend class="w-auto">Photo</legend>
                            <input type="file" name="photo" id="photo" accept="image/*" class="form-control"
                                   @change="photoPreview = URL.createObjectURL($event.target.files[0])">
                            <template x-if="photoPreview">
                                <div class="mt-2">
                                    <img :src="photoPreview" class="img-thumbnail" style="width: 120px;">
                                </div>
                            </template>
                        </fieldset>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Variant
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
