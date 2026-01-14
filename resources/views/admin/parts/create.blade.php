@extends('admin.layouts.app')
@section('title', 'Add Spare Part')

@section('styles')
<style>
    fieldset { 
        border: 1px solid #ddd; 
        padding: 15px; 
        margin-bottom: 20px; 
        border-radius: 5px;
    }
    legend { 
        width: auto; 
        padding: 0 10px; 
        font-weight: bold; 
        font-size: 1.1rem;
        color: #333;
    }
    .form-label { font-weight: 500; }
    .select2-container--default .select2-selection--multiple {
        min-height: 45px;
    }
    .action-bar { 
        position: sticky; 
        bottom: 0; 
        background: #fff; 
        padding: 10px 0; 
        z-index: 10; 
        border-top: 1px solid #ddd; 
    }

    .select2-container--default .select2-selection--multiple {
        min-height: 50px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        display: flex;
        flex-wrap: wrap;
        padding: 4px 8px;
    }

    .select2-container--default .select2-selection--multiple .select2-search__field {
        width: 100% !important;
        min-width: 150px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        padding: 4px 10px 4px 28px !important;
        margin-right: 5px !important;
        position: relative;
        border-radius: 4px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        position: absolute;
        left: 6px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        color: rgb(240, 83, 83);
        cursor: pointer;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: darkred;
    }
</style>
@endsection

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
<form action="{{ route('admin.spare-parts.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="row">

<!-- LEFT COLUMN -->
<div class="col-lg-6">
<fieldset>
<legend><i class="fas fa-info-circle"></i> General Info</legend>

<div class="row">
<div class="col-md-6 mb-3">
    <label class="form-label">Part Number</label>
    <input type="text" name="part_number" class="form-control">
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Part Name *</label>
    <input type="text" name="part_name" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Category *</label>
    <select name="category_id" class="form-control" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Part Brand *</label>
    <select name="part_brand_id" class="form-control" required>
        @foreach($partBrands as $brand)
            <option value="{{ $brand->id }}">{{ $brand->name }} ({{ $brand->type }})</option>
        @endforeach
    </select>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">OEM Number</label>
    <input type="text" name="oem_number" class="form-control">
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Price (RWF)</label>
    <input type="number" name="price" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Stock Quantity</label>
    <input type="number" name="stock_quantity" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-control">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select>
</div>
</div>
</fieldset>
</div>

<!-- RIGHT COLUMN -->
<div class="col-lg-6">
<fieldset>
<legend><i class="fas fa-car-side"></i> Fitment & Media</legend>

<!-- MODEL ONLY -->
<div class="mb-3">
<label class="form-label">Compatible Vehicle Models (No Variants)</label>
<select name="model_fitments[]" class="form-control select2-multiple" multiple>
@foreach($vehicleModels as $model)
@if($model->variants->isEmpty())
@foreach($model->specifications as $spec)
<option value="{{ $spec->id }}">
{{ optional($model->brand)->brand_name }} / {{ $model->model_name }}
({{ $spec->production_start }}–{{ $spec->production_end }})
</option>
@endforeach
@endif
@endforeach
</select>
</div>

<!-- VARIANT -->
<div class="mb-3">
<label class="form-label">Compatible Variant Specifications</label>
<select name="variant_specifications[]" class="form-control select2-multiple" multiple>
@foreach($variants as $variant)
@foreach($variant->specifications as $spec)
<option value="{{ $spec->id }}">
{{ optional($variant->vehicleModel->brand)->brand_name }} /
{{ $variant->vehicleModel->model_name }} —
{{ $variant->name }}
</option>
@endforeach
@endforeach
</select>
</div>

<div class="mb-3">
<label>Description</label>
<textarea name="description" class="form-control"></textarea>
</div>

<div class="mb-3">
<label>Photos</label>
<input type="file" name="photos[]" multiple class="form-control">
</div>

</fieldset>
</div>

</div>

<div class="action-bar text-end">
<button class="btn btn-success"><i class="fas fa-save"></i> Save Part</button>
</div>

</form>
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
