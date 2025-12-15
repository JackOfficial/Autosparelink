@extends('layouts.app')

@section('title', 'Shop | AutoSpareLink')

@section('content')

<!-- Shop Header -->
<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="/">Home</a>
                <span class="breadcrumb-item active">Shop</span>
            </nav>
        </div>
    </div>
</div>

<!-- Search Section -->
<div class="container-fluid mb-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <div class="bg-light p-4 rounded shadow-sm d-flex align-items-center">
                <input type="text" class="form-control form-control-lg mr-3"
                       placeholder="Enter Part Number or VIN / Frame">
                <button class="btn btn-primary btn-lg">
                    <i class="fa fa-search mr-1"></i> Search
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Parts Table -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">
            <div class="table-responsive bg-light p-4 rounded shadow-sm">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Make</th>
                            <th>Number</th>
                            <th>Name</th>
                            <th>Price, USD</th>
                            <th>Availability</th>
                            <th>Ship In, Days</th>
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
                        </tr>
                        <tr>
                            <td>Hyundai / KIA</td>
                            <td>0K08133044</td>
                            <td>SPACER</td>
                            <td>$2.88</td>
                            <td>5</td>
                            <td>6</td>
                        </tr>
                        <tr>
                            <td>Hyundai / KIA</td>
                            <td>0K9BV105F0A</td>
                            <td>S/ASSY-B/PLATE,LH</td>
                            <td>$2.71</td>
                            <td>30</td>
                            <td>7</td>
                        </tr>
                        <tr>
                            <td>Hyundai / KIA</td>
                            <td>0PN1113H51</td>
                            <td>WASHER-NOZZLE</td>
                            <td>$0.29</td>
                            <td>697</td>
                            <td>2</td>
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
    cursor: pointer;
}
.table thead th {
    font-weight: 600;
}
.bg-light {
    background-color: #f8f9fa !important;
}
</style>

@endsection
