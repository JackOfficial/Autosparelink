<x-shop-dashboard>
    <x-slot:title>Order Management</x-slot:title>

    @push('styles')
    <style>
        /* Logic: Blinking for 'callback_requested' status */
        .blink_me { animation: blinker 0.8s cubic-bezier(.45,.05,.55,.95) infinite; }
        @keyframes blinker { 0% { opacity: 1; } 50% { opacity: 0.2; } 100% { opacity: 1; } }

        .status-badge { font-size: 0.75rem; font-weight: 600; padding: 5px 12px; border-radius: 8px; }
        
        /* Soft UI Colors */
        .bg-warning-soft { background: #fff9db; color: #fcc419; border: 1px solid #ffe066; }
        .bg-success-soft { background: #ebfbee; color: #40c057; border: 1px solid #8ce99a; }
        .bg-primary-soft { background: #e7f5ff; color: #228be6; border: 1px solid #74c0fc; }
        .bg-danger-soft  { background: #fff5f5; color: #fa5252; border: 1px solid #ffa8a8; }

        [x-cloak] { display: none !important; }
    </style>
    @endpush

    <div class="container-fluid py-4" x-data="{ 
        search: '',
        showRow(rowText) {
            return rowText.toLowerCase().includes(this.search.toLowerCase())
        }
    }">
        {{-- Header Section --}}
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h1 class="h3 fw-bold text-dark mb-1">Order Management</h1>
                <p class="text-muted small mb-0">Manage and track your shop orders in real-time.</p>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end mt-3 mt-md-0">
                <div class="position-relative w-100" style="max-width: 350px;">
                    <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                        <i class="fas fa-search"></i>
                    </span>
                    <input 
                        type="text" 
                        x-model="search" 
                        class="form-control ps-5 border-0 shadow-sm" 
                        placeholder="Search by ID, Name, or Email..."
                        style="border-radius: 12px; height: 45px;"
                    >
                </div>
            </div>
        </div>

        {{-- Orders Table --}}
        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 text-muted small fw-bold text-uppercase">Order ID</th>
                            <th class="py-3 border-0 text-muted small fw-bold text-uppercase">Customer</th>
                            <th class="py-3 border-0 text-muted small fw-bold text-uppercase">Status</th>
                            <th class="py-3 border-0 text-muted small fw-bold text-uppercase text-center">Date</th>
                            <th class="py-3 border-0 text-muted small fw-bold text-uppercase">Amount</th>
                            <th class="pe-4 py-3 border-0 text-end text-muted small fw-bold text-uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $customerName = $order->user->name ?? $order->guest_name ?? 'Guest';
                                $customerEmail = $order->user->email ?? $order->guest_email ?? 'N/A';
                                $searchText = "#{$order->id} {$customerName} {$customerEmail}";
                            @endphp
                            <tr x-show="showRow('{{ addslashes($searchText) }}')" x-transition>
                                <td class="ps-4">
                                    <span class="fw-bold text-primary">#{{ $order->id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $customerName }}</div>
                                    <div class="text-muted small">{{ $customerEmail }}</div>
                                </td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-warning-soft',
                                            'delivered' => 'bg-success-soft',
                                            'processing' => 'bg-primary-soft',
                                            'cancelled' => 'bg-danger-soft',
                                        ];
                                        $class = $statusClasses[$order->status] ?? 'bg-light';
                                    @endphp

                                    @if($order->status === 'callback_requested')
                                        <span class="status-badge bg-danger text-white blink_me">
                                            <i class="fas fa-phone-alt me-1"></i> Urgent Call
                                        </span>
                                    @else
                                        <span class="status-badge {{ $class }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="text-dark small fw-bold">{{ $order->created_at->format('d M, Y') }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">{{ $order->created_at->format('H:i') }}</div>
                                </td>
                                <td class="fw-bold">
                                    {{ number_format($order->total_amount) }} <span class="small text-muted">RWF</span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('shop.orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm px-3" style="border-radius: 8px;">
                                        Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-muted">
                                    No orders found for your shop.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Empty Search State Logic --}}
            <div x-show="search.length > 0 && document.querySelectorAll('tbody tr[style*=\'display: none\']').length === {{ count($orders) }}" class="p-5 text-center bg-white" x-cloak>
                <i class="fas fa-search fa-2x text-light mb-3"></i>
                <p class="text-muted">No orders match your current search on this page.</p>
            </div>

            <div class="card-footer bg-white border-0 py-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-shop-dashboard>