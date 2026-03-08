@extends('admin.layouts.app')
@section('title', 'AutoSpareLink - Add Engine Type')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Engine Type</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Add Engine Type</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary" x-data="{ iconPreview: null }">
                <div class="card-header">
                    <h3 class="card-title">Add Engine Type</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
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

                    <form action="{{ route('admin.engine-types.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Name -->
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <!-- Icon -->
                        <div class="form-group">
                            <label for="icon_url">Icon</label>
                            <input type="file" name="icon_url" id="icon_url" accept="image/*" class="form-control"
                                   @change="iconPreview = URL.createObjectURL($event.target.files[0])">
                            <template x-if="iconPreview">
                                <div class="mt-2">
                                    <img :src="iconPreview" class="img-thumbnail" style="width: 120px;">
                                </div>
                            </template>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                        </div>

                        <!-- Submit -->
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Add Engine Type
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
