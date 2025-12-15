@extends('layouts.app')

@section('title', 'Shop All Products | AutoSpareLink')

@section('content')

<!-- Shop Header -->
<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30" aria-label="breadcrumb">
                <a class="breadcrumb-item text-dark" href="/">Home</a>
                <span class="breadcrumb-item active" aria-current="page">Shop</span>
            </nav>
        </div>
    </div>
</div>

<!-- Page Title -->
<div class="container-fluid mb-3">
    <div class="row px-xl-5">
        <div class="col-12 text-center">
            <h1 class="display-4 font-weight-bold mb-3">All Products</h1>
        </div>
    </div>
</div>

<!-- Search Section -->
<div class="container-fluid mb-5">
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-10">
            <div class="bg-white p-4 rounded shadow-sm">

                <!-- Search Input -->
                <div class="input-group input-group-lg mb-2">
                    <input type="text" class="form-control border-primary" placeholder="Enter Part Number or VIN / Frame">
                    <div class="input-group-append">
                        <button class="btn btn-primary">
                            <i class="fa fa-search mr-1"></i> Search
                        </button>
                    </div>
                </div>

                <!-- Helper Text -->
                <div class="d-flex justify-content-between small text-muted mb-3">
                    <span>Example: ZJ0118400A, 2562035130, 3VW217AUXFM052349, 5TDDK3EH7CS147140</span>
                    <a href="#" class="text-primary">Where is VIN/Frame?</a>
                </div>

                <!-- Optional Part Number List Toggle -->
                {{-- Future: Add dropdown to enter multiple parts if needed --}}
            </div>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="container-fluid mb-5">
    <div class="row px-xl-5">
        <div class="col-12">
            <div class="bg-white p-4 rounded shadow-sm table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light text-uppercase">
                        <tr>
                            <th>Make</th>
                            <th>Number</th>
                            <th>Name</th>
                            <th>Price, USD</th>
                            <th>Availability</th>
                            <th>Ship In, Days</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Hyundai / KIA</td>
                            <td>0K01133200B</td>
                            <td>HUB-FREE WHEEL</td>
                            <td>$71.16</td>
                            <td>1</td>
                            <td>7</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-search mr-1"></i> View
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Hyundai / KIA</td>
                            <td>0K08133044</td>
                            <td>SPACER</td>
                            <td>$2.88</td>
                            <td>5</td>
                            <td>6</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-search mr-1"></i> View
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Hyundai / KIA</td>
                            <td>0K9BV105F0A</td>
                            <td>S/ASSY-B/PLATE,LH</td>
                            <td>$2.71</td>
                            <td>30</td>
                            <td>7</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-search mr-1"></i> View
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Hyundai / KIA</td>
                            <td>0PN1113H51</td>
                            <td>WASHER-NOZZLE</td>
                            <td>$0.29</td>
                            <td>697</td>
                            <td>2</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-search mr-1"></i> View
                                </a>
                            </td>
                        </tr>
                        <!-- Add more placeholder rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
.table-hover tbody tr:hover {
    background-color: #f0f8ff;
}
.table thead th {
    font-weight: 600;
}
.btn-outline-primary:hover {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
}
.bg-white {
    background-color: #ffffff !important;
}
</style>

@endsection
