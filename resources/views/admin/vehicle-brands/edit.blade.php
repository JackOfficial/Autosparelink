@extends('admin.layouts.app')
@section('title', 'AutoSpareLink - Edit Brand')
@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Brand</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Edit Brand</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary" 
                 x-data="{ 
                     photoPreview: '{{ $brand->brand_logo ? asset('storage/' . $brand->brand_logo) : '' }}' 
                 }">
                <div class="card-header">
                    <h3 class="card-title">Edit Brand</h3>
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

                    <form action="{{ route('admin.vehicle-brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Brand Name -->
                        <div class="form-group">
                            <label for="brand_name">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" name="brand_name" id="brand_name" class="form-control" 
                                   value="{{ old('brand_name', $brand->brand_name) }}" required>
                        </div>

                        <!-- Brand Logo -->
                        <div class="form-group">
                            <label for="brand_logo">Brand Logo</label>
                            <input type="file" name="brand_logo" id="brand_logo" accept="image/png, image/jpeg, image/jpg, image/webp" 
                                   class="form-control"
                                   @change="photoPreview = URL.createObjectURL($event.target.files[0])">
                            <template x-if="photoPreview">
                                <div class="mt-2">
                                    <img :src="photoPreview" class="img-thumbnail" style="width: 120px;">
                                </div>
                            </template>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $brand->description) }}</textarea>
                        </div>

                        <!-- Country & Website -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <input type="text" name="country" id="country" class="form-control" 
                                           value="{{ old('country', $brand->country) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="website">Website</label>
                                    <input type="url" name="website" id="website" class="form-control" 
                                           value="{{ old('website', $brand->website) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Brand
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
