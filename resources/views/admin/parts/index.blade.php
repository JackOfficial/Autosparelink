@extends('admin.layouts.app')
@section('title', 'Spare Parts Inventory')

@section('content')
@push('styles')
<style>
    /* Professional photo stack styling */
    .photo-stack { position: relative; width: 60px; height: 45px; }
    .stack-img {
        position: absolute; width: 40px; height: 40px;
        object-fit: cover; border-radius: 6px;
        border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.2s ease-in-out;
    }
    .photo-stack:hover .stack-img { transform: translateX(10px) rotate(5deg); }
    
    /* Table Enhancements */
    .table thead th { border-top: none; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px; color: #8898aa; }
    .part-row { transition: background 0.15s; }
    .part-row:hover { background-color: #fcfdfe !important; }
    
    /* Custom Badges */
    .badge-soft-success { background: #e6fffa; color: #38b2ac; border: 1px solid #b2f5ea; }
    .badge-soft-danger { background: #fff5f5; color: #e53e3e; border: 1px solid #feb2b2; }
    .badge-soft-warning { background: #fffaf0; color: #dd6b20; border: 1px solid #fbd38d; }

    .sku-copy { cursor: pointer; border-style: dashed !important; }
    .sku-copy:hover { background: #f1f5f9; color: #3b82f6; }
</style>
@endpush

<div class="container-fluid py-4">
    {{-- Header & Stats --}}
    <div class="row mb-4 align-items-end">
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-1">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Inventory</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0">Spare Parts Management</h2>
        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-outline-secondary btn-sm mr-2"><i class="fas fa-file-export mr-1"></i> Export</button>
            <a href="{{ route('admin.spare-parts.create') }}" class="btn btn-primary px-4">
                <i class="fa fa-plus-circle mr-2"></i>New Part
            </a>
        </div>
    </div>

    {{-- Quick Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="bg-primary-subtle p-3 rounded mr-3"><i class="fas fa-boxes text-primary"></i></div>
                    <div><small class="text-muted d-block text-uppercase">Total Items</small><span class="h5 mb-0 font-weight-bold">{{ $parts->total() }}</span></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="bg-danger-subtle p-3 rounded mr-3"><i class="fas fa-exclamation-triangle text-danger"></i></div>
                    <div><small class="text-muted d-block text-uppercase">Out of Stock</small><span class="h5 mb-0 font-weight-bold">{{ \App\Models\Part::where('stock_quantity', '<=', 0)->count() }}</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-lg" x-data="{ search: '' }">
        <div class="card-header bg-white border-0 py-3">
            <form action="{{ route('admin.spare-parts.index') }}" method="GET" class="row align-items-center">
                <div class="col-md-5">
                    <div class="input-group border rounded-pill px-3 py-1">
                        <input type="text" name="search" class="form-control border-0 bg-transparent" placeholder="Search by name, SKU or number..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-transparent p-0 text-muted"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 text-right">
                    <div class="btn-group btn-group-toggle shadow-none" data-toggle="buttons">
                        <label class="btn btn-light btn-sm active"><input type="radio" name="filter" checked> All</label>
                        <label class="btn btn-light btn-sm"><input type="radio" name="filter"> Active</label>
                        <label class="btn btn-light btn-sm"><input type="radio" name="filter"> Out of Stock</label>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light-subtle">
                    <tr>
                        <th class="pl-4">Item Details</th>
                        <th>Compatibility</th>
                        <th>Inventory</th>
                        <th>Pricing</th>
                        <th>Status</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parts as $part)
                    <tr class="part-row">
                        <td class="pl-4">
                            <div class="d-flex align-items-center">
                                <div class="photo-stack mr-4">
                                    @forelse($part->photos->take(3) as $index => $photo)
                                        <img src="{{ asset('storage/' . $photo->file_path) }}" 
                                             class="stack-img shadow-sm" 
                                             style="left: {{ $index * 10 }}px; z-index: {{ 10 - $index }};">
                                    @empty
                                        <div class="bg-light rounded border d-flex align-items-center justify-content-center" style="width:40px; height:40px">
                                            <i class="fa fa-image text-muted small"></i>
                                        </div>
                                    @endforelse
                                </div>
                                <div>
                                    <div class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $part->part_name }}</div>
                                    <div class="d-flex align-items-center mt-1">
                                        <span class="badge badge-light border sku-copy text-muted mr-2" title="Click to copy SKU">{{ $part->sku }}</span>
                                        <small class="text-muted border-left pl-2">PN: {{ $part->part_number }}</small>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small text-dark fw-500">{{ $part->category->category_name ?? 'General' }}</div>
                            <div class="small text-muted">{{ $part->partBrand->name ?? 'Unbranded' }}</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @php
                                    $stockClass = $part->stock_quantity <= 0 ? 'badge-soft-danger' : ($part->stock_quantity < 10 ? 'badge-soft-warning' : 'badge-soft-success');
                                @endphp
                                <span class="badge {{ $stockClass }} px-2 py-1">
                                    {{ $part->stock_quantity }} units
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="font-weight-bold text-dark">{{ number_format($part->price, 0) }} RWF</div>
                            <small class="text-muted">Unit Price</small>
                        </td>
                        <td>
                            @if($part->status)
                                <span class="text-success small font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Published</span>
                            @else
                                <span class="text-muted small font-weight-bold"><i class="fas fa-eye-slash mr-1"></i> Draft</span>
                            @endif
                        </td>
                        <td class="text-right pr-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border dropdown-toggle no-caret px-2" data-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v text-muted"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow border-0 mt-2">
                                    <a class="dropdown-item" href="{{ route('admin.spare-parts.edit', $part->id) }}">
                                        <i class="fas fa-pencil-alt mr-2 text-warning"></i> Edit Details
                                    </a>
                                    <a class="dropdown-item" href="#"><i class="fas fa-eye mr-2 text-primary"></i> View Stock History</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('admin.spare-parts.destroy', $part->id) }}" method="POST" onsubmit="return confirm('Archive this part?');">
                                        @csrf @method('DELETE')
                                        <button class="dropdown-item text-danger"><i class="fas fa-trash-alt mr-2"></i> Delete Part</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <img src="https://illustrations.popsy.co/gray/box.svg" style="height: 120px;" class="mb-3">
                            <p class="text-muted">No spare parts found matching your criteria.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="card-footer bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col-sm-6 text-muted small">
                    Showing <b>{{ $parts->firstItem() }}</b> to <b>{{ $parts->lastItem() }}</b> of {{ $parts->total() }} results
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    {{ $parts->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection