<x-shop-dashboard>
    <x-slot:title>Edit Order #{{ $order->id }}</x-slot:title>

    <div class="container-fluid py-4">
        {{-- Header & Quick Actions --}}
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item small"><a href="{{ route('shop.orders.index') }}">Orders</a></li>
                        <li class="breadcrumb-item small active">Update Status</li>
                    </ol>
                </nav>
                <h1 class="h3 fw-bold text-dark mb-0">Order #{{ $order->id }}</h1>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end gap-2 mt-3 mt-md-0">
                <a href="{{ route('shop.orders.show', $order->id) }}" class="btn btn-white border shadow-sm px-3" style="border-radius: 10px; background: white;">
                    <i class="fas fa-eye me-2 text-primary"></i> View Details
                </a>
                <a href="{{ route('shop.orders.index') }}" class="btn btn-light border px-3" style="border-radius: 10px;">
                    Back
                </a>
            </div>
        </div>

        <div class="row g-4">
            {{-- Left Column: The Update Form --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Modify Order State</h5>
                        
                        <form action="{{ route('shop.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Status Workflow</label>
                                <select name="status" class="form-select form-select-lg border-0 bg-light px-3" style="border-radius: 12px; height: 55px; font-size: 1rem;">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text mt-2 small text-muted">
                                    <i class="fas fa-info-circle me-1"></i> 
                                    Update the status to move this order through the fulfillment pipeline.
                                </div>
                            </div>

                            {{-- Optional: Add a text area for internal notes if you have the column in your DB --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Internal Note (Optional)</label>
                                <textarea name="note" class="form-control border-0 bg-light" rows="3" style="border-radius: 12px;" placeholder="e.g. Customer requested late delivery..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm" style="border-radius: 12px; font-weight: 600;">
                                <i class="fas fa-sync-alt me-2"></i> Confirm Status Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right Column: Information Snapshot --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-uppercase text-muted mb-3 small">Customer Info</h6>
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-weight: bold;">
                                {{ strtoupper(substr($order->user->name ?? $order->guest_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $order->user->name ?? $order->guest_name }}</div>
                                <div class="text-muted small">{{ $order->user->email ?? $order->guest_email }}</div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-uppercase text-muted mb-2 small">Payment Details</h6>
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                            <div>
                                <div class="small text-muted">Total Bill</div>
                                <div class="fw-bold text-dark">{{ number_format($order->total_amount) }} RWF</div>
                            </div>
                            <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }} rounded-pill px-3">
                                {{ ucfirst($order->payment_status ?? 'Unpaid') }}
                            </span>
                        </div>
                        
                        <div class="mt-4">
                            <h6 class="fw-bold text-uppercase text-muted mb-2 small">Timeline</h6>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2"><i class="far fa-calendar-alt me-2 text-primary"></i> Placed on: {{ $order->created_at->format('M d, Y H:i') }}</li>
                                <li><i class="far fa-clock me-2 text-primary"></i> Last Update: {{ $order->updated_at->diffForHumans() }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .bg-soft-primary { background-color: #eef2ff; color: #4338ca; }
        .btn-white:hover { background-color: #f8f9fa !important; }
        .form-select-lg:focus, .form-control:focus { 
            background-color: #fff; 
            border: 1px solid #0d6efd !important; 
            box-shadow: none; 
        }
        .breadcrumb-item + .breadcrumb-item::before { content: "›"; font-size: 1.2rem; line-height: 1; }
    </ol>
    @endpush
</x-shop-dashboard>