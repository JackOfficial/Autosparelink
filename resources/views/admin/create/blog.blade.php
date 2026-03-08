@extends('admin.layouts.app')
@section('title', 'HFRO | Create Blog')
@section('content')

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Create Blog</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}">Blogs</a></li>
          <li class="breadcrumb-item active">Create Blog</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary shadow-sm">
        <div class="card-header">
          <h3 class="card-title">New Blog Post</h3>
        </div>

        <div class="card-body" x-data="{ photoPreview: null }">
          <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- TITLE + CAUSE --}}
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label for="title">Blog Title</label>
                  <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title') }}" 
                    class="form-control @error('title') is-invalid @enderror" 
                    placeholder="Enter blog title" 
                    required>
                  @error('title')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="cause_id">Cause</label>
                  <select 
                    name="cause_id" 
                    id="cause_id" 
                    class="form-control @error('cause_id') is-invalid @enderror" 
                    required>
                    <option value="">-- Select Cause --</option>
                    @foreach($causes as $cause)
                      <option value="{{ $cause->id }}" {{ old('cause_id') == $cause->id ? 'selected' : '' }}>
                        {{ $cause->name }}
                      </option>
                    @endforeach
                  </select>
                  @error('cause_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            {{-- PHOTO --}}
            <div class="form-group">
              <label for="photo">Blog Photo</label>
              <input 
                type="file" 
                name="photo" 
                id="photo" 
                class="form-control-file @error('photo') is-invalid @enderror" 
                accept="image/*"
                @change="photoPreview = URL.createObjectURL($event.target.files[0])">
              @error('photo')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror

              <template x-if="photoPreview">
                <div class="mt-2">
                  <img :src="photoPreview" class="img-fluid rounded shadow-sm" style="max-height:150px;">
                </div>
              </template>
            </div>

            {{-- CONTENT --}}
            <div class="form-group">
              <label for="myeditorinstance">Blog Content</label>
              <textarea 
                name="content" 
                id="myeditorinstance" 
                class="form-control @error('content') is-invalid @enderror" 
                rows="8"
                placeholder="Write your full blog content here...">{{ old('content') }}</textarea>
              @error('content')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            {{-- STATUS --}}
            <div class="form-group">
              <label for="status">Status</label>
              <select 
                name="status" 
                id="status" 
                class="form-control @error('status') is-invalid @enderror">
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
              </select>
              @error('status')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <hr>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Publish Blog
              </button>
              <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">Cancel</a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
