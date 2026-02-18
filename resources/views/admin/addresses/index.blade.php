@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <h2>My Addresses</h2>

    <a href="{{ route('admin.addresses.create') }}"
       class="btn btn-primary mb-3">Add New Address</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($addresses as $address)
            <div class="col-md-4">
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5>{{ $address->full_name }}</h5>
                        <p>{{ $address->street_address }}</p>
                        <p>{{ $address->city }}, {{ $address->country }}</p>
                        <p>{{ $address->phone }}</p>

                        <a href="{{ route('admin.addresses.show', $address->id) }}"
                           class="btn btn-sm btn-info">View</a>

                        <a href="{{ route('admin.addresses.edit', $address->id) }}"
                           class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('admin.addresses.destroy', $address->id) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this address?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>No addresses found.</p>
        @endforelse
    </div>
</div>
@endsection