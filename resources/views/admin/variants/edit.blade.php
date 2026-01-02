@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Variant</h5>
                    <a href="{{ route('admin.variants.index') }}" class="btn btn-sm btn-secondary">
                        Back
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.variants.update', $variant->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Vehicle Model --}}
                        <div class="mb-3">
                            <label class="form-label">Vehicle Model</label>
                            <select name="vehicle_model_id" class="form-select" required>
                                <option value="">-- Select Model --</option>
                                @foreach ($vehicleModels as $model)
                                    <option value="{{ $model->id }}"
                                        {{ $variant->vehicle_model_id == $model->id ? 'selected' : '' }}>
                                        {{ $model->model_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Variant Name --}}
                        <div class="mb-3">
                            <label class="form-label">Variant Name</label>
                            <input type="text"
                                   name="variant_name"
                                   class="form-control"
                                   value="{{ old('variant_name', $variant->variant_name) }}"
                                   required>
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="3">{{ old('description', $variant->description) }}</textarea>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ $variant->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $variant->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                Update Variant
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
