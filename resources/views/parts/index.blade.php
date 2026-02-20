@extends('layouts.app')
@push('styles')
<style>
.product-item{
    border-radius: 12px;
    overflow: hidden; /* keeps card clean */
    background: #fff;
    transition: transform .18s ease, box-shadow .18s ease;
}

.product-item:hover{
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,.08);
}

/* IMAGE WRAPPER */
.product-img{
    position: relative;
    overflow: hidden; /* 🔥 THIS is critical */
}

/* IMAGE ITSELF */
.product-img img{
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
    transition: transform .3s ease;
}

/* SCALE ONLY INSIDE WRAPPER */
.product-item:hover .product-img img{
    transform: scale(1.08);
}

/* ACTION BUTTONS */
.product-action{
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    gap: 8px;
    opacity: 0;
    z-index: 5;
    transition: opacity .15s ease;
}

.product-item:hover .product-action{
    opacity: 1;
}
</style>
@endpush

@section('content')



@endsection
