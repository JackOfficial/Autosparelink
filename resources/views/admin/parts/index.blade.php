@extends('admin.layouts.app')
@section('title', 'Spare Parts')

@section('content')

@push('styles')
<style>
    /* Professional photo stack styling */
    .photo-stack { position: relative; width: 80px; height: 50px; cursor: pointer; }
    .stack-img {
        position: absolute; width: 45px; height: 45px;
        object-fit: cover; border-radius: 8px;
        border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        transition: transform 0.2s;
    }
    .stack-img:hover { transform: scale(1.1); z-index: 50 !important; }
    .stack-more {
        position: absolute; right: 5px; bottom: 0;
        background: #343a40; color: #fff; font-size: 10px;
        padding: 2px 5px; border-radius: 10px; font-weight: bold; z-index: 11;
    }
    /* Subtle low stock warning */
    .stock-warning { background-color: #fff3cd !important; border-left: 4px solid #ffc107; }
    .stock-danger { background-color: #f8d7da !important; border-left: 4px solid #dc3545; }
        /* Status Dots */
    .dot { height: 8px; width: 8px; border-radius: 50%; display: inline-block; }
    .bg-info-subtle { background-color: #e1f5fe; color: #01579b !important; border: 1px solid #b3e5fc; }
    .btn-white { background: #fff; }
    .btn-white:hover { background: #f8f9fa; }
    .text-monospace { font-family: 'Courier New', Courier, monospace; }

    [x-cloak] { display: none !important; }
</style>
@endpush

<section class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark fw-bold">Spare Parts <span class="badge bg-secondary ms-2">{{ $parts->total() }}</span></h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.spare-parts.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fa fa-plus-circle mr-1"></i> Add Spare Part
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content" x-data="{ 
    search: '',
    checkMatch(el) {
        return this.search === '' || el.innerText.toLowerCase().includes(this.search.toLowerCase())
    }
}">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        {{-- ALPINE SEARCH BAR --}}
        <div class="card-header bg-white py-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="fa fa-search text-muted"></i></span>
                        </div>
                        <input type="text" x-model="search" 
                               class="form-control border-left-0 bg-light" 
                               placeholder="Search SKU, Part No, or Name...">
                    </div>
                </div>
                <div class="col-md-8 text-right">
                     <span class="text-muted small">Showing {{ $parts->firstItem() }} to {{ $parts->lastItem() }} of {{ $parts->total() }}</span>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small fw-bold">
                        <tr>
                            <th class="pl-3">SKU & Photo</th>
                            <th>Part Identity</th>
                            <th>Brand & Category</th>
                            <th>Compatibility</th>
                            <th>Stock & Price</th>
                            <th>Status</th>
                            <th class="text-right pr-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($parts as $part)
                        <tr class="part-row" 
                            x-show="checkMatch($el)" 
                            class="{{ $part->stock_quantity <= 0 ? 'stock-danger' : ($part->stock_quantity < 5 ? 'stock-warning' : '') }}">
                            
                            <td class="pl-3">
                                <div class="d-flex align-items-center">
                                    <div class="photo-stack mr-3">
                                        @forelse($part->photos->take(3) as $index => $photo)
                                            <img src="{{ asset('storage/' . $photo->file_path) }}"
                                                 class="stack-img"
                                                 style="left: {{ $index * 12 }}px; z-index: {{ 10 - $index }};">
                                        @empty
                                            <div class="bg-light rounded border d-flex align-items-center justify-content-center" style="width:45px; height:45px">
                                                <i class="fa fa-image text-muted"></i>
                                            </div>
                                        @endforelse
                                        @if($part->photos->count() > 3)
                                            <span class="stack-more">+{{ $part->photos->count() - 3 }}</span>
                                        @endif
                                    </div>
                                    <span class="badge badge-light border text-monospace">{{ $part->sku }}</span>
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold text-primary">{{ $part->part_name }}</div>
                                <div class="small text-muted">No: <span class="text-dark">{{ $part->part_number }}</span></div>
                                <div class="small text-muted">OEM: <span class="text-dark">{{ $part->oem_number ?? '-' }}</span></div>
                            </td>

                            <td>
                                <div class="badge badge-info-ping bg-info-subtle text-info px-2 py-1 small rounded">{{ $part->category->category_name ?? 'Uncategorized' }}</div>
                                <div class="mt-1 small fw-bold text-secondary">
                                    {{ $part->partBrand->name ?? '-' }}
                                    <span class="text-muted fw-normal">({{ $part->partBrand->type ?? 'N/A' }})</span>
                                </div>
                            </td>

                            <td>
                                @if($part->fitments->count())
                                    <div class="small text-truncate" style="max-width: 150px;">
                                        @foreach($part->fitments->take(2) as $fitment)
                                            <span class="d-block">• {{ $fitment->vehicleModel->brand->brand_name }} {{ $fitment->vehicleModel->model_name }}</span>
                                        @endforeach
                                    </div>
                                    @if($part->fitments->count() > 2)
                                        <span class="badge badge-secondary py-0 small">+{{ $part->fitments->count() - 2 }} more</span>
                                    @endif
                                @else
                                    <span class="text-muted small">Generic / All</span>
                                @endif
                            </td>

                            <td>
                                <div class="fw-bold">{{ number_format($part->price, 0) }} RWF</div>
                                <div class="mt-1">
                                    <span class="badge {{ $part->stock_quantity < 5 ? 'badge-danger' : 'badge-success' }}">
                                        {{ $part->stock_quantity }} in stock
                                    </span>
                                </div>
                            </td>

                            <td>
                                <span class="dot {{ $part->status ? 'bg-success' : 'bg-danger' }} mr-1"></span>
                                <span class="small">{{ $part->status ? 'Active' : 'Hidden' }}</span>
                            </td>

                            <td class="text-right pr-3">
                                <div class="btn-group shadow-sm border rounded">
                                    <a href="{{ route('admin.spare-parts.edit', $part->id) }}" class="btn btn-white btn-sm" title="Edit">
                                        <i class="fas fa-pencil-alt text-warning"></i>
                                    </a>
                                    <form action="{{ route('admin.spare-parts.destroy', $part->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this part?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-white btn-sm border-left" title="Delete">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top-0">
            <div class="d-flex justify-content-between align-items-center">
                <p class="text-muted small mb-0">Note: Use the search box above to filter results instantly on this page.</p>
                {{ $parts->links() }}
            </div>
        </div>
    </div>
</section>
@endsection