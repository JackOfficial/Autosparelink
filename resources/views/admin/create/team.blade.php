@extends('admin.layouts.app')
@section('title', 'Add a Team Member')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Add Team Member</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin">Home</a></li>
          <li class="breadcrumb-item active">Team</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">
      <strong>There were some errors with your input:</strong>
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Team Member Details</h5>
    </div>
    <div class="card-body">

      <form action="{{ route('admin.team.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Personal Information --}}
        <fieldset class="border rounded p-3 mb-4">
          <legend class="w-auto px-2 text-primary fw-bold">Personal Information</legend>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="col-md-6 mb-3">
              <label for="position" class="form-label">Position / Role</label>
              <input type="text" name="position" id="position" class="form-control" value="{{ old('position') }}">
            </div>

            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">Email (Optional)</label>
              <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
            </div>

            <div class="col-md-6 mb-3">
              <label for="phone" class="form-label">Phone (Optional)</label>
              <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
            </div>

            <div class="col-md-12 mb-3">
              <label for="bio" class="form-label">Short Bio</label>
              <textarea name="bio" id="bio" rows="4" class="form-control" placeholder="Write a short biography...">{{ old('bio') }}</textarea>
            </div>
          </div>
        </fieldset>

        {{-- Social Links --}}
        <fieldset class="border rounded p-3 mb-4">
          <legend class="w-auto px-2 text-primary fw-bold">Social Links (Optional)</legend>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="facebook" class="form-label">Facebook URL</label>
              <input type="url" name="facebook" id="facebook" class="form-control" placeholder="https://facebook.com/username" value="{{ old('facebook') }}">
            </div>
            <div class="col-md-4 mb-3">
              <label for="linkedin" class="form-label">LinkedIn URL</label>
              <input type="url" name="linkedin" id="linkedin" class="form-control" placeholder="https://linkedin.com/in/username" value="{{ old('linkedin') }}">
            </div>
            <div class="col-md-4 mb-3">
              <label for="twitter" class="form-label">Twitter (X) URL</label>
              <input type="url" name="twitter" id="twitter" class="form-control" placeholder="https://x.com/username" value="{{ old('twitter') }}">
            </div>
          </div>
        </fieldset>

        {{-- Status & Photo --}}
        <fieldset class="border rounded p-3 mb-4">
  <legend class="w-auto px-2 text-primary fw-bold">Profile Settings</legend>
  <div class="row">
    <div class="col-md-6 mb-3">
      <label for="status" class="form-label">Status</label>
      <select name="status" id="status" class="form-control">
        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
      </select>
    </div>

    <div class="col-md-6 mb-3" x-data="{ preview: null }">
      <label for="photo" class="form-label">Profile Photo</label>
      <input 
        type="file" 
        name="photo" 
        id="photo" 
        class="form-control" 
        accept="image/*"
        @change="const file = $event.target.files[0];
                 if (file) {
                   const reader = new FileReader();
                   reader.onload = e => preview = e.target.result;
                   reader.readAsDataURL(file);
                 } else {
                   preview = null;
                 }"
        required
      >

      <!-- Preview Section -->
      <template x-if="preview">
        <div class="mt-3 text-center">
          <p class="text-muted mb-2">Preview:</p>
          <img :src="preview" alt="Profile Preview" class="img-thumbnail shadow-sm" style="max-height: 180px; border-radius: 10px;">
        </div>
      </template>
    </div>
  </div>
</fieldset>

        <div class="text-end">
          <button type="submit" class="btn btn-primary px-4">💾 Save Member</button>
          <a href="{{ route('admin.team.index') }}" class="btn btn-light border px-4">Cancel</a>
        </div>

      </form>

    </div>
  </div>
</section>
@endsection
