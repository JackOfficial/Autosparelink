@extends('admin.layouts.app')
@section('title', 'Edit Fitment')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Edit Fitment</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.fitments.index') }}">Part Fitments</a></li>
                    <li class="breadcrumb-item active">Edit Fitment</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.fitments.update', $fitment->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="row">

                    <!-- Part -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Part</label>
                        <select name="part_id" class="form-control" required>
                            @foreach($parts as $part)
                                <option value="{{ $part->id }}" {{ $fitment->part_id == $part->id ? 'selected' : '' }}>
                                    {{ $part->part_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Vehicle Model -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vehicle Model</label>
                        <select name="vehicle_model_id" class="form-control" required>
                            @foreach($models as $model)
                                <option value="{{ $model->id }}" {{ $fitment->vehicle_model_id == $model->id ? 'selected' : '' }}>
                                    {{ $model->model_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Variant -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Variant</label>
                        <select name="variant_id" class="form-control">
                            <option value="">-- Select Variant --</option>
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}" {{ $fitment->variant_id == $variant->id ? 'selected' : '' }}>
                                    {{ $variant->name ?? $variant->model_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="active" {{ $fitment->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $fitment->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Year Start -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Year Start</label>
                        <input type="number" name="year_start" value="{{ $fitment->year_start }}" class="form-control">
                    </div>

                    <!-- Year End -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Year End</label>
                        <input type="number" name="year_end" value="{{ $fitment->year_end }}" class="form-control">
                    </div>

                    <!-- Add New Photos -->
                    <div class="col-md-12 mb-3" x-data="{ previews: [] }">
                        <label class="form-label">Add New Photos</label>
                        <input type="file" name="photos[]" multiple accept="image/*" class="form-control"
                               @change="previews = Array.from($event.target.files).map(f => URL.createObjectURL(f))">
                        <template x-for="src in previews" :key="src">
                            <img :src="src" class="img-thumbnail mt-2 me-2" width="100">
                        </template>
                    </div>

                    <!-- Existing Photos (read-only) -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Existing Photos</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($fitment->photos as $photo)
                                <img src="{{ asset('uploads/part_photos/'.$photo->photo_url) }}" width="100" class="img-thumbnail">
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Fitment</button>
                </div>

            </form>
        </div>
    </div>

</section>

@endsection
