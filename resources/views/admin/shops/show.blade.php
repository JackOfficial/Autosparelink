@extends('admin.layouts.app')
@section('title', 'Review: ' . $shop->shop_name)

@section('content')
<div class="container-fluid mt-4 pb-5">
    
    {{-- 1. HEADER: Context & Primary Actions --}}
    <div class="row align-items-end mb-4">
        <div class="col-md-8">
            <a href="{{ route('admin.shops.index') }}" class="btn btn-sm btn-link text-muted p-0 mb-2 text-decoration-none d-inline-flex align-items-center back-link">
                <i class="fas fa-chevron-left mr-1 small"></i> <span>Back to Shops List</span>
            </a>
            <div class="d-flex align-items-center">
                <h2 class="h3 font-weight-bold mb-0 text-dark mr-3">{{ $shop->shop_name }}</h2>
                <span class="badge badge-pill px-3 py-2 shadow-sm {{ $shop->is_active ? 'badge-success' : 'badge-warning text-dark' }}">
                    <i class="fas {{ $shop->is_active ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                    {{ $shop->is_active ? 'Verified Account' : 'Pending Verification' }}
                </span>
            </div>
        </div>
        <div class="col-md-4 text-md-right mt-3 mt-md-0">
            @if(!$shop->is_active)
                <form action="{{ route('admin.shops.approve', $shop) }}" method="POST" class="d-inline mr-2" onsubmit="return confirm('Approve this shop?');">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-success font-weight-bold px-4 shadow-sm">
                        <i class="fas fa-check mr-1"></i> Approve Shop
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.shops.toggle', $shop) }}" method="POST" class="d-inline">
                @csrf @method('PUT')
                <button type="submit" class="btn btn-{{ $shop->is_active ? 'outline-danger' : 'primary' }} font-weight-bold px-4 shadow-sm">
                    <i class="fas fa-{{ $shop->is_active ? 'user-slash' : 'user-check' }} mr-1"></i> 
                    {{ $shop->is_active ? 'Suspend Access' : 'Restore Access' }}
                </button>
            </form>
        </div>
    </div>

    {{-- 2. FINANCIAL OVERVIEW: Audited Stats --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-white border-left border-primary h-100" style="border-left-width: 4px !important;">
                <div class="card-body py-3">
                    <div class="text-muted small text-uppercase font-weight-bold">Gross Sales</div>
                    <div class="h4 mb-0 font-weight-bold text-dark">{{ number_format($totalGross) }} <small class="h6">RWF</small></div>
                    <div class="text-muted extra-small mt-1"><i class="fas fa-receipt mr-1"></i> Total transaction volume</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-white border-left border-info h-100" style="border-left-width: 4px !important;">
                <div class="card-body py-3">
                    <div class="text-muted small text-uppercase font-weight-bold">Revenue Share</div>
                    <div class="h4 mb-0 font-weight-bold text-info">{{ number_format($totalCommission) }} <small class="h6">RWF</small></div>
                    <div class="text-muted extra-small mt-1"><i class="fas fa-tag mr-1"></i> Platform Fee ({{ $shop->commission_rate }}%)</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-white border-left border-dark h-100" style="border-left-width: 4px !important;">
                <div class="card-body py-3">
                    <div class="text-muted small text-uppercase font-weight-bold">Net Earnings</div>
                    <div class="h4 mb-0 font-weight-bold text-dark">{{ number_format($netEarnings) }} <small class="h6">RWF</small></div>
                    <div class="text-success extra-small mt-1 font-weight-bold"><i class="fas fa-wallet mr-1"></i> After platform cut</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body py-3">
                    <div class="text-white-50 small text-uppercase font-weight-bold">Payable Balance</div>
                    <div class="h4 mb-0 font-weight-bold text-white">{{ number_format($availableBalance) }} <small class="h6">RWF</small></div>
                    <div class="text-white-50 extra-small mt-1">Locked: {{ number_format($pendingPayouts) }} RWF</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- 3. LEFT COLUMN: Identity & Trust --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                <div class="bg-dark py-5 text-center position-relative">
                    <img src="{{ $shop->logo ? asset('storage/' . $shop->logo) : asset('images/default-shop.png') }}" 
                         class="rounded-circle border border-white shadow-lg bg-white" 
                         style="width: 100px; height: 100px; object-fit: contain; border-width: 4px !important;">
                </div>
                <div class="card-body pt-4">
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item px-0 py-3 border-top-0 d-flex justify-content-between">
                            <span class="text-muted"><i class="fas fa-user-circle mr-2"></i> Owner</span>
                            <span class="font-weight-bold">{{ $shop->user->name }}</span>
                        </div>
                        <div class="list-group-item px-0 py-3 d-flex justify-content-between">
                            <span class="text-muted"><i class="fas fa-id-card mr-2"></i> TIN Number</span>
                            <span class="font-weight-bold text-primary">{{ $shop->tin_number }}</span>
                        </div>
                        <div class="list-group-item px-0 py-3 d-flex justify-content-between">
                            <span class="text-muted"><i class="fas fa-tools mr-2"></i> Inventory</span>
                            <span class="font-weight-bold">{{ number_format($shop->parts_count) }} Parts</span>
                        </div>
                        <div class="list-group-item px-0 py-3 d-flex justify-content-between">
                            <span class="text-muted"><i class="fas fa-phone mr-2"></i> Contact</span>
                            <span class="font-weight-bold">{{ $shop->phone_number }}</span>
                        </div>
                        <div class="list-group-item px-0 py-3 border-0 d-flex justify-content-between">
                            <span class="text-muted"><i class="fas fa-map-marker-alt mr-2"></i> Location</span>
                            <span class="font-weight-bold">{{ $shop->city ?? 'Rwanda' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Checklist Card --}}
            <div class="card shadow-sm border-0 bg-soft-warning border-left border-warning" style="border-left-width: 5px !important;">
                <div class="card-body">
                    <h6 class="font-weight-bold mb-3 small text-uppercase text-warning">
                        <i class="fas fa-tasks mr-1"></i> Verification Checklist
                    </h6>
                    <div class="custom-control custom-checkbox mb-2 small">
                        <input type="checkbox" class="custom-control-input" id="check1" checked disabled>
                        <label class="custom-control-label text-muted" for="check1">TIN Verified (RRA)</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-2 small">
                        <input type="checkbox" class="custom-control-input" id="check2" {{ $shop->is_active ? 'checked disabled' : '' }}>
                        <label class="custom-control-label text-muted" for="check2">RDB Certificate Valid</label>
                    </div>
                    <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="check3" {{ $shop->is_active ? 'checked disabled' : '' }}>
                        <label class="custom-control-label text-muted" for="check3">Physical Location Survey</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. RIGHT COLUMN: Detailed Activity --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom pt-0">
                    <ul class="nav nav-tabs card-header-tabs border-bottom-0" id="shopTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold text-uppercase small py-3" id="docs-tab" data-toggle="tab" href="#docs" role="tab">
                                <i class="fas fa-file-contract mr-2"></i>Legal Documents
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold text-uppercase small py-3" id="sales-tab" data-toggle="tab" href="#sales" role="tab">
                                <i class="fas fa-shopping-cart mr-2"></i>Recent Sales
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content" id="shopTabsContent">
                        {{-- Tab 1: Documents --}}
                        <div class="tab-pane fade show active p-4" id="docs" role="tabpanel">
                            @forelse($shop->documents as $doc)
                                <div class="border rounded p-3 mb-3 bg-light d-flex align-items-center justify-content-between hover-shadow transition">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white text-primary rounded shadow-sm p-3 mr-3">
                                            <i class="fas fa-file-pdf fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 font-weight-bold">{{ $doc->title }}</h6>
                                            <small class="text-muted text-uppercase">{{ $doc->file_type }} • {{ $doc->created_at->format('d M Y') }}</small>
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.shops.view-doc', $doc) }}" target="_blank" class="btn btn-sm btn-white border shadow-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.shops.download-doc', $doc) }}" class="btn btn-sm btn-white border shadow-sm" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" style="width: 60px; opacity: 0.3;">
                                    <p class="mt-3 text-muted">No legal documents submitted for review.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Tab 2: Sales --}}
                        <div class="tab-pane fade" id="sales" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr class="text-muted small text-uppercase">
                                            <th class="pl-4">Order ID</th>
                                            <th>Spare Part</th>
                                            <th class="text-right">Total Price</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $item)
                                            <tr>
                                                <td class="pl-4 font-weight-bold text-primary">#{{ $item->order->order_number }}</td>
                                                <td>
                                                    <div class="font-weight-bold text-dark">{{ Str::limit($item->part_name, 35) }}</div>
                                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                                </td>
                                                <td class="text-right font-weight-bold text-dark">
                                                    {{ number_format($item->unit_price * $item->quantity) }} <span class="small text-muted font-weight-normal">RWF</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-pill px-3 py-1 {{ $item->status == 'completed' ? 'badge-soft-success text-success' : 'badge-soft-secondary' }}">
                                                        {{ ucfirst($item->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center py-5 text-muted">No sales history recorded yet.</td></tr>
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
    /* Professional Utility Classes */
    .bg-soft-warning { background-color: #fff9e6; }
    .badge-soft-success { background-color: #e6fcf5; color: #0ca678; }
    .badge-soft-secondary { background-color: #f1f3f5; color: #495057; }
    .extra-small { font-size: 0.72rem; }
    .transition { transition: all 0.2s ease-in-out; }
    .hover-shadow:hover { transform: translateY(-2px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08) !important; }
    .back-link:hover { transform: translateX(-4px); }
    
    /* Dashboard Table Styling */
    .table td, .table th { vertical-align: middle; padding: 1rem 0.75rem; border-top: 1px solid #f2f2f2; }
    .nav-tabs .nav-link { border: none; color: #adb5bd; border-bottom: 2px solid transparent; }
    .nav-tabs .nav-link.active { color: #007bff; border-bottom: 2px solid #007bff; background: transparent; }
</style>
@endsection