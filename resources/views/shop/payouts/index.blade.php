<x-shop-dashboard>
    <x-slot:title>Earnings & Payouts</x-slot:title>

    @push('styles')
    <style>
        .balance-card { border: none; border-radius: 12px; }
        .bg-soft-primary { background-color: #eef2ff; color: #4338ca; }
        .bg-soft-success { background-color: #f0fdf4; color: #15803d; }
        .bg-soft-warning { background-color: #fffbeb; color: #b45309; }
        .table-hover tbody tr:hover { background-color: #fbfbfb; }
    </style>
    @endpush

    <div class="container-fluid py-4">
        {{-- Financial Summary Row --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card balance-card shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted small fw-bold mb-1 uppercase">Gross Revenue</p>
                        <h4 class="fw-bold mb-0">{{ number_format($totalGross) }} <small class="fs-6">RWF</small></h4>
                        <div class="mt-2 small text-muted">
                            <i class="fas fa-info-circle me-1"></i> Before commission
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card balance-card shadow-sm h-100 border-start border-danger border-4">
                    <div class="card-body">
                        <p class="text-danger small fw-bold mb-1 uppercase">Platform Fee (10%)</p>
                        <h4 class="fw-bold mb-0 text-danger">- {{ number_format($totalCommission) }} <small class="fs-6">RWF</small></h4>
                        <div class="mt-2 small text-muted">Service commission</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card balance-card shadow-sm h-100 border-start border-success border-4 bg-soft-success">
                    <div class="card-body">
                        <p class="text-success small fw-bold mb-1 uppercase">Available Balance</p>
                        <h4 class="fw-bold mb-0">{{ number_format($availableBalance) }} <small class="fs-6">RWF</small></h4>
                        <div class="mt-2 small text-success-emphasis">Ready to withdraw</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card balance-card shadow-sm h-100 bg-light">
                    <div class="card-body">
                        <p class="text-muted small fw-bold mb-1 uppercase">Total Withdrawn</p>
                        <h4 class="fw-bold mb-0">{{ number_format($totalWithdrawn) }} <small class="fs-6">RWF</small></h4>
                        <div class="mt-2 small text-muted">Sent to your account</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Payout History --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-white py-3 border-0">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-history me-2 text-primary"></i>Withdrawal History</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 small text-muted">DATE</th>
                                    <th class="small text-muted">AMOUNT</th>
                                    <th class="small text-muted">METHOD</th>
                                    <th class="small text-muted">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payouts as $payout)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $payout->created_at->format('d M Y') }}</div>
                                        <div class="small text-muted">{{ $payout->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="fw-bold text-dark">{{ number_format($payout->amount) }} RWF</td>
                                    <td>
                                        <div class="small fw-bold">{{ $payout->payout_method }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">{{ $payout->account_details }}</div>
                                    </td>
                                    <td>
                                        @if($payout->status == 'completed')
                                            <span class="badge bg-soft-success py-2 px-3">COMPLETED</span>
                                        @elseif($payout->status == 'pending')
                                            <span class="badge bg-soft-warning py-2 px-3">PENDING</span>
                                        @else
                                            <span class="badge bg-light text-muted py-2 px-3 border">{{ strtoupper($payout->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-wallet fa-3x mb-3 opacity-25"></i>
                                        <p>No payout requests found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $payouts->links() }}
                    </div>
                </div>
            </div>

            {{-- Withdrawal Form --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-primary py-3">
                        <h6 class="mb-0 fw-bold text-white">New Withdrawal Request</h6>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success border-0 small mb-4">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger border-0 small mb-4">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('shop.payouts.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Amount to Withdraw</label>
                                <div class="input-group">
                                    <input type="number" name="amount" class="form-control" placeholder="Minimum 5,000" min="5000" required>
                                    <span class="input-group-text bg-white small fw-bold">RWF</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Payout Method</label>
                                <select name="payout_method" class="form-select" required>
                                    <option value="MTN MoMo">MTN Mobile Money</option>
                                    <option value="Airtel Money">Airtel Money</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1">Account / Phone Details</label>
                                <input type="text" name="account_details" class="form-control" placeholder="078... or Bank Acc No" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm" 
                                {{ $availableBalance < 5000 ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane me-2"></i> Submit Request
                            </button>

                            @if($availableBalance < 5000)
                                <div class="text-center mt-3">
                                    <small class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Min. balance 5,000 RWF required.</small>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-shop-dashboard>