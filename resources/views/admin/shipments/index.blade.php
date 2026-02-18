@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>All Shippings</h2>

    <a href="{{ route('shippings.create') }}" class="btn btn-primary mb-3">
        Create Shipping
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Carrier</th>
                        <th>Status</th>
                        <th>Tracking</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shippings as $shipping)
                        <tr>
                            <td>{{ $shipping->id }}</td>
                            <td>#{{ $shipping->order->id }}</td>
                            <td>{{ $shipping->order->user->name }}</td>
                            <td>{{ $shipping->carrier }}</td>
                            <td>{{ ucfirst($shipping->status) }}</td>
                            <td>{{ $shipping->tracking_number ?? '-' }}</td>
                            <td>
                                <a href="{{ route('shippings.show', $shipping->id) }}"
                                   class="btn btn-sm btn-info">View</a>

                                <a href="{{ route('shippings.edit', $shipping->id) }}"
                                   class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('shippings.destroy', $shipping->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete shipping?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $shippings->links() }}
        </div>
    </div>
</div>
@endsection