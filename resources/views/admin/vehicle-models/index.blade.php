@extends('admin.layouts.app')
@section('title', 'Vehicle Models')
@section('content')

<!-- Content Header -->
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

<!-- Main content -->
<section class="content">
    <div class="row mb-3 align-items-center">
        <div class="col-6">
            <span class="badge badge-info shadow-sm p-2">
                Total Models: {{ $brands->sum(fn ($b) => $b->vehicle_models_count ?? $b->vehicleModels->count()) }}
            </span>
        </div>
        <div class="col-6 text-right">
            <a href="{{ route('admin.vehicle-models.create') }}" class="btn btn-primary shadow-sm">
                <i class="fa fa-plus-circle mr-1"></i> Add New Model
            </a>
        </div>
    </div>

    @forelse($brands as $brand)
        <div class="card card-outline card-primary mb-4 shadow-sm">
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
                            <th>Production Range</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brand->vehicleModels as $model)
                        <tr>
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
                                <div class="small text-muted">Year: {{ $model->year ?? 'N/A' }}</div>
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
                                    <a href="{{ route('admin.vehicle-models.show', $model->id) }}" class="btn btn-default btn-sm shadow-sm">
                                        <i class="fa fa-eye text-info"></i>
                                    </a>
                                    <a href="{{ route('admin.vehicle-models.edit', $model->id) }}" class="btn btn-default btn-sm shadow-sm">
                                        <i class="fas fa-edit text-warning"></i>
                                    </a>
                                    {{-- Delete Form here --}}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No models found for this brand.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        {{-- Empty state --}}
    @endforelse
</section>

@endsection
