@extends('admin.layouts.app')
@section('title', 'AutoSpareLink - Add Part Brand')
@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Part Brand</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Add Part Brand</li>
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
                    <h3 class="card-title">Add Part Brand</h3>
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

                    <form action="{{ route('admin.part-brands.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Brand Name -->
                        <div class="form-group">
                            <label for="name">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <!-- Brand Type -->
                        <!-- Brand Type -->
<div class="form-group">
    <label for="type">Brand Type <span class="text-danger">*</span></label>
    <select name="type" id="type" class="form-control" required>
        <option value="Aftermarket" {{ old('type')=='Aftermarket' ? 'selected' : '' }}>Aftermarket</option>
        <option value="OEM" {{ old('type')=='OEM' ? 'selected' : '' }}>OEM</option>
    </select>
</div>


                        <!-- Brand Logo -->
                        <div class="form-group">
                            <label for="logo">Brand Logo</label>
                            <input type="file" name="logo" id="logo" accept="image/png, image/jpeg, image/jpg" class="form-control"
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
                            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                        </div>

                        <!-- Country -->
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" name="country" id="country" class="form-control" value="{{ old('country') }}">
                        </div>

                        <!-- Submit -->
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Add Part Brand
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
