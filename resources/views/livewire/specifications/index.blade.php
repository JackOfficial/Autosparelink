<div>
    {{-- Breadcrumbs --}}
    <div class="container-fluid">
        <div class="row px-xl-5 mt-2">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>
                    <span class="breadcrumb-item text-dark">{{ $item->vehicleModel->brand->brand_name }}</span>
                    <span class="breadcrumb-item text-dark">{{ $item->vehicleModel->model_name }}</span>
                    <span class="breadcrumb-item active">{{ $item->name }}</span>
                </nav>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <div class="container-fluid px-xl-5">
        <div class="bg-white p-4 shadow-sm rounded mb-3 d-flex align-items-center border-start border-primary border-5 justify-content-between">
            <div class="d-flex align-items-center">
                @if($item->vehicleModel->brand->brand_logo)
                    <img src="{{ asset('storage/' . $item->vehicleModel->brand->brand_logo) }}" style="width: 50px; margin-right: 15px;" alt="Logo" />
                @endif
                <div>
                    <h4 class="text-uppercase mb-1" style="font-weight: 700;">{{ $item->name }}</h4>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2">{{ $item->vehicleModel->model_name }}</span>
                        <small class="text-muted">Technical Specifications & Configurations</small>
                    </div>
                </div>
            </div>
            
            {{-- Loading Spinner --}}
            <div wire:loading class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="container-fluid px-xl-5 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <select wire:model.live="steering_position" class="form-control">
                            <option value="">Steering Side (All)</option>
                            <option value="LEFT">Left Hand Drive (LHD)</option>
                            <option value="RIGHT">Right Hand Drive (RHD)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select wire:model.live="drive" class="form-control">
                            <option value="">Drivetrain (All)</option>
                            @foreach($driveTypes as $driveItem)
                                <option value="{{ $driveItem->id }}">{{ $driveItem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search chassis, or model code...">
                    </div>
                    <div class="col-md-2">
                        <button wire:click="resetFilters" class="btn btn-outline-danger w-100 fw-bold">
                            <i class="fa fa-refresh me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Results Table --}}
    <div class="container-fluid px-xl-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0" wire:loading.class="opacity-50">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted" style="font-size: 0.8rem; text-transform: uppercase;">
                                <th class="ps-4">Identification</th>
                                <th>Performance</th>
                                <th>Efficiency</th>
                                <th>Configuration</th>
                                <th>Exterior</th>
                                <th class="text-center pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($specifications as $spec)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $spec->model_code }}</div>
                                        <div class="text-primary small fw-bold">{{ $spec->chassis_code }}</div>
                                        <small class="text-muted">
                                            {{ $spec->destinations->pluck('region_name')->join(', ') ?: 'Global Market' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-primary">{{ $spec->horsepower ?? '-' }} HP</span>
                                            <small class="text-muted">{{ $spec->torque ?? '-' }} Nm</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $spec->fuel_efficiency ?? '-' }}L/100 KM</span>
                                            <small class="text-muted">{{ $spec->fuel_capacity ?? '-' }}L Tank</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $spec->driveType->name ?? '-' }}</span>
                                            <small class="text-muted">{{ $spec->steering_position }} Drive</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($spec->color)
                                            <span class="me-1" style="display:inline-block; width:12px; height:12px; border-radius:50%; background:{{ $spec->color }}; border:1px solid #ddd;"></span>
                                        @endif
                                        <span class="small text-uppercase">{{ $spec->color ?? 'Standard' }}</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <a href="#" class="btn btn-dark btn-sm rounded-pill px-3">View Parts</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        No specifications found.
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