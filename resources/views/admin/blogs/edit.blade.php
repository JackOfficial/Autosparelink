@extends('admin.layouts.app')
@section('title', 'Edit Blog: ' . $blog->title)

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-truncate" style="max-width: 400px;">Edit: {{ $blog->title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}">Blogs</a></li>
                    <li class="breadcrumb-item active">Edit Article</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-9">
                    <div class="card card-outline card-info shadow-sm">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Article Title</label>
                                <input type="text" name="title" id="title" 
                                       class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                       placeholder="Enter title" 
                                       value="{{ old('title', $blog->title) }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="content">Full Content</label>
                                <textarea name="content" id="editor" 
                                          class="form-control @error('content') is-invalid @enderror" 
                                          rows="18">{{ old('content', $blog->content) }}</textarea>
                                @error('content')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-outline card-secondary shadow-sm">
                        <div class="card-header"><h3 class="card-title text-xs font-weight-bold">Classification</h3></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="blog_category_id">Category</label>
                                <select name="blog_category_id" id="blog_category_id" class="form-control @error('blog_category_id') is-invalid @enderror">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ (old('blog_category_id', $blog->blog_category_id) == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name }} ({{ ucfirst($category->type) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card card-outline card-secondary shadow-sm">
                        <div class="card-header"><h3 class="card-title text-xs font-weight-bold">Featured Image</h3></div>
                        <div class="card-body text-center">
                            <div id="preview-wrapper" class="mb-3">
                                @if($blog->blogPhoto)
                                    <img id="imagePreview" src="{{ asset('storage/' . $blog->blogPhoto->file_path) }}" 
                                         alt="Current Image" class="img-fluid rounded border shadow-sm" style="max-height: 180px;">
                                    <p class="text-muted small mt-2 mb-0">Current Image</p>
                                @else
                                    <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded d-none border shadow-sm" style="max-height: 180px;">
                                    <div id="placeholder" class="py-4 bg-light rounded border"><i class="fas fa-image fa-2x text-muted"></i></div>
                                @endif
                            </div>

                            <div class="custom-file text-left">
                                <input type="file" name="photo" class="custom-file-input" id="photoInput" accept="image/*">
                                <label class="custom-file-label" for="photoInput">Replace Photo...</label>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-top border-success">
                        <div class="card-body">
                            <div class="small text-muted mb-3">
                                <i class="far fa-calendar-alt mr-1"></i> Published: <strong>{{ $blog->created_at->format('M d, Y') }}</strong><br>
                                <i class="far fa-user mr-1"></i> Author: <strong>{{ $blog->user->name ?? 'System' }}</strong>
                            </div>
                            <button type="submit" class="btn btn-success btn-block shadow-sm">
                                <i class="fas fa-save mr-1"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.blogs.index') }}" class="btn btn-default btn-block btn-sm mt-2">
                                Discard Changes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
    // Preview for replacement image
    document.getElementById('photoInput').onchange = evt => {
        const [file] = document.getElementById('photoInput').files;
        if (file) {
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('placeholder');
            
            preview.classList.remove('d-none');
            if(placeholder) placeholder.classList.add('d-none');
            
            preview.src = URL.createObjectURL(file);
        }
    }

    // Bootstrap File Input Label Update
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
@endpush

<style>
    .form-control-lg { font-weight: 700; border: none; border-bottom: 2px solid #eee; border-radius: 0; padding-left: 0; }
    .form-control-lg:focus { box-shadow: none; border-color: #007bff; }
    .card-title { font-size: 0.75rem; color: #888; letter-spacing: 1px; }
    .text-xs { font-size: 0.7rem; }
</style>
@endsection