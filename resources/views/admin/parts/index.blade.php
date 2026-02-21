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
    .part-row { transition: all 0.2s; }
    .part-row:hover { background-color: #fcfdfe !important; }
    
    /* Custom Badges */
    .badge-soft-success { background: #e6fffa; color: #38b2ac; border: 1px solid #b2f5ea; }
    .badge-soft-danger { background: #fff5f5; color: #e53e3e; border: 1px solid #feb2b2; }
    .badge-soft-warning { background: #fffaf0; color: #dd6b20; border: 1px solid #fbd38d; }
    .badge-soft-info { background: #ebf8ff; color: #3182ce; border: 1px solid #bee3f8; }

    .sku-copy { cursor: pointer; border-style: dashed !important; transition: 0.2s; }
    .sku-copy:hover { background: #f1f5f9; color: #3b82f6; border-color: #3b82f6 !important; }
    
    [x-cloak] { display: none !important; }
</style>
@endpush

<div class="container-fluid py-4" 
     x-data="{ 
        search: '',
        copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            alert('SKU Copied: ' + text);
        },
        {{-- Logic to check if row matches search --}}
        filterRow(el) {
            if (this.search === '') return true;
            return el.innerText.toLowerCase().includes(this.search.toLowerCase());
        }
     }">
    
    {{-- Header & Stats --}}
    <div class="row mb-4 align-items-end">
        <div class="col-md-6">
            <h2 class="fw-bold mb-0">Spare Parts Inventory</h2>
            <p class="text-muted small mb-0">Manage catalog, stock levels, and part substitutions.</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.spare-parts.create') }}" class="btn btn-primary px-4 shadow-sm">
                <i class="fa fa-plus-circle mr-2"></i>New Part
            </a>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="bg-primary-subtle p-3 rounded mr-3"><i class="fas fa-boxes text-primary"></i></div>
                    <div><small class="text-muted d-block text-uppercase">Total</small><span class="h5 mb-0 font-weight-bold">{{ $parts->total() }}</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        {{-- Real-time Search Bar --}}
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="input-group border rounded-pill px-3 py-1 bg-light">
                        <div class="input-group-prepend border-0">
                            <span class="btn btn-transparent p-0 text-muted"><i class="fa fa-search"></i></span>
                        </div>
                        <input type="text" 
                               x-model="search" 
                               class="form-control border-0 bg-transparent shadow-none" 
                               placeholder="Real-time filter by Name, SKU, Brand...">
                    </div>
                </div>
                <div class="col-md-7 text-right">
                    <span class="text-muted small" x-show="search.length > 0">
                        Filtering active... <a href="#" @click.prevent="search = ''" class="text-primary ml-2">Clear</a>
                    </span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light-subtle">
                    <tr>
                        <th class="pl-4">Item & SKU</th>
                        <th>Substitutes</th>
                        <th>Compatibility</th>
                        <th>Inventory</th>
                        <th>Status</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parts as $part)
                    <tr class="part-row" x-show="filterRow($el)" x-transition.opacity>
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
                                        <span class="badge badge-light border sku-copy text-muted mr-2" 
                                              @click="copyToClipboard('{{ $part->sku }}')"
                                              title="Click to copy SKU">
                                              <i class="far fa-copy mr-1 small"></i>{{ $part->sku }}
                                        </span>
                                        <small class="text-muted border-left pl-2">PN: {{ $part->part_number }}</small>
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        {{-- SUBSTITUTES COLUMN --}}
                        <td>
                            @if($part->substitutions && $part->substitutions->count() > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($part->substitutions->take(2) as $sub)
                                        <span class="badge badge-soft-info mb-1 mr-1" title="{{ $sub->part_name }}">
                                            <i class="fas fa-exchange-alt mr-1 small"></i> {{ $sub->partBrand->name ?? 'Alt' }}
                                        </span>
                                    @endforeach
                                    @if($part->substitutions->count() > 2)
                                        <span class="text-muted small mt-1">+{{ $part->substitutions->count() - 2 }} more</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted small italic">No alternatives</span>
                            @endif
                        </td>

                        <td>
                            <div class="small text-dark fw-500">{{ $part->category->category_name ?? 'General' }}</div>
                            <div class="small text-muted">{{ $part->partBrand->name ?? 'Unbranded' }}</div>
                        </td>

                        <td>
                            <div class="font-weight-bold text-dark mb-0">{{ number_format($part->price, 0) }} RWF</div>
                            @php
                                $stockClass = $part->stock_quantity <= 0 ? 'badge-soft-danger' : ($part->stock_quantity < 10 ? 'badge-soft-warning' : 'badge-soft-success');
                            @endphp
                            <span class="badge {{ $stockClass }} small">
                                {{ $part->stock_quantity }} in stock
                            </span>
                        </td>

                        <td>
                            @if($part->status)
                                <span class="text-success small font-weight-bold"><i class="fas fa-check-circle"></i> Active</span>
                            @else
                                <span class="text-muted small font-weight-bold"><i class="fas fa-eye-slash"></i> Draft</span>
                            @endif
                        </td>

                        <td class="text-right pr-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border dropdown-toggle no-caret" data-toggle="dropdown">
                                    <i class="fas fa-ellipsis-h text-muted"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                    <a class="dropdown-item" href="{{ route('admin.spare-parts.edit', $part->id) }}">
                                        <i class="fas fa-pencil-alt mr-2 text-warning"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.spare-parts.destroy', $part->id) }}" method="POST" onsubmit="return confirm('Archive this part?');">
                                        @csrf @method('DELETE')
                                        <button class="dropdown-item text-danger"><i class="fas fa-trash-alt mr-2"></i> Delete</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <p class="text-muted">No records found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="card-footer bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col-sm-6 text-muted small" x-show="search === ''">
                    Showing <b>{{ $parts->firstItem() }}</b> to <b>{{ $parts->lastItem() }}</b> of {{ $parts->total() }} results
                </div>
                <div class="col-sm-6 text-muted small" x-show="search !== ''" x-cloak>
                    Filtering local results...
                </div>
                <div class="col-sm-6 d-flex justify-content-end" x-show="search === ''">
                    {{ $parts->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection