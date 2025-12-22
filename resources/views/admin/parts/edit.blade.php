@extends('admin.layouts.app')
@section('title', 'Edit Spare Part')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Spare Part</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.spare-parts.index') }}">Spare Parts</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.spare-parts.update', $part->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">

                    <!-- SKU -->
                    <div class="col-md-6 mb-3">
                        <label>SKU <span class="text-danger">*</span></label>
                        <input type="text" name="sku" 
                               value="{{ $part->sku }}" 
                               class="form-control" required>
                    </div>

                    <!-- Part Number -->
                    <div class="col-md-6 mb-3">
                        <label>Part Number</label>
                        <input type="text" name="part_number" 
                               value="{{ $part->part_number }}" 
                               class="form-control">
                    </div>

                    <!-- Part Name -->
                    <div class="col-md-6 mb-3">
                        <label>Part Name <span class="text-danger">*</span></label>
                        <input type="text" name="part_name" 
                               value="{{ $part->part_name }}"
                               class="form-control" required>
                    </div>

                    <!-- Category -->
                    <div class="col-md-6 mb-3">
                        <label>Category</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ $category->id == $part->category_id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Part Brand -->
                    <div class="col-md-6 mb-3">
                        <label>Part Brand</label>
                        <select name="part_brand_id" class="form-control" required>
                            @foreach($partBrands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ $brand->id == $part->part_brand_id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- OEM Number -->
                    <div class="col-md-6 mb-3">
                        <label>OEM Number</label>
                        <input type="text" name="oem_number" 
                               value="{{ $part->oem_number }}" 
                               class="form-control">
                    </div>

                    <!-- Description -->
                    <div class="col-md-12 mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ $part->description }}</textarea>
                    </div>

                    <!-- Price -->
                    <div class="col-md-6 mb-3">
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" 
                               value="{{ $part->price }}"
                               class="form-control" required>
                    </div>

                    <!-- Stock Quantity -->
                    <div class="col-md-6 mb-3">
                        <label>Stock Quantity</label>
                        <input type="number" name="stock_quantity"
                               value="{{ $part->stock_quantity }}"
                               class="form-control" required>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $part->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $part->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Photo Preview + Alpine Live Preview -->
                    <div class="col-md-6 mb-3">
                        <label>Photo</label>

                        <div x-data="{ preview: null }">

                            <input type="file" name="photo" accept="image/*" class="form-control"
                                   @change="preview = URL.createObjectURL($event.target.files[0])">

                            <!-- Existing Image -->
                            @if($part->photo)
                                <img src="{{ asset('storage/' . $part->photo) }}" 
                                     class="img-thumbnail mt-2" width="120">
                            @endif

                            <!-- New Preview -->
                            <template x-if="preview">
                                <img :src="preview" class="img-thumbnail mt-2" width="120">
                            </template>
                        </div>
                    </div>

                </div>

                <button class="btn btn-primary mt-2">
                    <i class="fas fa-save"></i> Update Part
                </button>

            </form>

        </div>
    </div>

</section>

@endsection
