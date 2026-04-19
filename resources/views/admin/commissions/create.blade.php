@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 pt-4">
                    <h4 class="font-weight-bold text-dark">Set New Commission Rate</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.commissions.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Commission Rate (%)</label>
                            <div class="input-group input-group-lg">
                                <input type="number" name="rate" step="0.01" class="form-control @error('rate') is-invalid @enderror" placeholder="e.g. 10.00" required>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-light">%</span>
                                </div>
                                @error('rate') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <small class="form-text text-muted text-italic">This percentage will be deducted from vendor sales.</small>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Description / Note</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="e.g. Standard rate for 2026"></textarea>
                        </div>

                        <div class="form-group mb-4">
                            <div class="custom-control custom-switch custom-switch-lg">
                                <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="isActive" checked>
                                <label class="custom-control-label font-weight-bold" for="isActive">Activate immediately</label>
                            </div>
                            <small class="text-danger small">Note: Activating this will automatically deactivate the previous rate.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.commissions.index') }}" class="btn btn-link text-muted">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 font-weight-bold">Save Commission</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection