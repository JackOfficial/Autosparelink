@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Edit Payment Status</h2>

    <a href="{{ route('admin.payments.show', $payment->id) }}"
       class="btn btn-secondary mb-3">Back</a>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.payments.update', $payment->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Payment Status</label>
                    <select name="status" class="form-control">
                        @foreach(['pending','processing','successful','failed','refunded'] as $status)
                            <option value="{{ $status }}"
                                {{ $payment->status == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-primary">
                    Update Payment
                </button>
            </form>
        </div>
    </div>
</div>
@endsection