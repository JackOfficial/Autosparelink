@extends('admin.layouts.app')
@section('title', 'Transmission Types')
@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Transmission Types ({{ $transmissionTypes->count() }})</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Transmission Types</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.transmission-types.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Add Transmission Type
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="example1" class="table table-striped projects">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Gears Count</th>
                            <th>Description</th>
                            <th style="width:150px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($transmissionTypes as $type)
                        <tr>
                            <td>{{ $type->id }}</td>
                            <td>{{ $type->name }}</td>
                            <td>{{ $type->gears_count ?? '—' }}</td>
                            <td>{!! Str::limit($type->description, 50) !!}</td>

                            <td class="d-flex">
                                <a href="{{ route('admin.transmission-types.edit', $type->id) }}"
                                   class="btn btn-info btn-sm mr-2">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.transmission-types.destroy', $type->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this transmission type?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No transmission types available at the moment.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</section>

@endsection
