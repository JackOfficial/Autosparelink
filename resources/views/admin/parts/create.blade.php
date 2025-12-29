@extends('admin.layouts.app')
@section('title', 'Add Spare Part')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add New Spare Part</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.spare-parts.index') }}">Spare Parts</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content">

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.spare-parts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <!-- SKU -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">SKU <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" required>
                    </div>

                    <!-- Part Number -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Part Number</label>
                        <input type="text" name="part_number" class="form-control" value="{{ old('part_number') }}">
                    </div>

                    <!-- Part Name -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Part Name <span class="text-danger">*</span></label>
                        <input type="text" name="part_name" class="form-control" value="{{ old('part_name') }}" required>
                    </div>

                    <!-- Category -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Part Brand -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Part Brand <span class="text-danger">*</span></label>
                        <select name="part_brand_id" class="form-control" required>
                            @foreach($partBrands as $brand)
                                <option value="{{ $brand->id }}" {{ old('part_brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }} ({{ $brand->type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- OEM Number -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">OEM Number</label>
                        <input type="text" name="oem_number" class="form-control" value="{{ old('oem_number') }}">
                    </div>

                    <!-- Compatible Variants -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Compatible Variants</label>
                        <select name="variants[]" class="form-control select2-multiple" multiple="multiple" style="width: 100%;">
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}"
                                    {{ in_array($variant->id, old('variants', [])) ? 'selected' : '' }}>
                                    {{ $variant->vehicleModel->vehicleBrand->name ?? '—' }} /
                                    {{ $variant->vehicleModel->model_name ?? '—' }} -
                                    {{ $variant->name }} ({{ $variant->engineType->name ?? '—' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Search and select all variants that this part is compatible with.</small>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <!-- Price -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price') }}" required>
                    </div>

                    <!-- Stock Quantity -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity') }}" required>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Photo + Alpine Preview -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Photo</label>
                        
                        <div x-data="{ preview: null }">
                            <input 
                                type="file" 
                                name="photo" 
                                accept="image/*" 
                                class="form-control"
                                @change="preview = URL.createObjectURL($event.target.files[0])"
                            >

                            <template x-if="preview">
                                <img :src="preview" class="img-thumbnail mt-2" width="120">
                            </template>
                        </div>
                    </div>

                </div>

                <button class="btn btn-primary mt-2">
                    <i class="fas fa-save"></i> Save Part
                </button>

            </form>

        </div>
    </div>

</section>

@endsection

@section('scripts')
<!-- Select2 JS -->
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
