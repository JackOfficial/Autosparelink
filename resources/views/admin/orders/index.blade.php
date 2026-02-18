@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2>Your Orders</h2>

    @if($orders->isEmpty())
        <p>You have no orders yet.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <span class="badge 
                                    @if($order->status === 'pending') badge-warning
                                    @elseif($order->status === 'processing') badge-primary
                                    @elseif($order->status === 'shipped') badge-info
                                    @elseif($order->status === 'delivered') badge-success
                                    @elseif($order->status === 'cancelled') badge-danger
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ number_format($order->total_amount, 2) }} RWF</td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection