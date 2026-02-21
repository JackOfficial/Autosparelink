@extends('admin.layouts.app')
@section('title', 'Add Spare Part')

@push('styles')

@endpush

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-cogs"></i> Add Spare Part</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.spare-parts.index') }}">Spare Parts</a></li>
                    <li class="breadcrumb-item active">Create</li>
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
 <livewire:admin.part.create />
</div>
</div>
</section>

@endsection
