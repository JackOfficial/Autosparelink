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

            {{-- Inspection Form (Only if Delivered) --}}
            @if($order->status === 'delivered')
            <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-info border-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark"><i class="fas fa-search-plus text-info me-2"></i> Inspect Your Items</h5>
                    <p class="text-muted small">Please inspect the parts below. Confirming "Accept" will release payment to the seller.</p>
                    
                    <form action="{{ route('user.orders.inspection', $order->id) }}" method="POST">
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
                        <button type="submit" class="btn btn-dark w-100 rounded-pill py-2 fw-bold mt-2 shadow">
                            Submit Final Inspection
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Items Card --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between">
                    <h5 class="fw-bold mb-0">Spare Parts List</h5>
                    <span class="badge bg-light text-dark border rounded-pill px-3">{{ $order->orderItems->count() }} Items</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-uppercase text-muted">
                                    <th class="px-4 py-3 border-0">Part Description</th>
                                    <th class="py-3 border-0 text-center">Shop</th>
                                    <th class="py-3 border-0 text-center">Qty</th>
                                    <th class="px-4 py-3 border-0 text-end">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="fw-bold text-dark">{{ $item->part->part_name }}</div>
                                            </div>
                                            <div class="small text-muted">
                                                {{ $item->part->partBrand->name }} • {{ $item->part->category->category_name }}
                                            </div>
                                            @if($item->status === 'disputed')
                                                <span class="badge bg-danger-subtle text-danger rounded-pill mt-1" style="font-size: 10px;">Item Disputed</span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-center small text-muted">
                                            {{ $item->shop->shop_name ?? 'N/A' }}
                                        </td>
                                        <td class="py-3 text-center fw-bold">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3 text-end fw-bold">RWF {{ number_format($item->price, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light-subtle">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-end fw-bold">Subtotal:</td>
                                    <td class="px-4 py-3 text-end fw-bold">RWF {{ number_format($order->total_amount, 0) }}</td>
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
                        <span class="fw-bold text-primary">#{{ $order->order_number }}</span>
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