@extends('admin.layouts.app')
@section('title', 'Edit Spare Part')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
</style>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-cogs"></i> Edit Spare Part</h1>
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
            <h3 class="card-title">Part Details</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.spare-parts.update', $part->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-lg-6">
                        <fieldset>
                            <legend><i class="fas fa-info-circle"></i> General Info</legend>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fas fa-barcode"></i> SKU <span class="text-danger">*</span></label>
                                    <input type="text" name="sku" class="form-control" value="{{ old('sku', $part->sku) }}" required readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fas fa-hashtag"></i> Part Number</label>
                                    <input type="text" name="part_number" class="form-control" value="{{ old('part_number', $part->part_number) }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label"><i class="fas fa-tools"></i> Part Name <span class="text-danger">*</span></label>
                                    <input type="text" name="part_name" class="form-control" value="{{ old('part_name', $part->part_name) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fas fa-list-alt"></i> Category <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-control" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $part->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fas fa-industry"></i> Part Brand <span class="text-danger">*</span></label>
                                    <select name="part_brand_id" class="form-control" required>
                                        @foreach($partBrands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('part_brand_id', $part->part_brand_id) == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }} ({{ $brand->type }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fas fa-hashtag"></i> OEM Number</label>
                                    <input type="text" name="oem_number" class="form-control" value="{{ old('oem_number', $part->oem_number) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fas fa-dollar-sign"></i> Price</label>
                                    <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $part->price) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fas fa-boxes"></i> Stock Quantity</label>
                                    <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $part->stock_quantity) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="fas fa-toggle-on"></i> Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1" {{ old('status', $part->status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $part->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-6">
                        <fieldset>
                            <legend><i class="fas fa-car-side"></i> Fitment & Media</legend>

                            @php
                                $selectedVariants = old('variants', $part->variants ? $part->variants->pluck('id')->toArray() : []);
                            @endphp

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-list"></i> Compatible Variants</label>
                                <select name="variants[]" class="form-control select2-multiple" multiple="multiple" style="width: 100%;">
                                    @foreach($variants as $variant)
                                        <option value="{{ $variant->id }}" {{ in_array($variant->id, $selectedVariants) ? 'selected' : '' }}>
                                            {{ $variant->vehicleModel->brand->brand_name ?? '—' }} /
                                            {{ $variant->vehicleModel->model_name ?? '—' }} -
                                            {{ $variant->name }} ({{ $variant->engineType->name ?? '—' }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Search and select all variants that this part is compatible with.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-align-left"></i> Description</label>
                                <textarea name="description" class="form-control" rows="5">{{ old('description', $part->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-image"></i> Photo</label>
                                <div x-data="{ preview: '{{ $part->photo ? asset('storage/'.$part->photo) : '' }}' }">
                                    <input type="file" name="photo" accept="image/*" class="form-control"
                                        @change="preview = URL.createObjectURL($event.target.files[0])">
                                    <template x-if="preview">
                                        <div class="mt-2 position-relative">
                                            <img :src="preview" class="img-thumbnail" width="200">
                                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" @click="preview=null">×</button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <!-- Sticky Save Button -->
                <div class="action-bar text-end">
                    <button class="btn btn-success">
                        <i class="fas fa-save"></i> Update Part
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-multiple').select2({
            placeholder: "Search and select variants",
            allowClear: true
        });
    });
</script>
@endsection
