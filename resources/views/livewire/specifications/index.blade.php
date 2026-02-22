<div>
    {{-- Breadcrumbs --}}
    <div class="container-fluid py-3">
        <div class="px-xl-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 m-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-primary"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item text-muted">{{ $item->vehicleModel->brand->brand_name }}</li>
                    <li class="breadcrumb-item text-muted">{{ $item->vehicleModel->model_name }}</li>
                    <li class="breadcrumb-item active font-weight-bold text-dark">{{ $item->name }}</li>
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
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge badge-primary px-3 py-2 rounded-pill text-uppercase font-weight-bold shadow-sm" style="font-size: 0.7rem; letter-spacing: 1px;">
                                <i class="fa fa-check-circle mr-1"></i> Current Variant
                            </span>
                            <div class="mx-3" style="width: 1px; height: 20px; background: rgba(255,255,255,0.2);"></div>
                            <span class="text-white-50 small font-weight-bold text-uppercase" style="letter-spacing: 1px;">
                                {{ $item->vehicleModel->brand->brand_name }} {{ $item->vehicleModel->model_name }}
                            </span>
                        </div>
                        
                        {{-- Variant Name --}}
                        <h1 class="display-4 font-weight-black mb-1 text-white tracking-tight" style="font-weight: 900;">{{ $item->name }}</h1>
                        <p class="text-white-50 mb-4 h5 font-weight-light">Technical Specification & Configuration Catalog</p>
                        
                        @if(!empty($lockedInfo))
                            <div class="d-flex flex-wrap mt-4">
                                <div class="variant-feature-badge mr-3 mb-3">
                                    <div class="icon-box bg-primary"><i class="fa fa-gas-pump"></i></div>
                                    <div>
                                        <div class="label">Engine</div>
                                        <div class="value">{{ $lockedInfo['engine'] }}</div>
                                    </div>
                                </div>

                                <div class="variant-feature-badge mr-3 mb-3">
                                    <div class="icon-box bg-primary"><i class="fa fa-cog"></i></div>
                                    <div>
                                        <div class="label">Transmission</div>
                                        <div class="value">{{ $lockedInfo['trans'] }}</div>
                                    </div>
                                </div>

                                <div class="variant-feature-badge mb-3">
                                    <div class="icon-box bg-primary"><i class="fa fa-car-side"></i></div>
                                    <div>
                                        <div class="label">Body Style</div>
                                        <div class="value">{{ $lockedInfo['body'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-lg-4 text-center text-lg-right mt-4 mt-lg-0">
                        @if($item->vehicleModel->brand->brand_logo)
                            <div class="d-inline-block bg-white p-3 rounded shadow-lg mb-2 logo-container" style="border-radius: 15px !important;">
                                <img src="{{ asset('storage/' . $item->vehicleModel->brand->brand_logo) }}" style="height: 80px; width: 130px; object-fit: contain;" alt="Brand Logo" />
                            </div>
                        @endif
                       <div class="col-lg-4 text-center text-lg-right mt-4 mt-lg-0 position-relative">
    @if($item->vehicleModel->brand->brand_logo)
        <div class="d-inline-block bg-white p-3 rounded shadow-lg mb-2 logo-container" style="border-radius: 15px !important;">
            <img src="{{ asset('storage/' . $item->vehicleModel->brand->brand_logo) }}" style="height: 80px; width: 130px; object-fit: contain;" alt="Brand Logo" />
        </div>
    @endif

    {{-- Targeted & Absolutely Positioned Loading --}}
    <div wire:loading wire:target="search, destination, drive, steering_position, resetFilters" 
         class="position-absolute w-100" 
         style="bottom: -25px; right: 0;">
        <div class="d-flex align-items-center justify-content-center justify-content-lg-end">
            <div class="spinner-border spinner-border-sm text-primary mr-2" role="status"></div>
            <span class="text-white-50 small font-weight-bold">Refreshing results...</span>
        </div>
    </div>
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
                <div class="form-row align-items-end">
                    <div class="col-lg-4 mb-3 mb-lg-0">
                        <label class="font-weight-bold text-dark small mb-2 text-uppercase">Search Codes</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-0 text-muted"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-lg border-0 bg-light shadow-none" placeholder="Chassis / Model code..." style="font-size: 1rem;">
                        </div>
                    </div>
                    <div class="col-lg-2 mb-3 mb-lg-0">
                        <label class="font-weight-bold text-dark small mb-2 text-uppercase">Market</label>
                        <select wire:model.live="destination" class="form-control form-control-lg border-0 bg-light shadow-none" style="font-size: 1rem;">
                            <option value="">All Markets</option>
                            @foreach($destinations as $dest)
                                <option value="{{ $dest->id }}">{{ $dest->region_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 mb-3 mb-lg-0">
                        <label class="font-weight-bold text-dark small mb-2 text-uppercase">Drivetrain</label>
                        <select wire:model.live="drive" class="form-control form-control-lg border-0 bg-light shadow-none" style="font-size: 1rem;">
                            <option value="">Any Drive</option>
                            @foreach($driveTypes as $dt)
                                <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 mb-3 mb-lg-0">
                        <label class="font-weight-bold text-dark small mb-2 text-uppercase">Steering</label>
                        <select wire:model.live="steering_position" class="form-control form-control-lg border-0 bg-light shadow-none" style="font-size: 1rem;">
                            <option value="">LHD & RHD</option>
                            <option value="LEFT">Left Hand (LHD)</option>
                            <option value="RIGHT">Right Hand (RHD)</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <button wire:click="resetFilters" class="btn btn-lg btn-outline-danger border-0 w-100 reset-btn" style="border-radius: 10px;">
                            <i class="fa fa-undo mr-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Results Area --}}
    <div class="container-fluid px-xl-5 pb-5">
        <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 20px;">
            <div class="card-header bg-white py-4 px-4 border-bottom-0">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="media align-items-center">
                            <div class="bg-light p-2 rounded mr-3" style="color: #007bff;">
                                <i class="fa fa-microchip fa-lg"></i>
                            </div>
                            <div class="media-body">
                                <h5 class="font-weight-bold text-dark m-0">Technical Configurations</h5>
                                <p class="text-muted small mb-0">Full engineering specifications for {{ $item->name }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-right mt-3 mt-md-0">
                        <span class="badge badge-light text-muted border px-3 py-2 rounded-pill">
                            <i class="fa fa-database mr-1"></i> {{ count($specifications) }} Configurations Found
                        </span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-uppercase text-muted" style="font-size: 0.65rem; letter-spacing: 1.2px;">
                            <th class="pl-4 py-4 border-0">Technical Codes</th>
                            <th class="border-0">Region & Drive</th>
                            <th class="border-0">Performance & Fuel</th>
                            <th class="border-0">Dimensions & Paint</th>
                            <th class="text-right pr-4 border-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specifications as $spec)
                            <tr class="spec-row">
                                {{-- 1. Codes --}}
                                <td class="pl-4 py-5" style="vertical-align: middle;">
                                    <div class="d-flex align-items-center">
                                        <div class="pr-5 border-right" style="border-color: rgba(0,0,0,0.08) !important;">
                                            <small class="d-block text-muted text-uppercase font-weight-bold mb-1" style="font-size: 0.65rem; letter-spacing: 1px;">
                                                Model Code
                                            </small>
                                            <span class="d-block font-weight-bold text-dark font-family-mono h5 mb-0" style="letter-spacing: -0.5px;">
                                                #{{ $spec->model_code }}
                                            </span>
                                        </div>
                                        <div class="pl-5">
                                            <small class="d-block text-muted text-uppercase font-weight-bold mb-1" style="font-size: 0.65rem; letter-spacing: 1px;">
                                                Chassis ID
                                            </small>
                                            <span class="d-block font-weight-bold text-dark mb-0" style="font-size: 1rem;">
                                                #{{ $spec->chassis_code }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- 2. Region & Drive --}}
                                <td class="py-5" style="vertical-align: middle;">
                                    <div class="font-weight-bold text-dark mb-2">{{ $spec->destinations->pluck('region_name')->first() ?? 'Global' }}</div>
                                    <div class="d-flex flex-wrap">
                                        <span class="badge badge-light text-primary border-0 px-2 py-2 mr-2 mb-1" style="font-size: 0.7rem; border-radius: 6px; background: rgba(0,123,255,0.08);">
                                            <i class="fa fa-road mr-1"></i> {{ $spec->driveType->name ?? 'N/A' }}
                                        </span>
                                        <span class="badge badge-light text-dark border-0 px-2 py-2 mb-1" style="font-size: 0.7rem; border-radius: 6px; background: rgba(0,0,0,0.05);">
                                            <i class="fa fa-dharmachakra mr-1"></i> {{ $spec->steering_position }}H
                                        </span>
                                    </div>
                                </td>

                                {{-- 3. Performance & Fuel --}}
                                <td class="py-5" style="vertical-align: middle;">
                                    <div class="d-flex">
                                        <div class="pr-4">
                                            <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.6rem;">Output</div>
                                            <div class="font-weight-bold text-dark small mb-1">
                                                <i class="fa fa-bolt text-warning mr-1"></i>{{ $spec->horsepower ?? '-' }} <span class="text-muted font-weight-normal">HP</span>
                                            </div>
                                            <div class="font-weight-bold text-dark small">
                                                <i class="fa fa-tachometer-alt text-warning mr-1"></i>{{ $spec->torque ?? '-' }} <span class="text-muted font-weight-normal">Nm</span>
                                            </div>
                                        </div>
                                        <div class="pl-4 border-left" style="border-color: rgba(0,0,0,0.08) !important;">
                                            <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.6rem;">Fuel</div>
                                            <div class="font-weight-bold text-dark small mb-1">
                                                <i class="fa fa-gas-pump text-primary mr-1"></i>{{ $spec->fuel_capacity ?? '-' }}L
                                            </div>
                                            <div class="font-weight-bold text-success small">
                                                <i class="fa fa-leaf mr-1"></i>{{ $spec->fuel_efficiency ?? '-' }} <span class="text-muted font-weight-normal">L/100</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- 4. Dimensions & Color --}}
                                <td class="py-5" style="vertical-align: middle;">
                                    <div class="d-flex">
                                        <div class="pr-4">
                                            <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.6rem;">Layout</div>
                                            <div class="font-weight-bold text-dark small mb-1"><i class="fa fa-chair mr-2 opacity-50"></i>{{ $spec->seats }} Seats</div>
                                            <div class="font-weight-bold text-dark small"><i class="fa fa-door-open mr-2 opacity-50"></i>{{ $spec->doors }} Doors</div>
                                        </div>
                                        <div class="pl-4 border-left" style="border-color: rgba(0,0,0,0.08) !important;">
                                            <div class="text-muted text-uppercase font-weight-bold mb-2" style="font-size: 0.6rem;">Paint</div>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle shadow-sm border mr-2" 
                                                     style="width: 24px; height: 24px; background-color: {{ $spec->color }};">
                                                </div>
                                                <span class="font-weight-bold text-dark font-family-mono small">{{ strtoupper($spec->color) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- 5. Action --}}
                                <td class="text-right pr-4 py-5" style="vertical-align: middle;">
                                    <a href="#" class="btn btn-outline-primary font-weight-bold px-3 py-2 hover-lift" style="border-radius: 10px; font-size: 0.85rem;">
                                        Browse parts <i class="fas fa-arrow-right ml-2 small"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-5 mx-4" style="background: #f8f9fa; border-radius: 15px;">
                                        <i class="fa fa-search fa-3x text-muted mb-3" style="opacity: 0.2;"></i>
                                        <h5 class="text-muted font-weight-bold">No configurations found.</h5>
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
        .variant-feature-badge {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 10px 16px;
        }
        .variant-feature-badge .icon-box {
            border-radius: 10px;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: white;
        }
        .variant-feature-badge .label { color: rgba(255,255,255,0.4); font-size: 0.65rem; font-weight: 700; text-transform: uppercase; }
        .variant-feature-badge .value { color: white; font-weight: 700; font-size: 0.9rem; }

        .filter-card {
            border-radius: 18px; 
            margin-top: -45px; 
            z-index: 10;
        }

        .spec-row { transition: background 0.2s ease; }
        .spec-row:hover { background-color: #fcfdfe !important; }
        
        .hover-lift { transition: all 0.2s ease; }
        .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,123,255,0.15) !important; }
        
        .font-family-mono { font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important; }
        .tracking-tight { letter-spacing: -1px; }
        .logo-container { transition: transform 0.3s ease; }
        .logo-container:hover { transform: scale(1.03); }
    </style>
</div>