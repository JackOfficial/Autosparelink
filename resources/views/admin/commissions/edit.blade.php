@extends('admin.layouts.app')

@section('title', 'Edit Commission | Autosparelink')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 pt-4">
                    <h4 class="font-weight-bold text-dark">
                        <i class="fas fa-edit mr-2 text-primary"></i>Edit Commission Rate
                    </h4>
                </div>
                <div class="card-body">
                    @role('super-admin')
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
                                    @error('rate') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Description</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="e.g. Standard rate update">{{ $commission->description }}</textarea>
                            </div>

                            <div class="form-group mb-4">
                                <div class="custom-control custom-switch custom-switch-lg">
                                    <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="isActive" {{ $commission->is_active ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="isActive">Is Active</label>
                                </div>
                                <small class="text-muted small">Toggling this to active will deactivate the currently running rate.</small>
                            </div>

                            <div class="d-flex justify-content-between pt-3">
                                <a href="{{ route('admin.commissions.index') }}" class="btn btn-link text-muted shadow-none">Back</a>
                                <button type="submit" class="btn btn-primary px-5 font-weight-bold shadow-sm">Update Rate</button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-shield-alt fa-3x text-muted opacity-50"></i>
                            </div>
                            <h5 class="text-dark font-weight-bold">View Only Access</h5>
                            <p class="text-muted">You have permission to view commission details, but updates are reserved for <strong>Super Admin</strong> roles only.</p>
                            <hr>
                            <div class="bg-light p-3 rounded mb-3 text-left">
                                <small class="text-uppercase font-weight-bold text-muted d-block mb-1">Current Value</small>
                                <span class="h5 mb-0 text-dark">{{ $commission->rate }}%</span>
                            </div>
                            <a href="{{ route('admin.commissions.index') }}" class="btn btn-secondary btn-block">Return to List</a>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</div>
@endsection