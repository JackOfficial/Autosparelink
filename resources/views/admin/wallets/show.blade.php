@extends('admin.layouts.app')

@section('title', 'Wallet History: ' . $wallet->shop->shop_name)

@section('content')
<div class="container-fluid py-4">
    {{-- Header & Stats --}}
    <div class="row mb-4">
        <div class="col-12 col-lg-8 mb-3 mb-lg-0">
            <div class="media align-items-center">
                <a href="{{ route('admin.wallets.index') }}" class="btn btn-light rounded-circle mr-3 shadow-sm">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="media-body">
                    <h2 class="h4 font-weight-bold mb-0 text-dark">{{ $wallet->shop->shop_name }}</h2>
                    <p class="text-muted small mb-0">Wallet ID: #WL-{{ str_pad($wallet->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 text-lg-right">
            <div class="bg-white p-3 rounded-4 shadow-sm border-left border-primary d-inline-block text-left" style="border-left-width: 4px !important;">
                <span class="text-muted small d-block">Current Balance</span>
                <h3 class="font-weight-bold text-primary mb-0">{{ number_format($wallet->balance) }} RWF</h3>
            </div>
        </div>
    </div>

    {{-- Transaction Table --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="font-weight-bold mb-0">Transaction Audit Trail</h5>
            <button class="btn btn-sm btn-outline-primary rounded-pill px-3">
                <i class="fas fa-download mr-1"></i> Export CSV
            </button>
        </div>
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="pl-4">Date</th>
                        <th>Type</th>
                        <th>Reference</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th class="text-right pr-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wallet->transactions as $trx)
                        <tr>
                            <td class="pl-4">
                                <span class="d-block font-weight-bold text-dark small">{{ $trx->created_at->format('M d, Y') }}</span>
                                <span class="text-muted" style="font-size: 0.75rem;">{{ $trx->created_at->format('H:i A') }}</span>
                            </td>
                            <td>
                                {!! $trx->type_badge !!}
                            </td>
                            <td>
                                <span class="badge badge-light text-dark border font-weight-normal">
                                    {{ class_basename($trx->reference_type) }} #{{ $trx->reference_id }}
                                </span>
                            </td>
                            <td>
                                <p class="mb-0 small text-truncate" style="max-width: 250px;" title="{{ $trx->description }}">
                                    {{ $trx->description }}
                                </p>
                            </td>
                            <td>
                                <span class="font-weight-bold {{ $trx->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                    {{ $trx->type == 'credit' ? '+' : '-' }} {{ number_format($trx->amount) }}
                                </span>
                                @if($trx->service_fee > 0)
                                    <div class="text-muted" style="font-size: 0.7rem;">Fee: {{ number_format($trx->service_fee) }} RWF</div>
                                @endif
                            </td>
                            <td class="text-right pr-4">
                                <span class="badge badge-pill {{ $trx->status == 'completed' ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' }}">
                                    {{ ucfirst($trx->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-receipt fa-3x mb-3 text-light"></i>
                                <p class="text-muted">No transactions found for this shop.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* BS4 Compatibility & Soft UI */
    .bg-soft-success { background-color: rgba(40, 167, 69, 0.1) !important; }
    .bg-soft-warning { background-color: rgba(255, 193, 7, 0.1) !important; }
    .rounded-4 { border-radius: 1rem !important; }
    
    .table thead th { 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        font-weight: 700;
        color: #6c757d;
        border-bottom: none;
        vertical-align: middle;
    }

    .table td {
        vertical-align: middle;
        border-top: 1px solid #f8f9fa;
    }

    /* Badge tweaks for BS4 */
    .badge-pill {
        padding-right: 0.8em;
        padding-left: 0.8em;
        font-weight: 600;
    }
    
    .badge-light {
        background-color: #f8f9fa;
    }
</style>
@endsection