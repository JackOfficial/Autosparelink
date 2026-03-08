@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <h2>Address Details</h2>

    <a href="{{ route('admin.addresses.index') }}"
       class="btn btn-secondary mb-3">Back</a>

    <div class="card">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $address->full_name }}</p>
            <p><strong>Phone:</strong> {{ $address->phone }}</p>
            <p><strong>Street:</strong> {{ $address->street_address }}</p>
            <p><strong>City:</strong> {{ $address->city }}</p>
            <p><strong>State:</strong> {{ $address->state }}</p>
            <p><strong>Postal Code:</strong> {{ $address->postal_code }}</p>
            <p><strong>Country:</strong> {{ $address->country }}</p>
        </div>
    </div>
</div>
@endsection