@extends('admin.layouts.app')

@section('title', 'Audit Payout #' . str_pad($payout->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="container-fluid pt-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0 small">
            <li class="breadcrumb-item"><a href="{{ route('admin.payouts.index') }}">Payouts</a></li>
            <li class="breadcrumb-item active">Audit Request</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-primary shadow-sm border-0">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-search-dollar mr-2 text-primary"></i>
                        Payout Audit: #PO-{{ str_pad($payout->id, 5, '0', STR_PAD_LEFT) }}
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $payout->status == 'completed' ? 'success' : 'warning' }} px-3 py-2 text-uppercase">
                            {{ $payout->status }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-6">
    <label class="text-muted small text-uppercase font-weight-bold">Vendor Information</label>
    <div class="d-flex align-items-center mt-2">
        <div class="mr-3 shadow-sm border overflow-hidden bg-white" 
             style="width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            @if($payout->shop->logo)
                <img src="{{ asset('storage/' . $payout->shop->logo) }}" 
                     alt="{{ $payout->shop->shop_name }}" 
                     style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <div class="bg-soft-primary text-primary font-weight-bold h4 mb-0">
                    {{ strtoupper(substr($payout->shop->shop_name, 0, 1)) }}
                </div>
            @endif
        </div>

        <div>
            <h5 class="mb-0 font-weight-bold text-dark">{{ $payout->shop->shop_name }}</h5>
            <p class="text-muted mb-0 small">
                <i class="fas fa-envelope mr-1"></i>{{ $payout->shop->user->email }}
            </p>
            <span class="badge badge-light border text-xs mt-1">
                Vendor ID: #{{ str_pad($payout->shop->id, 4, '0', STR_PAD_LEFT) }}
            </span>
        </div>
    </div>
</div>
                        <div class="col-sm-6 text-sm-right">
                            <label class="text-muted small text-uppercase">Request Date</label>
                            <p class="font-weight-bold mb-0">{{ $payout->created_at->format('d M Y, H:i A') }}</p>
                            <label class="text-muted small text-uppercase mt-2">Payment Method</label>
                            <p class="mb-0 font-weight-bold"><i class="fas fa-mobile-alt mr-1"></i> {{ $payout->payment_method ?? 'Mobile Money' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="table-responsive">
                        <table class="table table-borderless bg-light rounded">
                            <thead>
                                <tr class="text-muted small text-uppercase">
                                    <th>Description</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold text-dark">Requested Withdrawal Amount</td>
                                    <td class="text-right text-danger font-weight-bold h5">
                                        - {{ number_format($payout->amount) }} RWF
                                    </td>
                                </tr>
                                @if($payout->admin_note)
                                <tr>
                                    <td colspan="2" class="pt-0">
                                        <div class="alert alert-secondary py-2 small mb-0">
                                            <strong>Admin Note:</strong> {{ $payout->admin_note }}
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top py-3">
                    <a href="{{ route('admin.payouts.index') }}" class="btn btn-default shadow-none">
                        <i class="fas fa-chevron-left mr-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title font-weight-bold small text-uppercase">Verification Audit</h3>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 border-bottom">
                        <small class="text-muted d-block mb-1">AUDITED NET EARNINGS</small>
                        <span class="h5 font-weight-bold text-success">
                            + {{ number_format($actualAvailableBeforeThisPayout + $payout->amount) }} RWF
                        </span>
                        <p class="text-muted small mb-0">Total earnings from completed orders minus commission.</p>
                    </div>
                    <div class="p-3 border-bottom bg-light">
                        <small class="text-muted d-block mb-1">OTHER PENDING/PAID DEDUCTIONS</small>
                        <span class="h6 font-weight-bold text-danger">
                            - {{ number_format($actualAvailableBeforeThisPayout - ($actualAvailableBeforeThisPayout - \App\Models\Payout::where('shop_id', $payout->shop_id)->where('id', '!=', $payout->id)->whereIn('status', ['completed', 'pending', 'processing'])->sum('amount'))) }} RWF
                        </span>
                    </div>
                    <div class="p-3">
                        <small class="text-muted d-block mb-1">CURRENT AVAILABLE BALANCE</small>
                        <span class="h4 font-weight-bold {{ $actualAvailableBeforeThisPayout >= $payout->amount ? 'text-primary' : 'text-danger' }}">
                            {{ number_format($actualAvailableBeforeThisPayout) }} RWF
                        </span>
                    </div>
                </div>
                <div class="card-footer">
                    @if($actualAvailableBeforeThisPayout >= $payout->amount)
                        <div class="d-flex align-items-center text-success small font-weight-bold">
                            <i class="fas fa-check-circle mr-2 fa-lg"></i> Sufficient Funds for Payout
                        </div>
                    @else
                        <div class="d-flex align-items-center text-danger small font-weight-bold">
                            <i class="fas fa-exclamation-triangle mr-2 fa-lg"></i> Balance Insufficient
                        </div>
                    @endif
                </div>
            </div>

            @if($payout->status !== 'completed' && $payout->status !== 'rejected')
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body">
                    <form action="{{ route('admin.payouts.update', $payout->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="small font-weight-bold">Action Decision</label>
                            <select name="status" class="form-control" required>
                                <option value="">Perform Action</option>
                                <option value="completed">Approve & Complete</option>
                                <option value="rejected">Reject Request</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold">Note to Vendor</label>
                            <textarea name="admin_note" class="form-control" rows="2" placeholder="e.g. Transaction Ref: 998234..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block font-weight-bold shadow-sm">
                            Execute Payout Update
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-soft-primary { background-color: #eef5ff; }
    .card-outline.card-primary { border-top: 3px solid #007bff; }
    .breadcrumb-item + .breadcrumb-item::before { content: "›"; font-size: 1.2rem; vertical-align: middle; }
</style>
@endpush