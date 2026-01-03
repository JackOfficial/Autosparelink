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

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Vehicle Specifications (Trims)</h3>
            <div class="card-tools">
                <a href="{{ route('admin.specifications.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> Add Specification
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Variant</th>
                        <th>Engine</th>
                        <th>Transmission</th>
                        <th>Drive</th>
                        <th>Years</th>
                        <th>Status</th>
                        <th width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($specifications as $spec)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                             {{ optional($spec->variant->vehicleModel)->model_name ?? '-' }}
                            </td>

                            <td>
                                {{ optional(optional($spec->variant->vehicleModel)->brand)->brand_name ?? '-' }}
                            </td>

                            <td>
                                {{ $spec->variant->name ?? '— No Variant —' }}
                            </td>

                            <td>
                                {{ $spec->engineType->name ?? '-' }}
                            </td>

                            <td>
                                {{ $spec->transmissionType->name ?? '-' }}
                            </td>

                            <td>
                                {{ $spec->driveType->name ?? '-' }}
                            </td>

                            <td>
                                {{ $spec->production_start ?? '?' }} -
                                {{ $spec->production_end ?? 'Present' }}
                            </td>

                            <td>
                                @if($spec->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('admin.specifications.show', $spec->id) }}"
                                   class="btn btn-xs btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.specifications.edit', $spec->id) }}"
                                   class="btn btn-xs btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.specifications.destroy', $spec->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this specification?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-xs btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                No specifications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($specifications, 'links'))
            <div class="card-footer clearfix">
                {{ $specifications->links() }}
            </div>
        @endif
    </div>

</section>

@endsection
