@extends('admin.layouts.app')

@section('title', 'Manage Shop Wallets')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Financial Overview Cards (Audited Totals for the Current Page) --}}
    <div class="row mb-5">
        {{-- Card: Total Commission (Your Revenue) --}}
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-success text-white">
                <div class="media align-items-center">
                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; background-color: rgba(255,255,255,0.2) !important;">
                        <i class="fas fa-hand-holding-usd fa-lg"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="mb-0 opacity-75 small text-uppercase font-weight-bold">Total Platform Commission</h6>
                        <h3 class="font-weight-bold mb-0 text-white">{{ number_format($wallets->sum('audited_commission')) }} RWF</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Shop Net Revenue --}}
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-primary text-white">
                <div class="media align-items-center">
                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; background-color: rgba(255,255,255,0.2) !important;">
                        <i class="fas fa-chart-line fa-lg"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="mb-0 opacity-75 small text-uppercase font-weight-bold">Total Shop Earnings (Net)</h6>
                        <h3 class="font-weight-bold mb-0 text-white">{{ number_format($wallets->sum('audited_net')) }} RWF</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Locked Funds --}}
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-left border-warning" style="border-left-width: 5px !important;">
                <div class="media align-items-center">
                    <div class="bg-soft-warning rounded-circle d-flex align-items-center justify-content-center mr-3 text-warning" style="width: 50px; height: 50px;">
                        <i class="fas fa-lock fa-lg"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="text-muted mb-0 small text-uppercase font-weight-bold">Total Locked Funds</h6>
                        <h3 class="font-weight-bold mb-0 text-dark">{{ number_format($wallets->sum('audited_locked')) }} RWF</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-end">
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
                    <h5 class="font-weight-bold mb-0">Audited Wallet Directory</h5>
                    <small class="text-muted">Calculated based on completed spare parts sales and payouts.</small>
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
                        <th>Gross Sales</th>
                        <th class="text-success">Our Commission</th> {{-- NEW COLUMN --}}
                        <th>Shop Net</th>
                        <th>Payable Balance</th>
                        <th>Locked/Pending</th>
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
                                            <img src="{{ asset('storage/' . $wallet->shop->logo) }}" class="rounded border shadow-sm" width="45" height="45" style="object-fit: cover;">
                                        @else
                                            <div class="rounded bg-soft-primary text-primary d-flex align-items-center justify-content-center font-weight-bold" style="width: 45px; height: 45px; font-size: 16px;">
                                                {{ strtoupper(substr($wallet->shop->shop_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="media-body">
                                        <h6 class="font-weight-bold text-dark mb-0">{{ $wallet->shop->shop_name }}</h6>
                                        <span class="text-muted extra-small">Rate: {{ $wallet->commission_rate }}%</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark small">{{ number_format($wallet->audited_gross) }}</div>
                                <div class="extra-small text-muted">Total Sales</div>
                            </td>
                            {{-- PLATFORM COMMISSION CELL --}}
                            <td>
                                <div class="font-weight-bold text-success small">+ {{ number_format($wallet->audited_commission) }}</div>
                                <div class="extra-small text-muted">Platform Fee</div>
                            </td>
                            <td>
                                <div class="font-weight-bold text-info small">{{ number_format($wallet->audited_net) }}</div>
                                <div class="extra-small text-muted">Shop Earnings</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="font-weight-bold text-dark h6 mb-0 mr-2">{{ number_format($wallet->audited_balance) }} <span class="small">RWF</span></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-pill badge-soft-warning px-3 py-1 font-weight-bold">
                                    {{ number_format($wallet->audited_locked) }} RWF
                                </span>
                            </td>
                            <td class="text-right pr-4">
                                <a href="{{ route('admin.shops.show', $wallet->shop->id) }}" class="btn btn-white btn-sm rounded-pill px-3 border shadow-sm mr-1" title="Shop Profile">
                                    <i class="fas fa-store text-muted"></i>
                                </a>
                                <a href="{{ route('admin.wallets.show', $wallet->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-history mr-1"></i> Ledger
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-file-invoice-dollar fa-3x mb-3 opacity-25"></i>
                                <p>No shop wallet data found matching current audit rules.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Pagination --}}
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
    /* Same styles as before, just ensured text-success is bright for revenue */
    .bg-soft-primary { background-color: rgba(0, 123, 255, 0.08) !important; }
    .bg-soft-warning { background-color: rgba(255, 193, 7, 0.1) !important; }
    .badge-soft-warning { background-color: #fff4e6; color: #d9480f; }
    .rounded-4 { border-radius: 0.85rem !important; }
    .extra-small { font-size: 0.7rem; }
    .table thead th { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 800; color: #8898aa; background-color: #f8f9fe; border-top: none; border-bottom: 1px solid #e9ecef; }
    .table td { vertical-align: middle; padding: 1.1rem 0.75rem; }
    .btn-white { background-color: #fff; color: #212529; }
</style>
@endsection