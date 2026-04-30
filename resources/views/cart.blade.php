@extends('layouts.app')

@push('styles')
    <style>
    .rounded-xl { border-radius: 1rem !important; }
    .shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.06) !important; }
    .vertical-align-middle { vertical-align: middle !important; }
    .transition-all { transition: all 0.3s ease; }
    .hover-bg-light:hover { background-color: #fbfbfb; }
    .transform-hover:hover { transform: translateY(-2px); }
    .border-dashed { border-style: dashed !important; }
    .btn-white { background-color: #fff; color: #000; }
    .badge-soft-success { background-color: #e6fcf5; color: #0ca678; }
    .badge-soft-warning { background-color: #fff9db; color: #f08c00; }
    .hover-shadow:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
</style>
@endpush

@section('content')

<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30 p-3 rounded shadow-sm">
                <a class="breadcrumb-item text-dark" href="{{ url('/') }}">Home</a>
                <a class="breadcrumb-item text-dark" href="/spare-parts">Shop</a>
                <span class="breadcrumb-item active">Shopping Cart</span>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">
            @livewire('cart-page')
        </div>
    </div>
</div>
@endsection