<div class="container-fluid px-xl-5 py-4">
    <style>
        .bg-primary-light { background-color: rgba(0, 123, 255, 0.08); }
        .tiny-icon { font-size: 0.6rem; }
        .part-card { border-radius: 15px; transition: all 0.3s ease; }
        .part-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
        .font-family-mono { font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace; }
        .category-item { border-radius: 8px !important; margin-bottom: 2px; border: none !important; }
        .category-item.active { background-color: #007bff; color: white !important; }
        .category-item.active .badge { background-color: rgba(255,255,255,0.2); color: white; border: none; }
    </style>

    <div class="row">
        <div class="col-lg-3">
            <div class="sticky-top" style="top: 20px; z-index: 10;">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-3">
                        <h6 class="font-weight-bold mb-3 text-dark px-2">Part Categories</h6>
                        <div class="list-group list-group-flush">
                            <a href="#" wire:click.prevent="$set('category_id', null)" 
                               class="list-group-item list-group-item-action category-item d-flex justify-content-between align-items-center {{ is_null($category_id) ? 'active' : '' }}">
                                <span><i class="fa fa-th-large mr-2 small"></i> All Parts</span>
                                <i class="fa fa-chevron-right tiny-icon"></i>
                            </a>
                            @foreach($categories as $cat)
                                <a href="#" wire:click.prevent="$set('category_id', {{ $cat->id }})" 
                                   class="list-group-item list-group-item-action category-item d-flex justify-content-between align-items-center {{ $category_id == $cat->id ? 'active' : '' }}">
                                    <span class="text-truncate mr-2">{{ $cat->name }}</span>
                                    <span class="badge badge-pill badge-light border small">{{ $cat->parts_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-dark mb-3">Filter Status</h6>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="inStock" wire:model.live="inStockOnly">
                            <label class="custom-control-label small font-weight-bold" for="inStock">In Stock Only</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm mb-4 bg-dark text-white overflow-hidden" style="border-radius: 15px;">
                <div class="card-body p-4 position-relative" style="z-index: 2;">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <span class="badge badge-primary mb-2 px-3 text-uppercase" style="letter-spacing: 1px;">Technical Specs</span>
                            <h3 class="font-weight-bold mb-1">{{ $specification->model_code }}</h3>
                            <p class="text-white-50 mb-0 small">Chassis: <span class="text-white font-weight-bold font-family-mono">{{ $specification->chassis_code }}</span></p>
                        </div>
                        <div class="col-md-5 mt-3 mt-md-0">
                            <div class="input-group bg-white rounded-pill overflow-hidden px-2 py-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-0"><i class="fa fa-search text-muted"></i></span>
                                </div>
                                <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-0 shadow-none small" placeholder="Search name or OEM...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @forelse($parts as $part)
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm part-card">
                            <div class="position-relative bg-light" style="height: 180px; border-radius: 15px 15px 0 0;">
                                @if($part->photo)
                                    <img src="{{ asset('storage/' . $part->photo) }}" class="w-100 h-100" style="object-fit: cover; border-radius: 15px 15px 0 0;">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted border-bottom">
                                        <i class="fa fa-image fa-3x opacity-25"></i>
                                    </div>
                                @endif
                                <div class="position-absolute p-2" style="top: 0; right: 0;">
                                    <span class="badge {{ $part->stock > 0 ? 'badge-success' : 'badge-danger' }} px-2 py-1 shadow-sm">
                                        {{ $part->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-body p-3 d-flex flex-column">
                                <small class="text-primary font-weight-bold text-uppercase mb-1" style="font-size: 0.65rem;">{{ $part->category->name ?? 'Uncategorized' }}</small>
                                <h6 class="font-weight-bold text-dark mb-1">{{ $part->name }}</h6>
                                <p class="text-muted small mb-3">OEM: <code class="text-dark font-weight-bold">{{ $part->part_number ?? 'N/A' }}</code></p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                    <span class="h6 mb-0 font-weight-bold text-dark">${{ number_format($part->price, 2) }}</span>
                                    <button class="btn btn-sm btn-outline-primary px-3 rounded-pill font-weight-bold">Details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fa fa-search fa-3x text-muted opacity-25 mb-3"></i>
                        <h5 class="text-muted">No parts found matching your selection.</h5>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $parts->links() }}
            </div>
        </div>
    </div>
</div>