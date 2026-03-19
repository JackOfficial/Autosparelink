@extends('admin.layouts.app')
@section('title', 'Add Blog Category')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Blog Category</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.blog-categories.index') }}">Blog Categories</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content" x-data="{ 
    name: '{{ old('category') }}',
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
                        {{-- Category Name & Slug Sync --}}
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
                                Slug preview: <span class="text-pink" x-text="slug || '...'"></span>
                            </small>
                        </div>

                        {{-- Photo Upload with Alpine Preview --}}
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
                                <div class="mt-2">
                                    <img :src="imagePreview" class="img-thumbnail shadow-sm" style="max-height: 200px;">
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
            <div class="info-box bg-light shadow-sm">
                <div class="info-box-content">
                    <span class="info-box-text text-muted font-weight-bold"><i class="fas fa-lightbulb text-warning mr-1"></i> SEO Tip</span>
                    <span class="info-box-number text-muted font-weight-normal mb-0" style="font-size: 0.9rem;">
                        The slug <code x-text="slug || 'category-name'"></code> is what appears in the URL. Keep names concise but descriptive for the best Google ranking.
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection