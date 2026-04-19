@extends('admin.layouts.app')

@section('title', 'Role Details | Autosparelink')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 pt-3">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="fas fa-shield-alt mr-2 text-primary"></i>Role Details
            </h1>
            <p class="text-muted small">Viewing configuration and members for the <strong>{{ ucfirst($role->name) }}</strong> role.</p>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-light border shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to Roles
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="font-weight-bold mb-0 text-dark">Role Capability Matrix</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="text-muted small text-uppercase font-weight-bold mb-0">Role Name</label>
                        <h4 class="text-primary font-weight-bold">{{ strtoupper($role->name) }}</h4>
                    </div>

                    <div class="permission-list" style="max-height: 500px; overflow-y: auto;">
                        @php
                            $groupedPermissions = $role->permissions->groupBy(function($item) {
                                return explode('.', $item->name)[0];
                            });
                        @endphp

                        @forelse($groupedPermissions as $group => $perms)
                            <div class="mb-3">
                                <h6 class="text-muted small font-weight-bold border-bottom pb-1 mb-2 text-uppercase">
                                    {{ $group }}
                                </h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($perms as $permission)
                                        <span class="badge badge-light border text-dark mb-1 mr-1 px-2 py-1">
                                            <i class="fas fa-check text-success mr-1 small"></i> 
                                            {{ str_replace('.', ' ', $permission->name) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 bg-light rounded">
                                <p class="text-muted small mb-0 italic">No specific permissions assigned.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer bg-soft-primary border-0">
                    <small class="text-primary font-weight-bold">
                        Total Permissions: {{ $role->permissions->count() }}
                    </small>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="font-weight-bold mb-0 text-dark">Users with this Role</h6>
                    <span class="badge badge-primary px-2 py-1">{{ $users->count() }} Members</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light small text-uppercase">
                                <tr>
                                    <th class="pl-4">User</th>
                                    <th>Email</th>
                                    <th class="text-right pr-4">Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $assignedUser)
                                    <tr>
                                        <td class="pl-4">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    @php
                                                        $photo = $assignedUser->photo ? asset($assignedUser->photo) : ($assignedUser->avatar ?: null);
                                                    @endphp
                                                    @if($photo)
                                                        <img src="{{ $photo }}" class="rounded-circle border" style="width: 35px; height: 35px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-soft-primary text-primary text-center font-weight-bold border" style="width: 35px; height: 35px; line-height: 33px; font-size: 12px;">
                                                            {{ strtoupper(substr($assignedUser->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <a href="{{ route('admin.users.show', $assignedUser->id) }}" class="text-dark font-weight-bold d-block mb-0">
                                                        {{ $assignedUser->name }}
                                                    </a>
                                                    <small class="text-muted">#USR-{{ str_pad($assignedUser->id, 4, '0', STR_PAD_LEFT) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="small">{{ $assignedUser->email }}</span>
                                        </td>
                                        <td class="text-right pr-4">
                                            <span class="text-muted small">{{ $assignedUser->created_at->format('M d, Y') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <div class="opacity-50">
                                                <i class="fas fa-user-slash fa-2x mb-2"></i>
                                                <p class="mb-0 small">No users are currently assigned to this role.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-soft-primary { background-color: #f0f7ff; }
    .italic { font-style: italic; }
    .permission-list::-webkit-scrollbar { width: 5px; }
    .permission-list::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .table td { vertical-align: middle !important; }
</style>
@endpush
@endsection