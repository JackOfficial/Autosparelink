@extends('layouts.app')

@section('title', 'All Products | AutoSpareLink')

@section('content')

<!-- Reading Progress Bar -->
<div id="reading-progress"></div>

<!-- Page Header -->
<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <h1 class="mb-4 font-weight-bold">All Products</h1>
        </div>
    </div>
</div>

<!-- Search Box with Helper Text -->
<div class="container-fluid mb-4">
    <div class="row px-xl-5">
        <div class="col-lg-12 col-md-12">
            <div class="bg-light p-4 rounded shadow-sm">
                <!-- Input Group -->
                <div class="input-group input-group-lg rounded-pill overflow-hidden">
                    <input type="text" class="form-control rounded-left" placeholder="Search by part number, VIN or frame...">
                    <div class="input-group-append">
                        <button class="btn btn-primary rounded-right">
                            <i class="fa fa-search mr-1"></i> Search
                        </button>
                    </div>
                </div>

                <!-- Helper Texts -->
                <div class="d-flex justify-content-between mt-2 small text-muted px-2">
                    <span>Example: ZJ0118400A, 2562035130, 3VW217AUXFM052349, 5TDDK3EH7CS147140</span>
                    <a href="#" class="text-primary">Where is VIN/Frame?</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Table -->
<div class="container-fluid mb-5">
    <div class="row px-xl-5">
        <div class="col-12">
            <div class="table-responsive shadow-sm">
                <table class="table table-striped table-hover bg-white">
                    <thead class="thead-dark">
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
                        @for ($i = 0; $i < 10; $i++)
                        <tr>
                            <td>Hyundai / KIA</td>
                            <td>0K01133200B</td>
                            <td>HUB-FREE WHEEL</td>
                            <td>$71.16</td>
                            <td>1</td>
                            <td>7</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary">
                                    <i class="fa fa-search mr-1"></i> View
                                </a>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="container-fluid mb-5">
    <div class="row px-xl-5">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-center">

            <!-- Results Info -->
            <div class="mb-2 mb-md-0 text-muted">
                Displaying 1-10 of 555,634 results
            </div>

            <!-- Pagination -->
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item disabled"><span class="page-link">&laquo; Previous</span></li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next &raquo;</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
#reading-progress {
    position: fixed;
    top: 0;
    left: 0;
    height: 4px;
    background: #007bff;
    width: 0%;
    z-index: 9999;
}
.table th, .table td {
    vertical-align: middle;
}
.input-group-lg .form-control {
    height: calc(2.875rem + 2px);
    font-size: 1.125rem;
}
</style>

<!-- Reading Progress Script -->
<script>
window.addEventListener('scroll', () => {
    const scrollTop = document.documentElement.scrollTop;
    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    document.getElementById('reading-progress').style.width = (scrollTop / height) * 100 + '%';
});
</script>

@endsection
