@extends('admin.layouts.app')

@section('title', 'Specifications')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Specifications</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Specifications</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        {{-- ACTION BAR --}}
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.specifications.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add Specification
            </a>
            <span class="text-muted">
              Total: <strong>{{ $groupedSpecs->sum(fn($group) => $group->count()) }}</strong> specifications
            </span>
        </div>

        {{-- GROUPED SPECIFICATIONS --}}
@forelse($groupedSpecs as $key => $specGroup)
    @php
        [$brand, $model, $variantName] = explode('|', $key);
        // Use the first spec's ID to ensure a unique collapse target
        $collapseId = 'variant-group-' . $specGroup->first()->id;
    @endphp

    <div class="card mb-4 shadow-sm border-left-primary">
        <div class="card-header bg-white d-flex justify-content-between align-items-center" 
             data-bs-toggle="collapse" 
             data-bs-target="#{{ $collapseId }}" 
             style="cursor:pointer;">
            <div>
                <span class="text-muted small text-uppercase fw-bold">{{ $brand }}</span>
                <h5 class="mb-0 text-dark">
                    {{ $model }} <i class="fa fa-angle-right mx-2 text-muted small"></i> {{ $variantName }}
                </h5>
            </div>
            <div>
                <span class="badge badge-pill bg-primary me-3">{{ $specGroup->count() }} Specs</span>
                <i class="fa fa-chevron-down text-muted"></i>
            </div>
        </div>

        <div id="{{ $collapseId }}" class="collapse show">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Body</th>
                                <th>Trans.</th>
                                <th>Fuel</th>
                                <th>Eng.</th>
                                <th>Power/Torque</th>
                                <th>Interior</th>
                                <th>Color</th>
                                <th class="text-center">Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($specGroup as $spec)
                                <tr>
                                    <td class="ps-3"><strong>{{ $spec->bodyType->name ?? '-' }}</strong></td>
                                    <td>{{ $spec->transmissionType->name ?? '-' }}</td>
                                    <td>{{ $spec->engineType->name ?? '-' }}</td>
                                    <td>{{ $spec->engineDisplacement->name ?? '-' }}</td>
                                    <td>
                                        <small class="d-block">{{ $spec->horsepower ?? '0' }} HP</small>
                                        <small class="text-muted">{{ $spec->torque ?? '0' }} Nm</small>
                                    </td>
                                    <td>
                                        <small title="Seats/Doors"><i class="fa fa-chair"></i> {{ $spec->seats }} | <i class="fa fa-door-open"></i> {{ $spec->doors }}</small>
                                    </td>
                                    <td>
                                        @if($spec->color)
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle border me-1" style="width: 15px; height: 15px; background-color: {{ $spec->color }}"></div>
                                                <small class="text-muted">{{ $spec->color }}</small>
                                            </div>
                                        @else - @endif
                                    </td>
                                    <td class="text-center">
                                        <i class="fa fa-circle {{ $spec->status ? 'text-success' : 'text-danger' }} small"></i>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.specifications.edit', $spec->id) }}" class="btn btn-sm btn-outline-warning border-0"><i class="fa fa-edit"></i></a>
                                            <form action="{{ route('admin.specifications.destroy', $spec->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Delete?')"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
        <p class="text-muted">No specifications found matching your criteria.</p>
    </div>
@endforelse

    </div>
</section>

@endsection
