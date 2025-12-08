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

                    <!-- Part Number -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Part Number</label>
                        <input type="text" name="part_number" class="form-control" required>
                    </div>

                    <!-- Part Name -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Part Name</label>
                        <input type="text" name="part_name" class="form-control" required>
                    </div>

                    <!-- Category -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Brand -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Brand</label>
                        <select name="brand_id" class="form-control" required>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Price -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" name="price" step="0.01" class="form-control" required>
                    </div>

                    <!-- Stock Quantity -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" required>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
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
                                @change="
                                    const file = $event.target.files[0];
                                    preview = URL.createObjectURL(file);
                                "
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
