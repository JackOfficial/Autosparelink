@extends('admin.layouts.app')
@section('title', 'Add Category')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Add Category</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active">Add Category</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary" x-data="{ photoPreview: null }">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="category_name">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="category_name" class="form-control" value="{{ old('category_name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/png,image/jpeg,image/jpg,image/webp" 
                                   @change="photoPreview = URL.createObjectURL($event.target.files[0])">
                            <template x-if="photoPreview">
                                <div class="mt-2"><img :src="photoPreview" class="img-thumbnail" style="width:120px;"></div>
                            </template>
                        </div>

                        <div class="form-group">
                            <label for="parent_id">Parent Category</label>
                            <select name="parent_id" class="form-control">
                                <option value="">-- None (Top Level) --</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- New Shipping Price Input Field --}}
                        <div class="form-group">
                            <label for="shipping_price">Shipping Price (RWF)</label>
                            <input type="number" name="shipping_price" class="form-control" min="0" step="0.01" 
                                   value="{{ old('shipping_price', 0) }}" 
                                   placeholder="e.g. 3000">
                            <small class="form-text text-muted">Set a customized shipping fee for this category, or leave at 0.</small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus mr-1"></i> Add Category</button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-default ml-1">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection