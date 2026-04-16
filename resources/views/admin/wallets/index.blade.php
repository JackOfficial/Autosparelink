@extends('admin.layouts.app')

@section('title', 'Manage Shop Wallets')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Financial Overview Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-primary text-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                        <i class="fas fa-wallet fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 opacity-75 small">Total System Balance</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($wallets->sum('balance')) }} RWF</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-soft-warning rounded-circle p-3 me-3 text-warning">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small">Total Pending Payouts</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ number_format($wallets->sum('pending_balance')) }} RWF</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end d-flex align-items-center justify-content-md-end">
            <a href="{{ route('admin.wallets.create') }}" class="btn btn-dark rounded-pill px-4 py-2 fw-bold shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Manual Adjustment
            </a>
        </div>
    </div>

    {{-- Shop Wallets Table --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="fw-bold mb-0">Shop Wallet Directory</h5>
                </div>
                <div class="col-auto">
                    <form action="{{ route('admin.wallets.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control form-control-sm border-light bg-light rounded-pill px-3" placeholder="Search shop name..." style="width: 200px;">
                    </form>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Shop Details</th>
                        <th>Available Balance</th>
                        <th>Pending</th>
                        <th>Withdrawn</th>
                        <th>Last Activity</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wallets as $wallet)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($wallet->shop->logo)
                                            <img src="{{ asset('storage/' . $wallet->shop->logo) }}" class="rounded-3 border" width="40" height="40" alt="Logo">
                                        @else
                                            <div class="rounded-3 bg-soft-primary text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 14px;">
                                                {{ strtoupper(substr($wallet->shop->shop_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ $wallet->shop->shop_name }}</h6>
                                        <span class="text-muted small">TIN: {{ $wallet->shop->tin_number ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">{{ number_format($wallet->balance) }} RWF</span>
                            </td>
                            <td>
                                <span class="text-warning small fw-semibold">{{ number_format($wallet->pending_balance) }}</span>
                            </td>
                            <td>
                                <span class="text-muted small">{{ number_format($wallet->withdrawn_balance) }}</span>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    {{ $wallet->last_transaction_at ? $wallet->last_transaction_at->diffForHumans() : 'No activity' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.wallets.show', $wallet->id) }}" class="btn btn-light btn-sm rounded-pill px-3 border">
                                    <i class="fas fa-history me-1 text-primary"></i> View History
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-store-slash fa-3x mb-3 opacity-25"></i>
                                <p>No shop wallets found in the system.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($wallets->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $wallets->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.08) !important; }
    .bg-soft-warning { background-color: rgba(255, 193, 7, 0.1) !important; }
    .rounded-4 { border-radius: 1rem !important; }
    .table thead th { 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        font-weight: 700;
        color: #6c757d;
        border-bottom: none;
    }
    .table td { border-bottom: 1px solid #f8f9fa; }
</style>
@endsection