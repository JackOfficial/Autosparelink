@extends('admin.layouts.app')
@section('title', 'Part Fitments')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Part Fitments ({{ $fitments->count() }})</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Part Fitments</li>
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
            <a href="{{ route('admin.fitments.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Add Fitment
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Part</th>
                            <th>Vehicle Model</th>
                            <th>Variant</th>
                            <th>Status</th>
                            <th>Year Range</th>
                            <th style="width:150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fitments as $fitment)
                        <tr>
                            <td>{{ $fitment->id }}</td>
                            <td>{{ $fitment->part->part_name ?? '—' }}</td>
                            <td>{{ $fitment->model->model_name ?? '—' }}</td>
                            <td>{{ $fitment->variant->name ?? '—' }}</td>
                            <td>{{ ucfirst($fitment->status) }}</td>
                            <td>{{ $fitment->year_start ?? '—' }} - {{ $fitment->year_end ?? '—' }}</td>
                            <td class="d-flex">
                                <a href="{{ route('admin.fitments.edit', $fitment->id) }}"
                                   class="btn btn-info btn-sm mr-2">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.fitments.destroy', $fitment->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this fitment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No fitments available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>

@endsection
