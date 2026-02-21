@extends('admin.layouts.app')
@section('title', 'Edit Spare Part')

@push('styles')
<style>

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
