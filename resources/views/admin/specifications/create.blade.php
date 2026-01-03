@extends('admin.layouts.app')
@section('title', 'Add Specification')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Add Specification</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Add Specification</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="row">
<div class="col-md-11">
<div class="card card-primary">
<div class="card-header"><h3 class="card-title">Specification Details</h3></div>
<div class="card-body">

@if($errors->any())
<div class="alert alert-danger">
<ul class="mb-0">
@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form action="{{ route('admin.specifications.store') }}" method="POST">
@csrf

{{-- Variant --}}
<fieldset class="border p-3 mb-4">
<legend class="w-auto">Variant</legend>
<div class="row">
<div class="col-md-6">
<label>Variant <span class="text-danger">*</span></label>
<select name="variant_id" class="form-control" required>
<option value="">Select Variant</option>
@foreach($variants as $variant)
<option value="{{ $variant->id }}">
{{ $variant->name ?? 'Unnamed Variant' }} — {{ $variant->vehicleModel->model_name }}
</option>
@endforeach
</select>
</div>
</div>
</fieldset>

{{-- Core Specs --}}
<fieldset class="border p-3 mb-4">
<legend class="w-auto">Core Specifications</legend>
<div class="row">
<div class="col-md-3">
<label>Body Type</label>
<select name="body_type_id" class="form-control">
<option value="">Select</option>
@foreach($bodyTypes as $item)
<option value="{{ $item->id }}">{{ $item->name }}</option>
@endforeach
</select>
</div>

<div class="col-md-3">
<label>Engine Type</label>
<select name="engine_type_id" class="form-control">
<option value="">Select</option>
@foreach($engineTypes as $item)
<option value="{{ $item->id }}">{{ $item->name }}</option>
@endforeach
</select>
</div>

<div class="col-md-3">
<label>Transmission</label>
<select name="transmission_type_id" class="form-control">
<option value="">Select</option>
@foreach($transmissionTypes as $item)
<option value="{{ $item->id }}">{{ $item->name }}</option>
@endforeach
</select>
</div>

<div class="col-md-3">
<label>Drive Type</label>
<select name="drive_type_id" class="form-control">
<option value="">Select</option>
@foreach($driveTypes as $item)
<option value="{{ $item->id }}">{{ $item->name }}</option>
@endforeach
</select>
</div>
</div>
</fieldset>

{{-- Performance --}}
<fieldset class="border p-3 mb-4">
<legend class="w-auto">Performance & Capacity</legend>
<div class="row">

<div class="col-md-3">
<label>Horsepower (HP)</label>
<input type="number" name="horsepower" class="form-control" min="0" placeholder="e.g. 150">
</div>

<div class="col-md-3">
<label>Torque (Nm)</label>
<input type="number" name="torque" class="form-control" min="0" placeholder="e.g. 320">
</div>

<div class="col-md-3">
<label>Fuel Capacity (Liters)</label>
<input type="number" name="fuel_capacity" class="form-control" min="0" step="0.1" placeholder="e.g. 55">
</div>

<div class="col-md-3">
<label>Fuel Efficiency (km/L)</label>
<input type="number" name="fuel_efficiency" class="form-control" min="0" step="0.1" placeholder="e.g. 14.5">
</div>

</div>
</fieldset>

{{-- Interior --}}
<fieldset class="border p-3 mb-4">
<legend class="w-auto">Interior & Layout</legend>
<div class="row">

<div class="col-md-2">
<label>Seats</label>
<input type="number" name="seats" class="form-control" min="1" max="20">
</div>

<div class="col-md-2">
<label>Doors</label>
<input type="number" name="doors" class="form-control" min="1" max="6">
</div>

<div class="col-md-4">
<label>Steering Position</label>
<select name="steering_position" class="form-control">
<option value="">Select</option>
<option value="LEFT">Left-Hand Drive</option>
<option value="RIGHT">Right-Hand Drive</option>
</select>
</div>

<div class="col-md-4">
    <label>Color</label>
    <div class="input-group my-colorpicker2">
        <input type="text"
               name="color"
               class="form-control"
               placeholder="Pick color (HEX)">
        <div class="input-group-append">
            <span class="input-group-text">
                <i class="fas fa-square"></i>
            </span>
        </div>
    </div>
    <small class="text-muted">Example: Black, Pearl White, Metallic Blue</small>
</div>


</div>
</fieldset>

{{-- Production --}}
<fieldset class="border p-3 mb-4">
<legend class="w-auto">Production</legend>
<div class="row">

<div class="col-md-3">
<label>Production Start Year</label>
<input type="number" name="production_start" class="form-control" min="1950" max="{{ date('Y') }}">
</div>

<div class="col-md-3">
<label>Production End Year</label>
<input type="number" name="production_end" class="form-control" min="1950" max="{{ date('Y') + 2 }}">
</div>

</div>
</fieldset>

<button type="submit" class="btn btn-primary">
<i class="fa fa-save"></i> Save Specification
</button>

</form>
</div>
</div>
</div>
</div>
</section>

@push('scripts')
<script>
$(function () {
    $('.my-colorpicker2').colorpicker()
})
</script>
@endpush

@endsection
