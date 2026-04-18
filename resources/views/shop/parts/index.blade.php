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
    
    /* Table styling - BS5 specific refinements */
    .table thead th { border-top: none; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px; color: #8898aa; background-color: #f8f9fe; border-bottom: 1px solid #e9ecef; }
    .part-row { transition: background-color 0.2s ease-in-out; }
    .part-row:hover { background-color: #fcfdfe !important; }
    
    /* Keep these for custom branding color control */
    .badge-soft-success { background: #e6fffa; color: #38b2ac; border: 1px solid #b2f5ea; }
    .badge-soft-danger { background: #fff5f5; color: #e53e3e; border: 1px solid #feb2b2; }
    .badge-soft-warning { background: #fffaf0; color: #dd6b20; border: 1px solid #fbd38d; }

    .sku-copy { cursor: pointer; border-style: dashed !important; transition: 0.2s; font-family: monospace; font-size: 0.75rem; }
    .sku-copy:hover { background: #f1f5f9; color: #0d6efd; border-color: #0d6efd !important; }
    .sku-copied { background: #198754 !important; color: white !important; border-color: #198754 !important; }

    .compat-pill { font-size: 0.7rem; padding: 2px 8px; border-radius: 4px; background: #f8f9fe; color: #525f7f; border: 1px solid #e9ecef; margin-inline-end: 4px; margin-bottom: 4px; display: inline-block; font-weight: 500; }
    
    /* State Badge Styling */
    .badge-state {
        font-size: 0.6rem;
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    
    [x-cloak] { display: none !important; }
</style>
@endpush

<div class="container-fluid py-4" 
     x-data="{ 
        search: '{{ request('search') }}', 
        filterType: 'all',
        filterState: 'all',
        copiedSku: null,
        copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            this.copiedSku = text;
            setTimeout(() => { this.copiedSku = null }, 2000);
        },
        submitSearch() {
            let url = new URL(window.location.href);
            url.searchParams.set('search', this.search);
            window.location.href = url.href;
        }
     }">
    
    {{-- Header --}}
    <div class="row mb-4 align-items-end">
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-1">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Shop</a></li>
                    <li class="breadcrumb-item active text-primary">Inventory</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0">Parts Inventory</h2>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('shop.parts.create') }}" class="btn btn-primary px-4 shadow-sm">
                <i class="fa fa-plus-circle me-2"></i>Add New Part
            </a>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="bg-primary-subtle p-3 rounded me-3 text-primary"><i class="fas fa-boxes"></i></div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Total Items</small>
                        <span class="h5 mb-0 fw-bold">{{ $parts->total() }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="bg-danger-subtle p-3 rounded me-3 text-danger"><i class="fas fa-exclamation-triangle"></i></div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Out of Stock</small>
                        <span class="h5 mb-0 fw-bold text-danger">{{ $parts->where('stock_quantity', '<=', 0)->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="bg-success-subtle p-3 rounded me-3 text-success"><i class="fas fa-globe"></i></div>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Live Listings</small>
                        <span class="h5 mb-0 fw-bold text-success">{{ $parts->where('status', 1)->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        {{-- Search and Filter Bar --}}
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <form @submit.prevent="submitSearch">
                        <div class="input-group border rounded-pill px-3 py-1 bg-light">
                            <input type="text" x-model="search" class="form-control border-0 bg-transparent shadow-none" placeholder="Press Enter to search...">
                            <button type="submit" class="btn btn-transparent p-0 text-muted">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-8 text-md-end mt-3 mt-md-0">
                    {{-- Status Filters --}}
                    <div class="btn-group shadow-none me-2">
                        <button class="btn btn-light btn-sm px-3 border" :class="filterType === 'all' && 'active border-primary'" @click="filterType = 'all'">All</button>
                        <button class="btn btn-light btn-sm px-3 border" :class="filterType === 'active' && 'active border-primary'" @click="filterType = 'active'">Live</button>
                        <button class="btn btn-light btn-sm px-3 border" :class="filterType === 'low' && 'active border-primary'" @click="filterType = 'low'">Low Stock</button>
                    </div>
                    {{-- State Filters --}}
                    <div class="btn-group shadow-none">
                        <button class="btn btn-outline-light btn-sm text-dark border" :class="filterState === 'all' && 'bg-white border-primary'" @click="filterState = 'all'">All Conditions</button>
                        <button class="btn btn-outline-light btn-sm text-dark border" :class="filterState === 'new' && 'bg-white border-primary'" @click="filterState = 'new'">New</button>
                        <button class="btn btn-outline-light btn-sm text-dark border" :class="filterState === 'used' && 'bg-white border-primary'" @click="filterState = 'used'">Used</button>
                        <button class="btn btn-outline-light btn-sm text-dark border" :class="filterState === 'refurbished' && 'bg-white border-primary'" @click="filterState = 'refurbished'">Restored</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Part Info</th>
                        <th>Compatibility</th>
                        <th>Inventory</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Control</th>
                    </tr>
                </thead>
               <tbody>
    @forelse($parts as $part)
    <tr class="part-row" 
        x-show="((filterType === 'all') || 
                (filterType === 'active' && {{ $part->status ? 'true' : 'false' }}) || 
                (filterType === 'low' && {{ $part->stock_quantity }} < 5)) && 
                (filterState === 'all' || filterState === '{{ $part->state->slug ?? '' }}')"
        x-transition>
        
        <td class="ps-4">
            <div class="d-flex align-items-center">
                <div class="photo-stack me-4">
                    @forelse($part->photos->take(3) as $index => $photo)
                        <img src="{{ asset('storage/' . $photo->file_path) }}" 
                             class="stack-img" 
                             style="left: {{ $index * 10 }}px; z-index: {{ 10 - $index }};"
                             alt="Part image">
                    @empty
                        <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="width:40px; height:40px">
                            <i class="fa fa-image small"></i>
                        </div>
                    @endforelse
                </div>
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <div class="fw-bold text-dark me-2">{{ $part->part_name }}</div>
                        @if($part->state)
                            <span class="badge-state text-white shadow-sm" 
                                  style="background-color: {{ $part->state->color_code ?? '#6c757d' }};">
                                {{ $part->state->name }}
                            </span>
                        @endif
                    </div>
                    <div class="mt-1">
                        <span class="badge bg-light text-dark border sku-copy me-2" 
                              @click="copyToClipboard('{{ $part->sku }}')"
                              :class="copiedSku === '{{ $part->sku }}' && 'sku-copied'">
                            <span x-text="copiedSku === '{{ $part->sku }}' ? 'Copied!' : '{{ $part->sku }}'"></span>
                        </span>
                        <div><small class="text-muted border-start ps-2">PN: {{ $part->part_number }}</small></div>
                    </div>
                </div>
            </div>
        </td>

        <td>
            <div class="small text-dark fw-bold mb-1">{{ $part->category->category_name ?? 'Spare Part' }}</div>
            <div class="d-flex flex-wrap" style="max-width: 220px;">
                @forelse($part->fitments->take(2) as $fitment)
                    <span class="compat-pill">
                        {{ $fitment->specification->variant->name ?? '' }} {{ $fitment->vehicleModel->name ?? '' }}
                    </span>
                @empty
                    <span class="text-muted small fst-italic">Universal</span>
                @endforelse
                
                @if($part->fitments->count() > 2)
                    <span class="text-primary small mt-1">+{{ $part->fitments->count() - 2 }}</span>
                @endif
            </div>
        </td>

        <td>
            @php
                $stockClass = $part->stock_quantity <= 0 ? 'badge-soft-danger' : ($part->stock_quantity < 5 ? 'badge-soft-warning' : 'badge-soft-success');
            @endphp
            <div class="d-flex flex-column">
                <span class="badge {{ $stockClass }} text-dark px-2 py-1 mb-1">
                    {{ $part->stock_quantity }} units
                </span>
                <small class="text-muted text-center" style="font-size: 0.7rem;">{{ $part->partBrand->name ?? 'Genuine' }}</small>
            </div>
        </td>

        <td>
            <div class="fw-bold text-dark">{{ number_format($part->price, 0) }} RWF</div>
            <div class="mt-1">
                @forelse($part->substitutions->take(3) as $sub)
                    <span class="badge bg-light text-muted border-0 p-0 me-1" style="font-size: 0.7rem;" title="Alternative Part SKU">
                        <i class="fas fa-sync-alt me-1" style="font-size: 0.6rem;"></i>{{ $sub->sku }}
                    </span>
                @empty
                    <small class="text-muted fst-italic" style="font-size: 0.7rem;">No alternatives</small>
                @endforelse
            </div>
        </td>

        <td>
            @if($part->status)
                <span class="badge rounded-pill bg-success" style="font-size: 0.6rem;">LIVE</span>
            @else
                <span class="badge rounded-pill bg-secondary" style="font-size: 0.6rem;">HIDDEN</span>
            @endif
        </td>

        <td class="text-end pe-4">
            <div class="btn-group shadow-none">
                <a href="{{ route('shop.parts.edit', $part->id) }}" class="btn btn-sm btn-outline-warning border-0" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('shop.parts.destroy', $part->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this part?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger border-0">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="text-center py-5">
            <div class="mb-3 text-muted opacity-50"><i class="fas fa-box-open fa-4x"></i></div>
            <h5 class="text-muted">No parts found</h5>
            <a href="{{ route('shop.parts.create') }}" class="btn btn-primary btn-sm mt-2">Add New Part</a>
        </td>
    </tr>
    @endforelse
</tbody>
            </table>
        </div>
        
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing <strong>{{ $parts->firstItem() ?? 0 }}</strong> to <strong>{{ $parts->lastItem() ?? 0 }}</strong> of {{ $parts->total() }} parts
                </div>
                <div>
                    {{ $parts->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
</x-shop-dashboard>