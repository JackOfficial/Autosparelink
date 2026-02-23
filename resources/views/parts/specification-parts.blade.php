@extends('layouts.app')

{{-- Dynamic SEO Tags --}}
@section('title', "Genuine Parts for {$specification->vehicleModel->brand->brand_name} {$specification->vehicleModel->model_name} ({$specification->model_code})")

@section('content')
<div class="main-content-wrapper">
    {{-- 1. Minimal Header / Breadcrumbs --}}
    <div class="container-fluid py-3 border-bottom bg-white">
        <div class="px-xl-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 m-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-primary"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Catalog</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">{{ $specification->vehicleModel->brand->brand_name }}</a></li>
                    <li class="breadcrumb-item active text-dark font-weight-bold">{{ $specification->vehicleModel->model_name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- 2. The Livewire Component --}}
    {{-- We pass the specification ID so Livewire knows what data to fetch --}}
    <div class="py-4">
        @livewire('parts.specification-parts', ['specificationId' => $specification->id])
    </div>
</div>

<style>
    /* Ensure the wrapper looks clean in v4.5.3 */
    .main-content-wrapper {
        background-color: #f8f9fa;
        min-height: 100vh;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        content: "\f105"; /* FontAwesome Chevron Right */
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        font-size: 0.7rem;
        color: #ced4da;
    }
</style>
@endsection