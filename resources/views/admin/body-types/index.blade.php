@extends('admin.layouts.app')
@section('title', 'Body Types')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Body Types ({{ $bodyTypes->count() }})</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Body Types</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.body-types.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Add Body Type
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped projects">
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
                        @forelse($bodyTypes as $bodyType)
                        <tr>
                            <td>{{ $bodyType->id }}</td>
                            <td>
                                @if($bodyType->icon_url)
                                    <img src="{{ asset('storage/' . $bodyType->icon_url) }}" class="img-thumbnail" style="width:80px">
                                @else
                                    <span class="text-muted">No icon</span>
                                @endif
                            </td>
                            <td>{{ $bodyType->name }}</td>
                            <td>{!! \Illuminate\Support\Str::limit($bodyType->description, 50) !!}</td>
                            <td class="d-flex">
                                <a href="{{ route('admin.body-types.edit', $bodyType->id) }}" class="btn btn-info btn-sm mr-2"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.body-types.destroy', $bodyType->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No body types available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>

@endsection
