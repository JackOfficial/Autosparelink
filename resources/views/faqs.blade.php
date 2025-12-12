@extends('layouts.app')

@section('content')

<!-- Breadcrumb Start -->
<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="#">Home</a>
                <span class="breadcrumb-item active">FAQ</span>
            </nav>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- FAQ Start -->
<div class="container-fluid">
    <div class="row px-xl-5">

        <!-- Sidebar Start -->
        <div class="col-lg-3 col-md-4">

            <!-- Search Start -->
            <div class="bg-light p-4 mb-30">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Search</span>
                </h5>
                <form action="#" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search FAQs...">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Search End -->

            <!-- Categories Start -->
            <div class="bg-light p-4 mb-30">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Categories</span>
                </h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a class="text-dark" href="#">Orders & Shipping</a></li>
                    <li class="mb-2"><a class="text-dark" href="#">Payments</a></li>
                    <li class="mb-2"><a class="text-dark" href="#">Products & Returns</a></li>
                    <li class="mb-2"><a class="text-dark" href="#">Account & Login</a></li>
                    <li><a class="text-dark" href="#">Technical Support</a></li>
                </ul>
            </div>
            <!-- Categories End -->

        </div>
        <!-- Sidebar End -->

        <!-- FAQ List Start -->
        <div class="col-lg-9 col-md-8">

            <div class="bg-light p-4 mb-4">
                <h3 class="mb-4">Frequently Asked Questions</h3>

                <div id="accordion">

                    @for ($i = 1; $i <= 6; $i++)
                    <div class="card mb-3">
                        <div class="card-header bg-white" id="heading{{ $i }}">
                            <h5 class="mb-0">
                                <button class="btn btn-link text-dark d-flex justify-content-between align-items-center w-100" data-toggle="collapse" data-target="#collapse{{ $i }}" aria-expanded="{{ $i === 1 ? 'true' : 'false' }}" aria-controls="collapse{{ $i }}">
                                    Sample FAQ Question {{ $i }}?
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                            </h5>
                        </div>

                        <div id="collapse{{ $i }}" class="collapse {{ $i === 1 ? 'show' : '' }}" aria-labelledby="heading{{ $i }}" data-parent="#accordion">
                            <div class="card-body text-muted" style="font-size: 14px; line-height: 1.6;">
                                This is the answer for FAQ question {{ $i }}. You can replace this placeholder text with your real content. Provide detailed yet concise explanations to help your users.
                            </div>
                        </div>
                    </div>
                    @endfor

                </div>
            </div>

        </div>
        <!-- FAQ List End -->

    </div>
</div>
<!-- FAQ End -->

<style>
    /* Accordion icon rotation */
    .card-header .btn-link {
        text-decoration: none;
        font-weight: 500;
    }
    .card-header .btn-link i {
        transition: transform 0.3s ease;
    }
    .card-header .btn-link.collapsed i {
        transform: rotate(0deg);
    }
    .card-header .btn-link:not(.collapsed) i {
        transform: rotate(180deg);
    }

    /* Hover shadow for FAQ cards */
    .card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
</style>

@endsection
