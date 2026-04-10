<x-shop-dashboard>
    <x-slot:title>Sales History & Reports</x-slot:title>

    @push('styles')
    <style>
        .stats-card { border: none; border-radius: 15px; transition: transform 0.2s; }
        .stats-card:hover { transform: translateY(-5px); }
        .img-stack-container { position: relative; height: 40px; width: 60px; }
        .stack-img { 
            width: 35px; height: 35px; object-fit: cover; border-radius: 8px; 
            border: 2px solid #fff; position: absolute; 
        }
        .table-hover tbody tr:hover { background-color: #f8fbff; cursor: pointer; }
        .badge-soft-success { background-color: #e6fffa; color: #38b2ac; border: 1px solid #b2f5ea; }
    </style>
    @endpush

    <div class="container-fluid py-4">
        {{-- Financial Summary Header --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card stats-card shadow-sm bg-primary text-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-white-50 small mb-1 fw-bold">TOTAL REVENUE</p>
                                <h3 class="mb-0 fw-bold">{{ number_format($totalRevenue) }} RWF</h3>
                            </div>
                            <div class="fs-1 opacity-25"><i class="fas fa-coins"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card shadow-sm bg-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted small mb-1 fw-bold">SALES COUNT</p>
                                <h3 class="mb-0 fw-bold text-dark">{{ $salesCount }}</h3>
                            </div>
                            <div class="fs-1 text-primary opacity-25"><i class="fas fa-shopping-cart"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card shadow-sm bg-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted small mb-1 fw-bold">AVG. ORDER VALUE</p>
                                <h3 class="mb-0 fw-bold text-dark">
                                    {{ $salesCount > 0 ? number_format($totalRevenue / $salesCount) : 0 }} RWF
                                </h3>
                            </div>
                            <div class="fs-1 text-success opacity-25"><i class="fas fa-chart-line"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales History Table --}}
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Transaction Log</h5>
                <div class="d-flex gap-2">
                    <form action="{{ route('shop.sales.index') }}" method="GET" class="d-flex gap-2">
                        <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="all">All Time</option>
                            <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>This Month</option>
                        </select>
                    </form>
                    <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-download me-1"></i> CSV</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0 small text-muted">ORDER & DATE</th>
                                <th class="border-0 small text-muted">CUSTOMER</th>
                                <th class="border-0 small text-muted">PARTS</th>
                                <th class="border-0 small text-muted">TOTAL</th>
                                <th class="border-0 small text-muted">PAYMENT</th>
                                <th class="border-0 small text-muted">STATUS</th>
                                <th class="text-end pe-4 border-0 small text-muted">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr onclick="window.location='{{ route('shop.orders.show', $sale->id) }}'">
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">#{{ $sale->id }}</div>
                                        <div class="small text-muted">{{ $sale->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold">{{ $sale->guest_name ?? $sale->user->name ?? 'Guest' }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">{{ $sale->guest_phone ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="img-stack-container me-2">
                                                @foreach($sale->orderItems->take(3) as $index => $item)
                                                    @php $photo = $item->part->photos->first(); @endphp
                                                    @if($photo)
                                                        <img src="{{ asset('storage/' . $photo->file_path) }}" 
                                                             class="stack-img shadow-sm" 
                                                             style="left: {{ $index * 12 }}px; z-index: {{ 10 - $index }};">
                                                    @else
                                                        <div class="stack-img bg-light d-flex align-items-center justify-content-center" 
                                                             style="left: {{ $index * 12 }}px; z-index: {{ 10 - $index }}; border: 1px solid #ddd;">
                                                            <i class="fa fa-wrench text-muted" style="font-size: 10px;"></i>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            @if($sale->orderItems->count() > 3)
                                                <span class="small text-muted">+{{ $sale->orderItems->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="fw-bold text-dark">{{ number_format($sale->total_amount) }} RWF</td>
                                    <td>
                                        <span class="small text-uppercase fw-bold text-muted">{{ $sale->payment->method ?? 'Unpaid' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill 
                                            {{ $sale->status == 'delivered' ? 'bg-soft-success text-success' : 'bg-light text-dark border' }}" 
                                            style="font-size: 0.65rem;">
                                            {{ strtoupper($sale->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('shop.sales.invoice', $sale->id) }}" class="btn btn-sm btn-white border shadow-sm" title="Print Invoice">
                                            <i class="fas fa-print text-muted"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-receipt fa-3x text-light mb-3"></i>
                                        <p class="text-muted">No sales found for this period.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</x-shop-dashboard>