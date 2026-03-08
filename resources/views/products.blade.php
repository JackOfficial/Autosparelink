@extends('layouts.app')

@section('title', 'All Products | AutoSpareLink')

@section('content')

<!-- Page Title -->
<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <h1 class="display-5 font-weight-bold mb-4">All Products</h1>
        </div>
    </div>
</div>

<!-- Search Box -->
<div class="container-fluid mb-4">
    <div class="row px-xl-5">
        <div class="col-lg-12 col-md-12">
            <div class="bg-light p-4 rounded shadow-sm searchbox-container">

                <!-- Search Form -->
                <form action="{{ url('/shop/products') }}" method="GET">
                    <div class="input-group searchbox-wrapper">
                        <input type="text" name="q" class="form-control searchbox-input"
                               placeholder="Search by part number, VIN or frame..."
                               value="{{ request('q') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary searchbox-btn" type="submit">
                                <i class="fa fa-search mr-1"></i> Search
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Helper Texts -->
                <div class="d-flex justify-content-between mt-2 small text-muted px-2">
                    <span>Example: ZJ0118400A, 2562035130, 3VW217AUXFM052349, 5TDDK3EH7CS147140</span>
                    <a href="#" class="text-primary">Where is VIN/Frame?</a>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="container-fluid mb-5">
    <div class="row px-xl-5">
        <div class="col-12">

            <div class="table-responsive bg-white rounded shadow-sm p-3">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Make</th>
                            <th>Number</th>
                            <th>Name</th>
                            <th>Price, RWF</th>
                            <th>Availability</th>
                            <th>Ship In, Days</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($parts as $part)
                            <tr>
                                <td>{{ $part->partBrand->name ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('spare-parts.show', $part->sku) }}">
                                        {{ $part->part_number }}
                                    </a>
                                </td>
                                <td>{{ $part->part_name }}</td>
                                <td>{{ number_format($part->price, 2) }} RWF</td>
                                <td>{{ $part->stock_quantity }}</td>
                                <td>1</td>
                                <td>
                                    <a href="{{ route('spare-parts.show', $part->sku) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fa fa-search mr-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No products available
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Info -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">
                    Displaying
                    {{ $parts->firstItem() ?? 0 }}–{{ $parts->lastItem() ?? 0 }}
                    of {{ number_format($parts->total()) }} results
                </small>

                <!-- Pagination -->
                <nav>
                    {{ $parts->links('pagination::bootstrap-4') }}
                </nav>
            </div>

        </div>
    </div>
</div>

<!-- Styles (unchanged) -->
<style>
/* Search Box Styles */
.searchbox-container {
    border-radius: 15px;
    background: #f8f9fa;
}

.searchbox-wrapper {
    border-radius: 50px;
    overflow: hidden;
    display: flex;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
}

.searchbox-input {
    border: none;
    padding-left: 20px;
    font-size: 1rem;
    height: 50px;
    border-radius: 0;
}

.searchbox-btn {
    border: none;
    height: 50px;
    padding: 0 25px;
    font-size: 1rem;
    border-radius: 0;
}

.searchbox-wrapper .form-control:focus {
    box-shadow: none;
    outline: none;
}

/* Table */
.table th, .table td {
    vertical-align: middle;
}

/* Pagination hover */
.pagination .page-item .page-link {
    border-radius: 50%;
    margin: 0 2px;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .searchbox-input,
    .searchbox-btn {
        height: 45px;
        font-size: 0.9rem;
    }
}
</style>

@endsection
