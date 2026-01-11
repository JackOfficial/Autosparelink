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
                [$brand, $model, $variant] = explode('|', $key);
                $collapseId = 'collapse-'.Str::slug($brand.'-'.$model.'-'.$variant);
            @endphp

            <div class="card mb-3">
                <div class="card-header bg-primary text-white" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" style="cursor:pointer;">
                    <h5 class="card-title mb-0">
                        {{ $brand }} 
                        <small class="text-light">/ {{ $model }} / {{ $variant }}</small>
                        <span class="badge bg-light text-dark ms-2">{{ $specGroup->count() }} Spec{{ $specGroup->count() > 1 ? 's' : '' }}</span>
                        <i class="fa fa-chevron-down float-end"></i>
                    </h5>
                </div>

                <div id="{{ $collapseId }}" class="collapse show">
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-striped table-hover text-nowrap align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Body Type</th>
                                    <th>Engine</th>
                                    <th>Transmission</th>
                                    <th>Drive</th>
                                    <th>Horsepower</th>
                                    <th>Torque</th>
                                    <th>Fuel Cap.</th>
                                    <th>Seats</th>
                                    <th>Doors</th>
                                    <th>Fuel Eff.</th>
                                    <th>Steering</th>
                                    <th>Color</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($specGroup as $spec)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $spec->bodyType->name ?? '-' }}</td>
                                        <td>{{ $spec->engineType->name ?? '-' }}</td>
                                        <td>{{ $spec->transmissionType->name ?? '-' }}</td>
                                        <td>{{ $spec->driveType->name ?? '-' }}</td>
                                        <td>{{ $spec->horsepower ?? '-' }}</td>
                                        <td>{{ $spec->torque ?? '-' }}</td>
                                        <td>{{ $spec->fuel_capacity ?? '-' }}</td>
                                        <td>{{ $spec->seats ?? '-' }}</td>
                                        <td>{{ $spec->doors ?? '-' }}</td>
                                        <td>{{ $spec->fuel_efficiency ?? '-' }}</td>
                                        <td>{{ $spec->steering_position ?? '-' }}</td>
                                        <td>
                                          <div class="border rounded-circle" style="width: 25px; height: 25px; background-color: {{ $spec->color ?? '#fff' }}"></div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $spec->status ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $spec->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="d-flex gap-1">
                                            <a href="{{ route('admin.specifications.show', $spec->id) }}" class="btn btn-xs btn-info">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.specifications.edit', $spec->id) }}" class="btn btn-xs btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.specifications.destroy', $spec->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this specification?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted py-4">No specifications found.</p>
        @endforelse

    </div>
</section>

@endsection
