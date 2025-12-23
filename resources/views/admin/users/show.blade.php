@extends('admin.layouts.app')

@section('title', 'View User')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>View User</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">User Details</h3>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-info btn-sm float-right">
                <i class="fas fa-edit"></i> Edit User
            </a>
        </div>

        <div class="card-body">
            <div class="row">

                <!-- Profile Photo -->
                <div class="col-md-3 text-center mb-3">
                    @if ($user->photo)
                        <img src="{{ asset($user->photo) }}" alt="Profile Photo" class="img-fluid rounded-circle" style="max-width:150px;">
                    @else
                        <img src="{{ asset('images/default-user.png') }}" alt="Default Photo" class="img-fluid rounded-circle" style="max-width:150px;">
                    @endif
                </div>

                <!-- User Info -->
                <div class="col-md-9">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Roles</th>
                                <td>
                                    @forelse($user->getRoleNames() as $role)
                                        <span class="badge badge-info">{{ ucfirst($role) }}</span>
                                    @empty
                                        <span class="badge badge-secondary">No role</span>
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <th>Social Login Providers</th>
                                <td>
                                    @if(!empty($user->social_providers))
                                        @foreach($user->social_providers as $provider)
                                            <span class="badge badge-primary">{{ ucfirst($provider) }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($user->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated</th>
                                <td>{{ $user->updated_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="mt-3">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>
        </div>
    </div>
</div>
</section>

@endsection
