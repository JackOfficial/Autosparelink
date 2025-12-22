@extends('admin.layouts.app')
@section('title', 'Engine Types')
@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Engine Types ({{ $engineTypes->count() }})</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Engine Types</li>
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
            <a href="{{ route('admin.engine-types.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Add Engine Type
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="example1" class="table table-striped projects">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th style="width:150px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($engineTypes as $engine)
                        <tr>
                            <td>{{ $engine->id }}</td>

                            <!-- Icon -->
                            <td>
                                @if($engine->icon_url)
                                    <img src="{{ asset('storage/' . $engine->icon_url) }}"
                                         class="img-thumbnail"
                                         style="width: 70px; height:auto;" />
                                @else
                                    <span class="text-muted">No icon</span>
                                @endif
                            </td>

                            <!-- Name -->
                            <td>
                                <strong>{{ $engine->name }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $engine->created_at }}
                                </small>
                            </td>

                            <!-- Description -->
                            <td>{!! Str::limit($engine->description, 60) !!}</td>

                            <!-- Actions -->
                            <td class="d-flex">

                                <a href="{{ route('admin.engine-types.edit', $engine->id) }}"
                                   class="btn btn-info btn-sm mr-2">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.engine-types.destroy', $engine->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this engine type?');">
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
                                No engine types available at the moment.
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
