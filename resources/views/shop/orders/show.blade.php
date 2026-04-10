<x-shop-dashboard>
    <x-slot:title>Order #{{ $order->id }} - Details</x-slot:title>

    @push('styles')
    <style>
        /* Logic: Blinking for Call Requests */
        .blink_me { animation: blinker 0.8s cubic-bezier(.45,.05,.55,.95) infinite; }
        @keyframes blinker { 0% { opacity: 1; } 50% { opacity: 0.2; } 100% { opacity: 1; } }

        .card { border-radius: 15px; border: none; }
        .info-label { font-size: 0.7rem; text-transform: uppercase; color: #95aac9; font-weight: 700; margin-bottom: 2px; display: block; }
        .info-value { font-size: 0.95rem; color: #12263f; font-weight: 500; }
        
        /* Status Control UI */
        .status-control-card { background: linear-gradient(135deg, #0d6efd, #0b5ed7); color: white; border-radius: 15px; }
        .status-select { background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; border-radius: 10px; font-weight: bold; }
        .status-select option { color: #333; }

        .bg-light-soft { background-color: #f8f9fa; }
        
        @media print {
            .btn, .status-control-card, nav, .breadcrumb { display: none !important; }
            .card { border: 1px solid #ddd !important; shadow: none !important; }
            .container-fluid { padding: 0 !important; }
        }
    </style>
    @endpush

    @php
        // PRIMARY LOGIC: Fallbacks for Guest vs Member
        $customerName = $order->guest_name ?? $order->user->name ?? 'Guest Customer';
        $customerEmail = $order->guest_email ?? $order->user->email ?? 'No email provided';
        $customerPhone = $order->guest_phone ?? $order->address->phone ?? 'N/A';
        
        $street = $order->guest_shipping_address ?? $order->address->street_address ?? 'No address provided';
        $city = $order->city ?? $order->address->city ?? '';
        $country = $order->country ?? $order->address->country ?? '';
        $initial = strtoupper(substr($customerName, 0, 1));
    @endphp

    <div class="container-fluid py-4">
        {{-- Header Section --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('shop.orders.index') }}" class="text-decoration-none">Orders</a></li>
                        <li class="breadcrumb-item active">#{{ $order->id }}</li>
                    </ol>
                </nav>
                <h2 class="h3 mb-0 fw-bold">Order Details</h2>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2">
                <button onclick="window.print()" class="btn btn-white border shadow-sm">
                    <i class="fas fa-print me-1 text-muted"></i> Print Invoice
                </button>
                <a href="{{ route('shop.orders.edit', $order->id) }}" class="btn btn-primary shadow-sm px-4">
                    <i class="fas fa-edit me-1"></i> Edit Status
                </a>
            </div>
        </div>

        {{-- Urgent Callback Alert --}}
        @if($order->status === 'callback_requested')
            <div class="alert alert-danger blink_me d-flex align-items-center mb-4 shadow-sm border-0 p-3" style="border-radius: 12px;">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3 text-danger" style="width: 40px; height: 40px;">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Urgent Call Requested!</h6>
                    <span class="small">Customer is waiting for a response at <strong>{{ $customerPhone }}</strong>.</span>
                </div>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                {{-- Items Table --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom">
        <h6 class="mb-0 fw-bold text-uppercase small text-muted">
            <i class="fas fa-shopping-basket me-2 text-primary"></i> Cart Items
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light-soft">
                    <tr>
                        <th class="ps-4 border-0 small text-muted">Product</th>
                        <th class="text-center border-0 small text-muted">Qty</th>
                        <th class="text-end border-0 small text-muted">Unit Price</th>
                        <th class="text-end pe-4 border-0 small text-muted">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    {{-- Part Image Logic --}}
                                    <div class="me-3">
                                        @if($item->part && $item->part->image)
                                            <img src="{{ asset('storage/' . $item->part->image) }}" 
                                                 alt="{{ $item->part->part_name }}" 
                                                 class="rounded border shadow-sm object-fit-cover" 
                                                 style="width: 50px; height: 50px;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center border shadow-sm" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-tools text-muted opacity-50"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        <div class="fw-bold text-dark">{{ $item->part->part_name ?? 'Product Deleted' }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            <span class="badge bg-light text-dark border-0 fw-normal">SKU: {{ $item->part->sku ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-bold">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->unit_price) }} <span class="small text-muted">RWF</span></td>
                            <td class="text-end pe-4 fw-bold text-dark">{{ number_format($item->quantity * $item->unit_price) }} RWF</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-light-soft">
                    <tr>
                        <td colspan="3" class="text-end fw-bold py-3 border-0">Total Amount:</td>
                        <td class="text-end pe-4 fw-bold text-primary h5 py-3 border-0">{{ number_format($order->total_amount) }} RWF</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

                <div class="row g-4">
                    {{-- Payment Status --}}
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-uppercase small text-muted mb-3">Payment Info</h6>
                                @if($order->payment)
                                    <div class="mb-3">
                                        <span class="info-label">Transaction ID</span>
                                        <span class="info-value text-break">{{ $order->payment->transaction_id }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                        <span class="small fw-bold">{{ strtoupper($order->payment->method) }}</span>
                                        <span class="badge {{ $order->payment->status === 'completed' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($order->payment->status) }}
                                        </span>
                                    </div>
                                @else
                                    <div class="text-center py-2">
                                        <i class="fas fa-hourglass-half text-warning mb-2"></i>
                                        <p class="small text-muted mb-0">No payment record found.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Shipping Status --}}
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-uppercase small text-muted mb-3">Logistics</h6>
                                @if($order->shipping)
                                    <div class="mb-3">
                                        <span class="info-label">Carrier</span>
                                        <span class="info-value">{{ $order->shipping->carrier }}</span>
                                    </div>
                                    <div class="p-2 border rounded border-dashed text-center">
                                        <span class="small text-muted me-2">Tracking:</span>
                                        <span class="fw-bold text-primary">{{ $order->shipping->tracking_number }}</span>
                                    </div>
                                @else
                                    <div class="text-center py-2">
                                        <i class="fas fa-truck-loading text-muted mb-2"></i>
                                        <p class="small text-muted mb-0">Shipping not initialized.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Quick Status Toggle --}}
                <div class="card shadow-sm status-control-card mb-4 border-0">
                    <div class="card-body text-center p-4">
                        <h6 class="text-uppercase mb-3 fw-bold opacity-75 small">Change Status</h6>
                        <form action="{{ route('shop.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-select form-select-lg status-select shadow-none mb-0" onchange="this.form.submit()">
                                @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'callback_requested'] as $stat)
                                    <option value="{{ $stat }}" {{ $order->status == $stat ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $stat)) }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                {{-- Customer Profile Card --}}
               <div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <span class="fw-bold small text-muted text-uppercase">Customer Profile</span>
        @if(!$order->user_id)
            <span class="badge bg-secondary rounded-pill" style="font-size: 0.6rem;">GUEST</span>
        @endif
    </div>
    
    <div class="card-body p-4">
        {{-- Avatar & Identity Section --}}
        <div class="d-flex align-items-center mb-4">
            <div class="me-3 position-relative">
                @if($order->user && $order->user->profile_photo_path)
                    <img src="{{ asset('storage/' . $order->user->profile_photo_path) }}" 
                         alt="{{ $customerName }}" 
                         class="rounded-circle shadow-sm object-fit-cover" 
                         style="width: 55px; height: 55px; border: 2px solid #fff;">
                @else
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                         style="width: 55px; height: 55px; font-size: 1.2rem; border: 2px solid #fff;">
                        <span class="fw-bold">{{ $initial }}</span>
                    </div>
                @endif
                
                <span class="position-absolute bottom-0 end-0 p-1 {{ $order->user_id ? 'bg-success' : 'bg-secondary' }} border border-white rounded-circle" 
                      style="width: 12px; height: 12px;" 
                      title="{{ $order->user_id ? 'Registered Member' : 'Guest' }}">
                </span>
            </div>

            <div>
                <div class="d-flex align-items-center">
                    <h6 class="mb-0 fw-bold text-dark me-2">{{ $customerName }}</h6>
                    @if($order->user_id)
                        <span class="badge bg-soft-primary text-primary border border-primary-subtle rounded-pill" style="font-size: 0.65rem; padding: 0.25em 0.6em;">
                            <i class="fas fa-user-check me-1"></i> MEMBER
                        </span>
                    @endif
                </div>
                <span class="text-muted small">{{ $customerEmail }}</span>
            </div>
        </div>

        {{-- Shipping Info Section --}}
        <div class="mb-3">
            <span class="info-label">Shipping To:</span>
            <div class="info-value small lh-base">
                {{ $street }}<br>
                {{ $city }}{{ $city && $country ? ',' : '' }} {{ $country }}
            </div>
        </div>

        {{-- Contact Section --}}
        <div class="mt-4">
            <span class="info-label">Phone Connection</span>
            @if($customerPhone !== 'N/A')
                <a href="tel:{{ $customerPhone }}" class="btn btn-outline-primary w-100 btn-sm mt-1" style="border-radius: 8px;">
                    <i class="fas fa-phone-alt me-2"></i> {{ $customerPhone }}
                </a>
            @else
                <div class="text-muted small italic mt-1">
                    <i class="fas fa-info-circle me-1"></i> No contact provided
                </div>
            @endif
        </div>
    </div>
</div>
            </div>
        </div>
    </div>
</x-shop-dashboard>