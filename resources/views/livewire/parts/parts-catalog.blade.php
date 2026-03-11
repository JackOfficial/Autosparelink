<div class="container-fluid px-xl-5 py-4" x-data="{ grid: true }">
    <style>
        .filter-card { border-radius: 15px; border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        .sticky-filter { top: 90px; z-index: 1020; }
        .vin-banner { border-radius: 12px; background: linear-gradient(45deg, #1a1a1a, #333); border-left: 5px solid #007bff; }
        .form-control-pill { border-radius: 50px; padding-left: 1.25rem; }
        .btn-toggle.active { background-color: #007bff; color: white; border-color: #007bff; }
        .part-grid-transition { transition: all 0.3s ease; }
        .force-full-width-child > div[class*="col-"] {
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-bottom: 0 !important;
        }
        .transition-all { transition: all 0.3s ease-in-out !important; }
        /* Highlight for pre-selected variant */
        .variant-preselected { border: 2px solid #007bff !important; background-color: #f0f7ff !important; }
    </style>

    {{-- 1. VIN Matched Vehicle Banner --}}
    @if(!empty($vinData))
    <div class="row mb-4 animate__animated animate__fadeInDown">
        <div class="col-12">
            <div class="card vin-banner text-white shadow-sm border-0">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle p-2 mr-3">
                            <i class="fa fa-car-side fa-fw"></i>
                        </div>
                        <div>
                            <small class="text-white-50 text-uppercase font-weight-bold" style="letter-spacing: 1px; font-size: 0.7rem;">VIN Search Active</small>
                           <h5 class="mb-0 font-weight-bold">
                                {{-- Pulling descriptive name from the DB Variant Model instead of raw VIN strings --}}
                                @if($selectedVariantModel)
                                    {{ $selectedVariantModel->vehicleModel->brand->brand_name ?? '' }} 
                                    {{ $selectedVariantModel->vehicleModel->model_name ?? '' }} 
                                    <span class="mx-2 text-white-50">|</span>
                                    <span class="font-weight-normal small text-info">{{ $selectedVariantModel->engine_code }} {{ $selectedVariantModel->engine_capacity }}</span>
                                @else
                                    {{-- Fallback if DB match isn't found yet --}}
                                    {{ $vinData['General Information']['Year'] ?? '' }} {{ $vinData['General Information']['Make'] ?? '' }} {{ $vinData['General Information']['Model'] ?? '' }}
                                @endif
                            </h5>
                        </div>
                    </div>
                    <button wire:click="clearFilters" class="btn btn-sm btn-outline-light rounded-pill px-4">
                        <i class="fa fa-times mr-1"></i> Clear VIN
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        {{-- 2. Sidebar Filters --}}
        <div class="col-lg-3">
            <div class="sticky-top sticky-filter">
                <div class="card filter-card mb-4">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold mb-3 text-dark"><i class="fa fa-search mr-2 text-primary"></i>Quick Search</h6>
                        <input type="text" class="form-control form-control-pill mb-4 border-light bg-light shadow-none" 
                               placeholder="Part name, SKU, OEM..." wire:model.live.debounce.500ms="search">

                        <h6 class="font-weight-bold mb-3 text-dark"><i class="fa fa-filter mr-2 text-primary"></i>Vehicle Filter</h6>
                        <div class="form-group small">
                            {{-- Brand Select --}}
                            <select class="form-control mb-2 custom-select border-light shadow-none" wire:model.live="brand">
                                <option value="">All Brands</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}">{{ $b->brand_name }}</option>
                                @endforeach
                            </select>

                            {{-- Model Select --}}
                            <select class="form-control mb-2 custom-select border-light shadow-none" wire:model.live="model" @disabled(!$brand || count($models) == 0)>
                                <option value="">Select Model</option>
                                @foreach($models as $m)
                                    <option value="{{ $m->id }}">{{ $m->model_name }}</option>
                                @endforeach
                            </select>

                            {{-- Variant Select: Added dynamic class if pre-selected via VIN --}}
                            <select class="form-control mb-3 custom-select border-light shadow-none {{ !empty($variant) && !empty($vinData) ? 'variant-preselected' : '' }}" 
                                    wire:model.live="variant" 
                                    @disabled(!$model || count($variants) == 0)>
                                <option value="">Select Specification/Engine</option>
                                @foreach($variants as $v)
                                    <option value="{{ $v->id }}">
                                        {{ $v->engine_code ?? '' }} {{ $v->engine_capacity ?? '' }} ({{ $v->start_year ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Technical Details (Visible during VIN search) --}}
                        @if(!empty($vinData))
                        <div class="p-3 mb-4 rounded bg-light border-0 small">
                            <h6 class="font-weight-bold mb-2 text-primary" style="font-size: 0.8rem;">VIN Match Details</h6>
                            <div class="d-flex justify-content-between mb-1"><span>Fuel:</span> <span class="text-dark font-weight-bold">{{ $vinData['General Information']['Fuel type'] ?? 'N/A' }}</span></div>
                            <div class="d-flex justify-content-between mb-1"><span>Transmission:</span> <span class="text-dark font-weight-bold">{{ $vinData['General Information']['Transmission'] ?? 'N/A' }}</span></div>
                            <div class="d-flex justify-content-between"><span>Origin:</span> <span class="text-dark font-weight-bold">{{ $vinData['Manufacturer']['Country'] ?? 'N/A' }}</span></div>
                        </div>
                        @endif

                        <h6 class="font-weight-bold mb-3 text-dark">Category</h6>
                        <select class="form-control mb-4 custom-select border-light shadow-none" wire:model.live="category">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">
                                    {{ $cat->category_name }} ({{ $cat->parts_count }})
                                </option>
                            @endforeach
                        </select>

                        <h6 class="font-weight-bold mb-3 text-dark">Price Range</h6>
                        <div class="row no-gutters mb-4">
                            <div class="col-6 pr-1">
                                <input type="number" class="form-control border-light small shadow-none" placeholder="Min" wire:model.lazy="min_price">
                            </div>
                            <div class="col-6 pl-1">
                                <input type="number" class="form-control border-light small shadow-none" placeholder="Max" wire:model.lazy="max_price">
                            </div>
                        </div>

                        <div class="custom-control custom-switch mb-4">
                            <input type="checkbox" class="custom-control-input" wire:model.live="in_stock" id="inStockCheck">
                            <label class="custom-control-label small font-weight-bold" for="inStockCheck">In Stock Only</label>
                        </div>

                        <button wire:click="clearFilters" class="btn btn-block btn-light text-muted font-weight-bold small py-2" style="border-radius: 10px;">
                            <i class="fa fa-undo mr-1"></i> Reset Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Main Parts Area --}}
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h4 class="font-weight-bold text-dark mb-0">{{ number_format($parts->total()) }} Parts Found</h4>
                    @if($category)
                        <span class="badge badge-primary px-3 py-2 rounded-pill mt-2">
                            Category: {{ $categories->firstWhere('id', $category)?->category_name }}
                        </span>
                    @endif
                </div>

                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <select class="form-control form-control-sm border-0 shadow-sm rounded-pill px-3" wire:model.live="sort">
                            <option value="latest">Sort: Latest</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="name_asc">Name: A-Z</option>
                        </select>
                    </div>

                    <div class="btn-group btn-group-sm bg-white shadow-sm p-1" style="border-radius: 10px;">
                        <button class="btn btn-light border-0 py-1 px-3 btn-toggle" @click="grid = true" :class="grid ? 'active' : ''">
                            <i class="fa fa-th-large"></i>
                        </button>
                        <button class="btn btn-light border-0 py-1 px-3 btn-toggle" @click="grid = false" :class="!grid ? 'active' : ''">
                            <i class="fa fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Loading State --}}
            <div wire:loading.flex wire:target="brand, model, variant, category, search, sort, in_stock" class="justify-content-center align-items-center py-5">
                <div class="spinner-grow text-primary" role="status"></div>
                <span class="ml-3 font-weight-bold text-primary">Updating Catalog...</span>
            </div>

            {{-- Results Grid/List --}}
            <div class="row" wire:loading.remove wire:target="brand, model, variant, category, search, sort, in_stock">
                @forelse($parts as $part)
                    <div :class="grid ? 'col-md-6 col-xl-4 mb-4' : 'col-12 mb-3'">
                        <div class="force-full-width-child h-100 part-grid-transition">
                            @livewire('part-component', [
                                'part' => $part, 
                                'isCompatible' => !empty($vinData) || !empty($this->variant)
                            ], key('part-'.$part->id . '-' . ($this->variant ?? 'all') . '-' . ($grid ? 'grid' : 'list'))) 
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fa fa-box-open fa-4x text-light mb-3"></i>
                        <h5 class="text-muted mt-3">No parts found matching your criteria.</h5>
                        <button wire:click="clearFilters" class="btn btn-primary btn-sm rounded-pill mt-2 px-4">See All Parts</button>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $parts->links() }}
            </div>
        </div>
    </div>
</div>