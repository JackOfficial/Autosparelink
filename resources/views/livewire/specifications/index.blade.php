<div>
    {{-- Breadcrumbs --}}
    <div class="container-fluid py-3">
        <div class="px-xl-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 m-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-primary text-decoration-none"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item text-muted">{{ $item->vehicleModel->brand->brand_name }}</li>
                    <li class="breadcrumb-item text-muted">{{ $item->vehicleModel->model_name }}</li>
                    <li class="breadcrumb-item active fw-bold text-dark">{{ $item->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Hero Variant Header: The Anchor --}}
    <div class="container-fluid px-xl-5 mb-4">
        <div class="card border-0 bg-dark text-white overflow-hidden shadow-lg" style="border-radius: 20px;">
            {{-- Background Decorative Element --}}
            <div class="position-absolute end-0 bottom-0 opacity-10 d-none d-lg-block" style="transform: translate(10%, 30%); z-index: 1;">
                 <i class="fa fa-car" style="font-size: 250px;"></i>
            </div>

            <div class="card-body p-4 p-lg-5 position-relative" style="z-index: 2;">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="badge bg-primary text-white px-3 py-2 rounded-pill text-uppercase fw-bold shadow-sm" style="font-size: 0.65rem; letter-spacing: 1px;">
                                Selected Variant
                            </span>
                            <div class="vr bg-white opacity-25" style="height: 20px;"></div>
                            <span class="text-white-50 small fw-bold text-uppercase">{{ $item->vehicleModel->brand->brand_name }} Catalog</span>
                        </div>
                        
                        <h1 class="display-4 fw-black mb-2 tracking-tight">{{ $item->name }}</h1>
                        
                        @if(!empty($lockedInfo))
                            <div class="d-flex flex-wrap gap-3 mt-4">
                                <div class="d-flex align-items-center bg-white bg-opacity-10 rounded-3 px-3 py-2 border border-white border-opacity-10 shadow-sm">
                                    <div class="bg-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="fa fa-gas-pump text-white small"></i>
                                    </div>
                                    <div>
                                        <div class="text-white-50 small-xs text-uppercase fw-bold" style="font-size: 0.6rem;">Engine</div>
                                        <div class="fw-bold small">{{ $lockedInfo['engine'] }}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center bg-white bg-opacity-10 rounded-3 px-3 py-2 border border-white border-opacity-10 shadow-sm">
                                    <div class="bg-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="fa fa-cog text-white small"></i>
                                    </div>
                                    <div>
                                        <div class="text-white-50 small-xs text-uppercase fw-bold" style="font-size: 0.6rem;">Transmission</div>
                                        <div class="fw-bold small">{{ $lockedInfo['trans'] }}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center bg-white bg-opacity-10 rounded-3 px-3 py-2 border border-white border-opacity-10 shadow-sm">
                                    <div class="bg-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="fa fa-car-side text-white small"></i>
                                    </div>
                                    <div>
                                        <div class="text-white-50 small-xs text-uppercase fw-bold" style="font-size: 0.6rem;">Body Style</div>
                                        <div class="fw-bold small">{{ $lockedInfo['body'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                        @if($item->vehicleModel->brand->brand_logo)
                            <div class="d-inline-block bg-white p-3 rounded-4 shadow-lg mb-2">
                                <img src="{{ asset('storage/' . $item->vehicleModel->brand->brand_logo) }}" style="height: 70px; width: 120px; object-fit: contain;" alt="Brand Logo" />
                            </div>
                        @endif
                        <div wire:loading class="d-block mt-2">
                            <span class="badge bg-primary px-3 py-2"><i class="fa fa-sync fa-spin me-2"></i>Updating specs...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="container-fluid px-xl-5 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; margin-top: -30px;">
            <div class="card-body p-4">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label fw-bold text-dark small mb-2"><i class="fa fa-search me-2 text-primary"></i>Refine by Code</label>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-lg border-light bg-light rounded-3 fs-6" placeholder="Chassis or Model code...">
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-bold text-dark small mb-2">Market</label>
                        <select wire:model.live="destination" class="form-select form-select-lg border-light bg-light rounded-3 fs-6">
                            <option value="">Global Market</option>
                            @foreach($destinations as $dest)
                                <option value="{{ $dest->id }}">{{ $dest->region_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-bold text-dark small mb-2">Drivetrain</label>
                        <select wire:model.live="drive" class="form-select form-select-lg border-light bg-light rounded-3 fs-6">
                            <option value="">Any Drive</option>
                            @foreach($driveTypes as $dt)
                                <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-bold text-dark small mb-2">Steering</label>
                        <select wire:model.live="steering_position" class="form-select form-select-lg border-light bg-light rounded-3 fs-6">
                            <option value="">LHD & RHD</option>
                            <option value="LEFT">Left Hand</option>
                            <option value="RIGHT">Right Hand</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <button wire:click="resetFilters" class="btn btn-lg btn-outline-secondary border-light w-100 rounded-3 hover-shadow transition">
                            <i class="fa fa-undo me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Results Area --}}
    <div class="container-fluid px-xl-5 pb-5">
        <div class="d-flex align-items-center justify-content-between mb-3 px-2">
            <h5 class="fw-bold text-dark m-0">Technical Configurations <span class="badge bg-light text-primary ms-2 border">{{ count($specifications) }} Found</span></h5>
        </div>

        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">
                            <th class="ps-4 py-4 border-0">Technical Codes</th>
                            <th class="border-0">Compliance & Drivetrain</th>
                            <th class="border-0">Performance Specs</th>
                            <th class="text-end pe-4 border-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specifications as $spec)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary fw-bold rounded-3 p-2 me-3 text-center" style="min-width: 60px;">
                                            {{ $spec->model_code }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark font-monospace">{{ $spec->chassis_code }}</div>
                                            <div class="text-muted small-xs text-uppercase">Identification Code</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $spec->destinations->pluck('region_name')->first() ?? 'Global' }}</div>
                                    <div class="small text-muted d-flex align-items-center mt-1">
                                        <span class="badge bg-white text-muted border me-2 shadow-xs">{{ $spec->driveType->name ?? '-' }}</span>
                                        <i class="fa fa-location-arrow me-1 small"></i> {{ $spec->steering_position }}HD
                                    </div>
                                </td>
                                <td>
                                    <div class="row g-0">
                                        <div class="col-auto me-3">
                                            <div class="text-muted small-xs text-uppercase fw-bold" style="font-size: 0.6rem;">Power</div>
                                            <div class="fw-bold"><i class="fa fa-bolt text-warning me-1"></i>{{ $spec->horsepower ?? '-' }} <small class="fw-normal">HP</small></div>
                                        </div>
                                        <div class="col-auto me-3">
                                            <div class="text-muted small-xs text-uppercase fw-bold" style="font-size: 0.6rem;">Capacity</div>
                                            <div class="fw-bold"><i class="fa fa-users text-info me-1"></i>{{ $spec->seats }} <small class="fw-normal">Seats</small></div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-muted small-xs text-uppercase fw-bold" style="font-size: 0.6rem;">Doors</div>
                                            <div class="fw-bold"><i class="fa fa-door-open text-secondary me-1"></i>{{ $spec->doors }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="#" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm btn-hover-zoom">
                                        Catalog <i class="fa fa-arrow-right ms-2"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fa fa-database fa-3x text-light mb-3"></i>
                                        <h5 class="text-muted fw-bold">No specifications match your filters.</h5>
                                        <p class="text-muted small">Try broadening your search or resetting the market filter.</p>
                                        <button wire:click="resetFilters" class="btn btn-primary rounded-pill px-4 mt-2 shadow-sm">Show All Specs</button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
    .fw-black { font-weight: 900; }
    .tracking-tight { letter-spacing: -1.5px; }
    .small-xs { font-size: 0.7rem; }
    .shadow-xs { shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .btn-hover-zoom:hover { transform: scale(1.05); transition: 0.2s; }
    .transition { transition: all 0.2s ease-in-out; }
</style>

</div>

