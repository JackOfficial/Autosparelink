<div class="container-fluid px-xl-5 py-4" x-data="{ grid: true }">
    <style>
        .filter-card { border-radius: 15px; border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        .sticky-filter { top: 90px; z-index: 1020; }
        .spec-banner { border-radius: 15px; background: linear-gradient(45deg, #1a1a1a, #333); }
        .form-control-pill { border-radius: 50px; padding-left: 1.25rem; }
        .btn-toggle.active { background-color: #007bff; color: white; border-color: #007bff; }
        .category-item { border-radius: 8px !important; margin-bottom: 2px; border: none !important; transition: all 0.2s; }
        .category-item:hover { background-color: rgba(0, 123, 255, 0.05); }
        .category-item.active { background-color: #007bff !important; color: white !important; }
        .category-item.active .badge { background-color: rgba(255,255,255,0.2) !important; color: white !important; border: none !important; }
        
        /* The "Neutralizer" for your existing col-lg-3 component */
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
        {{-- 1. Sidebar Filters --}}
        <div class="col-lg-3">
            <div class="sticky-top sticky-filter">
                {{-- Category Card --}}
                <div class="card filter-card mb-4">
                    <div class="card-body p-3">
                        <h6 class="font-weight-bold mb-3 text-dark px-2"><i class="fa fa-tags mr-2 text-primary"></i>Categories</h6>
                        <div class="list-group list-group-flush">
                            <a href="#" wire:click.prevent="$set('category_id', null)" 
                               class="list-group-item list-group-item-action category-item d-flex justify-content-between align-items-center {{ is_null($category_id) ? 'active' : '' }}">
                                <span>All Parts</span>
                                <i class="fa fa-chevron-right small opacity-50"></i>
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

                {{-- Status Card --}}
                <div class="card filter-card">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold mb-3 text-dark">Availability</h6>
                        <div class="custom-control custom-switch mb-0">
                            <input type="checkbox" class="custom-control-input" id="inStock" wire:model.live="inStockOnly">
                            <label class="custom-control-label small font-weight-bold" for="inStock">In Stock Only</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Main Content Area --}}
        <div class="col-lg-9">
            
            {{-- Technical Spec Banner --}}
            <div class="card spec-banner text-white shadow-sm border-0 mb-4 animate__animated animate__fadeIn">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <span class="badge badge-primary mb-2 px-3 text-uppercase" style="letter-spacing: 1px;">Compatibility Active</span>
                            <h3 class="font-weight-bold mb-1">{{ $specification->model_code }}</h3>
                            <p class="text-white-50 mb-0">
                                Chassis: <span class="text-white font-weight-bold">{{ $specification->chassis_code }}</span> 
                                <span class="mx-2">|</span> 
                                Engine: <span class="text-white font-weight-bold">{{ $specification->engine_code ?? 'Standard' }}</span>
                            </p>
                        </div>
                        <div class="col-md-5 mt-3 mt-md-0">
                            <div class="input-group bg-white rounded-pill overflow-hidden px-2 py-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-0"><i class="fa fa-search text-muted"></i></span>
                                </div>
                                <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-0 shadow-none small" placeholder="Search within results...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Controls --}}
            <div class="d-flex justify-content-between align-items-end mb-4 px-2">
                <div>
                    <h5 class="font-weight-bold text-dark mb-0">{{ number_format($parts->total()) }} Parts Matched</h5>
                    <p class="text-muted small mb-0">Tailored for {{ $specification->model_code }}</p>
                </div>

                <div class="d-flex align-items-center">
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
            <div wire:loading.flex class="justify-content-center align-items-center py-5">
                <div class="spinner-grow text-primary" role="status"></div>
                <span class="ml-3 font-weight-bold text-primary">Filtering Parts...</span>
            </div>

            {{-- Results --}}
            <div class="row" wire:loading.remove>
                @forelse($parts as $part)
                    <div :class="grid ? 'col-md-6 col-xl-4 mb-4' : 'col-12 mb-3'" class="part-grid-transition">
                        <div class="force-full-width-child h-100">
                            @livewire('part-component', ['part' => $part], key($part->id)) 
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 bg-white shadow-sm" style="border-radius: 15px;">
                        <i class="fa fa-tools fa-3x text-muted opacity-25 mb-3"></i>
                        <h5 class="text-muted">No specific parts found for this category.</h5>
                        <p class="small text-muted">Try clearing search or checking "All Parts"</p>
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