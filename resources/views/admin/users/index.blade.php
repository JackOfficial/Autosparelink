@extends('admin.layouts.app')

@section('title', 'Admin - Users')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-dark">Users ({{ $users->count() }})</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">
<div class="row">
<div class="col-12">

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title text-uppercase">All Users</h3>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add User
        </a>
    </div>

    <div class="card-body table-responsive p-3">
        <table id="example1" class="table table-bordered table-striped table-hover">
            <thead class="thead-light">
                <tr class="text-uppercase">
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        @if ($user->photo)
                            <a href="{{ asset('storage/'.$user->photo) }}" target="_blank">
                                <img src="{{ asset('storage/'.$user->photo) }}"
                                     loading="lazy"
                                     class="rounded-circle border"
                                     style="width:50px;height:50px;object-fit:cover;">
                            </a>
                        @else
                            <img src="{{ asset('images/avatar-placeholder.png') }}"
                                 class="rounded-circle border"
                                 style="width:50px;height:50px;object-fit:cover;">
                        @endif
                    </td>

                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>

                    <td>
                        @forelse($user->getRoleNames() as $role)
                            <span class="badge rounded-pill bg-info text-dark">{{ $role }}</span>
                        @empty
                            <span class="badge rounded-pill bg-secondary">No role</span>
                        @endforelse
                    </td>

                    <td>{{ optional($user->created_at)->format('Y-m-d') }}</td>

                    <td class="d-flex gap-1">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="btn btn-info btn-sm" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>

                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" title="Delete"
                                    onclick="return confirm('Delete this user?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-3">
                        <i class="fas fa-user-slash"></i> No users available.
                    </td>
                </tr>
            @endforelse
            </tbody>

            <tfoot>
                <tr class="text-uppercase">
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

</div>
</div>
</div>
</section>

@endsection
