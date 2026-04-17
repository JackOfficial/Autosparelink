@extends('admin.layouts.app')
@section('title', 'Review: ' . $shop->shop_name)

@section('content')
<div class="container-fluid mt-4">
    {{-- 1. Header & Quick Actions --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.shops.index') }}" class="btn btn-sm btn-link text-muted p-0 mb-2">
                <i class="fas fa-arrow-left me-1"></i> Back to Shops
            </a>
            <h2 class="h3 font-weight-bold mb-0 text-dark">Review: {{ $shop->shop_name }}</h2>
        </div>
        
        <div class="d-flex mt-3 mt-md-0 gap-2">
            @if(!$shop->is_active)
                <form action="{{ route('admin.shops.approve', $shop) }}" method="POST" 
                      onsubmit="return confirm('Approve this shop? The vendor will be notified.');">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-success px-4 shadow-sm">
                        <i class="fas fa-check-circle me-1"></i> Approve Shop
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.shops.toggle', $shop) }}" method="POST">
                @csrf @method('PUT')
                <button type="submit" class="btn btn-{{ $shop->is_active ? 'danger' : 'outline-primary' }} px-4 shadow-sm">
                    <i class="fas fa-{{ $shop->is_active ? 'ban' : 'play' }} me-1"></i> 
                    {{ $shop->is_active ? 'Suspend Shop' : 'Activate Shop' }}
                </button>
            </form>
        </div>
    </div>

    {{-- 2. Stats Cards (Based on updated Controller data) --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small text-uppercase fw-bold">Wallet Balance</div>
                            <div class="h4 mb-0 fw-bold">{{ number_format($shop->wallet->balance ?? 0) }} RWF</div>
                        </div>
                        <i class="fas fa-wallet fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small text-uppercase fw-bold">Total Revenue</div>
                            <div class="h4 mb-0 fw-bold">{{ number_format($totalRevenue ?? 0) }} RWF</div>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Parts Listed</div>
                            <div class="h4 mb-0 fw-bold">{{ $shop->parts_count }}</div>
                        </div>
                        <i class="fas fa-cogs fa-2x text-light"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Commission</div>
                            <div class="h4 mb-0 fw-bold text-primary">{{ $shop->commission_rate }}%</div>
                        </div>
                        <i class="fas fa-percentage fa-2x text-light"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Column: Identity --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ $shop->logo ? asset('storage/' . $shop->logo) : asset('images/default-shop.png') }}" 
                             class="rounded-circle border p-1 shadow-sm mb-3" 
                             style="width: 110px; height: 110px; object-fit: cover;">
                        <h5 class="fw-bold mb-1">{{ $shop->shop_name }}</h5>
                        <span class="badge bg-{{ $shop->is_active ? 'success' : 'warning text-dark' }} px-3">
                            {{ $shop->is_active ? 'Active' : 'Pending Verification' }}
                        </span>
                    </div>

                    <div class="list-group list-group-flush small">
                        <div class="list-group-item px-0 py-2 d-flex justify-content-between">
                            <span class="text-muted">Owner</span>
                            <span class="fw-bold">{{ $shop->user->name }}</span>
                        </div>
                        <div class="list-group-item px-0 py-2 d-flex justify-content-between">
                            <span class="text-muted">TIN Number</span>
                            <span class="fw-bold text-primary">{{ $shop->tin_number }}</span>
                        </div>
                        <div class="list-group-item px-0 py-2 d-flex justify-content-between">
                            <span class="text-muted">Email</span>
                            <span class="fw-bold">{{ $shop->shop_email ?? $shop->user->email }}</span>
                        </div>
                        <div class="list-group-item px-0 py-2 d-flex justify-content-between">
                            <span class="text-muted">Phone</span>
                            <span class="fw-bold">{{ $shop->phone_number }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body py-3">
                    <h6 class="fw-bold mb-2 small text-uppercase">Internal Note</h6>
                    <div class="p-2 bg-light rounded border-start border-warning" style="border-left-width: 4px !important;">
                        <small class="text-muted">Verify the <strong>RDB Documents</strong> below against the RRA TIN database before approving payouts.</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Tabs --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-3">
                    <ul class="nav nav-pills card-header-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active btn-sm" data-toggle="pill" data-target="#docs">Documents</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link btn-sm" data-toggle="pill" data-target="#sales">Recent Sales</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body pt-0">
                    <div class="tab-content mt-3">
                        {{-- Documents Tab --}}
                        <div class="tab-pane fade show active" id="docs">
                            @forelse($shop->documents as $doc)
                                <div class="border rounded p-3 mb-3 bg-light d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-contract fa-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $doc->title }}</h6>
                                            <small class="text-muted">{{ strtoupper($doc->file_type) }} • {{ $doc->created_at->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.shops.view-doc', $doc) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.shops.download-doc', $doc) }}" class="btn btn-sm btn-light border"><i class="fas fa-download"></i></a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-folder-open fa-2x text-light mb-2"></i>
                                    <p class="text-muted mb-0">No documents found.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Sales Tab --}}
                        <div class="tab-pane fade" id="sales">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle">
                                    <thead>
                                        <tr class="text-muted small text-uppercase">
                                            <th class="border-0">Order</th>
                                            <th class="border-0">Part</th>
                                            <th class="border-0 text-end">Amount</th>
                                            <th class="border-0 text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $item)
                                            <tr>
                                                <td class="fw-bold">#{{ $item->order->order_number }}</td>
                                                <td class="small">{{ Str::limit($item->part_name, 30) }}</td>
                                                <td class="text-end fw-bold">{{ number_format($item->unit_price * $item->quantity) }} RWF</td>
                                                <td class="text-center">
                                                    <span class="badge badge-pill bg-light text-dark border small">{{ $item->status }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center py-4">No recent activity.</td></tr>
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
@endsection