@extends('admin.layouts.app')

@section('title', 'Brand Details')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $brand->brand_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.vehicle-brands.index') }}">Brands</a></li>
                    <li class="breadcrumb-item active">{{ $brand->brand_name }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">

    {{-- ================= Brand Details ================= --}}
    <div class="card card-primary mb-4 shadow-sm">
        <div class="card-body d-flex align-items-center">
            <div class="brand-photo me-4">
                @if($brand->brand_logo)
                    <img src="{{ asset('storage/' . $brand->brand_logo) }}" class="img-fluid rounded" alt="{{ $brand->brand_name }}" style="width: 150px; height: auto; object-fit: contain;">
                @else
                    <img src="{{ asset('images/placeholder.png') }}" class="img-fluid rounded" alt="Placeholder" style="width: 150px; height: auto; object-fit: contain;">
                @endif
            </div>
            <div class="brand-details">
                <h3 class="mb-2">{{ $brand->brand_name }}</h3>
                <p><strong>Country:</strong> {{ $brand->country ?? '-' }}</p>
                <p><strong>Website:</strong> 
                    @if($brand->website)
                        <a href="{{ $brand->website }}" target="_blank">{{ $brand->website }}</a>
                    @else
                        -
                    @endif
                </p>
                <p><strong>Description:</strong> {{ $brand->description ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- ================= Vehicle Models ================= --}}
    <h4 class="mb-3">Vehicle Models</h4>
    @if($brand->vehicleModels->count())
        <div class="row g-3">
            @foreach($brand->vehicleModels as $model)
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        @if($model->photo)
                            <img src="{{ asset('storage/' . $model->photo) }}" class="card-img-top" alt="{{ $model->model_name }}" style="height: 180px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/placeholder.png') }}" class="card-img-top" alt="Placeholder" style="height: 180px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $model->model_name }}</h5>
                            <p class="card-text">
                                Years: {{ $model->year_from ?? '-' }} - {{ $model->year_to ?? 'Present' }}
                            </p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('admin.vehicle-models.show', $model->id) }}" class="btn btn-sm btn-info me-2">
                                <i class="fa fa-eye"></i> View
                            </a>
                            <a href="{{ route('admin.vehicle-models.edit', $model->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 text-muted">
            No vehicle models found for this brand.
        </div>
    @endif

</section>
@endsection
