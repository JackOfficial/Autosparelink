@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 pt-3">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="fas fa-user-shield mr-2 text-primary"></i>Roles & Permissions
            </h1>
        </div>
        <div class="col-sm-6 text-right">
            @role('super-admin')
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Create New Role
                </a>
            @endrole
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light small text-uppercase font-weight-bold">
                    <tr>
                        <th class="pl-4 py-3">Role Name</th>
                        <th>Assigned Users</th>
                        <th>Permissions</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td class="pl-4">
                            <span class="font-weight-bold text-dark">{{ ucfirst($role->name) }}</span>
                            @if($role->name === 'super-admin')
                                <i class="fas fa-crown text-warning ml-1" title="Highest Authority"></i>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-soft-primary text-primary px-2 py-1">
                                    <i class="fas fa-users mr-1"></i> {{ $role->users_count ?? $role->users->count() }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $role->permissions->count() > 0 ? 'badge-info' : 'badge-light border text-muted' }} px-2">
                                <i class="fas fa-key mr-1 small"></i> {{ $role->permissions->count() }} 
                            </span>
                        </td>
                        <td class="text-right pr-4">
                            <div class="btn-group">
                                <a href="{{ route('admin.roles.show', $role) }}" 
                                   class="btn btn-sm btn-white border shadow-none" 
                                   title="View Role Details">
                                    <i class="fas fa-eye text-success"></i>
                                </a>

                                @php
                                    $protectedRoles = ['super-admin', 'admin', 'user', 'seller'];
                                    $isProtected = in_array(strtolower($role->name), $protectedRoles);
                                @endphp

                                @if(!$isProtected)
                                    <a href="{{ route('admin.roles.edit', $role) }}" 
                                       class="btn btn-sm btn-white border shadow-none ml-1" 
                                       title="Edit Role">
                                        <i class="fas fa-edit text-primary"></i>
                                    </a>

                                    @role('super-admin')
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline ml-1">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-white border text-danger shadow-none" 
                                                title="Delete Role"
                                                onclick="return confirm('Are you sure you want to delete the {{ $role->name }} role?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    @endrole
                                @else
                                    <span class="btn btn-sm btn-light border ml-1 disabled" title="System Protected Role">
                                        <i class="fas fa-lock text-muted small"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-soft-primary { background-color: #eef5ff; color: #3b82f6; }
    .table td { vertical-align: middle !important; }
</style>
@endpush
@endsection