@extends('layouts.app')

@push('styles')
    <style>
    .letter-spacing-1 { letter-spacing: 1px; }
    .quantity-wrapper .btn:focus { box-shadow: none; }
    .breadcrumb-item + .breadcrumb-item::before { content: ">"; font-size: 10px; padding-top: 2px; }
    .card { transition: transform 0.2s ease-in-out; }
    .sticky-top { z-index: 1010; }
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