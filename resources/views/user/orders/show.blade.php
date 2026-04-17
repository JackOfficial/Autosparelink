@extends('layouts.dashboard')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="container py-4 py-lg-5">
    
    {{-- Top Navigation --}}
    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <a href="{{ route('user.orders.index') }}" class="text-decoration-none text-secondary d-inline-flex align-items-center fw-bold">
            <i class="fas fa-chevron-left me-2 small"></i> Back to Orders
        </a>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <button onclick="window.print()" class="btn btn-white border rounded-pill px-3 shadow-sm bg-white">
                <i class="fas fa-print me-1"></i> Print Invoice
            </button>
            <a href="{{ route('user.tickets.create', ['order_id' => $order->id]) }}" class="btn btn-info text-white rounded-pill px-4 fw-bold shadow-sm">
                <i class="fas fa-headset me-1"></i> Get Help
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Column: Order Content & Inspection --}}
        <div class="col-lg-8">
            
            {{-- Status Timeline --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-4 text-uppercase ls-1 small">Order Progress</h6>
                    <div class="position-relative px-3">
                        @php
                            $steps = ['pending', 'processing', 'delivered', 'completed'];
                            $currentIdx = array_search($order->status, $steps);
                        @endphp
                        
                        <div class="progress mb-2" style="height: 4px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $order->status == 'cancelled' || $order->status == 'disputed' ? '0' : ($currentIdx / 3 * 100) }}%"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between position-relative" style="top: -15px;">
                            @foreach($steps as $index => $step)
                                <div class="text-center">
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm 
                                        {{ $currentIdx >= $index ? 'bg-primary text-white' : 'bg-white border text-muted' }}" 
                                        style="width: 28px; height: 28px; font-size: 11px; z-index: 2; position: relative;">
                                        @if($currentIdx > $index) <i class="fas fa-check"></i> @else {{ $index + 1 }} @endif
                                    </div>
                                    <div class="small mt-2 fw-bold text-capitalize {{ $currentIdx >= $index ? 'text-dark' : 'text-muted' }}" style="font-size: 11px;">
                                        {{ $step }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
        <i class="fas fa-check-circle me-3 fa-lg"></i>
        <div>
            <h6 class="mb-0 fw-bold">Inspection Submitted</h6>
            <span class="small">{{ session('success') }}</span>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
        <ul class="mb-0 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            {{-- Inspection Form (Only if Delivered) --}}
            @if($order->status === 'delivered')
            <div class="card shadow-sm rounded-4 mb-4 border-start border-info border-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark"><i class="fas fa-search-plus text-info me-2"></i> Inspect Your Items</h5>
                    <p class="text-muted small">Please inspect the parts below. Confirming "Accept" will release payment to the seller.</p>
                    
                    <form action="{{ route('user.orders.inspection', $order->id) }}" method="POST" 
                     x-data="{ submitting: false }" @submit="submitting = true">
                        @csrf
                        @foreach($order->orderItems as $index => $item)
                            <div class="inspection-item p-3 border rounded-4 mb-3 bg-light-subtle" x-data="{ action: 'accept' }">
                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $item->part->photos->first() ? asset('storage/'.$item->part->photos->first()->file_path) : asset('images/placeholder-part.png') }}" 
                                         class="rounded-3 me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold">{{ $item->part->part_name }}</h6>
                                        <span class="small text-muted">{{ $item->part->partBrand->name }} | SKU: {{ $item->part->sku }}</span>
                                    </div>
                                    <div class="btn-group btn-group-sm rounded-pill overflow-hidden border">
                                        <input type="radio" class="btn-check" name="items[{{ $index }}][action]" id="accept_{{ $item->id }}" value="accept" x-model="action" checked>
                                        <label class="btn btn-outline-success border-0 px-3" for="accept_{{ $item->id }}">Accept</label>

                                        <input type="radio" class="btn-check" name="items[{{ $index }}][action]" id="dispute_{{ $item->id }}" value="dispute" x-model="action">
                                        <label class="btn btn-outline-danger border-0 px-3" for="dispute_{{ $item->id }}">Dispute</label>
                                    </div>
                                </div>
                                <div x-show="action === 'dispute'" x-collapse>
                                    <textarea name="items[{{ $index }}][reason]" class="form-control form-control-sm rounded-3" placeholder="Explain the issue (wrong part, damaged, etc.)..."></textarea>
                                </div>
                            </div>
                        @endforeach
                        <button type="submit" 
            class="btn btn-dark w-100 rounded-pill py-2 fw-bold mt-2 shadow" 
            :disabled="submitting">
        <span x-show="!submitting">Submit Final Inspection</span>
        <span x-show="submitting"><i class="fas fa-spinner fa-spin me-2"></i> Processing...</span>
    </button>
                    </form>
                </div>
            </div>
            @endif

           {{-- Items Card --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-0">Spare Parts List</h5>
            <p class="text-muted small mb-0">Review the items in your order</p>
        </div>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
            {{ $order->orderItems->count() }} {{ Str::plural('Item', $order->orderItems->count()) }}
        </span>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light-subtle">
                    <tr class="small text-uppercase text-muted fw-bold">
                        <th class="px-4 py-3 border-0">Part Description</th>
                        <th class="py-3 border-0">Shop & Location</th>
                        <th class="py-3 border-0 text-center">Status</th>
                        <th class="py-3 border-0 text-center">Qty</th>
                        <th class="py-3 border-0 text-end">Unit Price</th>
                        <th class="px-4 py-3 border-0 text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        @php
                            $photoPath = $item->part?->photos?->first()?->file_path;
                        @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($photoPath)
                                            <img src="{{ asset('storage/' . $photoPath) }}" 
                                                 class="rounded-3 border" 
                                                 style="width: 52px; height: 52px; object-fit: cover;" 
                                                 alt="Part">
                                        @else
                                            <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center text-muted" 
                                                 style="width: 52px; height: 52px;">
                                                <i class="fas fa-tools small"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        <div class="fw-bold text-dark">{{ $item->part?->part_name ?? 'Unknown Part' }}</div>
                                        <div class="small text-muted">
                                            {{ $item->part?->partBrand?->name ?? 'Genuine' }} • {{ $item->part?->category?->category_name ?? 'General' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark small mb-1">
                                        <i class="fas fa-store me-1 text-primary"></i>{{ $item->shop->shop_name ?? 'N/A' }}
                                    </span>
                                    <span class="text-muted" style="font-size: 11px;">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $item->shop->address ?? 'Kigali, Rwanda' }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                @php
                                    $statusClasses = [
                                        'pending'   => 'bg-warning-subtle text-warning border-warning-subtle',
                                        'delivered' => 'bg-info-subtle text-info border-info-subtle',
                                        'completed' => 'bg-success-subtle text-success border-success-subtle',
                                        'disputed'  => 'bg-danger-subtle text-danger border-danger-subtle',
                                    ];
                                    $currentClass = $statusClasses[$item->status] ?? 'bg-light text-muted';
                                @endphp
                                <span class="badge {{ $currentClass }} border rounded-pill px-2 py-1 text-uppercase" style="font-size: 10px;">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="py-3 text-center fw-medium">{{ $item->quantity }}</td>
                            <td class="py-3 text-end text-muted small">RWF {{ number_format($item->unit_price, 0) }}</td>
                            <td class="px-4 py-3 text-end fw-bold text-dark">RWF {{ number_format($item->unit_price * $item->quantity, 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-top">
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-end text-muted small">Subtotal</td>
                        <td class="px-4 py-2 text-end fw-bold text-dark">RWF {{ number_format($order->total_amount, 0) }}</td>
                    </tr>
                    <tr class="bg-light-subtle">
                        <td colspan="5" class="px-4 py-3 text-end fw-bold text-primary text-uppercase">Total Amount</td>
                        <td class="px-4 py-3 text-end fw-bold text-primary fs-5">RWF {{ number_format($order->total_amount, 0) }}</td>
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
                    <h6 class="fw-bold text-dark mb-4 d-flex align-items-center text-uppercase small ls-1">
                        <i class="fas fa-file-invoice text-primary me-2"></i> Order Details
                    </h6>
                    
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="small text-muted fw-bold mb-0">ORDER NUMBER</label>
                        <span class="fw-bold text-primary">#{{ $order->order_number ?? 'N/A' }}</span>
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="small text-muted fw-bold mb-0">DATE</label>
                        <span class="small">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="small text-muted fw-bold mb-0">PAYMENT</label>
                        <span class="badge bg-light text-dark border text-capitalize">{{ str_replace('_', ' ', $order->payment_method ?? 'Momo/Card') }}</span>
                    </div>

                    <hr class="opacity-10 my-4">

                    <div class="mb-0">
    <label class="small text-muted text-uppercase fw-bold ls-1 mb-2 d-block">Deliver To</label>
    <div class="d-flex">
        <i class="fas fa-map-marker-alt text-danger me-3 mt-1"></i>
        <div class="small text-dark mb-0 lh-base">
            @if($order->address)
                {{-- No manual decoding needed now! --}}
                <div class="fw-bold text-dark">{{ $order->address['full_name'] ?? 'Recipient' }}</div>
                <div class="text-muted">
                    {{ $order->address['street_address'] ?? 'N/A' }}<br>
                    {{ $order->address['city'] ?? '' }}{{ isset($order->address['country']) ? ', ' . $order->address['country'] : '' }}
                </div>
                <div class="mt-1 fw-medium text-primary">
                    <i class="fas fa-phone-alt me-1 small"></i> {{ $order->address['phone'] ?? 'No Phone' }}
                </div>
            @else
                <span class="text-muted italic">No delivery address specified.</span>
            @endif
        </div>
    </div>
</div>
                </div>
            </div>

            {{-- Support Box --}}
            <div class="card border-0 shadow-sm rounded-4 bg-dark text-white p-2">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2">Technical Issue?</h6>
                    <p class="small opacity-75 mb-3">If the spare part does not match your vehicle fitment, please open a dispute immediately.</p>
                    <a href="{{ route('user.tickets.create', ['order_id' => $order->id]) }}" class="btn btn-outline-light btn-sm rounded-pill w-100">
                        Report Fitment Issue
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .rounded-4 { border-radius: 1.25rem !important; }
    .bg-light-subtle { background-color: #f8f9fa; }
    .progress { overflow: visible; background-color: #e9ecef; }
    
    @media print {
        .btn, .bg-dark, .progress, .inspection-item, .alert { display: none !important; }
        .card { border: 1px solid #eee !important; box-shadow: none !important; }
        body { background: white !important; padding: 0; }
        .container { width: 100% !important; max-width: 100% !important; }
    }
</style>
@endsection