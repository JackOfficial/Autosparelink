@extends('admin.layouts.app')

@section('title', 'Set Commission | Autosparelink')

@section('content')
<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 pt-4">
                    <h4 class="font-weight-bold text-dark">
                        <i class="fas fa-percentage mr-2 text-primary"></i>Set New Commission Rate
                    </h4>
                </div>
                
                <div class="card-body">
                    @role('super-admin')
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
                                <a href="{{ route('admin.commissions.index') }}" class="btn btn-link text-muted shadow-none">Cancel</a>
                                <button type="submit" class="btn btn-primary px-5 font-weight-bold shadow-sm">Save Commission</button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                            <h5 class="text-dark">Access Restricted</h5>
                            <p class="text-muted">Only the Super Admin has the authority to create or modify platform commission rates.</p>
                            <a href="{{ route('admin.commissions.index') }}" class="btn btn-primary mt-2">Go Back</a>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</div>
@endsection