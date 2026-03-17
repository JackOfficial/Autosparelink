@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <h2>Edit Shipping</h2>

    <form method="POST" action="{{ route('admin.shippings.update', $shipping->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Carrier</label>
            <input type="text" name="carrier"
                   value="{{ $shipping->carrier }}"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Tracking Number</label>
            <input type="text" name="tracking_number"
                   value="{{ $shipping->tracking_number }}"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                @foreach(['pending','shipped','in_transit','delivered','failed'] as $status)
                    <option value="{{ $status }}"
                        {{ $shipping->status == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection