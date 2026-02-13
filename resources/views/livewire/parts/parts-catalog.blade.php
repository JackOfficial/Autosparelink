<div class="container-fluid py-4" x-data="{ grid: true }">

    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-lg-3 mb-4">

            <div class="card shadow-sm p-3 sticky-top" style="top:90px">

                <!-- SEARCH -->
                <h6 class="fw-bold mb-2">Search</h6>
                <input type="text" class="form-control mb-3" placeholder="Search part..." wire:model.debounce.500ms="search">

                <!-- VEHICLE FILTERS -->
                <h6 class="fw-bold mt-3 mb-2">Vehicle Compatibility</h6>

                <select class="form-select mb-2" wire:model.live="brand">
                    <option value="">Select Brand</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}">{{ $b->brand_name }}</option>
                    @endforeach
                </select>

                <select class="form-select mb-2" wire:model.live="model" @if(!$models) disabled @endif>
                    <option value="">Select Model</option>
                    @foreach($models as $m)
                        <option value="{{ $m->id }}">{{ $m->model_name }}</option>
                    @endforeach
                </select>

                <select class="form-select mb-2" wire:model.live="variant" @if(!$variants) disabled @endif>
                    <option value="">Select Variant</option>
                    @foreach($variants as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                </select>

                <input type="number" class="form-control mb-2" placeholder="Year" wire:model.lazy="year">

                <!-- CATEGORY -->
                <h6 class="fw-bold mt-3 mb-2">Category</h6>
                <select class="form-select mb-3" wire:model="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->category_name }} ({{ $cat->spare_parts_count }})</option>
                    @endforeach
                </select>

                <!-- OEM / AFTERMARKET -->
                <h6 class="fw-bold mt-3 mb-2">Manufacturer Type</h6>
                <select class="form-select mb-3" wire:model="oem">
                    <option value="">Any</option>
                    <option value="OEM">OEM</option>
                    <option value="Aftermarket">Aftermarket</option>
                </select>

                <!-- PRICE -->
                <h6 class="fw-bold mt-3 mb-2">Price Range</h6>
                <input type="number" class="form-control mb-2" placeholder="Min" wire:model.lazy="min_price">
                <input type="number" class="form-control mb-3" placeholder="Max" wire:model.lazy="max_price">

                <!-- STOCK -->
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" wire:model="in_stock">
                    <label class="form-check-label">In Stock Only</label>
                </div>

                <button wire:click="$reset" class="btn btn-light btn-sm w-100">Reset Filters</button>

            </div>
        </div>

        <!-- MAIN -->
        <div class="col-lg-9">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-0">{{ $parts->total() }} Parts Found</h5>
                    <small class="text-muted">Showing {{ $parts->firstItem() }} - {{ $parts->lastItem() }}</small>
                </div>

                <div class="d-flex gap-2">
                    <select class="form-select" wire:model="sort">
                        <option value="latest">Latest</option>
                        <option value="price_asc">Price ↑</option>
                        <option value="price_desc">Price ↓</option>
                        <option value="name_asc">Name A-Z</option>
                    </select>

                    <button class="btn btn-outline-secondary" @click="grid = !grid">
                        <i class="fa fa-th"></i>
                    </button>
                </div>
            </div>

            <div wire:loading.flex class="justify-content-center align-items-center my-5">
                <div class="spinner-border text-primary"></div>
            </div>

            <div class="row" wire:loading.remove>
                @forelse($parts as $part)
                    @php
                        $photo = $part->photos->first() ? asset('storage/'.$part->photos->first()->file_path) : asset('images/no-part.png');
                    @endphp

                    <div class="col-md-4 mb-4" x-show="grid">
                        <div class="card h-100 shadow-sm border-0 product-item">
                            <div class="position-relative text-center p-3">
                                <img src="{{ $photo }}" style="height:200px;object-fit:contain">
                                @if($part->stock_quantity > 0)
                                    <span class="badge bg-success position-absolute top-0 start-0 m-2">In Stock</span>
                                @else
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">Out</span>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h6 class="fw-semibold">{{ Str::limit($part->part_name, 40) }}</h6>
                                <small class="text-muted mb-2">SKU: {{ $part->sku }}</small>
                                <h5 class="text-primary fw-bold mt-auto">{{ number_format($part->price) }} RWF</h5>
                                <button class="btn btn-primary btn-sm mt-3">Add to Cart</button>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">No parts found.</div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">{{ $parts->links() }}</div>

        </div>
    </div>
</div>