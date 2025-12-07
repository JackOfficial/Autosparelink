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
        <div class="col-md-8">
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

                        <!-- Same form fields as create, but pre-filled -->
                        <div class="form-group">
                            <label for="vehicle_model_id">Vehicle Model <span class="text-danger">*</span></label>
                            <select name="vehicle_model_id" id="vehicle_model_id" class="form-control" required>
                                <option value="">Select Model</option>
                                @foreach($vehicleModels as $model)
                                    <option value="{{ $model->id }}" {{ $variant->vehicle_model_id == $model->id ? 'selected' : '' }}>
                                        {{ $model->model_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="body_type_id">Body Type <span class="text-danger">*</span></label>
                            <select name="body_type_id" id="body_type_id" class="form-control" required>
                                <option value="">Select Body Type</option>
                                @foreach($bodyTypes as $body)
                                    <option value="{{ $body->id }}" {{ $variant->body_type_id == $body->id ? 'selected' : '' }}>
                                        {{ $body->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="engine_type_id">Engine Type <span class="text-danger">*</span></label>
                            <select name="engine_type_id" id="engine_type_id" class="form-control" required>
                                <option value="">Select Engine Type</option>
                                @foreach($engineTypes as $engine)
                                    <option value="{{ $engine->id }}" {{ $variant->engine_type_id == $engine->id ? 'selected' : '' }}>
                                        {{ $engine->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="transmission_type_id">Transmission Type <span class="text-danger">*</span></label>
                            <select name="transmission_type_id" id="transmission_type_id" class="form-control" required>
                                <option value="">Select Transmission</option>
                                @foreach($transmissionTypes as $trans)
                                    <option value="{{ $trans->id }}" {{ $variant->transmission_type_id == $trans->id ? 'selected' : '' }}>
                                        {{ $trans->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Other fields prefilled -->
                        <input type="text" name="fuel_capacity" class="form-control" value="{{ $variant->fuel_capacity }}">
                        <input type="text" name="drive_type" class="form-control" value="{{ $variant->drive_type }}">
                        <input type="number" name="seats" class="form-control" value="{{ $variant->seats }}">
                        <input type="number" name="doors" class="form-control" value="{{ $variant->doors }}">
                        <input type="text" name="horsepower" class="form-control" value="{{ $variant->horsepower }}">
                        <input type="text" name="torque" class="form-control" value="{{ $variant->torque }}">
                        <input type="text" name="fuel_efficiency" class="form-control" value="{{ $variant->fuel_efficiency }}">

                        <!-- Photo -->
                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <input type="file" name="photo" id="photo" accept="image/*" class="form-control"
                                   @change="photoPreview = URL.createObjectURL($event.target.files[0])">
                            <template x-if="photoPreview">
                                <div class="mt-2">
                                    <img :src="photoPreview" class="img-thumbnail" style="width: 120px;">
                                </div>
                            </template>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Variant</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
