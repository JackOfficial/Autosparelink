@extends('admin.layouts.app')
@section('title', 'Edit News: ' . $news->title)

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-truncate" style="max-width: 450px;">Edit: {{ $news->title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">News</a></li>
                    <li class="breadcrumb-item active">Edit Article</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data" id="editNewsForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-9">
                    <div class="card card-outline card-info shadow-sm">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title" class="text-muted small uppercase">Article Title</label>
                                <input type="text" name="title" id="title" 
                                       class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                       placeholder="Enter title" 
                                       value="{{ old('title', $news->title) }}" required>
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="myeditorinstance" class="text-muted small uppercase">Full Content</label>
                                <textarea name="content" id="myeditorinstance" 
                                          class="form-control @error('content') is-invalid @enderror" 
                                          rows="20">{{ old('content', $news->content) }}</textarea>
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
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ (old('blog_category_id', $news->blog_category_id) == $category->id) ? 'selected' : '' }}>
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
                                @if($news->newsPhoto)
                                    <img id="imagePreview" src="{{ asset('storage/' . $news->newsPhoto->file_path) }}" 
                                         alt="Current Image" class="img-fluid rounded border shadow-sm" style="max-height: 180px;">
                                    <p class="text-muted small mt-2 mb-0" id="imageLabel">Current Image</p>
                                @else
                                    <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded d-none border shadow-sm" style="max-height: 180px;">
                                    <div id="placeholder" class="py-4 bg-light rounded border"><i class="fas fa-image fa-2x text-muted"></i></div>
                                @endif
                            </div>

                            <div class="custom-file text-left">
                                <input type="file" name="photo" class="custom-file-input" id="photoInput" accept="image/*">
                                <label class="custom-file-label text-truncate" for="photoInput">Replace Photo...</label>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-top border-success">
                        <div class="card-body">
                            <div class="small text-muted mb-3">
                                <i class="far fa-calendar-alt mr-1"></i> Posted: <strong>{{ $news->created_at->format('M d, Y') }}</strong><br>
                                <i class="far fa-user mr-1"></i> By: <strong>{{ $news->user->name ?? 'Admin' }}</strong>
                            </div>
                            <button type="submit" class="btn btn-success btn-block shadow-sm">
                                <i class="fas fa-save mr-1"></i> Update News
                            </button>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-default btn-block btn-sm mt-2">
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
    $('#editNewsForm').on('submit', function() {
        tinymce.triggerSave();
    });

    // Image Preview logic for replacement
    document.getElementById('photoInput').onchange = evt => {
        const [file] = document.getElementById('photoInput').files;
        if (file) {
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('placeholder');
            const label = document.getElementById('imageLabel');
            
            preview.classList.remove('d-none');
            if(placeholder) placeholder.classList.add('d-none');
            if(label) label.innerText = "New Image Preview";
            
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
    .card-title { font-size: 0.75rem; color: #888; letter-spacing: 1px; text-transform: uppercase; }
    .text-xs { font-size: 0.7rem; }
    .tox-tinymce { border-radius: 4px !important; border: 1px solid #dee2e6 !important; }
</style>
@endsection