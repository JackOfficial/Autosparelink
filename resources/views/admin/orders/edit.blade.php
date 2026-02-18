@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Update Order Status</h2>

    <a href="{{ route('admin.orders.show', $order->id) }}"
       class="btn btn-secondary mb-3">
        Back
    </a>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.orders.update', $order->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Order Status</label>
                    <select name="status" class="form-control">
                        @foreach(['pending','processing','shipped','delivered','cancelled'] as $status)
                            <option value="{{ $status }}"
                                {{ $order->status == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Status
                </button>
            </form>
        </div>
    </div>
</div>
@endsection