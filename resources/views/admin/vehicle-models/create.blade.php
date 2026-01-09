@extends('admin.layouts.app')
@section('title', 'AutoSpareLink - Add Vehicle Model')
@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Vehicle Model</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Add Vehicle Model</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary" x-data="{ photoPreview: null }">
                <div class="card-header">
                    <h3 class="card-title">Add Vehicle Model</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.vehicle-models.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Brand -->
                        <div class="form-group">
                            <label for="brand_id">Brand <span class="text-danger">*</span></label>
                            <select name="brand_id" id="brand_id" class="form-control" required>
                                <option value="">-- Select Brand --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->brand_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Model Name -->
                        <div class="form-group">
                            <label for="model_name">Model Name <span class="text-danger">*</span></label>
                            <input type="text" name="model_name" id="model_name" class="form-control" value="{{ old('model_name') }}" required>
                        </div>

                        <!-- Has Variants -->
<div class="form-group">
    <label>Does this model have variants?</label>
    <div class="custom-control custom-radio">
        <input type="radio" id="has_variants_yes" name="has_variants" value="1"
               class="custom-control-input"
               {{ old('has_variants', 1) == 1 ? 'checked' : '' }}>
        <label class="custom-control-label" for="has_variants_yes">Yes</label>
    </div>

    <div class="custom-control custom-radio">
        <input type="radio" id="has_variants_no" name="has_variants" value="0"
               class="custom-control-input"
               {{ old('has_variants') == 0 ? 'checked' : '' }}>
        <label class="custom-control-label" for="has_variants_no">No</label>
    </div>
</div>


                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                        </div>

                        <!-- Production Years -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="production_start_year">Start Year</label>
                                    <input type="number" name="production_start_year" id="production_start_year" class="form-control" value="{{ old('production_start_year') }}" min="1900" max="{{ date('Y') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="production_end_year">End Year</label>
                                    <input type="number" name="production_end_year" id="production_end_year" class="form-control" value="{{ old('production_end_year') }}" min="1900" max="{{ date('Y') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Photo -->
                        <div class="form-group">
                            <label for="photo">Vehicle Photo</label>
                            <input type="file" name="photo" id="photo" accept="image/png, image/jpeg, image/jpg" class="form-control"
                                   @change="photoPreview = URL.createObjectURL($event.target.files[0])">
                            <template x-if="photoPreview">
                                <div class="mt-2">
                                    <img :src="photoPreview" class="img-thumbnail" style="width:120px;">
                                </div>
                            </template>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1" {{ old('status',1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Add Vehicle Model
                            </button>
                        </div>

                    </form>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</section>

@endsection
