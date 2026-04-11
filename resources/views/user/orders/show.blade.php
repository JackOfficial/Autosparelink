@extends('layouts.dashboard')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="container py-4 py-lg-5" x-data="{ printing: false }">
    
    {{-- Top Navigation --}}
    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <a href="{{ route('orders.index') }}" class="text-decoration-none text-secondary d-inline-flex align-items-center fw-bold">
            <i class="fas fa-chevron-left me-2 small"></i> Back to Orders
        </a>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <button @click="window.print()" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                <i class="fas fa-print me-1"></i> Print Invoice
            </button>
            <a href="{{ route('tickets.create', ['order_id' => $order->id]) }}" class="btn btn-info text-white rounded-pill px-4 fw-bold shadow-sm">
                <i class="fas fa-headset me-1"></i> Get Help
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Column: Order Content & Timeline --}}
        <div class="col-lg-8">
            
            {{-- Status Timeline --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-4 text-uppercase ls-1 small">Order Progress</h6>
                    <div class="position-relative">
                        @php
                            $steps = ['pending', 'processing', 'shipped', 'completed'];
                            $currentIdx = array_search($order->status, $steps);
                        @endphp
                        
                        <div class="progress mb-2" style="height: 4px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $order->status == 'cancelled' ? '0' : ($currentIdx / 3 * 100) }}%"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between position-relative" style="top: -15px;">
                            @foreach($steps as $index => $step)
                                <div class="text-center">
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm 
                                        {{ $currentIdx >= $index ? 'bg-primary text-white' : 'bg-white border text-muted' }}" 
                                        style="width: 24px; height: 24px; font-size: 10px; z-index: 2; position: relative;">
                                        @if($currentIdx > $index) <i class="fas fa-check"></i> @else {{ $index + 1 }} @endif
                                    </div>
                                    <div class="small mt-2 fw-bold text-capitalize {{ $currentIdx >= $index ? 'text-dark' : 'text-muted' }}" style="font-size: 11px;">
                                        {{ $step }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    @if($order->status == 'cancelled')
                        <div class="alert alert-danger border-0 rounded-3 mt-4 mb-0 d-flex align-items-center small">
                            <i class="fas fa-exclamation-circle me-2"></i> This order was cancelled.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Items Card --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Order Summary</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-uppercase text-muted">
                                    <th class="px-4 py-3 border-0">Item Description</th>
                                    <th class="py-3 border-0 text-center">Qty</th>
                                    <th class="px-4 py-3 border-0 text-end">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- If multiple items exist --}}
                                @if(is_iterable($order->items))
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="fw-bold text-dark">{{ $item->name }}</div>
                                                <div class="small text-muted">SKU: {{ $item->sku ?? 'N/A' }}</div>
                                            </td>
                                            <td class="py-3 text-center">{{ $item->quantity }}</td>
                                            <td class="px-4 py-3 text-end fw-bold">RWF {{ number_format($item->price, 0) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    {{-- Single item fallback (common in SMM panels) --}}
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="fw-bold text-dark">{{ $order->item_name ?? 'Service Order' }}</div>
                                            <div class="small text-muted">{{ $order->category }}</div>
                                        </td>
                                        <td class="py-3 text-center">1</td>
                                        <td class="px-4 py-3 text-end fw-bold">RWF {{ number_format($order->total_amount, 0) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-end fw-bold">Total Amount:</td>
                                    <td class="px-4 py-3 text-end h5 fw-bold text-primary">RWF {{ number_format($order->total_amount, 0) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Side Info --}}
        <div class="col-lg-4">
            {{-- Order Meta Info --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-4 d-flex align-items-center">
                        <i class="fas fa-file-invoice text-primary me-2"></i> Order Details
                    </h6>
                    
                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold ls-1 mb-1 d-block">Order ID</label>
                        <span class="fw-bold">#{{ $order->order_number }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold ls-1 mb-1 d-block">Placed On</label>
                        <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted text-uppercase fw-bold ls-1 mb-1 d-block">Payment Method</label>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-wallet text-muted me-2"></i>
                            <span class="text-capitalize">{{ str_replace('_', ' ', $order->payment_method ?? 'Account Balance') }}</span>
                        </div>
                    </div>

                    <hr class="opacity-10">

                    <div class="mb-0">
                        <label class="small text-muted text-uppercase fw-bold ls-1 mb-2 d-block">Shipping Address</label>
                        <p class="small text-dark mb-0 lh-base">
                            {{ $order->address ?? 'Digital Delivery / Service' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Need Help Box --}}
            <div class="card border-0 shadow-sm rounded-4 bg-dark text-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2">Notice an issue?</h6>
                    <p class="small opacity-75 mb-3">If the order details are incorrect or you haven't received your service, let us know.</p>
                    <a href="{{ route('tickets.create', ['order_id' => $order->id]) }}" class="btn btn-outline-light btn-sm rounded-pill w-100">
                        Open Ticket for this Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .rounded-4 { border-radius: 1rem !important; }
    
    @media print {
        .btn, .breadcrumb, nav, .bg-dark, .progress { display: none !important; }
        .card { border: 1px solid #eee !important; box-shadow: none !important; }
        body { background: white !important; }
        .container { width: 100% !important; max-width: 100% !important; }
    }
</style>
@endsection