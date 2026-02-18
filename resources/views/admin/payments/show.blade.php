@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Payment #{{ $payment->id }}</h2>

    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary mb-3">
        Back
    </a>

    <div class="card">
        <div class="card-body">
            <p><strong>Customer:</strong> {{ $payment->order->user->name }}</p>
            <p><strong>Email:</strong> {{ $payment->order->user->email }}</p>
            <p><strong>Order ID:</strong> #{{ $payment->order->id }}</p>
            <p><strong>Amount:</strong> {{ number_format($payment->amount, 2) }} RWF</p>
            <p><strong>Method:</strong> {{ ucfirst($payment->method) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
            <p><strong>Transaction Ref:</strong> {{ $payment->transaction_reference }}</p>
            <p><strong>Date:</strong> {{ $payment->created_at }}</p>
        </div>
    </div>
</div>
@endsection