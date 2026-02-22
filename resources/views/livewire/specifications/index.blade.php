<div>
    {{-- Breadcrumbs --}}
    <div class="container-fluid py-3">
        <div class="px-xl-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 m-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-primary text-decoration-none"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item text-muted">{{ $item->vehicleModel->brand->brand_name }}</li>
                    <li class="breadcrumb-item active fw-bold">{{ $item->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Configuration Header --}}
    <div class="container-fluid px-xl-5 mb-4">
        <div class="bg-dark text-white p-4 rounded-3 shadow-lg d-flex align-items-center justify-content-between position-relative overflow-hidden">
            <div class="position-relative z-index-2">
                <h6 class="text-primary text-uppercase mb-1 fw-bold small" style="letter-spacing: 2px;">Step 2: Technical Specification</h6>
                <h2 class="display-6 fw-bold mb-2">{{ $item->name }}</h2>
                
                {{-- Locked attributes from the variant name --}}
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <span class="badge bg-white bg-opacity-10 border border-white border-opacity-25 px-3 py-2"><i class="fa fa-gas-pump text-primary me-2"></i>{{ $item->engineType->name }}</span>
                    <span class="badge bg-white bg-opacity-10 border border-white border-opacity-25 px-3 py-2"><i class="fa fa-cog text-primary me-2"></i>{{ $item->transmissionType->name }}</span>
                    <span class="badge bg-white bg-opacity-10 border border-white border-opacity-25 px-3 py-2"><i class="fa fa-car text-primary me-2"></i>{{ $item->bodyType->name }}</span>
                </div>
            </div>
            
            <div class="d-flex align-items-center z-index-2">
                @if($item->vehicleModel->brand->brand_logo)
                    <img src="{{ asset('storage/' . $item->vehicleModel->brand->brand_logo) }}" class="img-fluid bg-white p-2 rounded-circle shadow" style="width: 80px; height: 80px; object-fit: contain;" alt="Logo" />
                @endif
                <div wire:loading class="ms-4">
                    <div class="spinner-grow text-primary" role="status"></div>
                </div>
            </div>
            <i class="fa fa-microchip position-absolute" style="right: -20px; bottom: -30px; font-size: 150px; color: rgba(255,255,255,0.05);"></i>
        </div>
    </div>

    {{-- Refined Technical Filters --}}
    <div class="container-fluid px-xl-5 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body bg-light">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Chassis / Model Code</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-0"><i class="fa fa-search text-muted"></i></span>
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-0" placeholder="e.g. W204, Y62...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Market</label>
                        <select wire:model.live="destination" class="form-select border-0 shadow-sm">
                            <option value="">All Markets</option>
                            @foreach($destinations as $dest)
                                <option value="{{ $dest->id }}">{{ $dest->region_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted text-uppercase">Drive</label>
                        <select wire:model.live="drive" class="form-select border-0 shadow-sm">
                            <option value="">Any Drive</option>
                            @foreach($driveTypes as $dt)
                                <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted text-uppercase">Steering</label>
                        <select wire:model.live="steering_position" class="form-select border-0 shadow-sm">
                            <option value="">LHD & RHD</option>
                            <option value="LEFT">LHD</option>
                            <option value="RIGHT">RHD</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button wire:click="resetFilters" class="btn btn-outline-danger border-0 w-100 fw-bold" title="Reset Filters">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Comprehensive Results Table --}}
    <div class="container-fluid px-xl-5 pb-5">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body p-0" wire:loading.class="opacity-50">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-white border-bottom">
                            <tr class="text-uppercase text-muted" style="font-size: 0.7rem; letter-spacing: 1.2px;">
                                <th class="ps-4 py-3">Technical Identity</th>
                                <th>Market & Drive</th>
                                <th>Performance & Capacity</th>
                                <th class="text-end pe-4">Catalog</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($specifications as $spec)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark fs-6">{{ $spec->model_code }}</div>
                                        <div class="text-primary small font-monospace fw-bold">{{ $spec->chassis_code }}</div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold">{{ $spec->destinations->pluck('region_name')->first() ?? 'Global' }}</div>
                                        <div class="small text-muted">
                                            <span class="badge bg-light text-dark border-0 shadow-sm me-1">{{ $spec->driveType->name ?? '-' }}</span>
                                            {{ $spec->steering_position }} Hand Drive
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <span class="small" title="Horsepower"><i class="fa fa-bolt text-warning me-1"></i>{{ $spec->horsepower ?? '-' }} HP</span>
                                            <span class="small text-muted">|</span>
                                            <span class="small" title="Doors"><i class="fa fa-door-open me-1"></i>{{ $spec->doors }}D</span>
                                            <span class="small text-muted">|</span>
                                            <span class="small" title="Seats"><i class="fa fa-users me-1"></i>{{ $spec->seats }}S</span>
                                        </div>
                                        <div class="small text-muted mt-1">
                                            <i class="fa fa-tint me-1"></i>Fuel Tank: {{ $spec->fuel_tank ?? '-' }}L
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="#" class="btn btn-primary rounded-pill btn-sm px-4 shadow-sm fw-bold">
                                            View Parts <i class="fa fa-arrow-right ms-2"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="fa fa-search-minus display-4 text-light mb-3"></i>
                                        <h6 class="text-muted fw-bold">No exact technical match found.</h6>
                                        <p class="small text-muted">Try clearing filters to see all available configurations for this variant.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>