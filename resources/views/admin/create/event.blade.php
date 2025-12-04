@extends('admin.layouts.app')
@section('title', 'HFRO | Add Event')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Add Event</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin">Home</a></li>
          <li class="breadcrumb-item active">Add Event</li>
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
          <h3 class="card-title">Create New Event</h3>
        </div>

        <div class="card-body" x-data="{ photoPreview: null }">
          <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Event Title --}}
            <div class="form-group">
              <label for="title">Event Title</label>
              <input type="text" name="title" id="title" 
                     class="form-control @error('title') is-invalid @enderror" 
                     placeholder="Enter event title" value="{{ old('title') }}" required>
              @error('title')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            {{-- Event Description --}}
            <div class="form-group">
              <label for="myeditorinstance">Description</label>
              <textarea name="description" id="myeditorinstance" rows="4" 
                        class="form-control @error('description') is-invalid @enderror" 
                        placeholder="Enter event description">{{ old('description') }}</textarea>
              @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            {{-- Date & Time --}}
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="date">Event Date</label>
                  <input type="date" name="date" id="date" 
                         class="form-control @error('date') is-invalid @enderror" 
                         value="{{ old('date') }}">
                  @error('date')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="time">Event Time</label>
                  <input type="time" name="time" id="time" 
                         class="form-control @error('time') is-invalid @enderror" 
                         value="{{ old('time') }}">
                  @error('time')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            {{-- Location & Link --}}
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="location">Location</label>
                  <input type="text" name="location" id="location" 
                         class="form-control @error('location') is-invalid @enderror" 
                         value="{{ old('location') }}" placeholder="Event location">
                  @error('location')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="link">Event Link</label>
                  <input type="url" name="link" id="link" 
                         class="form-control @error('link') is-invalid @enderror" 
                         value="{{ old('link') }}" placeholder="Optional link">
                  @error('link')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            {{-- Status --}}
            <div class="form-group">
              <label for="status">Status</label>
              <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
              </select>
              @error('status')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            {{-- Photo Upload --}}
           <div class="form-group" x-data="{ photoPreviews: [] }">
    <label for="photos">Event Photos</label>
    <input type="file" name="photos[]" id="photos"
           class="form-control-file @error('photos') is-invalid @enderror"
           accept="image/*" multiple
           @change="
                photoPreviews = [];
                Array.from($event.target.files).forEach(file => {
                    photoPreviews.push(URL.createObjectURL(file));
                })
           ">
    @error('photos')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror

    <template x-if="photoPreviews.length">
        <div class="mt-2 d-flex flex-wrap gap-2">
            <template x-for="src in photoPreviews" :key="src">
                <img :src="src" class="img-fluid rounded" style="max-height: 150px;">
            </template>
        </div>
    </template>
</div>


            <hr>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-plus"></i> Create Event
              </button>
              <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Cancel</a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
