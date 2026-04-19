@extends('admin.layouts.app')

@section('title', 'Admin Payouts | Autosparelink')

@section('content')
<div class="container-fluid" x-data="{ 
    showModal: false, 
    payoutId: null, 
    shopName: '', 
    amount: 0,
    openModal(id, name, amt) {
        this.payoutId = id;
        this.shopName = name;
        this.amount = amt;
        this.showModal = true;
        // Optional: Prevent body scroll when modal is open
        document.body.classList.add('modal-open');
    },
    closeModal() {
        this.showModal = false;
        document.body.classList.remove('modal-open');
    }
}">
    <div class="row mb-4 pt-3">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="fas fa-wallet mr-2 text-primary"></i>Vendor Payouts
            </h1>
            <p class="text-muted small">Manage and audit withdrawal requests from your sellers.</p>
        </div>
        <div class="col-sm-6">
            <div class="d-flex justify-content-end">
                <div class="bg-white p-2 px-3 rounded shadow-sm border-left border-warning mr-2 text-right">
                    <small class="text-muted text-uppercase d-block" style="font-size: 10px;">Pending Payouts</small>
                    <span class="h6 font-weight-bold text-warning mb-0">
                        {{ number_format($payouts->where('status', 'pending')->sum('amount')) }} RWF
                    </span>
                </div>
                <div class="bg-white p-2 px-3 rounded shadow-sm border-left border-success text-right">
                    <small class="text-muted text-uppercase d-block" style="font-size: 10px;">Total Paid Out</small>
                    <span class="h6 font-weight-bold text-success mb-0">
                        {{ number_format($payouts->where('status', 'completed')->sum('amount')) }} RWF
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <ul class="nav nav-pills mb-3 small" id="payout-tabs">
                <li class="nav-item">
                    <a class="nav-link active shadow-sm mr-2" href="#">All Requests</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link bg-white shadow-sm mr-2 text-warning" href="#">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link bg-white shadow-sm text-success" href="#">Completed</a>
                </li>
            </ul>

            <div class="card card-outline card-primary shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title font-weight-bold text-dark">Recent Transactions</h3>
                    <div class="card-tools">
                        <form action="" method="GET">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" class="form-control" placeholder="Search by shop name...">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover align-middle">
                        <thead class="text-muted small text-uppercase" style="background: #f8f9fa;">
                            <tr>
                                <th class="pl-4">Date / ID</th>
                                <th>Vendor Details</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th class="text-right pr-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payouts as $payout)
                            <tr>
                                <td class="pl-4">
                                    <span class="d-block font-weight-bold text-dark">{{ $payout->created_at->format('d M, Y') }}</span>
                                    <small class="text-muted">#PO-{{ str_pad($payout->id, 5, '0', STR_PAD_LEFT) }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
    <div class="symbol-40 mr-3 overflow-hidden shadow-sm border" style="width: 40px; height: 40px; border-radius: 8px;">
        @if($payout->shop->logo)
            <img src="{{ asset('storage/' . $payout->shop->logo) }}" 
                 alt="{{ $payout->shop->shop_name }}" 
                 style="width: 100%; height: 100%; object-fit: cover;">
        @else
            <div class="bg-soft-primary text-primary font-weight-bold d-flex align-items-center justify-content-center" 
                 style="width: 100%; height: 100%;">
                {{ strtoupper(substr($payout->shop->shop_name, 0, 1)) }}
            </div>
        @endif
    </div>

    <div>
        <span class="font-weight-bold d-block text-dark line-height-1">
            {{ $payout->shop->shop_name }}
        </span>
        <small class="text-muted">
            <i class="fas fa-envelope mr-1" style="font-size: 10px;"></i>{{ $payout->shop->user->email }}
        </small>
    </div>
</div>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-dark">{{ number_format($payout->amount) }}</span>
                                    <small class="text-muted">RWF</small>
                                </td>
                                <td>
                                    <span class="badge badge-light border text-muted px-2">
                                        <i class="fas fa-university mr-1 small"></i> {{ $payout->payment_method ?? 'Mobile Money' }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = [
                                            'pending' => 'bg-soft-warning text-warning',
                                            'processing' => 'bg-soft-info text-info',
                                            'completed' => 'bg-soft-success text-success',
                                            'rejected' => 'bg-soft-danger text-danger'
                                        ][$payout->status] ?? 'bg-soft-secondary';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-3 py-2 text-uppercase" style="font-size: 10px; border-radius: 50px;">
                                        <i class="fas fa-circle mr-1" style="font-size: 7px;"></i> {{ $payout->status }}
                                    </span>
                                </td>
                                <td class="text-right pr-4">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.payouts.show', $payout->id) }}" 
                                           class="btn btn-sm btn-white border shadow-none" 
                                           title="Audit Balance">
                                            <i class="fas fa-file-invoice-dollar text-primary"></i> Audit
                                        </a>
                                        
                                        @if($payout->status === 'pending' || $payout->status === 'processing')
                                        <button @click="openModal('{{ $payout->id }}', '{{ addslashes($payout->shop->shop_name) }}', '{{ number_format($payout->amount) }}')" 
        class="btn btn-sm btn-primary shadow-none ml-1">
    Update
</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-receipt fa-3x mb-3 opacity-25"></i>
                                        <p>No payout requests found at this time.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($payouts->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Showing {{ $payouts->firstItem() }} to {{ $payouts->lastItem() }} of {{ $payouts->total() }} entries</small>
                        {{ $payouts->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div x-show="showModal" 
     x-cloak
     class="modal fade show" 
     style="display: block; background: rgba(0,0,0,0.6); z-index: 1050;"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-90"
     x-transition:enter-end="opacity-100 transform scale-100"
     @keydown.escape.window="showModal = false">
    
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-cog mr-2"></i>Process Payout
                </h5>
                <button type="button" class="close text-white" @click="showModal = false">
                    <span>&times;</span>
                </button>
            </div>

            <form :action="'{{ route('admin.payouts.index') }}/' + payoutId" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger py-2 small mb-3">
                            <i class="fas fa-exclamation-circle mr-1"></i> Please check the form for errors.
                        </div>
                    @endif

                    <div class="d-flex align-items-center mb-4 p-3 rounded" style="background: #f0f7ff; border: 1px solid #d0e5ff;">
                        <div class="mr-auto">
                            <small class="text-muted text-uppercase font-weight-bold d-block">Vendor</small>
                            <span class="h6 font-weight-bold text-dark mb-0" x-text="shopName"></span>
                        </div>
                        <div class="text-right">
                            <small class="text-muted text-uppercase font-weight-bold d-block">Payout Amount</small>
                            <span class="h5 font-weight-bold text-primary mb-0" x-text="amount + ' RWF'"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold small">Decision</label>
                        <select name="status" class="form-control form-control-lg custom-select" required style="font-size: 14px;">
                            <option value="processing">Move to Processing</option>
                            <option value="completed">Confirm Payment Dispatched</option>
                            <option value="rejected">Decline Request</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold small">Transfer Reference / Reason</label>
                        <textarea name="admin_note" 
                                  class="form-control @error('admin_note') is-invalid @enderror" 
                                  rows="3" 
                                  placeholder="Enter bank reference number or rejection reason for the vendor..."></textarea>
                        @error('admin_note')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="alert alert-info p-2 small mb-0">
                        <i class="fas fa-info-circle mr-1"></i> Updating to <strong>Completed</strong> will finalize the transaction and lock the record.
                    </div>
                </div>

                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-muted shadow-none" @click="showModal = false">Dismiss</button>
                    <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm">Confirm Action</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

@push('styles')
<style>
    /* Premium Look UI Components */
    .bg-soft-primary { background-color: #e7f1ff; }
    .bg-soft-success { background-color: #d4edda; }
    .bg-soft-warning { background-color: #fff3cd; }
    .bg-soft-danger  { background-color: #f8d7da; }
    
    .symbol-40 {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-pills .nav-link.active {
        background-color: #007bff;
        color: white;
    }

    .table th {
        font-weight: 600;
        letter-spacing: 0.5px;
        border-top: none;
    }

    .table td { vertical-align: middle; border-bottom: 1px solid #f1f1f1; }

    /* Modal Animation */
    [x-cloak] { display: none !important; }
</style>
@endpush
@endsection