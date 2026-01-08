@extends('layouts.app')

@section('style')
<style>
.table-hover tbody tr:hover { background-color: #f8f9fa; }
.info-icon { cursor: pointer; }
.options-tooltip { font-size: 0.85rem; color: #555; }
.collapse-row { transition: all 0.3s ease; }
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>
                @if(isset($model))
                    <a class="breadcrumb-item text-dark" href="{{ route('brand.models', $model->brand->id) }}">{{ $model->brand->brand_name }}</a>
                    <span class="breadcrumb-item active">{{ $model->model_name }}</span>
                @elseif(isset($variant))
                    <a class="breadcrumb-item text-dark" href="{{ route('brand.models', $variant->vehicleModel->brand->id) }}">{{ $variant->vehicleModel->brand->brand_name }}</a>
                    <span class="breadcrumb-item active">{{ $variant->name }}</span>
                @endif
            </nav>
        </div>
    </div>
</div>

<!-- Header -->
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded mb-3">
        <h4 class="text-uppercase mb-1" style="font-weight: 600;">
            @if(isset($model) && $model->brand->brand_logo)
                <img src="{{ asset('storage/' . $model->brand->brand_logo) }}" style="width:50px; height:auto;" />
            @elseif(isset($variant) && $variant->vehicleModel->brand->brand_logo)
                <img src="{{ asset('storage/' . $variant->vehicleModel->brand->brand_logo) }}" style="width:50px; height:auto;" />
            @endif

            @if(isset($model))
                {{ $model->brand->brand_name }} – {{ $model->model_name }}
            @elseif(isset($variant))
                {{ $variant->vehicleModel->brand->brand_name }} – {{ $variant->name }}
            @endif
        </h4>
        <small class="text-muted">Below is the list of specifications.</small>
    </div>
</div>

<!-- Specifications Table -->
<div class="container-fluid px-xl-5">
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Variant / Model</th>
                        <th>Body</th>
                        <th>Engine Type</th>
                        <th>Transmission</th>
                        <th>Drive Type</th>
                        <th>Steering</th>
                        <th>Trim</th>
                        <th>Doors</th>
                        <th>Seats</th>
                        <th>Horsepower</th>
                        <th>Torque</th>
                        <th>Fuel Efficiency</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($specifications as $spec)
                        <tr>
                            <td>
                                @if($spec->variant)
                                    {{ $spec->variant->name }}
                                @elseif($spec->vehicle_model)
                                    {{ $spec->vehicle_model->model_name }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $spec->bodyType->name ?? '-' }}</td>
                            <td>{{ $spec->engineType->name ?? '-' }}</td>
                            <td>{{ $spec->transmissionType->name ?? '-' }}</td>
                            <td>{{ $spec->driveType->name ?? '-' }}</td>
                            <td>{{ $spec->steering_position ?? '-' }}</td>
                            <td>{{ $spec->trim_level ?? '-' }}</td>
                            <td>{{ $spec->doors ?? '-' }}</td>
                            <td>{{ $spec->seats ?? '-' }}</td>
                            <td>{{ $spec->horsepower ?? '-' }}</td>
                            <td>{{ $spec->torque ?? '-' }}</td>
                            <td>{{ $spec->fuel_efficiency ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted">
                                No specifications found for this {{ isset($model) ? 'model' : 'variant' }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
