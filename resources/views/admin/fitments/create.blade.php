@extends('admin.layouts.app')
@section('title', 'Add Fitment')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Add Fitment</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.fitments.index') }}">Part Fitments</a></li>
                    <li class="breadcrumb-item active">Add Fitment</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.fitments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Part</label>
                        <select name="part_id" class="form-control" required>
                            @foreach($parts as $part)
                                <option value="{{ $part->id }}">{{ $part->part_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vehicle Model</label>
                        <select name="vehicle_model_id" class="form-control" required>
                            @foreach($models as $model)
                                <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Variant (optional)</label>
                        <select name="variant_id" class="form-control">
                            <option value="">-- Select Variant --</option>
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Year Start</label>
                        <input type="number" name="year_start" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Year End</label>
                        <input type="number" name="year_end" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Photos</label>
                        <div x-data="{ previews: [] }">
                            <input type="file" name="photos[]" multiple accept="image/*" class="form-control"
                                @change="previews = Array.from($event.target.files).map(f => URL.createObjectURL(f))">
                            <template x-for="src in previews" :key="src">
                                <img :src="src" class="img-thumbnail mt-2 me-2" width="100">
                            </template>
                        </div>
                    </div>

                </div>

                <button class="btn btn-primary"><i class="fas fa-save"></i> Save Fitment</button>
            </form>
        </div>
    </div>

</section>

@endsection
