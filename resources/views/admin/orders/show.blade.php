@extends('admin.layouts.app')

@push('styles')
<style>
    /* Status-specific branding */
    :root {
        --bs-success-rgb: 40, 167, 69;
        --bs-primary-rgb: 0, 123, 255;
    }

    /* Improved Blinking for Call Requests */
    .blink_me { animation: blinker 0.8s cubic-bezier(.45,.05,.55,.95) infinite; }
    @keyframes blinker { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }

    /* Modern UI Components */
    .card { border-radius: 12px; border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
    .card-header { 
        font-weight: 700 !important; 
        text-transform: uppercase; 
        font-size: 0.7rem; 
        letter-spacing: 0.05rem; 
        padding: 1.25rem; 
        background-color: #fff !important;
        border-bottom: 1px solid #f1f4f8; 
    }
    
    /* Order Stepper */
    .stepper-wrapper { display: flex; justify-content: space-between; margin-bottom: 2rem; padding: 0 1rem; }
    .stepper-item { position: relative; display: flex; flex-direction: column; align-items: center; flex: 1; }
    .stepper-item::before { position: absolute; content: ""; border-bottom: 2px solid #e9ecef; width: 100%; top: 18px; left: -50%; z-index: 1; }
    .stepper-item:first-child::before { content: none; }
    .step-counter { position: relative; z-index: 5; display: flex; justify-content: center; align-items: center; width: 36px; height: 36px; border-radius: 50%; background: #e9ecef; color: #adb5bd; font-weight: bold; margin-bottom: 6px; transition: 0.3s; }
    .stepper-item.active .step-counter { background-color: var(--primary-deep); color: #fff; }
    .stepper-item.completed .step-counter { background-color: #2b8a3e; color: #fff; }
    .step-name { font-size: 0.65rem; font-weight: 700; color: #adb5bd; text-transform: uppercase; }
    .stepper-item.active .step-name { color: var(--primary-deep); }

    /* Control Styling */
    .status-control-card { background: #fff; border: 1px solid #eef2f7; }
    .status-select { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; font-weight: 600; font-size: 0.9rem; }

    /* Info Display */
    .info-label { font-size: 0.65rem; text-transform: uppercase; color: #95aac9; font-weight: 700; margin-bottom: 2px; display: block; }
    .info-value { font-size: 0.9rem; color: #12263f; font-weight: 500; }
    
    .table thead th { background-color: #f9fbfd; text-transform: uppercase; font-size: 0.65rem; letter-spacing: .02em; color: #95aac9; border-top: none; }
    .btn-soft-success { background-color: rgba(43, 138, 62, 0.1); color: #2b8a3e; border: 1px solid rgba(43, 138, 62, 0.2); }
    .btn-soft-success:hover { background-color: #2b8a3e; color: #fff; }

    @media print {
        .no-print, .btn, .status-control-card { display: none !important; }
        .card { box-shadow: none; border: 1px solid #eee; }
    }
</style>
@endpush

@section('content')
@php
    $customerName = $order->guest_name ?? $order->user->name ?? 'Guest Customer';
    $customerEmail = $order->guest_email ?? $order->user->email ?? 'No email';
    $customerPhone = $order->guest_phone ?? $order->address->phone ?? 'N/A';
    
    $street = $order->guest_shipping_address ?? $order->address->street_address ?? 'No address provided';
    $city = $order->city ?? $order->address->city ?? '';
    $country = $order->country ?? $order->address->country ?? '';
    $initial = strtoupper(substr($customerName, 0, 1));

    // Stepper logic
    $statuses = ['pending', 'processing', 'shipped', 'delivered'];
    $currentIdx = array_search($order->status, $statuses);
@endphp

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 no-print">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}" class="text-primary">Pipeline</a></li>
                    <li class="breadcrumb-item active">INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</li>
                </ol>
            </nav>
            <h2 class="h4 mb-0 font-weight-bold">Order Details</h2>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <button onclick="window.print()" class="btn btn-white border shadow-sm px-3">
                <i class="fas fa-print mr-1"></i> Print
            </button>
            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-white border shadow-sm px-3">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary shadow-sm px-3">
                <i class="fas fa-list mr-1"></i> All Orders
            </a>
        </div>
    </div>

    @if($order->status === 'callback_requested')
        <div class="alert alert-danger blink_me d-flex align-items-center mb-4 border-0 shadow-sm" style="border-radius: 12px; background: #fff5f5; border-left: 5px solid #c92a2a !important;">
            <div class="bg-white rounded-circle p-2 mr-3 shadow-sm text-danger" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-phone-alt"></i>
            </div>
            <div>
                <h6 class="mb-0 font-weight-bold text-danger">Callback Requested</h6>
                <span class="small">Contact <strong>{{ $customerPhone }}</strong> immediately.</span>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body py-4">
                    <div class="stepper-wrapper">
                        @foreach($statuses as $index => $step)
                            <div class="stepper-item {{ $currentIdx >= $index ? 'completed' : ($currentIdx === $index ? 'active' : '') }}">
                                <div class="step-counter">
                                    @if($currentIdx > $index) <i class="fas fa-check"></i> @else {{ $index + 1 }} @endif
                                </div>
                                <div class="step-name">{{ str_replace('_', ' ', $step) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <i class="fas fa-box-open mr-2 text-primary"></i> Package Contents
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="px-4">Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-right px-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-2 mr-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-cube text-muted"></i>
                                                </div>
                                                <div>
                                                    <span class="d-block font-weight-bold text-dark" style="font-size: 0.9rem;">{{ $item->part->part_name ?? 'Product Deleted' }}</span>
                                                    <small class="text-muted">SKU: {{ $item->part->sku ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center font-weight-bold">{{ $item->quantity }}</td>
                                        <td class="text-right text-muted">{{ number_format($item->unit_price) }} RWF</td>
                                        <td class="text-right px-4 font-weight-bold text-dark">{{ number_format($item->quantity * $item->unit_price) }} RWF</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background: #fcfcfd;">
                                <tr>
                                    <td colspan="3" class="text-right font-weight-bold py-3 border-0">Grand Total:</td>
                                    <td class="text-right px-4 h5 font-weight-bold text-primary py-3 border-0">{{ number_format($order->total_amount) }} RWF</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Payment Status</span>
                            @php $isPaid = $order->payment && $order->payment->status === 'completed'; @endphp
                            <span class="badge {{ $isPaid ? 'bg-success text-white' : 'bg-warning text-dark' }} px-2 py-1" style="border-radius: 4px; font-size: 0.6rem;">
                                {{ $isPaid ? 'PAID' : 'PENDING' }}
                            </span>
                        </div>
                        <div class="card-body">
                            @if($order->payment)
                                <div class="mb-3">
                                    <span class="info-label">Transaction ID</span>
                                    <span class="info-value text-monospace">{{ $order->payment->transaction_id ?? '---' }}</span>
                                </div>
                                <div>
                                    <span class="info-label">Method</span>
                                    <span class="info-value"><i class="fas fa-wallet mr-1 text-muted"></i> {{ strtoupper($order->payment->method) }}</span>
                                </div>
                            @else
                                <div class="text-center py-2">
                                    <p class="text-muted small mb-0">No transaction data available.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">Logistics & Tracking</div>
                        <div class="card-body">
                            @if($order->shipping)
                                <div class="mb-3">
                                    <span class="info-label">Carrier</span>
                                    <span class="info-value">{{ $order->shipping->carrier }}</span>
                                </div>
                                <div>
                                    <span class="info-label">Tracking Number</span>
                                    <code class="text-primary font-weight-bold">{{ $order->shipping->tracking_number }}</code>
                                </div>
                            @else
                                <div class="text-center py-2 text-muted">
                                    <i class="fas fa-truck-loading fa-2x mb-2 opacity-25"></i>
                                    <p class="small mb-0">Awaiting shipment details</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm status-control-card mb-4 no-print">
                <div class="card-body p-4">
                    <span class="info-label text-center mb-3">Change Pipeline Stage</span>
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf @method('PUT')
                        <select name="status" class="form-control form-control-lg status-select shadow-none mb-3" onchange="this.form.submit()">
                            @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'callback_requested'] as $stat)
                                <option value="{{ $stat }}" {{ $order->status == $stat ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $stat)) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    
                    @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                        <form action="{{ route('shop.sales.finalize', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-soft-success btn-block font-weight-bold">
                                <i class="fas fa-check-double mr-2"></i> Mark as Delivered
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between">
                    Customer Details
                    <span class="text-muted" style="font-size: 0.6rem;">{{ $order->user_id ? 'MEMBER' : 'GUEST' }}</span>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; font-weight: 800; font-size: 1.2rem;">
                            {{ $initial }}
                        </div>
                        <div>
                            <h6 class="mb-0 font-weight-bold text-dark">{{ $customerName }}</h6>
                            <span class="text-muted small">{{ $customerEmail }}</span>
                        </div>
                    </div>
                    <hr class="my-3" style="border-style: dashed;">
                    
                    <div class="mb-3">
                        <span class="info-label">Deliver To</span>
                        <div class="info-value" style="font-size: 0.85rem; line-height: 1.5;">
                            {{ $street }}<br>
                            {{ $city }}{{ ($city && $country) ? ',' : '' }} {{ $country }}
                        </div>
                    </div>

                    <div>
                        <span class="info-label">Contact Number</span>
                        @if($customerPhone !== 'N/A')
                            <a href="tel:{{ $customerPhone }}" class="info-value text-primary font-weight-bold">
                                <i class="fas fa-phone-alt mr-2 small"></i>{{ $customerPhone }}
                            </a>
                        @else
                            <span class="text-muted small font-italic">No phone recorded</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow-sm bg-light border-0">
                <div class="card-body p-3">
                    <span class="info-label"><i class="fas fa-history mr-1"></i> Timeline Activity</span>
                    <ul class="list-unstyled small mb-0 mt-2">
                        <li class="mb-2"><i class="fas fa-circle text-success mr-2" style="font-size: 0.4rem;"></i> Created on {{ $order->created_at->format('M d, Y') }}</li>
                        @if($order->updated_at != $order->created_at)
                            <li><i class="fas fa-circle text-primary mr-2" style="font-size: 0.4rem;"></i> Last modified {{ $order->updated_at->diffForHumans() }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection