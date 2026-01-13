@extends('layouts.app')

@section('style')
<style>
.table-hover tbody tr:hover { background-color: #f8f9fa; }
.info-icon { cursor: pointer; }
.options-tooltip { font-size: 0.85rem; color: #555; }
.collapse-row { transition: all 0.3s ease; }

/* Filter Card */
.filter-card .form-control { height: calc(2.2rem + 2px); }
.filter-card .btn { height: calc(2.2rem + 2px); }
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>

                @if($type === 'model')
                    <span class="breadcrumb-item active">{{ $item->model_name }}</span>
                @elseif($type === 'variant')
                    <span class="breadcrumb-item active">{{ $item->name }}</span>
                @endif
            </nav>
        </div>
    </div>
</div>

<!-- Header -->
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded mb-3">
        <h4 class="text-uppercase mb-1" style="font-weight: 600;">
            @if($type === 'model' && $item->brand->brand_logo)
                <img src="{{ asset('storage/' . $item->brand->brand_logo) }}" style="width:50px; height:auto;" />
            @elseif($type === 'variant' && $item->vehicleModel->brand->brand_logo)
                <img src="{{ asset('storage/' . $item->vehicleModel->brand->brand_logo) }}" style="width:50px; height:auto;" />
            @endif

            @if($type === 'model')
                {{ $item->brand->brand_name }} – {{ $item->model_name }}
            @elseif($type === 'variant')
                {{ $item->vehicleModel->brand->brand_name }} – {{ $item->name }}
            @endif
        </h4>
        <small class="text-muted">Below is the list of specifications. Use filters to narrow down results.</small>
    </div>
</div>

<!-- Filters -->
<div class="container-fluid px-xl-5 mb-3">
    <div class="card shadow-sm filter-card">
        <div class="card-body">
            <form method="GET" action="">
                <div class="row g-2">

                    <!-- Variant / Model -->
                    <div class="col-md-2">
                        <select name="variant_id" class="form-control">
                            <option value="">Variant / Model</option>
                            @foreach($vehicleModels as $vm)
                                <option value="{{ $vm->id }}" {{ request('variant_id') == $vm->id ? 'selected' : '' }}>
                                    {{ $vm->model_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Body Type -->
                    <div class="col-md-2">
                        <select name="body" class="form-control">
                            <option value="">Body</option>
                            @foreach($bodyTypes as $body)
                                <option value="{{ $body->id }}" {{ request('body') == $body->id ? 'selected' : '' }}>
                                    {{ $body->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Engine Type -->
                    <div class="col-md-2">
                        <select name="engine" class="form-control">
                            <option value="">Engine</option>
                            @foreach($engineTypes as $engine)
                                <option value="{{ $engine->id }}" {{ request('engine') == $engine->id ? 'selected' : '' }}>
                                    {{ $engine->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Transmission Type -->
                    <div class="col-md-2">
                        <select name="transmission" class="form-control">
                            <option value="">Transmission</option>
                            @foreach($transmissionTypes as $trans)
                                <option value="{{ $trans->id }}" {{ request('transmission') == $trans->id ? 'selected' : '' }}>
                                    {{ $trans->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Drive Type -->
                    <div class="col-md-2">
                        <select name="drive" class="form-control">
                            <option value="">Drive Type</option>
                            @foreach($driveTypes as $drive)
                                <option value="{{ $drive->id }}" {{ request('drive') == $drive->id ? 'selected' : '' }}>
                                    {{ $drive->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year From -->
                    <div class="col-md-2">
                        <input type="number" name="production_start" class="form-control" placeholder="Year From" value="{{ request('production_start') }}">
                    </div>

                    <!-- Year To -->
                    <div class="col-md-2">
                        <input type="number" name="production_end" class="form-control" placeholder="Year To" value="{{ request('production_end') }}">
                    </div>

                    <!-- Seats -->
                    <div class="col-md-2">
                        <input type="number" name="seats" class="form-control" placeholder="Seats" value="{{ request('seats') }}">
                    </div>

                    <!-- Doors -->
                    <div class="col-md-2">
                        <input type="number" name="doors" class="form-control" placeholder="Doors" value="{{ request('doors') }}">
                    </div>

                    <!-- Horsepower -->
                    <div class="col-md-2">
                        <input type="number" name="horsepower" class="form-control" placeholder="Horsepower" value="{{ request('horsepower') }}">
                    </div>

                    <!-- Torque -->
                    <div class="col-md-2">
                        <input type="number" name="torque" class="form-control" placeholder="Torque" value="{{ request('torque') }}">
                    </div>

                    <!-- Steering -->
                    <div class="col-md-2">
                        <select name="steering_position" class="form-control">
                            <option value="">Steering</option>
                            <option value="Left" {{ request('steering_position') == 'Left' ? 'selected' : '' }}>Left</option>
                            <option value="Right" {{ request('steering_position') == 'Right' ? 'selected' : '' }}>Right</option>
                        </select>
                    </div>

                    <!-- Fuel Efficiency -->
                    <div class="col-md-2">
                        <input type="number" name="fuel_efficiency" class="form-control" placeholder="Fuel Efficiency" value="{{ request('fuel_efficiency') }}">
                    </div>

                    <!-- Submit -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>

                </div>
            </form>
        </div>
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
                        <th>Year</th>
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
        <a href="{{ route('specification.parts', [
            'type' => 'variant',
            'id'   => $spec->variant->id
        ]) }}">
            {{ $spec->variant->name }}
        </a>

    @elseif($spec->vehicleModel)
        <a href="{{ route('specification.parts', [
            'type' => 'model',
            'id'   => $spec->vehicleModel->id
        ]) }}">
            {{ $spec->vehicleModel->model_name }}
        </a>

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
                            <td>{{ $spec->production_start ?? '-' }} - {{ $spec->production_end ?? 'Present' }}</td>
                            <td>{{ $spec->doors ?? '-' }}</td>
                            <td>{{ $spec->seats ?? '-' }}</td>
                            <td>{{ $spec->horsepower ?? '-' }}</td>
                            <td>{{ $spec->torque ?? '-' }}</td>
                            <td>{{ $spec->fuel_efficiency ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted">
                                No specifications found for this {{ $type }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
