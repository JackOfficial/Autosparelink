@extends('admin.layouts.app')

@section('title', 'Create New Role | Autosparelink')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 pt-3">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="fas fa-shield-alt mr-2 text-primary"></i>Create Role
            </h1>
            <p class="text-muted small">Define a new access level and assign specific capabilities.</p>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-light border shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to Roles
            </a>
        </div>
    </div>

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="font-weight-bold mb-0 text-dark">Role Identity</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-uppercase text-muted" for="role_name">
                                Role Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="role_name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="e.g., Inventory Manager" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Use unique, descriptive names for different access levels.</small>
                        </div>

                        <hr>

                        <div class="alert alert-info border-0 shadow-none small">
                            <i class="fas fa-info-circle mr-1"></i> 
                            Permissions assigned here will apply to all users assigned to this role.
                        </div>

                        <button type="submit" class="btn btn-primary btn-block shadow-sm font-weight-bold py-2 mt-3">
                            <i class="fas fa-check-circle mr-1"></i> Create Role Instance
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold mb-0 text-dark">Permission Assignments</h6>
                        <div class="custom-control custom-checkbox small">
                            <input type="checkbox" class="custom-control-input" id="selectAll">
                            <label class="custom-control-label font-weight-bold text-primary" for="selectAll" style="cursor:pointer;">
                                Select All Permissions
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php
                                // We group permissions by prefix (e.g., 'users.edit' becomes 'users')
                                $groupedPermissions = $permissions->groupBy(function($item) {
                                    return explode('.', $item->name)[0];
                                });
                            @endphp

                            @forelse($groupedPermissions as $group => $perms)
                                <div class="col-md-6 mb-4">
                                    <div class="p-3 border rounded bg-light-soft">
                                        <h6 class="text-uppercase font-weight-bold small text-primary mb-3">
                                            <i class="fas fa-folder-open mr-1"></i> {{ ucfirst($group) }} Management
                                        </h6>
                                        
                                        @foreach($perms as $permission)
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->name }}" 
                                                       class="custom-control-input permission-checkbox" 
                                                       id="perm-{{ $permission->id }}">
                                                <label class="custom-control-label small text-dark" for="perm-{{ $permission->id }}" style="cursor:pointer;">
                                                    {{ str_replace(['.', '-', '_'], ' ', $permission->name) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <i class="fas fa-lock fa-3x text-muted opacity-20 mb-3"></i>
                                    <p class="text-muted">No permissions found in the database. Run your seeders first.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .bg-light-soft { background-color: #f8fbff; border: 1px solid #e9eff5 !important; }
    .custom-control-label { padding-top: 2px; }
    .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #007bff;
        border-color: #007bff;
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
@endpush
@endsection