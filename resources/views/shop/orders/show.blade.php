<x-shop-dashboard>
    <x-slot:title>Order #{{ $order->id }} - Details</x-slot:title>

    @push('styles')
    <style>
        :root {
            --surface-bg: #f8f9fa;
            --border-color: #eef2f6;
            --text-muted: #8492a6;
            --text-main: #2b3c4e;
        }

        .card { 
            border-radius: 12px; 
            border: 1px solid var(--border-color);
            background-color: #fff;
            transition: all 0.2s ease-in-out;
        }
        
        .info-label { 
            font-size: 0.72rem; 
            text-transform: uppercase; 
            color: var(--text-muted); 
            font-weight: 700; 
            letter-spacing: 0.5px;
            margin-bottom: 4px; 
            display: block; 
        }
        
        .info-value { 
            font-size: 0.92rem; 
            color: var(--text-main); 
            font-weight: 500; 
        }
        
        .item-status-select { 
            font-size: 0.82rem; 
            border-radius: 8px; 
            padding: 0.4rem 0.6rem; 
            border: 1px solid #d1d9e2;
            background-color: #fff;
            cursor: pointer;
            transition: border-color 0.15s ease-in-out;
        }

        .item-status-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
            outline: none;
        }
        
        .bg-light-soft { 
            background-color: var(--surface-bg); 
        }
        
        @media print {
            .btn, nav, .breadcrumb, .no-print, .item-status-select { display: none !important; }
            .card { border: 1px solid #ddd !important; box-shadow: none !important; }
            .container-fluid { padding: 0 !important; }
        }

        .img-stack-container {
            position: relative;
            width: 55px; 
            height: 42px;
        }

        .stack-img {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid #fff;
            position: absolute;
            transition: transform 0.2s ease;
        }

        .stack-img:hover {
            transform: translateY(-3px) scale(1.05);
            z-index: 30 !important;
        }

        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
        }

        .badge-status {
            font-size: 0.75rem;
            padding: 0.45em 0.85em;
            font-weight: 600;
            border-radius: 6px;
            letter-spacing: 0.3px;
        }
    </style>
    @endpush

    @php
        // Mask exact shipping address and phone for the shop (Admin handles delivery)
        $customerName = $order->guest_name ?? $order->user->name ?? 'Guest Customer';
        $customerEmail = $order->guest_email ?? $order->user->email ?? 'No email provided';
        
        $city = $order->city ?? $order->address->city ?? '';
        $country = $order->country ?? $order->address->country ?? '';
        $initial = strtoupper(substr($customerName, 0, 1));

        // Check if the order/client has already paid
        $isPaid = optional($order->payment)->status === 'completed';

        // Subtotal calculation for the shop's items only
        $shopSubtotal = $order->orderItems->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });
    @endphp

    <div class="container-fluid py-4">
        {{-- Header Section --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <nav aria-label="breadcrumb" class="no-print">
                    <ol class="breadcrumb mb-2" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="{{ route('shop.orders.index') }}" class="text-decoration-none text-muted">Orders</a></li>
                        <li class="breadcrumb-item active text-dark fw-medium">#{{ $order->id }}</li>
                    </ol>
                </nav>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <h2 class="h4 mb-0 fw-bold text-dark">Order #{{ $order->id }}</h2>
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle text-uppercase badge-status">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </div>
            </div>
            <div class="d-flex gap-2 no-print">
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center bg-white shadow-sm px-3 py-2" style="border-radius: 8px;">
                    <i class="fas fa-print me-2 text-muted"></i> Print Invoice
                </button>
            </div>
        </div>

        {{-- Urgent Callback Alert --}}
        @if($order->status === 'callback_requested')
            <div class="alert alert-warning d-flex align-items-center mb-4 shadow-sm border-0 p-3" style="border-radius: 12px;">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3 text-warning" style="width: 40px; height: 40px;">
                    <i class="fas fa-headset"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Support Action Pending</h6>
                    <span class="small">The customer requested a call. Platform administrators are resolving it directly.</span>
                </div>
            </div>
        @endif

        {{-- Unpaid Order Restriction Alert --}}
        @if(!$isPaid)
            <div class="alert alert-info d-flex align-items-center mb-4 shadow-sm border-0 p-3" style="border-radius: 12px;">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3 text-info" style="width: 40px; height: 40px;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">Awaiting Payment Completion</h6>
                    <span class="small">Order processing is paused until the client completes payment.</span>
                </div>
            </div>
        @endif

        {{-- Success/Error Alerts --}}
        @if(session('success')) 
            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4 py-3" style="border-radius: 12px;" role="alert">
                <i class="fas fa-check-circle me-3 fs-5"></i>
                <div class="small fw-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4 py-3" style="border-radius: 12px;" role="alert">
                <i class="fas fa-exclamation-circle me-3 fs-5"></i>
                <div class="small fw-medium">{{ session('error') }}</div>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                {{-- Items Table Card --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                        <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                            <i class="fas fa-shopping-basket me-2 text-primary"></i> Cart Items
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light-soft border-bottom">
                                    <tr>
                                        <th class="ps-4 border-0 small fw-bold text-muted text-uppercase" style="font-size: 0.72rem;">Product</th>
                                        <th class="text-center border-0 small fw-bold text-muted text-uppercase" style="font-size: 0.72rem;">Qty</th>
                                        <th class="text-end border-0 small fw-bold text-muted text-uppercase" style="font-size: 0.72rem;">Unit Price</th>
                                        <th class="text-end border-0 small fw-bold text-muted text-uppercase" style="font-size: 0.72rem;">Subtotal</th>
                                        <th class="text-center border-0 small fw-bold text-muted text-uppercase no-print" style="font-size: 0.72rem; width: 190px;">Item Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                        <tr class="border-bottom">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    {{-- Visual stack of parts images --}}
                                                    <div class="me-3 img-stack-container d-none d-sm-block">
                                                        @forelse($item->part->photos->take(3) as $index => $photo)
                                                            <img src="{{ asset('storage/' . $photo->file_path) }}" 
                                                                 class="stack-img shadow-sm" 
                                                                 style="left: {{ $index * 12 }}px; z-index: {{ 10 - $index }};"
                                                                 alt="Part Image">
                                                        @empty
                                                            <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted shadow-sm" 
                                                                 style="width: 38px; height: 38px;">
                                                                <i class="fa fa-image opacity-50" style="font-size: 0.8rem;"></i>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                    
                                                    <div>
                                                        <div class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">
                                                            {{ $item->part->part_name ?? 'Product Deleted' }}
                                                        </div>
                                                        <span class="badge bg-light text-secondary border border-secondary-subtle fw-medium" style="font-size: 0.68rem;">
                                                            SKU: {{ $item->part->sku ?? 'N/A' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center fw-semibold text-dark" style="font-size: 0.9rem;">{{ $item->quantity }}</td>
                                            <td class="text-end text-nowrap text-secondary" style="font-size: 0.9rem;">{{ number_format($item->unit_price) }} RWF</td>
                                            <td class="text-end fw-bold text-dark text-nowrap" style="font-size: 0.9rem;">{{ number_format($item->quantity * $item->unit_price) }} RWF</td>
                                            <td class="text-center no-print">
                                                @php
                                                    // Locked out edits if past "ready_for_pickup" or if payment is NOT completed.
                                                    $isLocked = in_array($item->status, [
                                                        'ready_for_pickup', 'collected', 'at_hub', 
                                                        'delivered', 'completed', 'canceled', 
                                                        'disputed', 'returned'
                                                    ]) || !$isPaid;
                                                @endphp

                                                @if($isLocked)
                                                    @php
                                                        $badgeClass = match($item->status) {
                                                            'completed', 'delivered' => 'bg-success-subtle text-success border-success-subtle',
                                                            'disputed', 'returned' => 'bg-danger-subtle text-danger border-danger-subtle',
                                                            'ready_for_pickup', 'collected', 'at_hub' => 'bg-info-subtle text-info-emphasis border-info-subtle',
                                                            default => 'bg-light text-secondary border-secondary-subtle'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }} border text-uppercase badge-status w-100 py-2 d-inline-block text-center">
                                                        {{ str_replace('_', ' ', $item->status) }}
                                                    </span>
                                                @else
                                                    <form action="{{ route('shop.orders.items.update-status', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="status" class="form-select form-select-sm item-status-select fw-semibold" onchange="this.form.submit()">
                                                            <option value="pending" {{ $item->status === 'pending' ? 'selected' : '' }}>
                                                                Pending
                                                            </option>
                                                            <option value="packed" {{ $item->status === 'packed' ? 'selected' : '' }}>
                                                                Packed
                                                            </option>
                                                            <option value="ready_for_pickup" {{ $item->status === 'ready_for_pickup' ? 'selected' : '' }}>
                                                                Ready for Pickup
                                                            </option>
                                                            <option value="canceled" {{ $item->status === 'canceled' ? 'selected' : '' }}>
                                                                Cancel
                                                            </option>
                                                        </select>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light-soft">
                                    <tr class="border-bottom-0">
                                        <td colspan="3" class="text-end fw-semibold py-2 border-0 text-muted" style="font-size: 0.85rem;">Items Subtotal:</td>
                                        <td colspan="2" class="text-end pe-4 fw-bold py-2 border-0 text-dark" style="font-size: 0.9rem;">
                                            {{ number_format($shopSubtotal) }} RWF
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-semibold py-2 border-0 text-muted" style="font-size: 0.85rem;">Delivery Fee:</td>
                                        <td colspan="2" class="text-end pe-4 fw-bold py-2 border-0 text-dark" style="font-size: 0.9rem;">
                                            {{ number_format($order->delivery_price ?? 0) }} RWF
                                        </td>
                                    </tr>
                                    <tr class="border-top">
                                        <td colspan="3" class="text-end fw-bold py-3 text-dark border-0">Total Amount:</td>
                                        <td colspan="2" class="text-end pe-4 fw-bold text-primary h5 mb-0 py-3 border-0 text-nowrap">
                                            {{ number_format($shopSubtotal + ($order->delivery_price ?? 0)) }} RWF
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Administrative Logistics Information (Uneditable by Shop) --}}
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-uppercase small text-muted mb-3 d-flex align-items-center">
                                        <i class="fas fa-receipt me-2 text-primary"></i> Payment Info
                                    </h6>
                                    @if($order->payment)
                                        <div class="mb-3">
                                            <span class="info-label">Transaction ID</span>
                                            <span class="info-value text-break" style="font-size: 0.85rem;">{{ $order->payment->transaction_id }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center p-3 bg-light-soft rounded border">
                                            <div>
                                                <span class="info-label mb-0">Payment Method</span>
                                                <span class="fw-bold text-dark small">{{ strtoupper($order->payment->method) }}</span>
                                            </div>
                                            <span class="badge {{ $order->payment->status === 'completed' ? 'bg-success text-white' : 'bg-warning text-dark' }} badge-status border-0">
                                                {{ ucfirst($order->payment->status) }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <i class="fas fa-hourglass-half text-muted opacity-50 mb-2 fs-5"></i>
                                            <p class="small text-muted mb-0">No payment record found.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-uppercase small text-muted mb-3 d-flex align-items-center">
                                        <i class="fas fa-truck-loading me-2 text-primary"></i> Logistics
                                    </h6>
                                    @if($order->shipping)
                                        <div class="mb-3">
                                            <span class="info-label">Carrier Partner</span>
                                            <span class="info-value" style="font-size: 0.88rem;">{{ $order->shipping->carrier }}</span>
                                        </div>
                                        <div class="p-3 bg-light-soft border border-dashed rounded text-center">
                                            <span class="small text-muted d-block mb-1" style="font-size: 0.75rem;">Tracking ID:</span>
                                            <span class="fw-bold text-primary" style="font-size: 0.9rem; letter-spacing: 0.5px;">
                                                {{ $order->shipping->tracking_number }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <i class="fas fa-shipping-fast text-muted opacity-50 mb-2 fs-5"></i>
                                            <p class="small text-muted mb-0">Managed entirely by platform administrators.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Customer / Delivery Destination Card --}}
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold small text-muted text-uppercase">Delivery Area</span>
                        @if(!$order->user_id)
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill py-1 px-2" style="font-size: 0.62rem; font-weight: 700;">GUEST</span>
                        @endif
                    </div>
                    
                    <div class="card-body p-4 d-flex flex-column h-100">
                        {{-- Identity Context --}}
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 position-relative">
                                @if($order->user && $order->user->avatar)
                                    <img src="{{ $order->user->avatar }}" 
                                         alt="{{ $customerName }}" 
                                         class="rounded-circle shadow-sm object-fit-cover" 
                                         style="width: 50px; height: 50px; border: 2px solid #fff;">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                         style="width: 50px; height: 50px; font-size: 1.15rem; border: 2px solid #fff;">
                                        <span class="fw-bold">{{ $initial }}</span>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <div class="d-flex align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold text-dark me-2" style="font-size: 0.95rem;">{{ $customerName }}</h6>
                                    @if($order->user_id)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size: 0.62rem; padding: 0.25em 0.6em; font-weight: 700;">
                                            <i class="fas fa-user-check me-1"></i> MEMBER
                                        </span>
                                    @endif
                                </div>
                                <span class="text-muted small" style="font-size: 0.78rem;">Customer ID: #{{ $order->user_id ?? 'Guest' }}</span>
                            </div>
                        </div>

                        {{-- Safe Hidden Target Destination Area --}}
                        <div class="mb-4">
                            <span class="info-label">Destination Region:</span>
                            <div class="info-value p-3 bg-light-soft border rounded lh-base text-dark mt-1" style="font-size: 0.88rem;">
                                <strong>{{ $city }}{{ $city && $country ? ',' : '' }} {{ $country }}</strong>
                                <hr class="my-2 border-secondary-subtle opacity-25">
                                <span class="text-muted" style="font-size: 0.78rem;">
                                    <i class="fas fa-shield-alt me-1 text-primary opacity-75"></i> Drop-off handled exclusively by platform couriers.
                                </span>
                            </div>
                        </div>

                        {{-- Information Banner --}}
                        <div class="mt-auto no-print">
                            <span class="info-label">Logistics Support</span>
                            <div class="p-3 border rounded bg-light-soft text-muted d-flex align-items-start" style="font-size: 0.78rem; border-color: var(--border-color) !important;">
                                <i class="fas fa-info-circle me-2 text-secondary mt-1"></i>
                                <span>Package the order securely with your specific parts invoice and update status to <strong>Ready for Pickup</strong>.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-shop-dashboard>