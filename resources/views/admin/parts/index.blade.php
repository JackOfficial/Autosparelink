@extends('admin.layouts.app')
@section('title', 'Spare Parts')

@section('content')

<style>
    /* Facebook-style stacked photos */
    .photo-stack {
        position: relative;
        width: 90px;
        height: 60px;
    }

    .stack-img {
        position: absolute;
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
        background: #f8f9fa;
    }

    .stack-more {
        position: absolute;
        right: -6px;
        bottom: -6px;
        background: rgba(0, 0, 0, 0.75);
        color: #fff;
        font-size: 11px;
        padding: 3px 6px;
        border-radius: 12px;
        font-weight: bold;
    }
</style>

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
        <div class="alert alert-success alert-dismissible fade show">
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
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>SKU</th>
                            <th>Photo</th>
                            <th>Part No.</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Compatible</th>
                            <th>OEM Number</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th style="width:150px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($parts as $part)
                        <tr class="{{ $part->stock_quantity < 5 ? 'bg-danger' : '' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $part->sku }}</td>

                            <!-- Stacked photos -->
                            <td>
                              @if($part->photos && $part->photos->count())
    <div class="photo-stack position-relative">
        @foreach($part->photos->take(3) as $index => $photo)
            <img 
                src="{{ asset('storage/' . $photo->file_path) }}"
                class="stack-img"
                style="left: {{ $index * 15 }}px; z-index: {{ 10 - $index }};"
                alt="{{ $part->part_name }}"
            >
        @endforeach

        @if($part->photos->count() > 3)
            <span class="stack-more">
                +{{ $part->photos->count() - 3 }}
            </span>
        @endif
    </div>
@else
    <img 
        src="{{ asset('images/no-image.png') }}"
        class="img-fluid"
        alt="No image available"
    >
@endif
                            </td>

                            <td>{{ $part->part_number }}</td>
                            <td>{{ $part->part_name }}</td>
                            <td>{{ $part->category->category_name ?? '-' }}</td>

                            <!-- Single brand -->
                            <td>
                                @if($part->partBrand)
                                    {{ $part->partBrand->name }} ({{ $part->partBrand->type }})
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
    @if($part->fitments && $part->fitments->count())
        <ul class="list-unstyled mb-0">
            @foreach($part->fitments->take(3) as $fitment)
            <ul>
                <li>
  <span 
    data-toggle="tooltip" 
    data-placement="top" 
    title="{{ $fitment->vehicleModel->brand->brand_name ?? '' }} {{ $fitment->vehicleModel->model_name ?? '' }} {{ $fitment->variant->name ?? '' }}" >
   {{ $fitment->vehicleModel->brand->brand_name ?? '' }} {{ $fitment->vehicleModel->model_name ?? '' }}
</span>
                </li>
            </ul>
          
            @endforeach
        </ul>

        @if($part->fitments->count() > 3)
            <span class="badge badge-secondary">
                +{{ $part->fitments->count() - 3 }} more
            </span>
        @endif
    @else
        <span class="text-muted">No fitments</span>
    @endif
</td>

                            <td>{{ $part->oem_number ?? '-' }}</td>
                            <td>{{ number_format($part->price, 2) }} RWF</td>
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
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $parts->links() }}
                </div>
            </div>
        </div>
    </div>

</section>
@endsection
