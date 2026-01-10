@extends('admin.layouts.app')

@section('title', 'Add Variant')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Variant</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.variants.index') }}">Variants</a>
                    </li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">

    <div class="row">
        <div class="col-md-10">
           <livewire:admin.variant-form :vehicle_model_id="request()->query('vehicle_model_id')"/>
        </div>
    </div>
  
</div>
</section>

@endsection
