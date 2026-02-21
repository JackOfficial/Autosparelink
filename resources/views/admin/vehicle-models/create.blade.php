@extends('admin.layouts.app')
@section('title', 'AutoSpareLink - Add Vehicle Model')

@push('styles')
    <style>
    .bg-danger-light { background-color: #fff5f5; }
    .extra-small { font-size: 0.75rem; }
    .cursor-pointer { cursor: pointer; }
    .opacity-0 { opacity: 0; }
    .border-dashed { border-style: dashed !important; }
    .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
</style>
@endpush
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Add Model</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Add Model</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="row">
<div class="col-md-10">

<livewire:admin.model-form />

</div>
</div>
</section>

@endsection
