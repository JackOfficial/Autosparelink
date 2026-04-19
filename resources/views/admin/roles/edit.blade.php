@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}" class="text-muted">Roles</a></li>
                    <li class="breadcrumb-item active font-weight-bold">Access Matrix</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}" method="POST">
        @csrf
        @if(isset($role)) @method('PUT') @endif

        <div class="row">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px; z-index: 100;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-soft-primary p-3 rounded-circle mr-3">
                                <i class="fas fa-user-shield text-primary fa-lg"></i>
                            </div>
                            <div>
                                <label class="text-muted small text-uppercase font-weight-bold mb-0">Role Profile</label>
                                <h3 class="font-weight-bold text-dark mb-0">{{ ucfirst($role->name ?? 'New Role') }}</h3>
                            </div>
                        </div>

                        <input type="hidden" name="name" value="{{ $role->name ?? '' }}">
                        
                        <div class="alert alert-warning border-0 small mb-4">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Modifying core roles affects <strong>all vendors</strong> and users immediately.
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block shadow-sm font-weight-bold py-3 mb-3">
                            <i class="fas fa-save mr-2"></i> Update Permissions
                        </button>
                        
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-light btn-block text-muted border-0 small">
                            <i class="fas fa-arrow-left mr-1"></i> Back to list
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-white py-4 px-4 border-bottom-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0 font-weight-bold text-dark">Capabilities Management</h5>
                                <p class="text-muted small mb-0">Toggle permissions individually or by group</p>
                            </div>
                            <div class="col-auto">
                                <span class="badge badge-primary badge-pill px-3 py-2">
                                    {{ $permissions->count() }} Active Guards
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4 bg-light-gray">
                        <div class="row">
                            @foreach($permissions->groupBy(fn($p) => explode('.', $p->name)[0]) as $group => $groupPermissions)
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100 permission-group">
                                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                                            <h6 class="mb-0 font-weight-bold text-primary text-uppercase small">
                                                <i class="fas fa-folder-open mr-2 opacity-5"></i>{{ $group }}
                                            </h6>
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input select-all-group" id="select-all-{{ $group }}">
                                                <label class="custom-control-label font-weight-bold text-muted" for="select-all-{{ $group }}">All</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @foreach($groupPermissions as $permission)
                                                <div class="custom-control custom-switch mb-3">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                        class="custom-control-input permission-checkbox" 
                                                        id="perm-{{ $permission->id }}"
                                                        {{ isset($rolePermissions) && in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                    <label class="custom-control-label text-dark text-capitalize w-100" for="perm-{{ $permission->id }}" style="cursor: pointer;">
                                                        {{ str_replace('.', ' ', $permission->name) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .bg-light-gray { background-color: #f8f9fc; }
    .bg-soft-primary { background-color: rgba(59, 130, 246, 0.1); }
    .permission-group { transition: transform 0.2s, box-shadow 0.2s; border-radius: 12px; }
    .permission-group:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important; }
    
    /* Modern Switch Styling */
    .custom-switch .custom-control-label::before { height: 1.4rem; width: 2.5rem; border-radius: 1rem; border: none; background-color: #dee2e6; }
    .custom-switch .custom-control-label::after { width: calc(1.4rem - 4px); height: calc(1.4rem - 4px); border-radius: 1rem; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .custom-switch .custom-control-input:checked ~ .custom-control-label::before { background-color: #3b82f6; }
    
    .opacity-5 { opacity: 0.5; }
</style>
@endpush

@push('scripts')
<script>
    // UX Helper: Select all permissions in a group
    document.querySelectorAll('.select-all-group').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const group = this.closest('.permission-group');
            const checkboxes = group.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    });

    // UX Helper: Auto-check "All" if all group checkboxes are checked manually
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const group = this.closest('.permission-group');
            const allCheckbox = group.querySelector('.select-all-group');
            const total = group.querySelectorAll('.permission-checkbox').length;
            const checked = group.querySelectorAll('.permission-checkbox:checked').length;
            allCheckbox.checked = (total === checked);
        });
    });
</script>
@endpush
@endsection