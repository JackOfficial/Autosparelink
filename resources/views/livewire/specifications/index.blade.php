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
    
    {{-- Hero Variant Header: Refined High-Contrast --}}
    <div class="container-fluid px-xl-5 mb-4">
        <div class="card border-0 shadow-lg overflow-hidden" 
             style="border-radius: 24px; background: linear-gradient(135deg, #1a1a1a 0%, #2c2c2c 100%);">
            
            <div class="card-body p-4 p-lg-5 position-relative">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        {{-- Identity Tag --}}
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="badge bg-primary text-white px-3 py-2 rounded-pill text-uppercase fw-bold shadow-sm" style="font-size: 0.7rem; letter-spacing: 1px;">
                                <i class="fa fa-check-circle me-1"></i> Current Variant
                            </span>
                            <div class="vr bg-white opacity-25" style="height: 20px;"></div>
                            <span class="text-white-50 small fw-bold text-uppercase" style="letter-spacing: 1px;">
                                {{ $item->vehicleModel->brand->brand_name }} {{ $item->vehicleModel->model_name }}
                            </span>
                        </div>
                        
                        {{-- Variant Name --}}
                        <h1 class="display-4 fw-black mb-1 tracking-tight text-white">{{ $item->name }}</h1>
                        <p class="text-white-50 mb-4 fs-5">Technical Specification & Configuration Catalog</p>
                        
                        @if(!empty($lockedInfo))
                            <div class="d-flex flex-wrap gap-3 mt-4">
                                {{-- Feature Badges with clearer contrast --}}
                                <div class="d-flex align-items-center bg-dark bg-opacity-50 rounded-4 px-3 py-2 border border-white border-opacity-10 shadow-sm">
                                    <div class="bg-primary rounded-3 p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fa fa-gas-pump text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">Engine</div>
                                        <div class="fw-bold text-white fs-6">{{ $lockedInfo['engine'] }}</div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center bg-dark bg-opacity-50 rounded-4 px-3 py-2 border border-white border-opacity-10 shadow-sm">
                                    <div class="bg-primary rounded-3 p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fa fa-cog text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">Transmission</div>
                                        <div class="fw-bold text-white fs-6">{{ $lockedInfo['trans'] }}</div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center bg-dark bg-opacity-50 rounded-4 px-3 py-2 border border-white border-opacity-10 shadow-sm">
                                    <div class="bg-primary rounded-3 p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fa fa-car-side text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">Body Style</div>
                                        <div class="fw-bold text-white fs-6">{{ $lockedInfo['body'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                        @if($item->vehicleModel->brand->brand_logo)
                            <div class="d-inline-block bg-white p-3 rounded-4 shadow-lg mb-2 border border-white border-opacity-10">
                                <img src="{{ asset('storage/' . $item->vehicleModel->brand->brand_logo) }}" style="height: 80px; width: 130px; object-fit: contain;" alt="Brand Logo" />
                            </div>
                        @endif
                        <div wire:loading class="d-block mt-3">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                            <span class="text-white-50 small fw-bold">Refreshing results...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="container-fluid px-xl-5 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 18px; margin-top: -35px; z-index: 10;">
            <div class="card-body p-4 p-lg-4">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label fw-bold text-dark small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Search Codes</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light text-muted"><i class="fa fa-search"></i></span>
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-lg border-light bg-light rounded-end-3 fs-6" placeholder="Chassis / Model code...">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-bold text-dark small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Market</label>
                        <select wire:model.live="destination" class="form-select form-select-lg border-light bg-light rounded-3 fs-6">
                            <option value="">All Markets</option>
                            @foreach($destinations as $dest)
                                <option value="{{ $dest->id }}">{{ $dest->region_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-bold text-dark small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Drivetrain</label>
                        <select wire:model.live="drive" class="form-select form-select-lg border-light bg-light rounded-3 fs-6">
                            <option value="">Any Drive</option>
                            @foreach($driveTypes as $dt)
                                <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-bold text-dark small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Steering</label>
                        <select wire:model.live="steering_position" class="form-select form-select-lg border-light bg-light rounded-3 fs-6">
                            <option value="">LHD & RHD</option>
                            <option value="LEFT">Left Hand (LHD)</option>
                            <option value="RIGHT">Right Hand (RHD)</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <button wire:click="resetFilters" class="btn btn-lg btn-outline-danger border-0 w-100 rounded-3 transition">
                            <i class="fa fa-refresh me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Results Area --}}
    <div class="container-fluid px-xl-5 pb-5">
        <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 20px;">
            {{-- Professional Header with Stats --}}
            <div class="card-header bg-white py-4 px-4 border-bottom border-light">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="fa fa-microchip text-primary fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark m-0">Technical Configurations</h5>
                                <p class="text-muted small mb-0">Browse through the engineered specifications for this variant</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">
                            <i class="fa fa-database me-1"></i> {{ count($specifications) }} Total Entries
                        </span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-uppercase text-muted" style="font-size: 0.65rem; letter-spacing: 1.2px;">
                            <th class="ps-4 py-3 border-0">Technical Codes</th>
                            <th class="border-0">Region & Setup</th>
                            <th class="border-0">Engineering Specs</th>
                            <th class="text-end pe-4 border-0">Catalog Access</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specifications as $spec)
                            <tr class="spec-row">
                                <td class="ps-4 py-4">
                                    <div class="d-flex align-items-center">
                                        {{-- Model Code Highlight --}}
                                        <div class="bg-dark text-white fw-bold rounded-2 px-2 py-1 me-3 small font-monospace shadow-sm" style="min-width: 65px; letter-spacing: 0.5px;">
                                            {{ $spec->model_code }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-6 mb-0">{{ $spec->chassis_code }}</div>
                                            <div class="text-muted d-flex align-items-center" style="font-size: 0.7rem;">
                                                <i class="fa fa-fingerprint me-1"></i> Chassis Identification
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="fw-bold text-dark mb-0">{{ $spec->destinations->pluck('region_name')->first() ?? 'Global' }}</div>
                                            <div class="d-flex gap-2 mt-1">
                                                <span class="badge bg-info bg-opacity-10 text-info border-0 px-2 py-1" style="font-size: 0.65rem;">
                                                    <i class="fa fa-road me-1"></i> {{ $spec->driveType->name ?? 'N/A' }}
                                                </span>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border-0 px-2 py-1" style="font-size: 0.65rem;">
                                                    <i class="fa fa-dharmachakra me-1"></i> {{ $spec->steering_position }}HD
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row g-0 align-items-center">
                                        <div class="col-auto pe-4 border-end border-light">
                                            <div class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.6rem;">Output</div>
                                            <div class="fw-bold text-dark"><i class="fa fa-bolt text-warning me-1"></i>{{ $spec->horsepower ?? '-' }} <span class="small fw-normal">HP</span></div>
                                        </div>
                                        <div class="col-auto px-4 border-end border-light">
                                            <div class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.6rem;">Capacity</div>
                                            <div class="fw-bold text-dark"><i class="fa fa-chair text-primary me-1"></i>{{ $spec->seats }} <span class="small fw-normal">Seats</span></div>
                                        </div>
                                        <div class="col-auto ps-4">
                                            <div class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.6rem;">Layout</div>
                                            <div class="fw-bold text-dark"><i class="fa fa-door-closed text-muted me-1"></i>{{ $spec->doors }} <span class="small fw-normal">Doors</span></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm d-flex align-items-center transition">
                                            <span>Open Catalog</span>
                                            <i class="fa fa-chevron-right ms-2" style="font-size: 0.7rem;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-5 bg-light rounded-4 mx-4 my-2 border border-dashed">
                                        <div class="mb-3">
                                            <i class="fa fa-search-minus fa-3x text-muted opacity-25"></i>
                                        </div>
                                        <h5 class="text-dark fw-bold">No Matching Specs Found</h5>
                                        <p class="text-muted mx-auto mb-4" style="max-width: 300px;">We couldn't find any configurations matching your current filters. Try resetting or adjusting your search.</p>
                                        <button wire:click="resetFilters" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                            <i class="fa fa-undo me-2"></i>Reset All Filters
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Footer info --}}
            <div class="card-footer bg-light py-3 px-4 border-0">
                <div class="row">
                    <div class="col-12 text-center text-muted small">
                        <i class="fa fa-info-circle me-1"></i> Technical data is updated based on manufacturer release cycles.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .spec-row { transition: background-color 0.2s ease; }
        .spec-row:hover { background-color: rgba(0, 123, 255, 0.02) !important; cursor: pointer; }
        .border-dashed { border-style: dashed !important; border-width: 2px !important; }
        .transition { transition: all 0.2s ease-in-out; }
        .btn-primary:hover { transform: translateX(3px); }
        
        .fw-black { font-weight: 900; }
        .tracking-tight { letter-spacing: -1.5px; }
        .btn-hover-zoom:hover { transform: translateY(-2px); transition: 0.2s; box-shadow: 0 5px 15px rgba(0,123,255,0.3); }
        .transition { transition: all 0.2s ease-in-out; }
        .form-select-lg, .form-control-lg { border-radius: 10px; }
    </style>
</div>