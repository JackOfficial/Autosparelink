@extends('admin.layouts.app')
@section('title', 'HFRO | Edit Story')
@section('content')

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Edit Story</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin">Home</a></li>
          <li class="breadcrumb-item active">Edit Story</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Edit Story</h3>
        </div>

        <div class="card-body" x-data="{ photoPreview: null }">
          <form method="POST" action="{{ route('admin.stories.update', $story->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- TITLE --}}
            <div class="form-group">
              <label for="title">Story Title</label>
              <input 
                type="text" 
                name="title" 
                id="title" 
                value="{{ old('title', $story->title) }}" 
                class="form-control @error('title') is-invalid @enderror" 
                placeholder="Enter story title" 
                required>
              @error('title')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            {{-- SUMMARY --}}
            <div class="form-group">
              <label for="summary">Short Summary</label>
              <textarea 
                name="summary" 
                id="summary" 
                rows="3" 
                class="form-control @error('summary') is-invalid @enderror" 
                placeholder="Write a short summary...">{{ old('summary', $story->summary) }}</textarea>
              @error('summary')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            {{-- CURRENT PHOTO --}}
            @if($story->photo)
            <div class="form-group">
              <label>Current Photo:</label><br>
              <img src="{{ asset('storage/' . $story->photo->file_path) }}" 
                   alt="{{ $story->photo->caption }}" 
                   class="img-thumbnail mb-2" 
                   style="max-width: 200px;">
            </div>
            @endif

            {{-- NEW PHOTO --}}
            <div class="form-group">
              <label for="photo">Change Photo</label>
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

              {{-- PREVIEW NEW PHOTO --}}
              <template x-if="photoPreview">
                <div class="mt-2">
                  <img :src="photoPreview" class="img-fluid rounded" style="max-height:150px;">
                </div>
              </template>
            </div>

            {{-- CONTENT --}}
            <div class="form-group">
              <label for="myeditorinstance">Story Content</label>
              <textarea 
                name="content" 
                id="myeditorinstance" 
                class="form-control @error('content') is-invalid @enderror" 
                rows="8">{{ old('content', $story->content) }}</textarea>
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
                <option value="draft" {{ old('status', $story->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status', $story->status) == 'published' ? 'selected' : '' }}>Published</option>
                <option value="archived" {{ old('status', $story->status) == 'archived' ? 'selected' : '' }}>Archived</option>
              </select>
              @error('status')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <hr>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Update Story
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
