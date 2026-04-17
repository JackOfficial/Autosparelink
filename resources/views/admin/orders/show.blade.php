@extends('admin.layouts.app')

@push('styles')
<style>
    /* Status-specific branding */
    :root {
        --bs-success-rgb: 40, 167, 69;
        --bs-primary-rgb: 0, 123, 255;
        --primary-deep: #1971c2;
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
    
    .product-img {
       width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #edf2f9;
    background-color: #f8f9fa;
    transition: transform 0.2s;
    }

    .product-img:hover {
    transform: scale(1.1);
    z-index: 10;
}

.img-placeholder {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f4f8;
    border-radius: 8px;
    color: #cbd5e0;
}

    .shop-badge {
        font-size: 0.65rem;
        background: #f8f9fa;
        color: #6c757d;
        border: 1px solid #e9ecef;
        padding: 2px 8px;
        border-radius: 4px;
        display: inline-block;
        margin-top: 4px;
    }
    
    /* Order Stepper - Now supporting 5 stages */
    .stepper-wrapper { display: flex; justify-content: space-between; margin-bottom: 2rem; padding: 0 1rem; }
    .stepper-item { position: relative; display: flex; flex-direction: column; align-items: center; flex: 1; }
    .stepper-item::before { position: absolute; content: ""; border-bottom: 2px solid #e9ecef; width: 100%; top: 18px; left: -50%; z-index: 1; }
    .stepper-item:first-child::before { content: none; }
    
    .step-counter { position: relative; z-index: 5; display: flex; justify-content: center; align-items: center; width: 36px; height: 36px; border-radius: 50%; background: #e9ecef; color: #adb5bd; font-weight: bold; margin-bottom: 6px; transition: 0.3s; border: 2px solid #fff; }
    
    .stepper-item.active .step-counter { background-color: var(--primary-deep); color: #fff; box-shadow: 0 0 0 3px rgba(25, 113, 194, 0.2); }
    .stepper-item.completed .step-counter { background-color: #2b8a3e; color: #fff; }
    .stepper-item.completed::before { border-color: #2b8a3e; }

    .step-name { font-size: 0.6rem; font-weight: 700; color: #adb5bd; text-transform: uppercase; text-align: center; }
    .stepper-item.active .step-name { color: var(--primary-deep); }
    .stepper-item.completed .step-name { color: #2b8a3e; }

    /* Control Styling */
    .status-control-card { background: #fff; border: 1px solid #eef2f7; border-top: 4px solid var(--primary-deep); }
    .status-select { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; font-weight: 600; font-size: 0.9rem; height: 45px !important; }

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

    // Refined Stepper logic - explicitly handling 'completed' casing
    $statuses = ['pending', 'processing', 'shipped', 'delivered', 'completed'];
    $currentStatus = strtolower($order->status);
    $currentIdx = array_search($currentStatus, $statuses);
@endphp

<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 no-print">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}" class="text-primary">Pipeline</a></li>
                    <li class="breadcrumb-item active text-muted">INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</li>
                </ol>
            </nav>
            <h2 class="h4 mb-0 font-weight-bold text-dark">Order Management</h2>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <button onclick="window.print()" class="btn btn-white border shadow-sm px-3 font-weight-bold">
                <i class="fas fa-print mr-1"></i> Print
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary shadow-sm px-3 font-weight-bold">
                <i class="fas fa-arrow-left mr-1"></i> Back to Pipeline
            </a>
        </div>
    </div>

    {{-- Urgent Callback Alert --}}
    @if($order->status === 'callback_requested')
        <div class="alert alert-danger blink_me d-flex align-items-center mb-4 border-0 shadow-sm" style="border-radius: 12px; background: #fff5f5; border-left: 5px solid #c92a2a !important;">
            <div class="bg-white rounded-circle p-2 mr-3 shadow-sm text-danger" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-phone-alt"></i>
            </div>
            <div>
                <h6 class="mb-0 font-weight-bold text-danger">Action Required: Callback Requested</h6>
                <span class="small">The client is waiting for a call at <strong>{{ $customerPhone }}</strong>.</span>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Pipeline Stepper --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body py-4">
                    <div class="stepper-wrapper">
                        @foreach($statuses as $index => $step)
                            @php
                                $isCompleted = $currentIdx > $index || $currentStatus === 'completed';
                                $isActive = $currentIdx === $index;
                            @endphp
                            <div class="stepper-item {{ $isCompleted ? 'completed' : ($isActive ? 'active' : '') }}">
                                <div class="step-counter">
                                    @if($isCompleted) <i class="fas fa-check"></i> @else {{ $index + 1 }} @endif
                                </div>
                                <div class="step-name">{{ ucfirst($step) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

           {{-- Items Table --}}
<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-shopping-cart mr-2 text-primary"></i> Order Items</span>
        <span class="badge badge-soft-primary px-3">{{ $order->orderItems->count() }} Positions</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
    <thead>
        <tr>
            <th class="px-4" style="width: 40%;">Product Detail</th>
            <th class="text-left">Vendor/Shop</th>
            <th class="text-center">Quantity</th>
            <th class="text-right">Unit Price</th>
            <th class="text-right px-4">Line Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->orderItems as $item)
        <tr>
            <td class="px-4 py-3">
                <div class="d-flex align-items-center">
                    {{-- Part Photo Logic: orderItems->part->photos->file_path --}}
                    <div class="mr-3">
                        @php
                            $firstPhoto = $item->part->photos->first() ?? null;
                        @endphp

                        @if($firstPhoto && $firstPhoto->file_path)
                            <img src="{{ asset('storage/' . $firstPhoto->file_path) }}" 
                                 alt="{{ $item->part->part_name ?? 'Product Image' }}" 
                                 class="product-img shadow-sm"
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div class="img-placeholder shadow-sm d-flex align-items-center justify-content-center bg-light" 
                                 style="width: 50px; height: 50px; border-radius: 8px; border: 1px solid #f1f4f8;">
                                <i class="fas fa-tools text-muted opacity-50"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Part Info --}}
                    <div>
                        <span class="d-block font-weight-bold text-dark">
                            {{ $item->part->part_name ?? 'Unknown Part' }}
                        </span>
                        <div class="d-flex align-items-center mt-1">
                            <span class="badge badge-soft-secondary mr-2" style="font-size: 0.6rem;">
                                SKU: {{ $item->part->sku ?? 'N/A' }}
                            </span>
                            @if($item->part && $item->part->category)
                                <small class="text-muted text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.5px;">
                                    <i class="fas fa-tag mr-1"></i>{{ $item->part->category->name }}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </td>
            
            <td class="text-left">
                <div class="d-flex flex-column">
                    <span class="small font-weight-bold text-dark">
                        {{ $item->part->shop->name ?? 'Direct Warehouse' }}
                    </span>
                    <span class="shop-badge">
                        <i class="fas fa-map-marker-alt mr-1" style="font-size: 0.5rem;"></i>
                        {{ $item->part->shop->location ?? 'Main Branch' }}
                    </span>
                </div>
            </td>

            <td class="text-center">
                <span class="badge badge-light px-3 py-2 text-dark font-weight-bold">
                    {{ $item->quantity }}
                </span>
            </td>

            <td class="text-right text-muted font-weight-medium">
                {{ number_format($item->unit_price) }} RWF
            </td>

            <td class="text-right px-4 font-weight-bold text-primary">
                {{ number_format($item->quantity * $item->unit_price) }} RWF
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot style="background: #fcfcfd; border-top: 2px solid #f1f4f8;">
        <tr>
            <td colspan="4" class="text-right font-weight-bold py-3 text-muted">ORDER TOTAL:</td>
            <td class="text-right px-4 h5 font-weight-bold text-primary py-3">
                {{ number_format($order->total_amount) }} RWF
            </td>
        </tr>
    </tfoot>
</table>
        </div>
    </div>
</div>

            {{-- Logistics & Payment Grid --}}
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
                                    <span class="info-value text-capitalize"><i class="fas fa-credit-card mr-1 text-muted"></i> {{ $order->payment->method }}</span>
                                </div>
                            @else
                                <div class="text-center py-2">
                                    <p class="text-muted small mb-0 font-italic">No payment record found.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header">Logistics Tracking</div>
                        <div class="card-body">
                            @if($order->shipping)
                                <div class="mb-3">
                                    <span class="info-label">Courier Service</span>
                                    <span class="info-value">{{ $order->shipping->carrier }}</span>
                                </div>
                                <div>
                                    <span class="info-label">Tracking Code</span>
                                    <span class="badge bg-light text-primary font-weight-bold px-2 py-2 w-100 text-left" style="font-size: 0.85rem;">
                                        <i class="fas fa-barcode mr-2"></i> {{ $order->shipping->tracking_number }}
                                    </span>
                                </div>
                            @else
                                <div class="text-center py-2 text-muted">
                                    <i class="fas fa-shipping-fast fa-2x mb-2 opacity-25"></i>
                                    <p class="small mb-0">No shipping info assigned</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Controls --}}
        <div class="col-lg-4">
            <div class="card shadow-sm status-control-card mb-4 no-print">
                <div class="card-body p-4">
                    <span class="info-label text-center mb-3">Update Order Progress</span>
                    
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="mb-3">
                        @csrf @method('PUT')
                        <select name="status" class="form-control status-select shadow-none mb-3" onchange="this.form.submit()">
                            @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $stat)
                                <option value="{{ $stat }}" {{ strtolower($order->status) == $stat ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $stat)) }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    {{-- Logic for Final Acceptance --}}
                    @if($currentStatus === 'delivered')
                        <form action="{{ route('shop.sales.finalize', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block py-2 font-weight-bold shadow-sm">
                                <i class="fas fa-handshake mr-2"></i> Confirm Customer Accepted
                            </button>
                            <p class="text-muted small text-center mt-2 mb-0">This verifies items match user expectations.</p>
                        </form>
                    @elseif($currentStatus === 'completed')
                        <div class="alert bg-success-soft text-success text-center border-0 mb-0">
                            <i class="fas fa-check-double fa-2x mb-2"></i>
                            <div class="font-weight-bold">Order Finalized</div>
                            <small>Transaction accepted by customer</small>
                        </div>
                    @else
                        {{-- Contextual Action for non-delivered items --}}
                        <div class="p-3 bg-light rounded text-center">
                            <small class="text-muted d-block mb-2">Next standard step:</small>
                            <span class="badge badge-pill badge-primary px-3 py-2">
                                {{ $currentIdx < 4 ? ucfirst($statuses[$currentIdx + 1] ?? 'None') : 'Finalized' }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Customer Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Customer Profile
                    <span class="badge {{ $order->user_id ? 'tag-member' : 'tag-guest' }} px-2" style="font-size: 0.55rem; background: #f1f3f5;">{{ $order->user_id ? 'MEMBER' : 'GUEST' }}</span>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
    <div class="mr-3">
        @if($order->user && $order->user->avatar)
            {{-- User Avatar --}}
            <img src="{{ $order->user->avatar }}" 
                 alt="{{ $customerName }}" 
                 class="rounded-circle shadow-sm"
                 style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #fff;">
        @else
            {{-- Initial Fallback --}}
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                 style="width: 50px; height: 50px; font-weight: 800; font-size: 1.2rem; box-shadow: 0 4px 8px rgba(0,123,255,0.2);">
                {{ $initial }}
            </div>
        @endif
    </div>

    <div style="overflow: hidden;">
        <h6 class="mb-0 font-weight-bold text-dark text-truncate">{{ $customerName }}</h6>
        <span class="text-muted small text-truncate d-block">{{ $customerEmail }}</span>
    </div>
</div>
                    <hr class="my-3" style="border-style: dashed; opacity: 0.5;">
                    
                    <div class="mb-3">
                        <span class="info-label">Shipping Address</span>
                        <div class="info-value" style="font-size: 0.85rem; line-height: 1.4;">
                            {{ $street }}<br>
                            <span class="font-weight-bold">{{ $city }}</span>{{ ($city && $country) ? ',' : '' }} {{ $country }}
                        </div>
                    </div>

                    <div>
                        <span class="info-label">Direct Line</span>
                        @if($customerPhone !== 'N/A')
                            <a href="tel:{{ $customerPhone }}" class="info-value text-primary font-weight-bold d-block">
                                <i class="fas fa-phone-alt mr-2 small"></i>{{ $customerPhone }}
                            </a>
                        @else
                            <span class="text-muted small font-italic">No phone recorded</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Activity Feed --}}
            <div class="card shadow-sm bg-light border-0">
                <div class="card-body p-3">
                    <span class="info-label mb-2"><i class="fas fa-history mr-1"></i> Audit Trail</span>
                    <div class="small">
                        <div class="d-flex mb-2">
                            <i class="fas fa-dot-circle text-success mr-2 mt-1"></i>
                            <span>Placed: {{ $order->created_at->format('M d, Y @ H:i') }}</span>
                        </div>
                        @if($order->updated_at != $order->created_at)
                        <div class="d-flex">
                            <i class="fas fa-dot-circle text-primary mr-2 mt-1"></i>
                            <span>Modified: {{ $order->updated_at->diffForHumans() }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection