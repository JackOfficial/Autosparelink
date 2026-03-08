@extends('layouts.app')

@section('title', 'Parts Catalog | AutoSpareLink')

@section('content')

<div class="container-fluid mt-4">

    <!-- Breadcrumb -->
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-4 p-3 rounded">
                <a class="breadcrumb-item text-dark" href="/">Home</a>
                <a class="breadcrumb-item text-dark" href="/shop">Shop</a>
                <span class="breadcrumb-item active">Parts Catalog</span>
            </nav>
        </div>
    </div>

    <!-- Vehicle Info -->
    <div class="row px-xl-5 mb-4">
        <div class="col-12">
            <div class="bg-light p-4 rounded shadow-sm">
                <h4 class="font-weight-bold mb-2">Kia Parts Catalogs - Sportage</h4>
                <div class="row">
                    <div class="col-md-3"><strong>Brand:</strong> KIA</div>
                    <div class="col-md-3"><strong>Model:</strong> Sportage</div>
                    <div class="col-md-2"><strong>Market:</strong> DOM</div>
                    <div class="col-md-2"><strong>Year From:</strong> 1999</div>
                    <div class="col-md-2"><strong>Year To:</strong> 2002</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Parts Section -->
    @php
        $sections = [
            'AXLE & BRAKE MECHANISM-FRONT' => [
                ['number'=>'0K08133044','name'=>'SPACER','code'=>'6','date_range'=>'12.06.2001 - 25.07.2002','remarks'=>'','quantity'=>'2pcs'],
                ['number'=>'0K01133044','name'=>'SPACER','code'=>'6','date_range'=>'... - 12.06.2001','remarks'=>'','quantity'=>'2pcs'],
                // Add more rows as needed
            ]
        ];
    @endphp

    @foreach($sections as $sectionName => $parts)
        <div class="row px-xl-5 mb-4">
            <div class="col-12">
                <h5 class="mb-3 font-weight-bold text-primary">{{ $sectionName }}</h5>
                <div class="row">
                    <!-- Table -->
                    <div class="col-lg-8">
                        <div class="table-responsive bg-light p-3 rounded shadow-sm">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Number</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Date Range</th>
                                        <th>Remarks</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($parts as $part)
                                        <tr>
                                            <td><a href="#" class="text-primary">{{ $part['number'] }}</a></td>
                                            <td>{{ $part['name'] }}</td>
                                            <td>{{ $part['code'] }}</td>
                                            <td>{{ $part['date_range'] }}</td>
                                            <td>{{ $part['remarks'] }}</td>
                                            <td>{{ $part['quantity'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="col-lg-4 d-flex align-items-center justify-content-center">
                        <img src="{{ asset('frontend/img/parts.jpg') }}" class="img-fluid rounded shadow-sm" alt="{{ $sectionName }}">
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>

<style>
.table-hover tbody tr:hover {
    background-color: #f1f1f1;
}
.bg-light h5 {
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.25rem;
}
</style>

@endsection
