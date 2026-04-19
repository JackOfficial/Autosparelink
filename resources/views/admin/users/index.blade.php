@extends('admin.layouts.app')

@section('title', 'User Management | Autosparelink')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 pt-3">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="fas fa-users mr-2 text-primary"></i>Users Management
            </h1>
            <p class="text-muted small">Overview of all registered platform users and their access levels.</p>
        </div>
        <div class="col-sm-6 text-right">
            <div class="d-inline-flex">
                <div class="bg-white p-2 px-3 rounded shadow-sm border-left border-primary mr-3 text-left">
                    <small class="text-muted text-uppercase d-block" style="font-size: 10px;">Total Registered</small>
                    <span class="h6 font-weight-bold text-dark mb-0">{{ $users->count() }} Users</span>
                </div>
                @role('super-admin')
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm d-flex align-items-center">
                    <i class="fas fa-user-plus mr-2"></i> Add New User
                </a>
                @endrole
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h3 class="card-title font-weight-bold text-uppercase small text-muted mb-0" style="letter-spacing: 1px;">
                User Directory
            </h3>
        </div>

        <div class="card-body table-responsive p-0">
            <table id="example1" class="table table-hover align-middle mb-0">
                <thead class="bg-light small text-uppercase font-weight-bold">
                    <tr>
                        <th class="pl-4">User</th>
                        <th>Email</th>
                        <th>Business / Shop</th>
                        <th>Access Roles</th>
                        <th>Joined Date</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="pl-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-wrapper mr-3">
                                    @php
                                        $userPhoto = $user->photo ? asset($user->photo) : ($user->avatar ?: null);
                                    @endphp
                                    @if ($userPhoto)
                                        <img src="{{ $userPhoto }}" alt="" class="rounded-circle border shadow-sm" style="width:42px; height:42px; object-fit:cover;">
                                    @else
                                        <div class="rounded-circle border bg-soft-primary text-primary d-flex align-items-center justify-content-center shadow-sm font-weight-bold" style="width:42px; height:42px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-weight-bold text-dark d-block">{{ $user->name }}</span>
                                    <small class="text-muted">ID: #USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-dark">{{ $user->email }}</span>
                        </td>
                        <td>
                            @if($user->shop)
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-soft-success text-success border border-success px-2 py-1" style="font-size: 11px;">
                                        <i class="fas fa-store mr-1"></i> {{ $user->shop->name }}
                                    </span>
                                </div>
                            @else
                                <span class="text-muted small italic">No Shop Linked</span>
                            @endif
                        </td>
                        <td>
                            @forelse($user->getRoleNames() as $role)
                                @php
                                    $roleClass = $role === 'super-admin' ? 'badge-danger' : ($role === 'admin' ? 'badge-primary' : 'badge-info');
                                @endphp
                                <span class="badge {{ $roleClass }} px-2 py-1 text-uppercase" style="font-size: 10px; border-radius: 4px;">
                                    <i class="fas fa-shield-alt mr-1 small"></i> {{ $role }}
                                </span>
                            @empty
                                <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 10px;">No Role Assigned</span>
                            @endforelse
                        </td>
                        <td>
                            <span class="text-muted small">
                                <i class="far fa-calendar-alt mr-1"></i> {{ optional($user->created_at)->format('d M, Y') }}
                            </span>
                        </td>
                        <td class="text-right pr-4">
                            <div class="btn-group">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="btn btn-sm btn-white border shadow-none" 
                                   title="View Profile">
                                    <i class="fas fa-eye text-success"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="btn btn-sm btn-white border shadow-none" 
                                   title="Edit User">
                                    <i class="fas fa-user-edit text-primary"></i>
                                </a>

                                @role('super-admin')
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline ml-1">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-white border text-danger shadow-none" 
                                            title="Delete User"
                                            onclick="return confirm('Permanently delete {{ $user->name }}?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                @endrole
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="opacity-50">
                                <i class="fas fa-users-slash fa-3x mb-3"></i>
                                <p class="mb-0">No users found in the system.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-soft-primary { background-color: #eef5ff; }
    .bg-soft-success { background-color: #e6fcf5; }
    .table td { vertical-align: middle !important; }
    .badge { font-weight: 600; letter-spacing: 0.5px; }
    .btn-white { background: #fff; color: #6c757d; }
    .btn-white:hover { background: #f8f9fa; color: #333; }
    .italic { font-style: italic; }
</style>
@endpush
@endsection