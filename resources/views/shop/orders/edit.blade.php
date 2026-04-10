<x-shop-dashboard>
    <x-slot:title>Edit Order #{{ $order->id }}</x-slot:title>

    <div class="container-fluid py-4">
        {{-- Breadcrumb/Header --}}
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold text-dark mb-1">Update Status</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('shop.orders.index') }}" class="text-decoration-none">Orders</a></li>
                            <li class="breadcrumb-item active">Order #{{ $order->id }}</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('shop.orders.index') }}" class="btn btn-white border shadow-sm px-3" style="border-radius: 10px; background: white;">
                    <i class="fas fa-times me-2 text-danger"></i> Cancel
                </a>
            </div>
        </div>

        <div class="row g-4">
            {{-- Main Form --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-0">Order Configuration</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('shop.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Select New Status</label>
                                <div class="position-relative">
                                    <select name="status" class="form-select form-select-lg border-0 bg-light ps-3" style="border-radius: 12px; height: 55px; font-size: 1rem;">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3 p-3 bg-light-primary rounded-3 border-start border-primary border-4">
                                    <p class="mb-0 small text-muted">
                                        <i class="fas fa-info-circle me-2 text-primary"></i>
                                        Setting the status to <strong>Delivered</strong> or <strong>Cancelled</strong> will finalize this transaction record.
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm" style="border-radius: 12px; font-weight: 600;">
                                    <i class="fas fa-check-circle me-2"></i> Update Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Order Quick Info Sidebar --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-uppercase text-muted mb-3 small">Customer Details</h6>
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $order->user->name ?? $order->guest_name }}</div>
                                <div class="text-muted small">{{ $order->user->email ?? $order->guest_email }}</div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-uppercase text-muted mb-3 small">Financial Summary</h6>
                        <div class="p-3 bg-light rounded-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <span class="fw-bold">{{ number_format($order->total_amount) }} RWF</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Payment:</span>
                                <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($order->payment_status ?? 'Pending') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('shop.orders.show', $order->id) }}" class="btn btn-link text-primary fw-bold text-decoration-none">
                        <i class="fas fa-eye me-1"></i> View Order Details
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .bg-light-primary { background-color: #f0f7ff; }
        .bg-soft-primary { background-color: #e7f5ff; }
        .btn-white:hover { background-color: #f8f9fa !important; }
        .form-select-lg:focus { border: 1px solid #0d6efd !important; box-shadow: none; background-color: #fff; }
    </style>
    @endpush
</x-shop-dashboard>