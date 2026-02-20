@extends('admin.layouts.app')

@section('title', 'Variants')

@section('content')
<div x-data="{ 
    search: '',
    itemsFound: true,
    checkResults() {
        this.$nextTick(() => {
            let visible = document.querySelectorAll('.variant-row:not([style*=\'display: none\'])').length;
            this.itemsFound = visible > 0;
        });
    }
}" class="pb-5">

    <section class="content-header sticky-top bg-light pb-2 shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-4">
                    <h1 class="m-0">Vehicle Variants</h1>
                </div>
                <div class="col-sm-5">
                    {{-- SEARCH BOX --}}
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                        <input type="text" 
                               x-model="search" 
                               @input="checkResults()"
                               class="form-control border-start-0 ps-0" 
                               placeholder="Search brand, model, trim, or chassis code...">
                        <button x-show="search.length > 0" @click="search = ''; checkResults()" class="btn btn-outline-secondary border-start-0" type="button">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="col-sm-3 text-end">
                    <a href="{{ route('admin.variants.create') }}" class="btn btn-success">
                        <i class="fa fa-plus"></i> New Variant
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content mt-3">
        <div class="container-fluid">

            {{-- EMPTY STATE --}}
            <div x-show="!itemsFound" x-cloak class="text-center py-5">
                <i class="fa fa-search fa-3x text-muted mb-3"></i>
                <p class="h5 text-muted">No variants match "<span x-text="search"></span>"</p>
                <button @click="search = ''; checkResults()" class="btn btn-link">Clear search</button>
            </div>

            @forelse($brands as $brand)
                {{-- Only show brand card if it contains models that match the search --}}
                <div class="card mb-4 shadow-sm variant-card" 
                     x-show="search === '' || $el.innerText.toLowerCase().includes(search.toLowerCase())">
                    
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fa fa-car-side me-2 text-warning"></i>
                            <strong>{{ $brand->brand_name }}</strong>
                        </h3>
                        <span class="badge bg-secondary">{{ $brand->vehicleModels->sum(fn($m) => $m->variants->count()) }} Total</span>
                    </div>

                    <div class="card-body p-0">
                        @foreach($brand->vehicleModels as $model)
                            <div class="model-section p-3 border-bottom"
                                 x-show="search === '' || $el.innerText.toLowerCase().includes(search.toLowerCase())">
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="text-primary fw-bold mb-0">
                                        {{ $model->model_name }}
                                    </h5>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">#</th>
                                                <th width="80">Photo</th>
                                                <th>Variant Info</th>
                                                <th>Chassis</th>
                                                <th>Status</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($model->variants as $variant)
                                                @php 
                                                    $searchData = strtolower($brand->brand_name . ' ' . $model->model_name . ' ' . $variant->name . ' ' . $variant->trim_level . ' ' . $variant->chassis_code);
                                                @endphp
                                                <tr class="variant-row" x-show="search === '' || '{{ $searchData }}'.includes(search.toLowerCase())">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        @if($variant->photo)
                                                            <img src="{{ asset('storage/'.$variant->photo) }}" 
                                                                 class="rounded shadow-sm" 
                                                                 style="width:50px; height:50px; object-fit:cover;">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                                                <i class="fa fa-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold">{{ $variant->name }}</div>
                                                        <div class="small text-muted">{{ $variant->trim_level ?? 'Standard Trim' }}</div>
                                                    </td>
                                                    <td><code class="text-dark">{{ $variant->chassis_code ?? 'N/A' }}</code></td>
                                                    <td>
                                                        <span class="badge rounded-pill {{ $variant->status ? 'bg-success' : 'bg-light text-dark border' }}">
                                                            {{ $variant->status ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="btn-group shadow-sm">
                                                            <a href="{{ route('admin.variants.show', $variant->id) }}" class="btn btn-white btn-sm border" title="Details">
                                                                <i class="fa fa-eye text-info"></i>
                                                            </a>
                                                            <a href="{{ route('admin.variants.edit', $variant->id) }}" class="btn btn-white btn-sm border" title="Edit">
                                                                <i class="fa fa-edit text-warning"></i>
                                                            </a>
                                                            <form action="{{ route('admin.variants.destroy', $variant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this variant?');">
                                                                @csrf @method('DELETE')
                                                                <button class="btn btn-white btn-sm border" title="Delete">
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
                <div class="card card-body text-center py-5">
                    <p class="text-muted">No brands or variants available.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>

<style>
    [x-cloak] { display: none !important; }
    .sticky-top { top: 0; z-index: 1020; }
    .variant-row { transition: all 0.2s; }
    .btn-white { background: white; }
    .btn-white:hover { background: #f8f9fa; }
</style>
@endsection