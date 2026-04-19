@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                    <li class="breadcrumb-item active">Edit Permissions</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}" method="POST">
        @csrf
        @if(isset($role)) @method('PUT') @endif

        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px; z-index: 1000;">
                    <div class="card-body">
                        <label class="text-muted small text-uppercase font-weight-bold mb-0">Modifying Role</label>
                        <h2 class="display-5 font-weight-bold text-primary mb-2">
                            {{ ucfirst($role->name ?? 'New Role') }}
                        </h2>
                        
                        <input type="hidden" name="name" value="{{ $role->name ?? '' }}">
                        
                        <div class="p-3 bg-light rounded border mb-4">
                            <ul class="list-unstyled mb-0 small text-muted">
                                <li class="mb-2"><i class="fas fa-info-circle mr-2 text-info"></i> Role name is fixed to protect system integrity.</li>
                                <li><i class="fas fa-shield-alt mr-2 text-info"></i> Changes take effect immediately for all users in this group.</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block shadow font-weight-bold py-3">
                            <i class="fas fa-check-circle mr-2"></i> Update Permissions
                        </button>
                        
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-link btn-block text-muted small mt-2">
                            Cancel and go back
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bold">Access Control Matrix</h5>
                            <span class="badge badge-soft-primary px-3 py-2">
                                <i class="fas fa-key mr-1"></i> {{ $permissions->count() }} Total Capabilities
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row">
                            @foreach($permissions->groupBy(fn($p) => explode('.', $p->name)[0]) as $group => $groupPermissions)
                                <div class="col-md-6 mb-4">
                                    <div class="permission-group-card p-3 rounded border h-100">
                                        <h6 class="text-dark font-weight-bold text-uppercase small mb-3 d-flex align-items-center">
                                            <span class="bg-primary text-white rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width:24px; height:24px; font-size:10px;">
                                                <i class="fas fa-cog"></i>
                                            </span>
                                            {{ $group }} Module
                                        </h6>
                                        
                                        <div class="pl-2">
                                            @foreach($groupPermissions as $permission)
                                                <div class="custom-control custom-switch mb-2">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                        class="custom-control-input" id="perm-{{ $permission->id }}"
                                                        {{ isset($rolePermissions) && in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                    <label class="custom-control-label text-capitalize py-1" for="perm-{{ $permission->id }}" style="cursor: pointer;">
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

<style>
    .display-5 { font-size: 2.2rem; line-height: 1.2; letter-spacing: -1px; }
    .badge-soft-primary { background-color: #eef5ff; color: #3b82f6; font-weight: 600; }
    .permission-group-card { transition: all 0.2s; background: #fafafa; }
    .permission-group-card:hover { background: #fff; border-color: #3b82f6 !important; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .custom-switch .custom-control-label::before { height: 1.25rem; width: 2.25rem; border-radius: 1rem; }
    .custom-switch .custom-control-label::after { width: calc(1.25rem - 4px); height: calc(1.25rem - 4px); border-radius: 1rem; }
    .custom-switch .custom-control-input:checked ~ .custom-control-label::before { background-color: #3b82f6; border-color: #3b82f6; }
</style>
@endsection