@extends('layouts.app')

@section('content')

<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30 p-3 rounded shadow-sm">
                <a class="breadcrumb-item text-dark" href="{{ url('/') }}">Home</a>
                <a class="breadcrumb-item text-dark" href="{{ route('shop.index') }}">Shop</a>
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