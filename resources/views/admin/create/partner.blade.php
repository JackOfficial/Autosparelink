@extends('admin.layouts.app')
@section('title', 'HFRO | Add Partner')
@section('content')

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Add Partner</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Add Partner</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-10">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Create New Partner</h3>
        </div>

        <div class="card-body" x-data="{ logoPreview: null }">
          <form method="POST" action="{{ route('admin.partners.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">
              {{-- NAME --}}
              <div class="form-group col-md-6">
                <label for="name">Partner Name</label>
                <input 
                  type="text" 
                  name="name" 
                  id="name" 
                  value="{{ old('name') }}" 
                  class="form-control @error('name') is-invalid @enderror" 
                  placeholder="Enter partner name" 
                  required>
                @error('name')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              {{-- WEBSITE --}}
              <div class="form-group col-md-6">
                <label for="website">Website</label>
                <input 
                  type="url" 
                  name="website" 
                  id="website" 
                  value="{{ old('website') }}" 
                  class="form-control @error('website') is-invalid @enderror" 
                  placeholder="Enter website URL (optional)">
                @error('website')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- LOGO --}}
            <div class="form-group">
              <label for="logo">Partner Logo</label>
              <input 
                type="file" 
                name="logo" 
                id="logo" 
                class="form-control-file @error('logo') is-invalid @enderror" 
                accept="image/*"
                @change="logoPreview = URL.createObjectURL($event.target.files[0])">
              @error('logo')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror

              <template x-if="logoPreview">
                <div class="mt-2">
                  <img :src="logoPreview" class="img-fluid rounded" style="max-height:150px;">
                </div>
              </template>
            </div>

            {{-- DESCRIPTION --}}
            <div class="form-group">
              <label for="myeditorinstance">Description</label>
              <textarea 
                name="description" 
               id="myeditorinstance" 
                rows="5" 
                class="form-control @error('description') is-invalid @enderror" 
                placeholder="Enter description">{{ old('description') }}</textarea>
              @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <hr>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add Partner
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
