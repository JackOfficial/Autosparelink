<div>
    <div class="container-fluid px-xl-5">
        {{-- Technical Header Card --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; border-left: 5px solid #007bff !important;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="font-weight-bold text-dark mb-1">
                            Technical Configuration: {{ $specification->model_code }}
                        </h4>
                        <p class="text-muted mb-0">
                            Chassis: <span class="font-weight-bold text-primary">{{ $specification->chassis_code }}</span> | 
                            Market: {{ $specification->destinations->first()->region_name ?? 'Global' }}
                        </p>
                    </div>
                    <div class="col-md-4 mt-3 mt-md-0">
                        <div class="input-group border rounded-pill bg-light">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-transparent border-0 text-muted"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="search" 
                                   class="form-control bg-transparent border-0 shadow-none" 
                                   placeholder="Search parts by name or ID...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Parts List Table --}}
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-uppercase text-muted small font-weight-bold" style="letter-spacing: 1px;">
                            <th class="pl-4 py-3 border-0">Part Reference</th>
                            <th class="py-3 border-0">Category</th>
                            <th class="py-3 border-0">OEM Number</th>
                            <th class="text-right pr-4 py-3 border-0">Availability</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parts as $part)
                            <tr>
                                <td class="pl-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded p-2 mr-3 d-none d-sm-block" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa fa-cog"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">{{ $part->name }}</div>
                                            <small class="text-muted">ID: #{{ str_pad($part->id, 6, '0', STR_PAD_LEFT) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 align-middle">
                                    <span class="badge badge-pill badge-light text-dark border px-3 py-2">
                                        {{ $part->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="py-4 align-middle">
                                    <code class="text-primary font-weight-bold">{{ $part->part_number ?? 'N/A' }}</code>
                                </td>
                                <td class="text-right pr-4 py-4 align-middle">
                                    <button class="btn btn-sm btn-outline-primary px-3 font-weight-bold" style="border-radius: 8px;">
                                        View Specs
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="fa fa-box-open fa-3x text-muted mb-3 opacity-25"></i>
                                    <h5 class="text-muted">No parts compatible with this configuration.</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($parts->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $parts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>