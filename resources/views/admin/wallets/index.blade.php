@extends('admin.layouts.app')

@section('title', 'Manage Shop Wallets')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Financial Overview Cards --}}
    <div class="row mb-5">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-primary text-white">
                <div class="media align-items-center">
                    <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; background-color: rgba(255,255,255,0.2) !important;">
                        <i class="fas fa-wallet fa-lg"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="mb-0 opacity-75 small">Total System Balance</h6>
                        <h3 class="font-weight-bold mb-0 text-white">{{ number_format($wallets->sum('balance')) }} RWF</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="media align-items-center">
                    <div class="bg-soft-warning rounded-circle d-flex align-items-center justify-content-center mr-3 text-warning" style="width: 50px; height: 50px;">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="text-muted mb-0 small">Total Pending Payouts</h6>
                        <h3 class="font-weight-bold mb-0 text-dark">{{ number_format($wallets->sum('pending_balance')) }} RWF</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-center justify-content-md-end">
            <a href="{{ route('admin.wallets.create') }}" class="btn btn-dark rounded-pill px-4 py-2 font-weight-bold shadow-sm">
                <i class="fas fa-plus-circle mr-2"></i> Manual Adjustment
            </a>
        </div>
    </div>

    {{-- Shop Wallets Table --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="font-weight-bold mb-0">Shop Wallet Directory</h5>
                </div>
                <div class="col-auto">
                    <form action="{{ route('admin.wallets.index') }}" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control form-control-sm border-0 bg-light rounded-pill px-3" placeholder="Search shop name..." style="width: 200px;">
                    </form>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="pl-4">Shop Details</th>
                        <th>Available Balance</th>
                        <th>Pending</th>
                        <th>Withdrawn</th>
                        <th>Last Activity</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wallets as $wallet)
                        <tr>
                            <td class="pl-4">
                                <div class="media align-items-center">
                                    <div class="mr-3">
                                        @if($wallet->shop->logo)
                                            <img src="{{ asset('storage/' . $wallet->shop->logo) }}" class="rounded border" width="40" height="40" alt="Logo">
                                        @else
                                            <div class="rounded bg-soft-primary text-primary d-flex align-items-center justify-content-center font-weight-bold" style="width: 40px; height: 40px; font-size: 14px;">
                                                {{ strtoupper(substr($wallet->shop->shop_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="media-body">
                                        <h6 class="font-weight-bold text-dark mb-0">{{ $wallet->shop->shop_name }}</h6>
                                        <span class="text-muted small">TIN: {{ $wallet->shop->tin_number ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="font-weight-bold text-primary">{{ number_format($wallet->balance) }} RWF</span>
                            </td>
                            <td>
                                <span class="text-warning small font-weight-bold">{{ number_format($wallet->pending_balance) }}</span>
                            </td>
                            <td>
                                <span class="text-muted small">{{ number_format($wallet->withdrawn_balance) }}</span>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    {{ $wallet->last_transaction_at ? $wallet->last_transaction_at->diffForHumans() : 'No activity' }}
                                </span>
                            </td>
                            <td class="text-right pr-4">
                                <a href="{{ route('admin.wallets.show', $wallet->id) }}" class="btn btn-light btn-sm rounded-pill px-3 border shadow-sm">
                                    <i class="fas fa-history mr-1 text-primary"></i> View History
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
                <div class="d-flex justify-content-center">
                    {{ $wallets->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    /* Soft UI Custom Styling for BS4 */
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
        vertical-align: middle;
    }
    .table td { 
        border-bottom: 1px solid #f8f9fa; 
        vertical-align: middle;
    }
    .media-body h3 { line-height: 1.2; }
</style>
@endsection