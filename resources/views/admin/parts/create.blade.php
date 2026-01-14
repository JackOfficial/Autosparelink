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
        min-height: 50px;
        display: flex;
        flex-wrap: wrap;
        padding: 4px 8px;
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
    .action-bar { 
        position: sticky; 
        bottom: 0; 
        background: #fff; 
        padding: 10px 0; 
        z-index: 10; 
        border-top: 1px solid #ddd; 
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
    <input type="text" name="part_number" class="form-control" value="{{ old('part_number') }}">
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Part Name *</label>
    <input type="text" name="part_name" class="form-control" required value="{{ old('part_name') }}">
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Category *</label>
    <select name="category_id" class="form-control" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->category_name }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Part Brand *</label>
    <select name="part_brand_id" class="form-control" required>
        @foreach($partBrands as $brand)
            <option value="{{ $brand->id }}" {{ old('part_brand_id') == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }} ({{ $brand->type }})
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">OEM Number</label>
    <input type="text" name="oem_number" class="form-control" value="{{ old('oem_number') }}">
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Price (RWF)</label>
    <input type="number" name="price" class="form-control" required value="{{ old('price') }}">
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Stock Quantity</label>
    <input type="number" name="stock_quantity" class="form-control" required value="{{ old('stock_quantity') }}">
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-control">
        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
    </select>
</div>

</div>
</fieldset>
</div>

<!-- RIGHT COLUMN -->
<div class="col-lg-6">
<fieldset>
<legend><i class="fas fa-car-side"></i> Fitment & Media</legend>

<!-- COMBINED MODELS + VARIANTS -->
<div class="mb-3">
<label class="form-label">Compatible Vehicles</label>
<select name="fitment_specifications[]" class="form-control select2-multiple" multiple>
@foreach($vehicleModels as $model)
    @if($model->variants->isEmpty())
        {{-- Model-level specs --}}
        @foreach($model->specifications as $spec)
            <option value="{{ $spec->id }}">
                {{ optional($model->brand)->brand_name }} / {{ $model->model_name }}
                ({{ $spec->production_start }}–{{ $spec->production_end }})
            </option>
        @endforeach
    @else
        {{-- Variant-level specs --}}
        @foreach($model->variants as $variant)
            @foreach($variant->specifications as $spec)
                <option value="{{ $spec->id }}">
                    {{ optional($model->brand)->brand_name }} /
                    {{ $model->model_name }} —
                    {{ $variant->name }}
                    ({{ $spec->production_start }}–{{ $spec->production_end }})
                </option>
            @endforeach
        @endforeach
    @endif
@endforeach
</select>
<small class="text-muted">Select all vehicle models or variant specifications this part is compatible with.</small>
</div>

<div class="mb-3">
<label class="form-label">Description</label>
<textarea name="description" class="form-control">{{ old('description') }}</textarea>
</div>

<div class="mb-3">
<label class="form-label">Photos</label>
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
