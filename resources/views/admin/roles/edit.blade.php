@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-3">
    <form action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}" method="POST">
        @csrf
        @if(isset($role)) @method('PUT') @endif

        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <label class="font-weight-bold small text-uppercase">Role Name</label>
                        <input type="text" name="name" value="{{ old('name', $role->name ?? '') }}" class="form-control" placeholder="e.g. Editor" required>
                        <p class="text-muted small mt-2">Define a unique name for this user group.</p>
                        <button type="submit" class="btn btn-primary btn-block shadow-sm font-weight-bold mt-3">
                            <i class="fas fa-save mr-1"></i> Save Role
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold">Assign Permissions</h5></div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($permissions->groupBy(fn($p) => explode('.', $p->name)[0]) as $group => $groupPermissions)
                                <div class="col-md-6 mb-4">
                                    <h6 class="text-primary font-weight-bold text-uppercase small border-bottom pb-1">{{ $group }} Management</h6>
                                    @foreach($groupPermissions as $permission)
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                class="custom-control-input" id="perm-{{ $permission->id }}"
                                                {{ isset($rolePermissions) && in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="custom-control-label small" for="perm-{{ $permission->id }}">
                                                {{ str_replace('.', ' ', $permission->name) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection