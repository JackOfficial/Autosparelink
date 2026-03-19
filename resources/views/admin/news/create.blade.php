@extends('admin.layouts.app')
@section('title', 'Create News Article')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add New Article</h1>
            </div>
            <div class="col-sm-6 text-right">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">News</a></li>
                    <li class="breadcrumb-item active">New Article</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" id="createNewsForm">
            @csrf
            <div class="row">
                <div class="col-md-9">
                    <div class="card card-outline card-info shadow-sm">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title" class="text-muted small uppercase">Article Title</label>
                                <input type="text" name="title" id="title" 
                                       class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                       placeholder="Enter headline here..." 
                                       value="{{ old('title') }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="myeditorinstance" class="text-muted small uppercase">Full Content</label>
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
                    <div class="card card-outline card-secondary shadow-sm">
                        <div class="card-header border-0"><h3 class="card-title text-xs font-weight-bold">Classification</h3></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="blog_category_id">Category</label>
                                <select name="blog_category_id" id="blog_category_id" class="form-control @error('blog_category_id') is-invalid @enderror">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('blog_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card card-outline card-secondary shadow-sm">
                        <div class="card-header border-0"><h3 class="card-title text-xs font-weight-bold">Featured Image</h3></div>
                        <div class="card-body text-center">
                            <div id="preview-wrapper" class="mb-3">
                                <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded d-none border shadow-sm" style="max-height: 180px;">
                                <div id="placeholder" class="py-4 bg-light rounded border">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-muted"></i>
                                    <p class="small text-muted mb-0 mt-2">No image selected</p>
                                </div>
                            </div>

                            <div class="custom-file text-left">
                                <input type="file" name="photo" class="custom-file-input" id="photoInput" accept="image/*">
                                <label class="custom-file-label text-truncate" for="photoInput">Choose image...</label>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-top border-primary">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary btn-block shadow-sm mb-2">
                                <i class="fas fa-paper-plane mr-1"></i> Publish Now
                            </button>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-default btn-block btn-sm">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@push('scripts')
{{-- TinyMCE --}}
<script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#myeditorinstance',
        plugins: 'advlist autolink lists link image charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media table emoticons help',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        branding: false,
        height: 600,
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });

    // Ensure editor content is synced on form submit
    $('#createNewsForm').on('submit', function() {
        tinymce.triggerSave();
    });

    // Image Preview Logic
    document.getElementById('photoInput').onchange = evt => {
        const [file] = document.getElementById('photoInput').files;
        if (file) {
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('placeholder');
            
            preview.classList.remove('d-none');
            placeholder.classList.add('d-none');
            preview.src = URL.createObjectURL(file);
        }
    }

    // Bootstrap File Label Update
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
@endpush

<style>
    .form-control-lg { font-weight: 700; border: none; border-bottom: 2px solid #eee; border-radius: 0; padding-left: 0; }
    .form-control-lg:focus { box-shadow: none; border-color: #007bff; }
    .card-title { font-size: 0.75rem; color: #888; letter-spacing: 1px; text-transform: uppercase; }
    .text-xs { font-size: 0.7rem; }
    .tox-tinymce { border-radius: 4px !important; border: 1px solid #dee2e6 !important; }
</style>
@endsection