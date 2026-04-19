@extends('admin.layouts.app')
@section('title', 'Review: ' . $shop->shop_name)

@section('content')
<div class="container-fluid mt-4 pb-5">
    {{-- 1. Header & Quick Actions --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        {{-- Improved Back Button with hover transition --}}
        <a href="{{ route('admin.shops.index') }}" class="btn btn-sm btn-link text-muted p-0 mb-2 text-decoration-none d-inline-flex align-items-center back-link">
            <i class="fas fa-chevron-left mr-1 small"></i> <span>Back to Shops</span>
        </a>
        
        <div class="d-flex align-items-center flex-wrap">
            {{-- Main Title --}}
            <h2 class="h3 font-weight-bold mb-0 text-dark mr-3">{{ $shop->shop_name }}</h2>
            
            {{-- Status Badge --}}
            <span class="badge badge-pill px-3 py-2 shadow-sm {{ $shop->is_active ? 'badge-success' : 'badge-warning text-dark' }}">
                <i class="fas {{ $shop->is_active ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                {{ $shop->is_active ? 'Verified' : 'Pending' }}
            </span>
        </div>

        {{-- Meta Info Row: Including Spare Parts Count --}}
        <div class="mt-2 d-flex align-items-center text-muted small">
            <span class="mr-3">
                <i class="fas fa-tools mr-1"></i> 
                <strong>{{ number_format($shop->parts_count) }}</strong> Spare Parts
            </span>
            <span class="mr-3">
                <i class="fas fa-calendar-alt mr-1"></i> 
                Joined {{ $shop->created_at->format('M d, Y') }}
            </span>
            <span>
                <i class="fas fa-map-marker-alt mr-1"></i> 
                {{ $shop->city ?? 'Rwanda' }}
            </span>
        </div>
    </div>
    
    {{-- Action Buttons --}}
    <div class="d-flex mt-3 mt-lg-0 align-items-center">
        @if(!$shop->is_active)
            <form action="{{ route('admin.shops.approve', $shop) }}" method="POST" 
                  onsubmit="return confirm('Approve this shop? The vendor will be notified.');" class="mr-2">
                @csrf @method('PUT')
                <button type="submit" class="btn btn-success font-weight-bold px-4 shadow-sm">
                    <i class="fas fa-check mr-1"></i> Approve
                </button>
            </form>
        @endif

        <form action="{{ route('admin.shops.toggle', $shop) }}" method="POST">
            @csrf @method('PUT')
            <button type="submit" class="btn btn-{{ $shop->is_active ? 'outline-danger' : 'primary' }} font-weight-bold px-4 shadow-sm">
                <i class="fas fa-{{ $shop->is_active ? 'user-slash' : 'user-check' }} mr-1"></i> 
                {{ $shop->is_active ? 'Suspend' : 'Activate' }}
            </button>
        </form>
    </div>
</div>

    {{-- 2. Audited Stats Cards --}}
 <div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-white border-left border-primary" style="border-left-width: 4px !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Total Gross Sales</div>
                        <div class="h4 mb-0 font-weight-bold text-dark">{{ number_format($totalGross) }} <small class="h6">RWF</small></div>
                        <small class="text-muted">Total audited revenue</small>
                    </div>
                    <div class="icon-shape bg-soft-primary text-primary rounded-circle px-3 py-2">
                        <i class="fas fa-chart-bar fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-white border-left border-info" style="border-left-width: 4px !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Total Commission</div>
                        <div class="h4 mb-0 font-weight-bold text-info">{{ number_format($totalCommission) }} <small class="h6">RWF</small></div>
                        <small class="text-muted">Platform share ({{ $shop->commission_rate }}%)</small>
                    </div>
                    <div class="icon-shape bg-soft-info text-info rounded-circle px-3 py-2">
                        <i class="fas fa-percentage fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-white border-left border-dark" style="border-left-width: 4px !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase font-weight-bold">Net Earnings</div>
                        <div class="h4 mb-0 font-weight-bold text-dark">{{ number_format($netEarnings) }} <small class="h6 text-muted">RWF</small></div>
                        <small class="text-success small"><i class="fas fa-arrow-down mr-1"></i> After platform fees</small>
                    </div>
                    <div class="icon-shape bg-light text-dark rounded-circle px-3 py-2">
                        <i class="fas fa-hand-holding-usd fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-success text-white border-left border-success" style="border-left-width: 4px !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small text-uppercase font-weight-bold">Available Balance</div>
                        <div class="h4 mb-0 font-weight-bold text-white">{{ number_format($availableBalance) }} <small class="h6">RWF</small></div>
                        <small class="text-white-50">Locked: {{ number_format($pendingPayouts) }} RWF</small>
                    </div>
                    <div class="icon-shape bg-white text-success rounded-circle px-3 py-2 shadow-sm">
                        <i class="fas fa-wallet fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="row">
        {{-- Left Column: Identity --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 overflow-hidden text-center">
                <div class="bg-primary py-4">
                    <img src="{{ $shop->logo ? asset('storage/' . $shop->logo) : asset('images/default-shop.png') }}" 
                         class="rounded-circle border border-white shadow-lg bg-white" 
                         style="width: 110px; height: 110px; object-fit: contain; border-width: 4px !important;">
                </div>
                <div class="card-body pt-0 mt-n1">
                    <h5 class="font-weight-bold mt-3 mb-1">{{ $shop->shop_name }}</h5>
                    <p class="text-muted small mb-3">Member since {{ $shop->created_at->format('M Y') }}</p>

                    <div class="list-group list-group-flush text-left small">
                        <div class="list-group-item px-0 py-3 d-flex align-items-center">
                            <i class="fas fa-user-circle text-muted mr-3"></i>
                            <div>
                                <div class="text-muted extra-small text-uppercase">Owner</div>
                                <div class="font-weight-bold">{{ $shop->user->name }}</div>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-3 d-flex align-items-center">
                            <i class="fas fa-id-card text-muted mr-3"></i>
                            <div>
                                <div class="text-muted extra-small text-uppercase">TIN Number</div>
                                <div class="font-weight-bold text-primary">{{ $shop->tin_number }}</div>
                            </div>
                        </div>
                        <div class="list-group-item px-0 py-3 d-flex align-items-center border-0">
                            <i class="fas fa-phone mr-3 text-muted"></i>
                            <div>
                                <div class="text-muted extra-small text-uppercase">Phone</div>
                                <div class="font-weight-bold">{{ $shop->phone_number }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4 bg-light border-left border-warning" style="border-left-width: 5px !important;">
                <div class="card-body">
                    <h6 class="font-weight-bold mb-2 small text-uppercase text-warning">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Admin Checklist
                    </h6>
                    <ul class="mb-0 small text-muted pl-3">
                        <li>Cross-check <strong>TIN</strong> with RRA Portal.</li>
                        <li>Verify <strong>RDB Certificate</strong> validity.</li>
                        <li>Ensure Shop Location is accurate.</li>
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
                            <a class="nav-link active font-weight-bold text-uppercase small py-3" id="docs-tab" data-toggle="tab" href="#docs" role="tab">
                                <i class="fas fa-file-invoice mr-2"></i>Legal Documents
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold text-uppercase small py-3" id="sales-tab" data-toggle="tab" href="#sales" role="tab">
                                <i class="fas fa-shopping-cart mr-2"></i>Recent Sales
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="shopTabsContent">
                        {{-- Documents Tab --}}
                        <div class="tab-pane fade show active" id="docs" role="tabpanel">
                            @forelse($shop->documents as $doc)
                                <div class="border rounded p-3 mb-3 bg-white d-flex align-items-center justify-content-between hover-shadow">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-soft-primary text-primary rounded p-3 mr-3">
                                            <i class="fas fa-file-contract"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 font-weight-bold">{{ $doc->title }}</h6>
                                            <small class="text-muted">{{ strtoupper($doc->file_type) }} • {{ $doc->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ route('admin.shops.view-doc', $doc) }}" target="_blank" class="btn btn-sm btn-light border">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                        <a href="{{ route('admin.shops.download-doc', $doc) }}" class="btn btn-sm btn-light border">
                                            <i class="fas fa-download text-muted"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">No documents uploaded.</div>
                            @endforelse
                        </div>

                        {{-- Sales Tab --}}
                        <div class="tab-pane fade" id="sales" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="bg-light">
                                        <tr class="text-muted small text-uppercase">
                                            <th>Order #</th>
                                            <th>Spare Part</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $item)
                                            <tr>
                                                <td class="font-weight-bold">#{{ $item->order->order_number }}</td>
                                                <td>{{ Str::limit($item->part_name, 30) }}</td>
                                                <td class="text-right font-weight-bold">{{ number_format($item->unit_price * $item->quantity) }} RWF</td>
                                                <td class="text-center">
                                                    <span class="badge badge-pill px-3 {{ $item->status == 'completed' ? 'badge-success' : 'badge-secondary' }}">
                                                        {{ ucfirst($item->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center py-4">No sales found.</td></tr>
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
    .bg-soft-primary { background-color: rgba(0, 123, 255, 0.1); }
    .bg-soft-info { background-color: rgba(23, 162, 184, 0.1); }
    .extra-small { font-size: 0.7rem; }
    .mt-n1 { margin-top: -3rem !important; }
    .hover-shadow:hover { box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1); transition: 0.3s; }
    .opacity-20 { opacity: 0.2; }
       /* Subtle hover effect for the back link */
    .back-link { transition: transform 0.2s ease; }
    .back-link:hover { transform: translateX(-3px); color: #000 !important; }
    
    /* Ensure badges look crisp */
    .badge-pill { font-size: 85%; letter-spacing: 0.3px; }
</style>
@endsection