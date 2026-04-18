<x-shop-dashboard>
    <x-slot:title>Earnings & Payouts</x-slot:title>

    @push('styles')
    <style>
        .balance-card { border: none; border-radius: 12px; transition: transform 0.2s; }
        .balance-card:hover { transform: translateY(-3px); }
        .bg-soft-primary { background-color: #eef2ff; color: #4338ca; }
        .bg-soft-success { background-color: #f0fdf4; color: #15803d; }
        .bg-soft-warning { background-color: #fffbeb; color: #b45309; }
        .bg-soft-danger { background-color: #fef2f2; color: #991b1b; }
        .table-hover tbody tr:hover { background-color: #f8fafc; }
        .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
        .smaller { font-size: 0.75rem; }
    </style>
    @endpush

    <div class="container-fluid py-4">
        {{-- Financial Summary Row --}}
        <div class="row g-3 mb-4">
            {{-- Gross Revenue --}}
            <div class="col-md-3">
                <div class="card balance-card shadow-sm h-100 border-start border-primary border-4">
                    <div class="card-body">
                        <p class="text-muted small fw-bold mb-1 uppercase">Gross Revenue</p>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalGross) }} <small class="fs-6">RWF</small></h4>
                        <div class="mt-2 small text-muted">
                            <i class="ti ti-info-circle me-1"></i> Audited total sales
                        </div>
                    </div>
                </div>
            </div>

            {{-- Platform Fee - NOW DYNAMIC --}}
            <div class="col-md-3">
                <div class="card balance-card shadow-sm h-100 border-start border-danger border-4 bg-soft-danger">
                    <div class="card-body">
                        <p class="text-danger small fw-bold mb-1 uppercase">Platform Fee ({{ $commissionRate }}%)</p>
                        <h4 class="fw-bold mb-0 text-danger">- {{ number_format($totalCommission) }} <small class="fs-6 text-danger">RWF</small></h4>
                        <div class="mt-2 small text-danger-emphasis">Service commission</div>
                    </div>
                </div>
            </div>

            {{-- Available Balance --}}
            <div class="col-md-3">
                <div class="card balance-card shadow-sm h-100 border-start border-success border-4 bg-soft-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <p class="text-success small fw-bold mb-1 uppercase">Available Wallet</p>
                            @if($pendingPayouts > 0)
                                <span class="badge bg-warning text-dark rounded-pill smaller" title="Locked in pending requests">
                                    {{ number_format($pendingPayouts) }} Locked
                                </span>
                            @endif
                        </div>
                        <h4 class="fw-bold mb-0 text-success">{{ number_format($availableBalance) }} <small class="fs-6 text-success">RWF</small></h4>
                        <div class="mt-2 small text-success-emphasis">Ready to withdraw</div>
                    </div>
                </div>
            </div>

            {{-- Total Withdrawn --}}
            <div class="col-md-3">
                <div class="card balance-card shadow-sm h-100 bg-light">
                    <div class="card-body">
                        <p class="text-muted small fw-bold mb-1 uppercase">Total Withdrawn</p>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalWithdrawn) }} <small class="fs-6">RWF</small></h4>
                        <div class="mt-2 small text-muted">Successfully transferred</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Payout History --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white py-3 border-0">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="ti ti-history me-2 text-primary fs-5"></i>Withdrawal History
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="bg-light">
                                <tr class="smaller text-muted uppercase">
                                    <th class="ps-4">Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payouts as $payout)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $payout->created_at->format('d M Y') }}</div>
                                        <div class="smaller text-muted">{{ $payout->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="fw-bold text-dark">{{ number_format($payout->amount) }} RWF</td>
                                    <td>
                                        <div class="small fw-bold">{{ $payout->payout_method }}</div>
                                        <div class="text-muted smaller">{{ $payout->account_details }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'completed' => 'bg-soft-success',
                                                'pending'   => 'bg-soft-warning',
                                                'processing'=> 'bg-soft-primary',
                                                'rejected'  => 'bg-soft-danger'
                                            ];
                                            $currentClass = $statusClasses[$payout->status] ?? 'bg-light text-muted';
                                        @endphp
                                        <span class="badge {{ $currentClass }} text-dark py-2 px-3 rounded-pill uppercase smaller">
                                            {{ $payout->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <p class="text-muted mb-0">No withdrawal requests found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($payouts->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $payouts->links() }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- Withdrawal Form --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                    <div class="card-header bg-primary py-3">
                        <h6 class="mb-0 fw-bold text-white">New Withdrawal Request</h6>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success border-0 small mb-4 shadow-sm">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger border-0 small mb-4 shadow-sm">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('shop.payouts.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1 uppercase">Amount to Withdraw</label>
                                <div class="input-group border rounded-3 overflow-hidden">
                                    <input type="number" name="amount" class="form-control border-0 py-2" 
                                           placeholder="Min. 5,000" min="5000" required>
                                    <span class="input-group-text bg-light border-0 small fw-bold">RWF</span>
                                </div>
                                <div class="mt-1 d-flex justify-content-between">
                                    <small class="text-muted">Available: <strong>{{ number_format($availableBalance) }}</strong></small>
                                    <button type="button" class="btn btn-link p-0 smaller text-primary text-decoration-none" 
                                            onclick="document.getElementsByName('amount')[0].value = '{{ floor($availableBalance) }}'">Max</button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1 uppercase">Payout Method</label>
                                <select name="payout_method" class="form-select border rounded-3 py-2" required>
                                    <option value="MTN MoMo">MTN Mobile Money</option>
                                    <option value="Airtel Money">Airtel Money</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 uppercase">Account Details</label>
                                <input type="text" name="account_details" class="form-control border rounded-3 py-2" 
                                       placeholder="Phone number or Bank info" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm rounded-pill" 
                                    {{ $availableBalance < 5000 ? 'disabled' : '' }}>
                                <i class="ti ti-send me-1"></i> Request Payout
                            </button>

                            @if($availableBalance < 5000)
                                <div class="mt-3 p-2 bg-light rounded-3 text-center">
                                    <small class="text-danger fw-medium">
                                        Min. 5,000 RWF required to withdraw.
                                    </small>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-shop-dashboard>