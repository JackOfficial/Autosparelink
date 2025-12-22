@extends('admin.layouts.app')
@section('title', 'Spare Parts')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Spare Parts ({{ $parts->count() }})</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Spare Parts</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
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
            <a href="{{ route('admin.spare-parts.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Add Spare Part
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="example1" class="table table-striped projects">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>SKU</th>
                            <th>Photo</th>
                            <th>Part No.</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>OEM Number</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th style="width:150px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($parts as $part)
                        <tr>
                            <td>{{ $part->id }}</td>
                            <td>{{ $part->sku }}</td>

                            <td>
                                @if($part->photo)
                                    <img src="{{ asset('storage/'.$part->photo) }}"
                                         class="img-thumbnail"
                                         style="width: 70px;">
                                @else
                                    <span class="text-muted">No photo</span>
                                @endif
                            </td>

                            <td>{{ $part->part_number }}</td>
                            <td>{{ $part->part_name }}</td>
                            <td>{{ $part->category->category_name }}</td>
                            <td>{{ $part->partBrand->name }}</td>
                            <td>{{ $part->oem_number ?? '-' }}</td>

                            <td>${{ number_format($part->price, 2) }}</td>
                            <td>{{ $part->stock_quantity }}</td>

                            <td>
                                @if($part->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>

                            <td class="d-flex">
                                <a href="{{ route('admin.spare-parts.edit', $part->id) }}"
                                   class="btn btn-info btn-sm mr-2">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.spare-parts.destroy', $part->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this part?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</section>

@endsection
