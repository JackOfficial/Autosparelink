<div class="container-fluid px-xl-5 py-4">

    <style>
    .bg-primary-light { background-color: rgba(0, 123, 255, 0.08); }
    .tiny-icon { font-size: 0.6rem; }
    .part-card:hover { transform: translateY(-5px); }
    .font-family-mono { font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
</style>

    <div class="row">
        
        <div class="col-lg-3">
            <div class="sticky-top" style="top: 20px; z-index: 10;">
                
                {{-- Category Sidebar --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom">
                            <h6 class="font-weight-bold mb-0 text-dark">Part Categories</h6>
                        </div>
                        <div class="list-group list-group-flush small">
                            <a href="#" wire:click.prevent="$set('category_id', null)" 
                               class="list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center {{ is_null($category_id) ? 'bg-primary-light text-primary font-weight-bold' : '' }}">
                                All Categories <i class="fa fa-chevron-right tiny-icon"></i>
                            </a>
                            @foreach($categories as $cat)
                                <a href="#" wire:click.prevent="$set('category_id', {{ $cat->id }})" 
                                   class="list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center">
                                    {{ $cat->name }}
                                    <span class="badge badge-pill badge-light border">{{ $cat->parts_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-dark mb-3">Availability</h6>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="inStock" wire:model.live="inStockOnly">
                            <label class="custom-control-label small" for="inStock">In Stock Only</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            
            {{-- Technical Header --}}
            <div class="card border-0 shadow-sm mb-4 bg-dark text-white overflow-hidden" style="border-radius: 15px;">
                <div class="card-body p-4 position-relative" style="z-index: 2;">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <span class="badge badge-primary mb-2 px-3 text-uppercase">Configured Specs</span>
                            <h3 class="font-weight-bold mb-1">{{ $specification->model_code }}</h3>
                            <p class="text-white-50 mb-0 small">Chassis: <span class="text-white font-weight-bold">{{ $specification->chassis_code }}</span></p>
                        </div>
                        <div class="col-md-5 mt-3 mt-md-0">
                            <div class="input-group bg-white rounded-pill overflow-hidden px-2 py-1 shadow-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-0"><i class="fa fa-search text-muted"></i></span>
                                </div>
                                <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-0 shadow-none small" placeholder="Search by name, SKU or OEM...">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Decorative background element --}}
                <div class="position-absolute" style="right: -30px; top: -30px; opacity: 0.1;">
                    <i class="fa fa-tools fa-10x"></i>
                </div>
            </div>

            {{-- Parts Grid --}}
            <div class="row">
                @forelse($parts as $part)
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm part-card" style="border-radius: 15px; transition: transform 0.2s;">
                            {{-- Part Photo --}}
                            <div class="position-relative overflow-hidden bg-light" style="height: 180px; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                                @if($part->photo)
                                    <img src="{{ asset('storage/' . $part->photo) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $part->name }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                        <i class="fa fa-image fa-3x opacity-25"></i>
                                    </div>
                                @endif
                                <div class="position-absolute top-0 right-0 p-2">
                                    <span class="badge {{ $part->stock > 0 ? 'badge-success' : 'badge-danger' }} shadow-sm">
                                        {{ $part->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-body p-3">
                                <small class="text-primary font-weight-bold text-uppercase" style="font-size: 0.65rem;">{{ $part->category->name }}</small>
                                <h6 class="font-weight-bold text-dark text-truncate mb-1 mt-1">{{ $part->name }}</h6>
                                <p class="text-muted small mb-3">OEM: <code class="text-dark">{{ $part->part_number ?? 'N/A' }}</code></p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                    <span class="h6 mb-0 font-weight-bold text-dark">${{ number_format($part->price, 2) }}</span>
                                    <button class="btn btn-sm btn-outline-primary px-3 rounded-pill font-weight-bold">Details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" style="width: 80px; opacity: 0.3;" alt="empty">
                        <h5 class="text-muted mt-3">No parts matching these criteria.</h5>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $parts->links() }}
            </div>
        </div>
    </div>
</div>

