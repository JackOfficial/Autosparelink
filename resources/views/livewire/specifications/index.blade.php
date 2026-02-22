<div>
    {{-- Breadcrumbs - Sleeker look --}}
    <div class="container-fluid py-3">
        <div class="px-xl-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 m-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-primary">Home</a></li>
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
                <p class="mb-0 text-white-50">Select your specific configuration below to see compatible spare parts.</p>
            </div>
            
            <div class="d-flex align-items-center z-index-2">
                @if($item->vehicleModel->brand->brand_logo)
                    <img src="{{ asset('storage/' . $item->vehicleModel->brand->brand_logo) }}" class="img-fluid bg-white p-2 rounded-circle" style="width: 70px; height: 70px; object-fit: contain;" alt="Logo" />
                @endif
                
                {{-- Global Loading State --}}
                <div wire:loading class="ms-4">
                    <div class="spinner-grow text-primary" role="status"></div>
                </div>
            </div>
            
            {{-- Decorative background icon --}}
            <i class="fa fa-car position-absolute shadow-none" style="right: -20px; bottom: -30px; font-size: 150px; color: rgba(255,255,255,0.05);"></i>
        </div>
    </div>

    {{-- Advanced Filter Suite --}}
    <div class="container-fluid px-xl-5 mb-4">
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fa fa-sliders me-2 text-primary"></i>Technical Filters</h5>
                    <button wire:click="resetFilters" class="btn btn-link btn-sm text-danger text-decoration-none fw-bold">
                        <i class="fa fa-times-circle me-1"></i>Clear All
                    </button>
                </div>
            </div>
            <div class="card-body bg-light border-top">
                <div class="row g-3">
                    {{-- Row 1: Primary Configuration --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Steering Orientation</label>
                        <select wire:model.live="steering_position" class="form-select border-0 shadow-sm">
                            <option value="">Any Orientation</option>
                            <option value="LEFT">LHD (Left Hand Drive)</option>
                            <option value="RIGHT">RHD (Right Hand Drive)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Drivetrain Type</label>
                        <select wire:model.live="drive" class="form-select border-0 shadow-sm">
                            <option value="">All Drive Types</option>
                            @foreach($driveTypes as $driveItem)
                                <option value="{{ $driveItem->id }}">{{ $driveItem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Quick Search</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-0"><i class="fa fa-search text-muted"></i></span>
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-0" placeholder="Type Chassis Code (e.g. W204) or Model Code...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Results Counter --}}
    <div class="container-fluid px-xl-5 mb-2">
        <p class="small text-muted">Found <strong>{{ count($specifications) }}</strong> matching configuration(s)</p>
    </div>

    {{-- Main Results Grid/Table --}}
    <div class="container-fluid px-xl-5">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body p-0" wire:loading.class="opacity-50">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-white border-bottom">
                            <tr class="text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <th class="ps-4 py-3">Vehicle Details</th>
                                <th>Powertrain</th>
                                <th>Chassis & Drive</th>
                                <th>Market Info</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($specifications as $spec)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-subtle text-primary rounded p-2 me-3 text-center" style="width: 45px;">
                                                <i class="fa fa-barcode"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-6">{{ $spec->model_code }}</div>
                                                <div class="text-primary small font-monospace">{{ $spec->chassis_code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small"><i class="fa fa-bolt text-warning me-1"></i> <strong>{{ $spec->horsepower ?? '-' }}</strong> HP</div>
                                        <div class="small text-muted"><i class="fa fa-gas-pump me-1"></i> {{ $spec->engineType->name ?? 'Engine N/A' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $spec->driveType->name ?? '-' }}</span>
                                        <div class="small text-muted mt-1">{{ $spec->steering_position }} Hand Drive</div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold">{{ $spec->destinations->pluck('region_name')->first() ?? 'Global' }}</div>
                                        <div class="text-muted small">{{ $spec->production_start }} - {{ $spec->production_end ?? 'Present' }}</div>
                                    </td>
                                    <td class="text-end pe-4">
                                        {{-- This is the "Specific" button users click --}}
                                        <a href="#" class="btn btn-primary rounded-pill btn-sm px-4 shadow-sm hover-elevate">
                                            Select Vehicle <i class="fa fa-arrow-right ms-2"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fa fa-search-minus display-1 text-light mb-3"></i>
                                            <h5 class="text-muted">No exact match found</h5>
                                            <p class="text-muted small">Try adjusting your filters or search terms.</p>
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