@extends('admin.layouts.app')
@section('title', 'Add Category')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Category</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.blog-categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content" x-data="{ 
    name: '{{ old('category') }}',
    type: '{{ old('type', 'blog') }}',
    description: '{{ old('description') }}',
    imagePreview: null,
    fileName: 'Choose file...',
    get slug() {
        return this.name
            .toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-')
            .replace(/^-+|-+$/g, '');
    }
}">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Category Details</h3>
                </div>
                
                <form action="{{ route('admin.blog-categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            {{-- Category Name --}}
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="category">Category Name</label>
                                    <input type="text" name="category" id="category" 
                                           x-model="name"
                                           class="form-control @error('category') is-invalid @enderror" 
                                           placeholder="e.g. Engine Maintenance" 
                                           required autofocus>
                                    @error('category')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted">
                                        Slug preview: <span class="text-pink" x-text="'/category/' + (slug || '...')"></span>
                                    </small>
                                </div>
                            </div>

                            {{-- Category Type --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type">Placement</label>
                                    <select name="type" id="type" x-model="type" class="form-control">
                                        <option value="blog">Article / Blog</option>
                                        <option value="news">News & Updates</option>
                                    </select>
                                    <small class="text-muted">Where will this appear?</small>
                                </div>
                            </div>
                        </div>

                        {{-- Category Description --}}
                        <div class="form-group">
                            <label for="description">Description <span class="text-muted small">(Optional)</span></label>
                            <textarea name="description" id="description" x-model="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="3" placeholder="Briefly describe what this category covers..."></textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Photo Upload --}}
                        <div class="form-group">
                            <label for="photo">Category Photo</label>
                            <div class="custom-file mb-3">
                                <input type="file" name="photo" id="photo" 
                                       class="custom-file-input @error('photo') is-invalid @enderror" 
                                       accept="image/*"
                                       @change="
                                           const file = $event.target.files[0];
                                           if (file) {
                                               fileName = file.name;
                                               imagePreview = URL.createObjectURL(file);
                                           }
                                       ">
                                <label class="custom-file-label" for="photo" x-text="fileName"></label>
                                @error('photo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            {{-- Image Preview Area --}}
                            <template x-if="imagePreview">
                                <div class="mt-2 text-center border p-2 bg-light rounded">
                                    <img :src="imagePreview" class="img-thumbnail shadow-sm" style="max-height: 150px;">
                                    <p class="small text-muted mt-1 mb-0">Image Preview</p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Save Category
                        </button>
                        <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-default float-right">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            {{-- Preview Card --}}
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-eye mr-1"></i> Live Preview</h3>
                </div>
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                         <template x-if="imagePreview">
                            <img class="profile-user-img img-fluid img-circle" :src="imagePreview" alt="Category Image">
                         </template>
                         <template x-if="!imagePreview">
                            <div class="profile-user-img img-fluid img-circle bg-light d-flex align-items-center justify-content-center mx-auto" style="height:100px; width:100px">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                         </template>
                    </div>
                    <h3 class="profile-username text-center" x-text="name || 'Category Name'"></h3>
                    <p class="text-muted text-center">
                        <span class="badge" :class="type === 'blog' ? 'badge-primary' : 'badge-success'">
                            <i class="fas mr-1" :class="type === 'blog' ? 'fa-newspaper' : 'fa-bullhorn'"></i>
                            <span x-text="type === 'blog' ? 'Blog' : 'News'"></span>
                        </span>
                    </p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Description Preview:</b> <br>
                            <span class="text-muted small" x-text="description || 'No description provided.'"></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="info-box bg-light shadow-sm">
                <div class="info-box-content">
                    <span class="info-box-text text-muted font-weight-bold"><i class="fas fa-search text-info mr-1"></i> SEO Tip</span>
                    <span class="info-box-number text-muted font-weight-normal mb-0" style="font-size: 0.85rem;">
                        For **Autosparelink**, use keywords in the description. Example: "Genuine Toyota body parts and replacement panels."
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection