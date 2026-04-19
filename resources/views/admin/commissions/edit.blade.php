@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 pt-4">
                    <h4 class="font-weight-bold text-dark">Edit Commission Rate</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.commissions.update', $commission) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Commission Rate (%)</label>
                            <div class="input-group input-group-lg">
                                <input type="number" name="rate" step="0.01" value="{{ $commission->rate }}" class="form-control @error('rate') is-invalid @enderror" required>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-light">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ $commission->description }}</textarea>
                        </div>

                        <div class="form-group mb-4">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="isActive" {{ $commission->is_active ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold" for="isActive">Is Active</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.commissions.index') }}" class="btn btn-link text-muted">Back</a>
                            <button type="submit" class="btn btn-primary px-5 font-weight-bold">Update Rate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection