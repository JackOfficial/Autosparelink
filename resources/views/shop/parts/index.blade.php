<x-shop-dashboard>
    <x-slot:title>My Inventory</x-slot:title>

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
    
    .table thead th { border-top: none; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px; color: #8898aa; }
    .part-row { transition: opacity 0.2s ease-in-out; }
    .part-row:hover { background-color: #fcfdfe !important; }
    
    .badge-soft-success { background: #e6fffa; color: #38b2ac; border: 1px solid #b2f5ea; }
    .badge-soft-danger { background: #fff5f5; color: #e53e3e; border: 1px solid #feb2b2; }
    .badge-soft-warning { background: #fffaf0; color: #dd6b20; border: 1px solid #fbd38d; }

    .sku-copy { cursor: pointer; border-style: dashed !important; transition: 0.2s; font-family: monospace; }
    .sku-copy:hover { background: #f1f5f9; color: #3b82f6; border-color: #3b82f6 !important; }

    /* Compatibility pill styling */
    .compat-pill { font-size: 0.7rem; padding: 2px 8px; border-radius: 4px; background: #f8f9fe; color: #525f7f; border: 1px solid #e9ecef; margin-right: 4px; margin-bottom: 4px; display: inline-block; font-weight: 500; }
    .sub-item { font-size: 0.75rem; color: #4c51bf; background: #ebf4ff; padding: 1px 6px; border-radius: 3px; display: block; margin-top: 2px; width: fit-content; border: 1px solid #c3dafe; }
    
    [x-cloak] { display: none !important; }
</style>
@endpush

<div class="container-fluid py-4" 
     x-data="{ 
        search: '', 
        filterType: 'all',
        copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            // Optional: Add a 'Copied!' toast notification here
        },
        shouldShow(el, stock, status) {
            const searchTerm = this.search.toLowerCase();
            const textContent = el.innerText.toLowerCase();
            const matchesSearch = searchTerm === '' || textContent.includes(searchTerm);
            
            let matchesFilter = true;
            if (this.filterType === 'active') matchesFilter = status === 1;
            if (this.filterType === 'out_of_stock') matchesFilter = stock <= 0;
            
            return matchesSearch && matchesFilter;
        }
     }">
    
    {{-- Header --}}
    <div class="row mb-4 align-items-end">
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-1">
                    <li class="breadcrumb-item"><a href="#" class="text-muted">Shop</a></li>
                    <li class="breadcrumb-item active text-primary">My Parts</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0">Parts Inventory</h2>
        </div>
        <div class="col-md-6 text-md-right mt-3 mt-md-0">
            <a href="{{ route('shop.parts.create') }}" class="btn btn-primary px-4 shadow-sm">
                <i class="fa fa-plus-circle mr-2"></i>Add New Part
            </a>
        </div>
    </div>

    {{-- Shop Specific Quick Stats --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="bg-primary-subtle p-3 rounded mr-3 text-primary"><i class="fas fa-boxes"></i></div>
                    <div>
                        <small class="text-muted d-block text-uppercase">Total Listed</small>
                        <span class="h5 mb-0 font-weight-bold">{{ $parts->total() }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="bg-danger-subtle p-3 rounded mr-3 text-danger"><i class="fas fa-exclamation-triangle"></i></div>
                    <div>
                        <small class="text-muted d-block text-uppercase">Out of Stock</small>
                        <span class="h5 mb-0 font-weight-bold">{{ $parts->where('stock_quantity', '<=', 0)->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="bg-success-subtle p-3 rounded mr-3 text-success"><i class="fas fa-eye"></i></div>
                    <div>
                        <small class="text-muted d-block text-uppercase">Active on Market</small>
                        <span class="h5 mb-0 font-weight-bold">{{ $parts->where('status', 1)->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        {{-- Search and Filter Bar --}}
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="input-group border rounded-pill px-3 py-1 bg-light">
                        <input type="text" x-model="search" class="form-control border-0 bg-transparent shadow-none" placeholder="Search my parts, SKU, or PN...">
                        <div class="input-group-append">
                            <span class="btn btn-transparent p-0 text-muted d-flex align-items-center">
                                <i class="fa fa-search" x-show="search === ''"></i>
                                <i class="fa fa-times" x-show="search !== ''" @click="search = ''" style="cursor:pointer" x-cloak></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 text-right">
                    <div class="btn-group btn-group-toggle shadow-none">
                        <label class="btn btn-light btn-sm" :class="filterType === 'all' && 'active shadow-sm'">
                            <input type="radio" x-model="filterType" value="all"> All
                        </label>
                        <label class="btn btn-light btn-sm" :class="filterType === 'active' && 'active shadow-sm'">
                            <input type="radio" x-model="filterType" value="active"> Published
                        </label>
                        <label class="btn btn-light btn-sm" :class="filterType === 'out_of_stock' && 'active shadow-sm'">
                            <input type="radio" x-model="filterType" value="out_of_stock"> Low/No Stock
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light-subtle">
                    <tr>
                        <th class="pl-4">Item Details</th>
                        <th>Compatibility</th>
                        <th>Stock Level</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parts as $part)
                    <tr class="part-row" 
                        x-show="shouldShow($el, {{ $part->stock_quantity }}, {{ $part->status ? 1 : 0 }})"
                        x-transition:enter.duration.300ms>
                        <td class="pl-4">
                            <div class="d-flex align-items-center">
                                <div class="photo-stack mr-4">
                                    @forelse($part->photos->take(3) as $index => $photo)
                                        <img src="{{ asset('storage/' . $photo->file_path) }}" class="stack-img shadow-sm" style="left: {{ $index * 10 }}px; z-index: {{ 10 - $index }};">
                                    @empty
                                        <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="width:40px; height:40px"><i class="fa fa-image small"></i></div>
                                    @endforelse
                                </div>
                                <div>
                                    <div class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $part->part_name }}</div>
                                    <div class="d-flex align-items-center mt-1">
                                        <span class="badge badge-light border sku-copy text-muted mr-2" @click="copyToClipboard('{{ $part->sku }}')" title="Click to copy SKU">
                                            {{ $part->sku }}
                                        </span>
                                        <small class="text-muted border-left pl-2">PN: {{ $part->part_number }}</small>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small text-dark fw-500 mb-1">{{ $part->category->category_name ?? 'General' }}</div>
                            <div class="d-flex flex-wrap" style="max-width: 250px;">
                                @forelse($part->fitments->take(2) as $fitment)
                                    <span class="compat-pill">
                                        {{ $fitment->vehicleModel->brand->name ?? '' }} {{ $fitment->vehicleModel->name ?? '' }}
                                    </span>
                                @empty
                                    <span class="text-muted small">Universal Fit</span>
                                @endforelse
                                @if($part->fitments->count() > 2)
                                    <span class="text-primary small mt-1">+{{ $part->fitments->count() - 2 }} more</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @php
                                $stockClass = $part->stock_quantity <= 0 ? 'badge-soft-danger' : ($part->stock_quantity < 5 ? 'badge-soft-warning' : 'badge-soft-success');
                            @endphp
                            <span class="badge {{ $stockClass }} px-2 py-1">
                                {{ $part->stock_quantity }} units
                            </span>
                        </td>
                        <td>
                            <div class="font-weight-bold text-dark">{{ number_format($part->price, 0) }} RWF</div>
                            <small class="text-muted">{{ $part->partBrand->name ?? 'Generic' }}</small>
                        </td>
                        <td>
                            @if($part->status)
                                <span class="text-success small font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Live</span>
                            @else
                                <span class="text-muted small font-weight-bold"><i class="fas fa-eye-slash mr-1"></i> Hidden</span>
                            @endif
                        </td>
                        <td class="text-right pr-4">
                            <div class="btn-group shadow-none">
                                <a href="{{ route('shop.parts.edit', $part->id) }}" class="btn btn-sm btn-outline-light border text-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('shop.parts.destroy', $part->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this part from your inventory?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-light border text-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
    <td colspan="6" class="text-center py-5">
        <div class="mb-3">
            <i class="fas fa-box-open fa-3x text-light"></i>
        </div>
        <p class="text-muted">Your inventory is empty.</p>
        <a href="{{ route('shop.parts.create') }}" class="btn btn-sm btn-primary">
            Add Your First Part
        </a>
    </td>
</tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="card-footer bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col-sm-6 text-muted small">
                    <span x-show="search === '' && filterType === 'all'">
                        Showing {{ $parts->count() }} of your total parts.
                    </span>
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    {{ $parts->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
</x-shop-dashboard>