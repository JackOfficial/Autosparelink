<x-shop-dashboard>
    <x-slot:title>Order Management</x-slot:title>

    @push('styles')
    <style>
        /* Logic: Blinking for 'callback_requested' status */
        .blink_me { animation: blinker 0.8s cubic-bezier(.45,.05,.55,.95) infinite; }
        @keyframes blinker { 0% { opacity: 1; } 50% { opacity: 0.2; } 100% { opacity: 1; } }

        /* Custom UI Elements */
        .status-badge { font-size: 0.72rem; font-weight: 700; padding: 6px 12px; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.3px; }
        .user-badge { font-size: 0.6rem; font-weight: 800; text-transform: uppercase; padding: 2px 8px; border-radius: 50px; display: inline-flex; align-items: center; }
        
        /* Avatar Logic */
        .avatar-circle { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; background: #f8f9fa; border: 1px solid #e9ecef; overflow: hidden; }
        .avatar-circle img { width: 100%; height: 100%; object-fit: cover; }

        /* Soft UI Colors */
        .bg-warning-soft { background: #fff9db; color: #fcc419; border: 1px solid #ffe066; }
        .bg-success-soft { background: #ebfbee; color: #40c057; border: 1px solid #8ce99a; }
        .bg-primary-soft { background: #e7f5ff; color: #228be6; border: 1px solid #74c0fc; }
        .bg-danger-soft  { background: #fff5f5; color: #fa5252; border: 1px solid #ffa8a8; }
        .bg-info-soft    { background: #e3fafc; color: #15aabf; border: 1px solid #99e9f2; }

        [x-cloak] { display: none !important; }
        .table-hover tbody tr:hover { background-color: rgba(0,123,255, 0.01); }
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
                <p class="text-muted small mb-0">Track your shop's performance and customer requests.</p>
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
                        placeholder="Search ID, Name, or Email..."
                        style="border-radius: 12px; height: 45px;"
                    >
                </div>
            </div>
        </div>

        {{-- Quick Stats Row --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3" style="border-radius: 12px;">
                    <div class="text-muted small fw-bold text-uppercase">New Orders</div>
                    <div class="h4 fw-bold mb-0 text-primary">{{ $orders->where('status', 'pending')->count() }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm p-3" style="border-radius: 12px;">
                    <div class="text-muted small fw-bold text-uppercase">Urgent Calls</div>
                    <div class="h4 fw-bold mb-0 text-danger">{{ $orders->where('status', 'callback_requested')->count() }}</div>
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
                                    <span class="badge bg-light text-primary fw-bold p-2">#{{ $order->id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            @if($order->user && $order->user->avatar)
                                                <img src="{{ $order->user->avatar }}" alt="Avatar">
                                            @else
                                                <span class="text-muted small">{{ strtoupper(substr($customerName, 0, 1)) }}</span>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $customerName }}</span>
                                            <span class="text-muted small" style="font-size: 0.75rem;">{{ $customerEmail }}</span>
                                            
                                            @if($order->user_id)
                                                <span class="user-badge bg-info-soft mt-1">
                                                    <i class="fas fa-shield-alt me-1" style="font-size: 8px;"></i> Member
                                                </span>
                                            @else
                                                <span class="user-badge bg-light text-secondary border mt-1">
                                                    <i class="fas fa-shopping-cart me-1" style="font-size: 8px;"></i> Guest
                                                </span>
                                            @endif
                                        </div>
                                    </div>
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
                                <td class="fw-bold text-dark">
                                    {{ number_format($order->total_amount) }} <span class="small text-muted">RWF</span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('shop.orders.show', $order->id) }}" class="btn btn-sm btn-white border shadow-sm px-3" style="border-radius: 8px; background: white;">
                                        <i class="fas fa-eye text-primary me-1"></i> Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-muted">
                                    <i class="fas fa-box-open fa-3x mb-3 text-light"></i>
                                    <p>No orders found for your shop.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Showing page {{ $orders->currentPage() }}</span>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-shop-dashboard>