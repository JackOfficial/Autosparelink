<x-shop-dashboard>
    <x-slot:title>Update Order #{{ $order->id }}</x-slot:title>

    <div class="container-fluid py-4">
        {{-- Header & Back Button --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Update Order Status</h1>
                <p class="text-muted small">Modifying status for Order <span class="text-primary fw-bold">#{{ $order->id }}</span></p>
            </div>
            <a href="{{ route('shop.orders.index') }}" class="btn btn-light border shadow-sm px-3" style="border-radius: 10px;">
                <i class="fas fa-arrow-left me-2"></i> Back to List
            </a>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <form action="{{ route('shop.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Current Status</label>
                                <select name="status" class="form-select form-select-lg border-0 bg-light" style="border-radius: 12px; font-size: 1rem;">
                                    @foreach(['pending','processing','shipped','delivered','cancelled', 'callback_requested'] as $status)
                                        <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text mt-2 small">
                                    <i class="fas fa-info-circle me-1"></i> Changing the status will notify the customer via email.
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm" style="border-radius: 12px; font-weight: 600;">
                                    <i class="fas fa-save me-2"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Summary Sidebar --}}
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm bg-primary text-white" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Customer:</span>
                            <span class="fw-bold">{{ $order->user->name ?? $order->guest_name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Amount:</span>
                            <span class="fw-bold text-white">{{ number_format($order->total_amount) }} RWF</span>
                        </div>
                        <hr class="bg-white opacity-25">
                        <div class="text-center">
                            <a href="{{ route('shop.orders.show', $order->id) }}" class="text-white text-decoration-none small fw-bold">
                                View Full Details <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .form-select:focus {
            box-shadow: none;
            border: 1px solid #0d6efd !important;
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
        }
    </style>
    @endpush
</x-shop-dashboard>