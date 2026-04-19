@extends('admin.layouts.app')
@section('title', 'Review: ' . $shop->shop_name)

@section('content')
<div class="container-fluid mt-4 pb-5">
    {{-- 1. Header & Quick Actions --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.shops.index') }}" class="btn btn-sm btn-link text-muted p-0 mb-2 text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i> Back to Shops
            </a>
            <div class="d-flex align-items-center">
                <h2 class="h3 font-weight-bold mb-0 text-dark me-3">{{ $shop->shop_name }}</h2>
                <span class="badge bg-{{ $shop->is_active ? 'success' : 'warning text-dark' }} rounded-pill px-3 shadow-sm">
                    {{ $shop->is_active ? 'Verified Shop' : 'Verification Pending' }}
                </span>
            </div>
        </div>
        
        <div class="d-flex mt-3 mt-md-0 gap-2">
            @if(!$shop->is_active)
                <form action="{{ route('admin.shops.approve', $shop) }}" method="POST" 
                      onsubmit="return confirm('Approve this shop? The vendor will be notified.');">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-success px-4 shadow-sm border-0">
                        <i class="fas fa-check-circle me-1"></i> Approve Shop
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.shops.toggle', $shop) }}" method="POST">
                @csrf @method('PUT')
                <button type="submit" class="btn btn-{{ $shop->is_active ? 'outline-danger' : 'primary' }} px-4 shadow-sm">
                    <i class="fas fa-{{ $shop->is_active ? 'ban' : 'play' }} me-1"></i> 
                    {{ $shop->is_active ? 'Suspend Shop' : 'Activate Shop' }}
                </button>
            </form>
        </div>
    </div>

    {{-- 2. Audited Stats Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-white overflow-hidden">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Wallet Balance</div>
                            <div class="h3 mb-0 fw-bold text-dark">{{ number_format($shop->wallet->balance ?? 0) }} <small class="h6">RWF</small></div>
                        </div>
                        <div class="icon-shape bg-soft-primary text-primary rounded-circle px-3 py-2">
                            <i class="fas fa-wallet fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-dark text-white overflow-hidden position-relative">
                <div class="card-body z-index-10">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small text-uppercase fw-bold">Audited Revenue</div>
                            <div class="h3 mb-0 fw-bold text-success">{{ number_format($totalRevenue ?? 0) }} <small class="h6">RWF</small></div>
                            <div class="text-success small mt-1"><i class="fas fa-shield-check me-1"></i> Verified Only</div>
                        </div>
                        <i class="fas fa-money-bill-trend-up fa-2x text-white-50 opacity-20"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Active Parts</div>
                            <div class="h3 mb-0 fw-bold text-dark">{{ $shop->parts_count }}</div>
                        </div>
                        <div class="icon-shape bg-soft-info text-info rounded-circle px-3 py-2">
                            <i class="fas fa-boxes-stacked fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-white border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Platform Fee</div>
                            <div class="h3 mb-0 fw-bold text-primary">{{ $shop->commission_rate }}%</div>
                        </div>
                        <div class="icon-shape bg-soft-secondary text-muted rounded-circle px-3 py-2">
                            <i class="fas fa-hand-holding-dollar fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Column: Identity --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                <div class="bg-primary py-4 text-center">
                    <img src="{{ $shop->logo ? asset('storage/' . $shop->logo) : asset('images/default-shop.png') }}" 
                         class="rounded-circle border border-4 border-white shadow-lg bg-white" 
                         style="width: 110px; height: 110px; object-fit: contain;">
                </div>
                <div class="card-body pt-0 mt-n1 text-center">
                    <h5 class="fw-bold mt-3 mb-1">{{ $shop->shop_name }}</h5>
                    <p class="text-muted small mb-3">Member since {{ $shop->created_at->format('M Y') }}</p>

                    <div class="list-group list-group-flush text-start small">
                        <div class="list-group-item px-0 py-3 d-flex align-items-center">
                            <i class="fas fa-user-circle text-muted me-3"></i>
                            <div>
                                <div class="text-muted extra-small text-uppercase">Owner</div>
                                <div class="fw-bold">{{ $user->name }}</div>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-3 d-flex align-items-center">
                            <i class="fas fa-id-card text-muted me-3"></i>
                            <div>
                                <div class="text-muted extra-small text-uppercase">TIN Number</div>
                                <div class="fw-bold text-primary">{{ $shop->tin_number }}</div>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-3 d-flex align-items-center">
                            <i class="fas fa-envelope text-muted me-3"></i>
                            <div>
                                <div class="text-muted extra-small text-uppercase">Contact Email</div>
                                <div class="fw-bold">{{ $shop->shop_email ?? $shop->user->email }}</div>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-3 d-flex align-items-center border-0">
                            <i class="fas fa-phone-alt text-muted me-3"></i>
                            <div>
                                <div class="text-muted extra-small text-uppercase">Phone</div>
                                <div class="fw-bold">{{ $shop->phone_number }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 bg-light border-start border-warning" style="border-left-width: 5px !important;">
                <div class="card-body">
                    <h6 class="fw-bold mb-2 small text-uppercase text-warning-emphasis">
                        <i class="fas fa-exclamation-triangle me-1"></i> Admin Checklist
                    </h6>
                    <ul class="mb-0 small text-muted ps-3">
                        <li>Cross-check <strong>TIN</strong> with RRA Portal.</li>
                        <li>Verify <strong>RDB Certificate</strong> validity.</li>
                        <li>Ensure Shop Location is accurate for delivery.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Right Column: Tabs --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom pt-3">
                    <ul class="nav nav-tabs card-header-tabs border-bottom-0" id="shopTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-bold text-uppercase small py-3" id="docs-tab" data-bs-toggle="tab" data-bs-target="#docs">
                                <i class="fas fa-file-invoice me-2"></i>Legal Documents
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold text-uppercase small py-3" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales">
                                <i class="fas fa-shopping-cart me-2"></i>Recent Sales (Audited)
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="shopTabsContent">
                        {{-- Documents Tab --}}
                        <div class="tab-pane fade show active" id="docs" role="tabpanel">
                            @forelse($shop->documents as $doc)
                                <div class="border rounded-3 p-3 mb-3 bg-white d-flex align-items-center justify-content-between hover-shadow">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-soft-primary text-primary rounded-3 p-3 me-3">
                                            <i class="fas fa-file-contract fa-xl"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $doc->title }}</h6>
                                            <small class="text-muted">{{ strtoupper($doc->file_type) }} • Uploaded {{ $doc->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ route('admin.shops.view-doc', $doc) }}" target="_blank" class="btn btn-sm btn-white border">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                        <a href="{{ route('admin.shops.download-doc', $doc) }}" class="btn btn-sm btn-white border">
                                            <i class="fas fa-download text-muted"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <img src="{{ asset('images/empty-docs.svg') }}" style="width: 120px; opacity: 0.5" class="mb-3">
                                    <p class="text-muted mb-0">No verification documents uploaded yet.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Sales Tab --}}
                        <div class="tab-pane fade" id="sales" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr class="text-muted small text-uppercase">
                                            <th class="border-0">Order #</th>
                                            <th class="border-0">Spare Part</th>
                                            <th class="border-0 text-end">Amount</th>
                                            <th class="border-0 text-center">Audit Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $item)
                                            <tr>
                                                <td class="fw-bold">
                                                    <a href="#" class="text-decoration-none text-dark">#{{ $item->order->order_number }}</a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="small fw-medium">{{ Str::limit($item->part_name, 40) }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-end fw-bold text-dark">
                                                    {{ number_format($item->unit_price * $item->quantity) }} RWF
                                                </td>
                                                <td class="text-center">
                                                    @if($item->status == 'completed')
                                                        <span class="badge bg-soft-success text-success border border-success rounded-pill px-3">
                                                            <i class="fas fa-check me-1"></i> Completed
                                                        </span>
                                                    @else
                                                        <span class="badge bg-soft-secondary text-muted border rounded-pill px-3">
                                                            {{ ucfirst($item->status) }}
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5 text-muted fst-italic">
                                                    No audited sales records found for this shop.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    .bg-soft-success { background-color: rgba(25, 135, 84, 0.1); }
    .bg-soft-info { background-color: rgba(13, 202, 240, 0.1); }
    .bg-soft-secondary { background-color: rgba(108, 117, 125, 0.1); }
    .extra-small { font-size: 0.7rem; }
    .mt-n1 { margin-top: -3rem !important; }
    .hover-shadow:hover { box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1); transition: 0.3s; }
</style>
@endsection