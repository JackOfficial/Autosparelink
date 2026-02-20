@extends('admin.layouts.app')
@section('title', 'Vehicle Models')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Vehicle Models</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Vehicle Models</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content" x-data="{ 
    search: '',
    /** * Helper to check if a brand or any of its models match the search 
     */
    shouldShowBrand(brandName, models) {
        if (!this.search) return true;
        let term = this.search.toLowerCase();
        if (brandName.toLowerCase().includes(term)) return true;
        return models.some(m => m.model_name.toLowerCase().includes(term));
    }
}">
    <div class="row mb-3 align-items-center">
        <div class="col-md-8">
            <div class="input-group shadow-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                </div>
                <input type="text" 
                       x-model="search" 
                       class="form-control border-left-0" 
                       placeholder="Search brands or model names...">
                <div class="input-group-append" x-show="search.length > 0">
                    <button class="btn btn-outline-secondary border-left-0" @click="search = ''" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-right mt-md-0 mt-2">
            <a href="{{ route('admin.vehicle-models.create') }}" class="btn btn-primary shadow-sm">
                <i class="fa fa-plus-circle mr-1"></i> Add New Model
            </a>
        </div>
    </div>

    <div class="mb-3">
        <span class="badge badge-info shadow-sm p-2">
            Total Models: {{ $brands->sum(fn ($b) => $b->vehicle_models_count ?? $b->vehicleModels->count()) }}
        </span>
    </div>

    @forelse($brands as $brand)
        <div class="card card-outline card-primary mb-4 shadow-sm"
             x-show="shouldShowBrand('{{ addslashes($brand->brand_name) }}', {{ json_encode($brand->vehicleModels) }})"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100">
            
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-industry mr-2 text-muted"></i>
                    {{ $brand->brand_name }} 
                    <span class="text-muted small ml-2">({{ $brand->vehicleModels->count() }} variants)</span>
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <table class="table table-valign-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 80px">Photo</th>
                            <th>Model Identity</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brand->vehicleModels as $model)
                            <tr x-show="!search || 
                                       '{{ addslashes(strtolower($brand->brand_name)) }}'.includes(search.toLowerCase()) || 
                                       '{{ addslashes(strtolower($model->model_name)) }}'.includes(search.toLowerCase())">
                                <td>
                                    @if($model->photos->isNotEmpty())
                                        <img src="{{ asset('storage/'.$model->photos->first()->file_path) }}" 
                                             class="img-size-50 rounded shadow-sm border">
                                    @else
                                        <div class="bg-light text-center rounded border" style="width:50px; height:50px; line-height:50px;">
                                            <i class="fa fa-car text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $model->model_name }}</strong>
                                    <div class="small text-muted">Year: {{ Str::limit($model->description, 50) ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-light border">
                                        {{ $model->production_start_year }} - {{ $model->production_end_year ?? 'Present' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $model->status ? 'success' : 'secondary' }}">
                                        {{ $model->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.vehicle-models.show', $model->id) }}" class="btn btn-default btn-sm shadow-sm" title="View">
                                            <i class="fa fa-eye text-info"></i>
                                        </a>
                                        <a href="{{ route('admin.vehicle-models.edit', $model->id) }}" class="btn btn-default btn-sm shadow-sm" title="Edit">
                                            <i class="fas fa-edit text-warning"></i>
                                        </a>
                                        <form action="{{ route('admin.vehicle-models.destroy', $model->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this model?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-default btn-sm shadow-sm" title="Delete">
                                                <i class="fas fa-trash text-danger"></i>
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
    @empty
        <div class="text-center py-5">
            <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">No vehicle models available.</p>
        </div>
    @endforelse
</section>

@endsection