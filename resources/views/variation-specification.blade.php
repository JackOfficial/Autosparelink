@extends('layouts.app')

@section('style')
<style>
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}
.info-icon {
    cursor: pointer;
}
.options-tooltip {
    font-size: 0.85rem;
    color: #555;
}
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>
                <a class="breadcrumb-item text-dark" href="#">{{ $model->brand->brand_name }}</a>
                <span class="breadcrumb-item active">{{ $model->model_name }}</span>
            </nav>
        </div>
    </div>
</div>

<!-- Header -->
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded mb-3">
        <h4 class="text-uppercase mb-1" style="font-weight: 600;">
            @if($model->brand->brand_logo)
                <img src="{{ asset('storage/' . $model->brand->brand_logo) }}"
                     style="width: 50px; height:auto;" />
            @endif
            {{ $model->brand->brand_name }} – {{ $model->model_name }}
        </h4>
        <small class="text-muted">Click the <i class="fas fa-info-circle"></i> icon for full variant details.</small>
    </div>
</div>

<!-- Filters -->
<div class="container-fluid px-xl-5 mb-3">
    <div class="card">
        <div class="card-header">Filters</div>
        <div class="card-body">
            <form method="GET" action="">
                <div class="row">
                    <div class="col-md-2 form-group">
                        <input type="text" name="frame" class="form-control" placeholder="Frame" value="{{ request('frame') }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="text" name="year" class="form-control" placeholder="Year" value="{{ request('year') }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="text" name="body" class="form-control" placeholder="Body" value="{{ request('body') }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="text" name="driver_position" class="form-control" placeholder="Driver's Position" value="{{ request('driver_position') }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="text" name="engine" class="form-control" placeholder="Engine" value="{{ request('engine') }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <button class="btn btn-sm btn-primary w-100" type="submit">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Variants Table -->
<div class="container-fluid px-xl-5">
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Variant Name</th>
                        <th>Description</th>
                        <th>Model</th>
                        <th>Production Period</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($variants as $v)
                    <tr>
                        <td><a href="/spare-parts/{{ $v->id }}">{{ $v->name ?? 'N/A' }}</a></td>
                        <td>{{ $v->model_code ?? '-' }}</td>
                        <td>{{ $v->chassis_code ?? '-' }}</td>
                        <td>
                            {{ $v->production_start ?? '-' }} 
                            - 
                            {{ $v->production_end ?? '-' }}
                        </td>
                        <td>
                            <a class="info-icon" data-toggle="collapse" href="#variant-{{ $v->id }}" role="button" aria-expanded="false" aria-controls="variant-{{ $v->id }}">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </td>
                    </tr>
                    <!-- Collapsible Row for Detailed Specs -->
                    <tr class="collapse" id="variant-{{ $v->id }}">
                        <td colspan="5">
                            <div class="p-3 bg-light rounded">
                                <div class="row">
                                    <div class="col-md-4 options-tooltip">
                                        <strong>Body:</strong> {{ $v->bodyType->name ?? '-' }}<br>
                                        <strong>Engine Type:</strong> {{ $v->engineType->name ?? '-' }}<br>
                                        <strong>Transmission:</strong> {{ $v->transmissionType->name ?? '-' }}
                                    </div>
                                    <div class="col-md-4 options-tooltip">
                                        <strong>Drive Type:</strong> {{ $v->driveType->name ?? '-' }}<br>
                                        <strong>Steering:</strong> {{ $v->steering_position ?? '-' }}<br>
                                        <strong>Trim:</strong> {{ $v->trim_level ?? '-' }}
                                    </div>
                                    <div class="col-md-4 options-tooltip">
                                        <strong>Doors:</strong> {{ $v->doors ?? '-' }}<br>
                                        <strong>Seats:</strong> {{ $v->seats ?? '-' }}<br>
                                        <strong>Horsepower:</strong> {{ $v->horsepower ?? '-' }}<br>
                                        <strong>Torque:</strong> {{ $v->torque ?? '-' }}<br>
                                        <strong>Fuel Efficiency:</strong> {{ $v->fuel_efficiency ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
