@extends('admin.layouts.app')
@section('title', 'Brands')
@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Brands ({{ $brands->count() }})</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Brands</li>
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
            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Add Brand
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="example1" class="table table-striped projects">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Logo</th>
                            <th>Brand Name</th>
                            <th>Description</th>
                            <th>Country</th>
                            <th>Website</th>
                            <th style="width:150px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($brands as $brand)
                        <tr>
                            <td>{{ $brand->id }}</td>

                            <!-- Logo -->
                            <td>
                                @if($brand->brand_logo)
                                    <img src="{{ asset('storage/' . $brand->brand_logo) }}"
                                         class="img-thumbnail"
                                         style="width: 80px; height:auto;" />
                                @else
                                    <span class="text-muted">No logo</span>
                                @endif
                            </td>

                            <!-- Brand name -->
                            <td>
                                <strong>{{ $brand->brand_name }}</strong>
                                <br>
                                <small class="text-muted">{{ $brand->created_at->format('Y-m-d') }}</small>
                            </td>

                            <!-- Description -->
                            <td>{!! Str::limit($brand->description, 50) !!}</td>

                            <!-- Country -->
                            <td>{{ $brand->country ?? '—' }}</td>

                            <!-- Website -->
                            <td>
                                @if($brand->website)
                                    <a href="{{ $brand->website }}" target="_blank">{{ $brand->website }}</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="d-flex">

                                <a href="{{ route('admin.brands.edit', $brand->id) }}"
                                   class="btn btn-info btn-sm mr-2">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.brands.destroy', $brand->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this brand?');">
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
                            <td colspan="7" class="text-center text-muted">
                                No brands available at the moment.
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
