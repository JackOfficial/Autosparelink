@extends('admin.layouts.app')
@section('title', 'Create Blog Post')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Write New Article</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}">Blogs</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-9">
                    <div class="card card-outline card-primary shadow-sm">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Article Title</label>
                                <input type="text" name="title" id="title" 
                                       class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                       placeholder="Enter a catchy title for your post..." 
                                       value="{{ old('title') }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="myeditorinstance">Content</label>
                                {{-- Updated ID to myeditorinstance --}}
                                <textarea name="content" id="myeditorinstance" 
                                          class="form-control @error('content') is-invalid @enderror" 
                                          rows="20">{{ old('content') }}</textarea>
                                @error('content')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-outline card-info shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title text-sm font-weight-bold">Classification</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="blog_category_id">Category</label>
                                <select name="blog_category_id" id="blog_category_id" class="form-control select2 @error('blog_category_id') is-invalid @enderror">
                                    <option value="" selected disabled>-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('blog_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }} ({{ ucfirst($category->type) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('blog_category_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card card-outline card-info shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title text-sm font-weight-bold">Featured Image</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="custom-file mb-3">
                                    <input type="file" name="photo" class="custom-file-input" id="photoInput" accept="image/*">
                                    <label class="custom-file-label" for="photoInput">Choose file</label>
                                </div>
                                <div id="preview-container" class="mt-2 text-center d-none">
                                    <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded shadow-sm border" style="max-height: 150px;">
                                </div>
                                <small class="text-muted d-block mt-2">Max 2MB. Recommended: 1200x800px.</small>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary btn-block shadow-sm">
                                <i class="fas fa-paper-plane mr-1"></i> Publish Article
                            </button>
                            <a href="{{ route('admin.blogs.index') }}" class="btn btn-default btn-block btn-sm mt-2">
                                Cancel & Exit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@push('scripts')
{{-- Adding the Rich Text Editor Script --}}
<script src="https://cdn.tiny.cloud/1/{{ env('TINYMCE_API_KEY') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#myeditorinstance',
        plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
        toolbar_mode: 'floating',
        height: 500,
        branding: false,
    });

    // Simple Image Preview
    document.getElementById('photoInput').onchange = evt => {
        const [file] = document.getElementById('photoInput').files;
        if (file) {
            document.getElementById('preview-container').classList.remove('d-none');
            document.getElementById('imagePreview').src = URL.createObjectURL(file);
        }
    }

    // Update file label name
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
@endpush

<style>
    .form-control-lg { font-weight: 600; font-size: 1.5rem; border: none; border-bottom: 2px solid #eee; border-radius: 0; }
    .form-control-lg:focus { box-shadow: none; border-color: #007bff; }
    .card-title { font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .tox-tinymce { border: 1px solid #eee !important; border-radius: 4px; }
</style>
@endsection