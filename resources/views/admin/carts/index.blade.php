@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Admin - User Carts</h2>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Total Orders</th>
                <th>View Orders</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->orders->count() }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $user->id) }}" class="btn btn-sm btn-primary">
                            View Orders
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No data found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection