<div>
    {{-- Breadcrumbs --}}
    <div class="container-fluid py-3">
        <div class="px-xl-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 m-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-primary text-decoration-none">Home</a></li>
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
                <h6 class="text-primary text-uppercase mb-1 fw-bold" style="letter-spacing: 2px;">Step 1: Identify Your Vehicle</h6>
                <h2 class="display-6 fw-bold mb-0">{{ $item->name }} <span class="fw-light text-white-50">{{ $item->vehicleModel->model_name }}</span></h2>
                <p class="mb-0 text-white-50 small">Configure your technical specifications to find the exact parts matching your build.</p>
            </div>
            
            <div class="d-flex align-items-center z-index-2">
                @if($item->vehicleModel->brand->brand_logo)
                    <img src="{{ asset('storage/' . $item->vehicleModel->brand->brand_logo) }}" class="img-fluid bg-white p-2 rounded-circle shadow" style="width: 75px; height: 75px; object-fit: contain;" alt="Brand Logo" />
                @endif
                
                <div wire:loading class="ms-4">
                    <div class="spinner-grow text-primary" role="status"></div>
                </div>
            </div>
            <i class="fa fa-car position-absolute shadow-none" style="right: -20px; bottom: -30px; font-size: 150px; color: rgba(255,255,255,0.05);"></i>
        </div>
    </div>

    {{-- Advanced Technical Filter Suite --}}
    <div class="container-fluid px-xl-5 mb-4">
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fa fa-microchip me-2 text-primary"></i>Technical Matrix</h5>
                <button wire:click="resetFilters" class="btn btn-link btn-sm text-danger text-decoration-none fw-bold small">
                    <i class="fa fa-refresh me-1"></i>RESET ALL FILTERS
                </button>
            </div>
            
            <div class="card-body bg-light border-top">
                <div class="row g-3">
                    {{-- Row 1: Identification & Market --}}
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted text-uppercase">Chassis or Model Code</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-0"><i class="fa fa-search text-muted"></i></span>
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-0" placeholder="e.g. W212, E350, 4MATIC...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Body Style</label>
                        <select wire:model.live="body_type" class="form-select border-0 shadow-sm">
                            <option value="">All Body Styles</option>
                            @foreach($bodyTypes as $bt)
                                <option value="{{ $bt->id }}">{{ $bt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Sales Market</label>
                        <select wire:model.live="destination" class="form-select border-0 shadow-sm">
                            <option value="">Global Market</option>
                            @foreach($destinations as $dest)
                                <option value="{{ $dest->id }}">{{ $dest->region_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Row 2: Mechanicals --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Engine Type</label>
                        <select wire:model.live="engine_type" class="form-select border-0 shadow-sm">
                            <option value="">All Engines</option>
                            @foreach($engineTypes as $et)
                                <option value="{{ $et->id }}">{{ $et->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Transmission</label>
                        <select wire:model.live="transmission_type" class="form-select border-0 shadow-sm">
                            <option value="">All Transmissions</option>
                            @foreach($transmissionTypes as $tt)
                                <option value="{{ $tt->id }}">{{ $tt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Drivetrain</label>
                        <select wire:model.live="drive" class="form-select border-0 shadow-sm">
                            <option value="">All Drivetrains</option>
                            @foreach($driveTypes as $dt)
                                <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Steering Side</label>
                        <select wire:model.live="steering_position" class="form-select border-0 shadow-sm">
                            <option value="">LHD & RHD</option>
                            <option value="LEFT">Left Hand Drive (LHD)</option>
                            <option value="RIGHT">Right Hand Drive (RHD)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Results Counter --}}
    <div class="container-fluid px-xl-5 mb-2">
        <div class="d-flex justify-content-between align-items-end">
            <p class="small text-muted mb-0">Found <strong>{{ count($specifications) }}</strong> matching technical profiles</p>
        </div>
    </div>

    {{-- Comprehensive Results Table --}}
    <div class="container-fluid px-xl-5 pb-5">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body p-0" wire:loading.class="opacity-50">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-white border-bottom">
                            <tr class="text-uppercase" style="font-size: 0.7rem; letter-spacing: 1.2px;">
                                <th class="ps-4 py-3">Technical ID</th>
                                <th>Body & Market</th>
                                <th>Powertrain Details</th>
                                <th>Mechanical Specs</th>
                                <th class="text-end pe-4">Catalog</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($specifications as $spec)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-subtle text-primary rounded p-2 me-3 text-center" style="width: 42px;">
                                                <i class="fa fa-id-card-o"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-6 mb-0">{{ $spec->model_code }}</div>
                                                <div class="text-primary small font-monospace fw-bold">{{ $spec->chassis_code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold text-dark">{{ $spec->bodyType->name ?? 'Universal' }}</div>
                                        <div class="small text-muted">
                                            <i class="fa fa-globe-americas me-1 small"></i>
                                            {{ $spec->destinations->pluck('region_name')->first() ?? 'Global' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold text-dark">{{ $spec->engineType->name ?? 'N/A' }}</div>
                                        <div class="small text-muted">
                                            <span class="text-primary fw-bold">{{ $spec->horsepower ?? '-' }} HP</span> 
                                            <span class="mx-1">|</span> 
                                            {{ $spec->torque ?? '-' }} Nm
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold">{{ $spec->transmissionType->name ?? '-' }}</div>
                                        <div class="small text-muted">
                                            <span class="badge bg-light text-dark border-0 shadow-sm me-1">{{ $spec->driveType->name ?? '-' }}</span>
                                            {{ $spec->steering_position }} Drive
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="#" class="btn btn-primary rounded-pill btn-sm px-4 shadow-sm hover-elevate fw-bold">
                                            Browse Parts <i class="fa fa-chevron-right ms-2 small"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-5">
                                            <i class="fa fa-filter display-1 text-light mb-3"></i>
                                            <h5 class="text-muted fw-bold">No Matching Configurations</h5>
                                            <p class="text-muted small">Try removing filters or searching for a broader chassis code.</p>
                                        </div>
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