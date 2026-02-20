<div class="container-fluid py-4" x-data="{ grid: true }">

    @if(!empty($vinData))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-dark text-white">
                <div class="card-body d-flex align-items-center justify-content-between py-2">
                    <div>
                        <i class="fa fa-barcode me-2"></i>
                        <span class="fw-bold text-uppercase">Matched Vehicle:</span>
                        <span class="ms-2">
                            {{ $vinData['General Information']['Year'] }} {{ $vinData['General Information']['Make'] }} {{ $vinData['General Information']['Model'] }} 
                            | {{ $vinData['General Information']['Engine type'] }} 
                            | {{ $vinData['Vehicle Specification']['Engine horsepower'] }} HP
                        </span>
                    </div>
                    <button wire:click="$reset" class="btn btn-sm btn-outline-light">Clear VIN</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">

        <div class="col-lg-3 mb-4">

            <div class="card shadow-sm p-3 sticky-top" style="top:90px">

                <h6 class="fw-bold mb-2">Search</h6>
                <input type="text" class="form-control mb-3" placeholder="Search part..." wire:model.debounce.500ms="search">

                <h6 class="fw-bold mt-3 mb-2">Vehicle Compatibility</h6>

                <select class="form-control mb-2" wire:model.live="brand">
                    <option value="">Select Brand</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}">{{ $b->brand_name }}</option>
                    @endforeach
                </select>

                <select class="form-control mb-2" wire:model.live="model" @if(!$models || count($models) == 0) disabled @endif>
                    <option value="">Select Model</option>
                    @foreach($models as $m)
                        <option value="{{ $m->id }}">{{ $m->model_name }}</option>
                    @endforeach
                </select>

                <select class="form-control mb-2" wire:model.live="variant" @if(!$variants || count($variants) == 0) disabled @endif>
                    <option value="">Select Variant</option>
                    @foreach($variants as $v)
                        <option value="{{ $v->id }}">{{ $v->full_name }}</option>
                    @endforeach
                </select>

                @if(!empty($vinData))
                <div class="p-2 mt-2 rounded bg-light border">
                    <h6 class="small fw-bold mb-1"><i class="fa fa-info-circle me-1"></i> Technical Specs</h6>
                    <ul class="list-unstyled mb-0" style="font-size: 0.75rem;">
                        <li><strong>Fuel:</strong> {{ $vinData['General Information']['Fuel type'] }}</li>
                        <li><strong>Transmission:</strong> {{ $vinData['General Information']['Transmission'] }}</li>
                        <li><strong>Driveline:</strong> {{ $vinData['Vehicle Specification']['Driveline'] }}</li>
                        <li><strong>Origin:</strong> {{ $vinData['Manufacturer']['Country'] }}</li>
                    </ul>
                </div>
                @endif

                <h6 class="fw-bold mt-3 mb-2">Category</h6>
                <select class="form-control mb-3" wire:model.live="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->category_name }} ({{ $cat->parts_count }})</option>
                    @endforeach
                </select>

                <h6 class="fw-bold mt-3 mb-2">Price Range</h6>
                <input type="number" class="form-control mb-2" placeholder="Min" wire:model.lazy="min_price">
                <input type="number" class="form-control mb-3" placeholder="Max" wire:model.lazy="max_price">

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" wire:model.live="in_stock" id="inStockCheck">
                    <label class="form-check-label" for="inStockCheck">In Stock Only</label>
                </div>

                <button wire:click="$reset" class="btn btn-light btn-sm w-100">Reset Filters</button>

            </div>
        </div>

        <div class="col-lg-9">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-0">{{ $parts->total() }} Parts Found</h5>
                    <small class="text-muted">Showing {{ $parts->firstItem() }} - {{ $parts->lastItem() }}</small>
                </div>

                <div class="d-flex gap-2">
                    <select class="form-control" wire:model.live="sort">
                        <option value="latest">Latest</option>
                        <option value="price_asc">Price ↑</option>
                        <option value="price_desc">Price ↓</option>
                        <option value="name_asc">Name A-Z</option>
                    </select>

                    <button class="btn btn-outline-secondary" @click="grid = !grid" :class="grid ? 'active' : ''">
                        <i class="fa fa-th"></i>
                    </button>
                </div>
            </div>

            <div wire:loading.flex class="justify-content-center align-items-center my-5">
                <div class="spinner-border text-primary"></div>
            </div>

            <div class="row" wire:loading.remove>
                @forelse($parts as $part)
                    <div :class="grid ? 'col-md-4 mb-4' : 'col-12 mb-3'">
                        @livewire('part-component', ['part' => $part], key($part->id)) 
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">No parts found matching your criteria.</div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">{{ $parts->links() }}</div>

        </div>
    </div>
</div>