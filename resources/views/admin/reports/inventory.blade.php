@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="font-weight-bold mb-0">Inventory Status</h3>
        
        <a href="{{ route('admin.reports.inventory.pdf') }}" class="btn btn-outline-danger shadow-sm rounded-pill px-4">
            <i class="fa fa-file-pdf mr-2"></i> Export Inventory PDF
        </a>
    </div>

    <div class="row mb-4">
        @foreach([
            ['Title' => 'Total Catalog', 'Val' => $stats['total_items'], 'Color' => 'primary'],
            ['Title' => 'Active Parts', 'Val' => $stats['active_parts'], 'Color' => 'success'],
            ['Title' => 'Stock Quantity', 'Val' => $stats['total_stock'], 'Color' => 'info'],
            ['Title' => 'Inventory Value', 'Val' => '$'.number_format($stats['inventory_val'], 2), 'Color' => 'dark']
        ] as $stat)
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-xl">
                <div class="card-body">
                    <small class="text-muted font-weight-bold text-uppercase">{{ $stat['Title'] }}</small>
                    <h3 class="mb-0 font-weight-bold text-{{ $stat['Color'] }}">{{ $stat['Val'] }}</h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card border-0 shadow-sm rounded-xl">
                <div class="card-header bg-danger text-white border-0 py-3" style="border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0 font-weight-bold text-white">
                        <i class="fa fa-exclamation-triangle mr-2"></i> Low Stock Alerts
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 pl-4">Part Details</th>
                                    <th class="border-0">Category / Brand</th>
                                    <th class="border-0">In Stock</th>
                                    <th class="border-0 text-right pr-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockItems as $part)
                                <tr>
                                    <td class="pl-4">
                                        <div class="font-weight-bold text-dark">{{ $part->part_name }}</div>
                                        <small class="text-muted">Part #: {{ $part->part_number }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-light border">{{ $part->category->name ?? 'General' }}</span><br>
                                        <small>{{ $part->partBrand->name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="text-danger font-weight-bold">{{ $part->stock_quantity }} units left</span>
                                    </td>
                                    <td class="text-right pr-4">
                                        <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">Restock</a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">No low stock items detected.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if($outOfStockItems->count() > 0)
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-xl border-left-danger">
                <div class="card-body">
                    <h5 class="text-danger font-weight-bold mb-3">Out of Stock ({{ $outOfStockItems->count() }})</h5>
                    <div class="row">
                        @foreach($outOfStockItems as $item)
                        <div class="col-md-4 mb-2">
                            <div class="p-2 bg-light rounded d-flex justify-content-between border">
                                <span class="small text-dark font-weight-bold">{{ $item->part_name }}</span>
                                <span class="badge badge-danger d-flex align-items-center">0</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection