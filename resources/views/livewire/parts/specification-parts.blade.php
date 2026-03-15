<div class="container-fluid px-xl-5 py-4" x-data="{ grid: true }">
    <style>
        .filter-card { border-radius: 15px; border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        .sticky-filter { top: 90px; z-index: 1000; }
        .spec-banner { border-radius: 15px; background: linear-gradient(45deg, #1a1a1a, #333); }
        .btn-toggle.active { background-color: #007bff; color: white; border-color: #007bff; }
        
        /* Category Styling */
        .category-item { border-radius: 8px !important; margin-bottom: 2px; border: none !important; transition: all 0.2s; color: #444; cursor: pointer; }
        .category-item:hover { background-color: rgba(0, 123, 255, 0.05); color: #007bff; }
        .category-item.active { background-color: #007bff !important; color: white !important; }
        .category-item.active .badge { background-color: rgba(255,255,255,0.2) !important; color: white !important; border: none !important; }

        .force-full-width-child > div[class*="col-"] {
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-bottom: 0 !important;
        }
    </style>

    <div class="row">
        {{-- 1. Sidebar --}}
        <div class="col-lg-3">
            <div class="sticky-top sticky-filter">
                
                {{-- Drill-down Categories --}}
                <div class="card filter-card mb-4">
                    <div class="card-body p-3">
                        <h6 class="font-weight-bold mb-3 text-dark px-2">Part Categories</h6>
                        <div class="list-group list-group-flush">
                            
                            {{-- "Back" or "All" Button --}}
                            @if($category_id)
                                @php 
                                    $currentCat = \App\Models\Category::find($category_id); 
                                @endphp
                                <a href="#" wire:click.prevent="$set('category_id', {{ $currentCat?->parent_id ?? 'null' }})" 
                                   class="list-group-item list-group-item-action category-item mb-2 bg-light border d-flex align-items-center">
                                    <i class="fa fa-arrow-left mr-2 small text-primary"></i> 
                                    <span class="small font-weight-bold text-primary">
                                        {{ $currentCat?->parent_id ? 'Back to ' . $currentCat->parent->category_name : 'All Categories' }}
                                    </span>
                                </a>
                            @else
                                <a href="#" wire:click.prevent="$set('category_id', null)" 
                                   class="list-group-item list-group-item-action category-item d-flex justify-content-between align-items-center {{ is_null($category_id) ? 'active' : '' }}">
                                    <span><i class="fa fa-th-large mr-2 small"></i> All Parts</span>
                                </a>
                            @endif

                            {{-- Category List --}}
                            @foreach($categories as $cat)
                                <a href="#" wire:click.prevent="$set('category_id', {{ $cat->id }})" 
                                   class="list-group-item list-group-item-action category-item d-flex justify-content-between align-items-center {{ $category_id == $cat->id ? 'active' : '' }}">
                                    <span class="text-truncate mr-2">
                                        {{ $cat->category_name }}
                                        @if($cat->children_count > 0) 
                                            <i class="fa fa-chevron-right ml-1 small text-muted" style="font-size: 0.6rem;"></i> 
                                        @endif
                                    </span>
                                    <span class="badge badge-pill badge-light border small">{{ $cat->parts_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Sidebar: Condition & Price Filters --}}
                <div class="card filter-card mb-4">
                    <div class="card-body p-3">
                        <h6 class="font-weight-bold mb-3 text-dark px-2">Refine Search</h6>

                        <div class="form-group px-2 mt-4">
                            <label class="small font-weight-bold text-muted text-uppercase">Max Price (RWF)</label>
                            <input type="range" class="custom-range" min="0" max="2000000" step="10000" wire:model.live="maxPrice">
                            <div class="d-flex justify-content-between small font-weight-bold text-primary">
                                <span>0</span>
                                <span>{{ number_format($maxPrice ?? 2000000) }} RWF</span>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="custom-control custom-switch px-2 ml-4">
                            <input type="checkbox" class="custom-control-input" id="inStock" wire:model.live="inStockOnly">
                            <label class="custom-control-label small font-weight-bold" for="inStock">In Stock Only</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Main Content --}}
        <div class="col-lg-9">

            {{-- Breadcrumb Navigation --}}
            <nav aria-label="breadcrumb" class="mb-3 px-2">
                <ol class="breadcrumb bg-transparent p-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('parts.catalog') }}" class="text-muted">Catalog</a></li>
                    <li class="breadcrumb-item"><span class="text-muted">{{ $specification->vehicleModel->brand->brand_name }}</span></li>
                    <li class="breadcrumb-item"><span class="text-muted">{{ $specification->vehicleModel->model_name }}</span></li>
                    <li class="breadcrumb-item active text-primary font-weight-bold" aria-current="page">{{ $specification->model_code }}</li>
                </ol>
            </nav>
            
            {{-- Vehicle Header Banner --}}
            <div class="card spec-banner text-white shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <span class="badge badge-primary mb-2 px-3 text-uppercase">Active Specification</span>
                            <h3 class="font-weight-bold mb-1">{{ $specification->model_code }}</h3>
                            <p class="text-white-50 mb-0 small">Chassis: <span class="text-white font-weight-bold">{{ $specification->chassis_code }}</span></p>
                        </div>
                        <div class="col-md-5 mt-3 mt-md-0">
                            <div class="input-group bg-white rounded-pill overflow-hidden px-2 py-1 shadow-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-0"><i class="fa fa-search text-muted"></i></span>
                                </div>
                                <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-0 shadow-none small" placeholder="Search compatibility...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grid Controls with Sorting --}}
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <div>
                    <h5 class="font-weight-bold text-dark mb-0">{{ number_format($parts->total()) }} Parts Found</h5>
                    <p class="small text-muted mb-0">Filtered for your {{ $specification->chassis_code }}</p>
                </div>
                
                <div class="d-flex align-items-center">
                    <select wire:model.live="sortBy" class="form-control form-control-sm border-0 shadow-sm mr-3" style="width: 160px; border-radius: 8px;">
                        <option value="latest">Newest First</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                    </select>

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

            {{-- Parts Display --}}
            <div class="row" wire:loading.class="opacity-50">
                @forelse($parts as $part)
                    <div :class="grid ? 'col-md-6 col-xl-4 mb-4' : 'col-12 mb-3'">
                        <div class="force-full-width-child h-100">
                            @livewire('part-component', ['part' => $part], key('spec-part-'.$part->id))
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3">
                            <i class="fa fa-tools fa-3x text-light"></i>
                        </div>
                        <h5 class="text-dark font-weight-bold">No Exact Matches Found</h5>
                        <p class="text-muted">Try adjusting your filters or search terms for this {{ $specification->chassis_code }}.</p>
                        <button wire:click="resetFilters" class="btn btn-primary rounded-pill px-4">
                            Clear All Filters
                        </button>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $parts->links() }}
            </div>
        </div>
    </div>
</div>