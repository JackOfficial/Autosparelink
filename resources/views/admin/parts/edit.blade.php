@extends('admin.layouts.app')
@section('title', 'Edit Spare Part')

@push('styles')
<style>
    .border-dashed { border: 2px dashed #dee2e6; }
    .form-control:focus, .form-select:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1); }
    .card-title { font-size: 1rem; letter-spacing: 0.02rem; }
    .group:hover .btn-danger { display: block !important; }
    .sticky-bottom { margin-bottom: -1.5rem; } /* Ties to the footer better */
    .shadow-hover:hover { transform: translateY(-2px); transition: 0.3s; }
</style>
@endpush

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-edit"></i> Edit Spare Part</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.spare-parts.index') }}">Spare Parts</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Part</h3>
    </div>

    <div class="card-body">
            <livewire:admin.part.edit :part="$part" />
    </div>
</div>
</section>

@push('scripts')
<script>
$('.select2-multiple').select2({
    width: '100%',
    allowClear: true
});
</script>
@endpush

@endsection
