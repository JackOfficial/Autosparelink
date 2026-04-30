@extends('layouts.app')
@push('styles')
    <style>
          .bg-success-light { background-color: rgba(40, 167, 69, 0.1); }
    .letter-spacing-1 { letter-spacing: 0.5px; }
    .table-hover tbody tr:hover { background-color: #fbfbfb; }
    .btn-outline-light:hover { background-color: #fff5f5 !important; color: #dc3545 !important; }
    </style>
@endpush
@section('content')

<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30 p-3 rounded shadow-sm">
                <a class="breadcrumb-item text-dark" href="{{ url('/') }}">Home</a>
                <a class="breadcrumb-item text-dark" href="/spare-parts">Shop</a>
                <span class="breadcrumb-item active">Wishlist</span>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">
            @livewire('wishlist-page')
        </div>
    </div>
</div>
@endsection