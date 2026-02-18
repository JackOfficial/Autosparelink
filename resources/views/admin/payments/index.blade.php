@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>All Payments</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Order</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="130">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>
                                {{ $payment->order->user->name }} <br>
                                <small>{{ $payment->order->user->email }}</small>
                            </td>
                            <td>#{{ $payment->order->id }}</td>
                            <td>{{ number_format($payment->amount, 2) }} RWF</td>
                            <td>{{ ucfirst($payment->method) }}</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>{{ $payment->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('payments.show', $payment->id) }}"
                                   class="btn btn-sm btn-info">View</a>

                                <a href="{{ route('payments.edit', $payment->id) }}"
                                   class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('payments.destroy', $payment->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete payment?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection