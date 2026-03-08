@extends('admin.layouts.app')

@section('title', 'Variants')

@section('content')
<div x-data="{ 
    search: '',
    itemsFound: true,
    checkResults() {
        this.$nextTick(() => {
            // Count rows that aren't hidden by the search
            let visible = document.querySelectorAll('.variant-row:not([style*=\'display: none\'])').length;
            this.itemsFound = visible > 0;
        });
    }
}" class="pb-5">

    <section class="content-header sticky-top bg-light pb-3 shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-4">
                    <h1 class="m-0 fw-bold">Vehicle Variants</h1>
                </div>
                <div class="col-sm-5">
                    <div class="input-group border rounded-pill overflow-hidden bg-white shadow-sm">
                        <span class="input-group-text bg-white border-0"><i class="fa fa-search text-muted"></i></span>
                        <input type="text" 
                               x-model="search" 
                               @input="checkResults()"
                               class="form-control border-0 ps-0 shadow-none" 
                               placeholder="Search brand, model, trim, or chassis code...">
                        <button x-show="search.length > 0" @click="search = ''; checkResults()" class="btn btn-white border-0" type="button">
                            <i class="fa fa-times text-muted"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content mt-3">
        <div class="container-fluid">

            {{-- EMPTY STATE --}}
            <div x-show="!itemsFound" x-cloak class="text-center py-5 bg-white rounded shadow-sm">
                <i class="fa fa-search fa-3 (text-muted mb-3"></i>
                <p class="h5 text-muted">No variants match "<span x-text="search" class="fw-bold"></span>"</p>
                <button @click="search = ''; checkResults()" class="btn btn-primary rounded-pill mt-2">Clear search</button>
            </div>

            @forelse($brands as $brand)
                {{-- Only show brand card if it contains matching variants --}}
                <div class="card mb-4 shadow-sm variant-card border-0" 
                     x-data="{ hasVisibleVariants: true }"
                     x-show="search === '' || hasVisibleVariants"
                     x-init="$watch('search', () => { 
                        $nextTick(() => { 
                            hasVisibleVariants = $el.querySelectorAll('.variant-row:not([style*=\'display: none\'])').length > 0 
                        }) 
                     })">
                    
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0 fs-5">
                            <i class="fa fa-car-side me-2 text-warning"></i>
                            <strong>{{ $brand->brand_name }}</strong>
                        </h3>
                        <span class="badge bg-secondary rounded-pill">{{ $brand->vehicleModels->sum(fn($m) => $m->variants->count()) }} Total</span>
                    </div>

                    <div class="card-body p-0">
                        @foreach($brand->vehicleModels as $model)
                            {{-- Nested model visibility logic --}}
                            <div class="model-section p-0 border-bottom"
                                 x-data="{ hasVisibleInModel: true }"
                                 x-show="search === '' || hasVisibleInModel"
                                 x-init="$watch('search', () => { 
                                    $nextTick(() => { 
                                        hasVisibleInModel = $el.querySelectorAll('.variant-row:not([style*=\'display: none\'])').length > 0 
                                    }) 
                                 })">
                                
                                <div class="bg-light-subtle px-3 py-2 border-bottom">
                                    <h5 class="text-primary fw-bold mb-0" style="font-size: 1.1rem;">
                                        {{ $model->model_name }}
                                    </h5>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light small text-uppercase">
                                            <tr>
                                                <th width="50" class="ps-3">#</th>
                                                <th width="80">Photo</th>
                                                <th>Year & Trim</th>
                                                <th>Identification</th>
                                                <th>Status</th>
                                                <th class="text-end pe-3">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($model->variants as $variant)
                                                @php 
        // Use ->first() to get the actual object out of the collection
        $spec = $variant->specifications->first(); 
        
        $searchData = strtolower(
            $brand->brand_name . ' ' . 
            $model->model_name . ' ' . 
            $variant->name . ' ' . 
            ($spec->chassis_code ?? '') . ' ' . 
            ($spec->model_code ?? '') . ' ' . 
            $variant->production_year
        );
    @endphp
                                                <tr class="variant-row" x-show="search === '' || '{{ $searchData }}'.includes(search.toLowerCase())">
                                                    <td class="ps-3 text-muted small">{{ $loop->iteration }}</td>
                                                    <td>
                                                        @if($variant->photo)
                                                            <img src="{{ asset('storage/'.$variant->photo) }}" class="rounded border shadow-sm" style="width:45px; height:45px; object-fit:cover;">
                                                        @else
                                                            <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="width:45px; height:45px;">
                                                                <i class="fa fa-image fa-xs opacity-50"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center mb-0">
                                                            <span class="badge bg-primary text-white me-2" style="font-size: 0.75rem;">
                                                                {{ $variant->production_year }}
                                                            </span>
                                                            <span class="fw-bold text-dark">{{ $variant->trim_level }}</span>
                                                        </div>
                                                        <div class="small text-muted text-truncate" style="max-width: 200px;" title="{{ $variant->name }}">
                                                            {{ $variant->name }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="small">
                                                            <span class="text-muted small text-uppercase fw-bold" style="font-size: 0.65rem;">Chassis:</span>
                                                            <code class="text-danger fw-bold bg-light px-1 rounded">{{ $spec->chassis_code ?? 'N/A' }}</code>
                                                        </div>
                                                        <div class="small">
                                                            <span class="text-muted small text-uppercase fw-bold" style="font-size: 0.65rem;">Code:</span>
                                                            <span class="text-dark font-monospace">{{ $spec->model_code ?? 'N/A' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($variant->status)
                                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3">
                                                                Active
                                                            </span>
                                                        @else
                                                            <span class="badge rounded-pill bg-light text-muted border px-3">
                                                                Inactive
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end pe-3">
                                                        <div class="btn-group btn-group-sm shadow-sm border rounded">
                                                            <a href="{{ route('admin.variants.show', $variant->id) }}" class="btn btn-white px-2" title="View Technical Specs">
                                                                <i class="fa fa-eye text-primary"></i>
                                                            </a>
                                                            <a href="{{ route('admin.variants.edit', $variant->id) }}" class="btn btn-white border-start px-2" title="Edit">
                                                                <i class="fa fa-edit text-warning"></i>
                                                            </a>
                                                            <form action="{{ route('admin.variants.destroy', $variant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Permanently delete this variant?');">
                                                                @csrf @method('DELETE')
                                                                <button class="btn btn-white border-start px-2" title="Delete">
                                                                    <i class="fa fa-trash text-danger"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="card card-body text-center py-5 border-0 shadow-sm">
                    <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No brands or variants available.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>

<style>
    [x-cloak] { display: none !important; }
    .sticky-top { top: 0; z-index: 1020; }
    .variant-row { transition: background-color 0.2s; }
    .variant-row:hover { background-color: rgba(0,0,0,0.02); }
    .btn-white { background: white; color: #6c757d; }
    .btn-white:hover { background: #f8f9fa; color: #333; }
    .bg-light-subtle { background-color: #fcfcfd; }
</style>
@endsection