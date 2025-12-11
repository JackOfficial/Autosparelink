@extends('admin.layouts.app')

@section('title', 'Admin - Users')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Users ({{ $users->count() }})</h1>
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

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users</h3>
                </div>

                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    @if($user->photo == null)
                                        No photo
                                    @else
                                        <a href="{{ asset($user->photo) }}" target="_blank">
                                            <img src="{{ asset($user->photo) }}" alt="Photo" style="width:50px; height:50px; object-fit:cover;">
                                        </a>
                                    @endif
                                </td>

                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>

                                <td>
                                    @if($user->roles == 0)
                                    <div class="badge badge-warning">Disactive</div>
                                    @endif
                                    @if($user->roles == 1)
                                    <div class="badge badge-success">Active</div>
                                    @endif
                                    @if($user->roles == 2)
                                    <div class="badge badge-danger">Banned</div>
                                    @endif
                                </td>

                                <td>{{ $user->created_at->format('Y-m-d') }}</td>

                                <td class="d-flex">
                                    <a href="{{ url('/admin/users/'.$user->id.'/edit') }}" class="btn btn-info btn-sm mr-2">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>

                                    <form method="POST" action="{{ url('/admin/users/'.$user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center my-3">
                                    There's no user available at the moment.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                        <tfoot>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
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
