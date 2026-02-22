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
    
    {{-- Hero Variant Header --}}
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
                                <div class="variant-feature-badge">
                                    <div class="icon-box"><i class="fa fa-gas-pump"></i></div>
                                    <div>
                                        <div class="label">Engine</div>
                                        <div class="value">{{ $lockedInfo['engine'] }}</div>
                                    </div>
                                </div>

                                <div class="variant-feature-badge">
                                    <div class="icon-box"><i class="fa fa-cog"></i></div>
                                    <div>
                                        <div class="label">Transmission</div>
                                        <div class="value">{{ $lockedInfo['trans'] }}</div>
                                    </div>
                                </div>

                                <div class="variant-feature-badge">
                                    <div class="icon-box"><i class="fa fa-car-side"></i></div>
                                    <div>
                                        <div class="label">Body Style</div>
                                        <div class="value">{{ $lockedInfo['body'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                        @if($item->vehicleModel->brand->brand_logo)
                            <div class="d-inline-block bg-white p-3 rounded-4 shadow-lg mb-2 border border-white border-opacity-10 logo-container">
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
        <div class="card border-0 shadow-sm filter-card">
            <div class="card-body p-4">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label fw-bold text-dark small mb-2 text-uppercase">Search Codes</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted"><i class="fa fa-search"></i></span>
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-lg border-0 bg-light fs-6" placeholder="Chassis / Model code...">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-bold text-dark small mb-2 text-uppercase">Market</label>
                        <select wire:model.live="destination" class="form-select form-select-lg border-0 bg-light fs-6">
                            <option value="">All Markets</option>
                            @foreach($destinations as $dest)
                                <option value="{{ $dest->id }}">{{ $dest->region_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-bold text-dark small mb-2 text-uppercase">Drivetrain</label>
                        <select wire:model.live="drive" class="form-select form-select-lg border-0 bg-light fs-6">
                            <option value="">Any Drive</option>
                            @foreach($driveTypes as $dt)
                                <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-bold text-dark small mb-2 text-uppercase">Steering</label>
                        <select wire:model.live="steering_position" class="form-select form-select-lg border-0 bg-light fs-6">
                            <option value="">LHD & RHD</option>
                            <option value="LEFT">Left Hand (LHD)</option>
                            <option value="RIGHT">Right Hand (RHD)</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <button wire:click="resetFilters" class="btn btn-lg btn-outline-danger border-0 w-100 rounded-3 transition reset-btn">
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
            <div class="card-header bg-white py-4 px-4 border-bottom border-light">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="fa fa-microchip text-primary fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark m-0">Technical Configurations</h5>
                                <p class="text-muted small mb-0">Full engineering specifications for {{ $item->name }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">
                            <i class="fa fa-database me-1"></i> {{ count($specifications) }} Configurations Found
                        </span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-uppercase text-muted" style="font-size: 0.65rem; letter-spacing: 1.2px;">
                            <th class="ps-4 py-4 border-0">Technical Codes</th>
                            <th class="border-0">Region & Drive</th>
                            <th class="border-0">Performance & Fuel</th>
                            <th class="border-0">Dimensions & Paint</th>
                            <th class="text-end pe-4 border-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specifications as $spec)
                            <tr class="spec-row">
                                {{-- 1. Codes --}}
                                <td class="ps-4 py-5">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-dark text-white fw-bold rounded-3 px-3 py-2 me-4 small font-monospace shadow-sm" style="min-width: 90px; text-align: center; letter-spacing: 1px;">
                                            {{ $spec->model_code }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-6 mb-1">{{ $spec->chassis_code }}</div>
                                            <div class="text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Chassis Reference</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- 2. Region & Drive Type (Including Steering) --}}
                                <td class="py-5">
                                    <div class="fw-bold text-dark mb-2 fs-6">{{ $spec->destinations->pluck('region_name')->first() ?? 'Global' }}</div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-primary bg-opacity-10 text-white border-0 px-3 py-2" style="font-size: 0.7rem; border-radius: 6px;">
                                            <i class="fa fa-road me-1"></i> {{ $spec->driveType->name ?? 'N/A' }}
                                        </span>
                                        <span class="badge bg-dark bg-opacity-10 text-white border-0 px-3 py-2" style="font-size: 0.7rem; border-radius: 6px;">
                                            <i class="fa fa-dharmachakra me-1"></i> {{ $spec->steering_position }}HD
                                        </span>
                                    </div>
                                </td>

                                {{-- 3. Performance (Torque) & Fuel (Tank + Efficiency) --}}
                                <td class="py-5">
                                    <div class="d-flex gap-5">
                                        <div class="performance-group">
                                            <div class="text-muted text-uppercase fw-bold mb-2" style="font-size: 0.6rem; opacity: 0.7;">Engine Output</div>
                                            <div class="fw-bold text-dark mb-1">
                                                <i class="fa fa-bolt text-warning me-2"></i>{{ $spec->horsepower ?? '-' }} <span class="small text-muted fw-normal">HP</span>
                                            </div>
                                            <div class="fw-bold text-dark">
                                                <i class="fa fa-tachometer-alt text-warning me-2"></i>{{ $spec->torque ?? '-' }} <span class="small text-muted fw-normal">Nm</span>
                                            </div>
                                        </div>
                                        <div class="fuel-group border-start ps-4">
                                            <div class="text-muted text-uppercase fw-bold mb-2" style="font-size: 0.6rem; opacity: 0.7;">Fuel Metrics</div>
                                            <div class="fw-bold text-dark mb-1">
                                                <i class="fa fa-gas-pump text-primary me-2"></i>{{ $spec->fuel_capacity ?? '-' }} <span class="small text-muted fw-normal">L</span>
                                            </div>
                                            <div class="fw-bold text-success">
                                                <i class="fa fa-leaf me-2"></i>{{ $spec->fuel_efficiency ?? '-' }} <span class="small text-muted fw-normal">L/100 Km</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- 4. Dimensions & Color (Visual Preview) --}}
                                <td class="py-5">
                                    <div class="d-flex gap-5">
                                        <div class="layout-group">
                                            <div class="text-muted text-uppercase fw-bold mb-2" style="font-size: 0.6rem; opacity: 0.7;">Configuration</div>
                                            <div class="fw-bold text-dark small d-block mb-1"><i class="fa fa-chair me-2 opacity-50"></i>{{ $spec->seats }} Seats</div>
                                            <div class="fw-bold text-dark small d-block"><i class="fa fa-door-open me-2 opacity-50"></i>{{ $spec->doors }} Doors</div>
                                        </div>
                                        <div class="color-group border-start ps-4">
                                            <div class="text-muted text-uppercase fw-bold mb-2" style="font-size: 0.6rem; opacity: 0.7;">Paint Finish</div>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle shadow-sm border me-3" 
                                                     style="width: 28px; height: 28px; background-color: {{ $spec->color }};" 
                                                     title="{{ $spec->color }}">
                                                </div>
                                                <span class="fw-bold text-dark font-monospace" style="letter-spacing: 1px;">{{ strtoupper($spec->color) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- 5. Action --}}
                                <td class="text-end pe-4 py-5">
                                    <a href="#" class="btn btn-outline-primary rounded-3 px-4 py-2 fw-bold shadow-sm transition-all hover-lift">
                                        Full Specs <i class="fa fa-arrow-right ms-2 small"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-5 bg-light rounded-4 mx-4">
                                        <i class="fa fa-search fa-3x text-muted opacity-25 mb-3"></i>
                                        <h5 class="text-muted fw-bold">No configurations found.</h5>
                                        <button wire:click="resetFilters" class="btn btn-primary rounded-pill px-4 mt-2">Clear Filters</button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Styles --}}
    <style>
        /* Hero UI Refinement */
        .variant-feature-badge {
            display: flex;
            align-items: center;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 10px 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .variant-feature-badge .icon-box {
            background: var(--bs-primary);
            border-radius: 10px;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: white;
        }
        .variant-feature-badge .label { color: rgba(255,255,255,0.5); font-size: 0.65rem; font-weight: 700; text-transform: uppercase; }
        .variant-feature-badge .value { color: white; font-weight: 700; font-size: 0.9rem; }

        /* Filter Card Refinement */
        .filter-card {
            border-radius: 18px; 
            margin-top: -35px; 
            z-index: 10;
        }

        /* Table Spacing & Hover */
        .spec-row { transition: all 0.2s ease; border-bottom: 1px solid #f2f4f6; }
        .spec-row:hover { background-color: rgba(0, 123, 255, 0.01) !important; }
        .spec-row:last-child { border-bottom: none; }
        
        /* Utility */
        .hover-lift { transition: all 0.2s ease; }
        .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important; background-color: var(--bs-primary); color: white; }
        .font-monospace { font-family: 'SFMono-Regular', Consolas, "Liberation Mono", Courier, monospace !important; }
        .fw-black { font-weight: 900; }
        .tracking-tight { letter-spacing: -1.5px; }
        .reset-btn:hover { background-color: #dc3545 !important; color: white !important; }
        .logo-container { transition: transform 0.3s ease; }
        .logo-container:hover { transform: scale(1.05); }
    </style>
</div>