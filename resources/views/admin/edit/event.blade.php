@extends('admin.layouts.app')
@section('title', 'HFRO | Edit Event')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Edit Event</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin">Home</a></li>
          <li class="breadcrumb-item active">Edit Event</li>
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
          <h3 class="card-title">Edit Event: {{ $event->title }}</h3>
        </div>

        <div class="card-body" x-data="{ photoPreview: '{{ $event->photo ? asset('storage/' . $event->photo) : '' }}' }">
          <form method="POST" action="{{ route('admin.events.update', $event->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Event Title --}}
            <div class="form-group">
              <label for="title">Event Title</label>
              <input type="text" name="title" id="title" 
                     class="form-control @error('title') is-invalid @enderror" 
                     placeholder="Enter event title" 
                     value="{{ old('title', $event->title) }}" required>
              @error('title')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            {{-- Event Description --}}
            <div class="form-group">
              <label for="myeditorinstance">Description</label>
              <textarea name="description" id="myeditorinstance" rows="4" 
                        class="form-control @error('description') is-invalid @enderror" 
                        placeholder="Enter event description">{{ old('description', $event->description) }}</textarea>
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
                         value="{{ old('date', $event->date?->format('Y-m-d')) }}">
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
                         value="{{ old('time', $event->time?->format('H:i')) }}">
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
                         value="{{ old('location', $event->location) }}" placeholder="Event location">
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
                         value="{{ old('link', $event->link) }}" placeholder="Optional link">
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
                <option value="active" {{ old('status', $event->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $event->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
              </select>
              @error('status')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group" x-data="{ 
        photoPreviews: [], 
        existingPhotos: @json($event->event_photos),
        removedPhotoIds: []
    }">

    <label>Event Photos</label>

    {{-- Existing Photos --}}
    <div class="mb-2 d-flex flex-wrap gap-2">
        <template x-for="photo in existingPhotos" :key="photo.id">
            <div class="position-relative">
                <img :src="'{{ asset('storage') }}/' + photo.file_path" 
                     class="img-fluid rounded" style="max-height: 150px;">
                <button type="button" @click="removedPhotoIds.push(photo.id); existingPhotos = existingPhotos.filter(p => p.id !== photo.id)"
                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                        title="Remove">
                    &times;
                </button>
            </div>
        </template>
    </div>

    {{-- New Uploads --}}
    <input type="file" name="photos[]" 
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

    {{-- New Photo Previews --}}
    <template x-if="photoPreviews.length">
        <div class="mt-2 d-flex flex-wrap gap-2">
            <template x-for="src in photoPreviews" :key="src">
                <img :src="src" class="img-fluid rounded" style="max-height: 150px;">
            </template>
        </div>
    </template>

    {{-- Hidden input to send removed photo IDs --}}
    <input type="hidden" name="removed_photos" :value="removedPhotoIds.join(',')">
</div>



            <hr>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Update Event
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
