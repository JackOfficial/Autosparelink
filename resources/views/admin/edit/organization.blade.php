@extends('admin.layouts.app')

@section('title', 'HFRO - Edit Organization')
@section('content')

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit Organization</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin">Home</a></li>
            <li class="breadcrumb-item active">Edit Organization</li>
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
            <h3 class="card-title">Organization Information</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          
          <div class="card-body" x-data="{ logoPreview: '{{ asset('storage/'.$organization->logo) }}' }">
            <form method="POST" action="{{ route('admin.organization.update', $organization->id) }}" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              {{-- Basic Info --}}
              <fieldset class="border p-3 mb-3">
                <legend class="w-auto px-2">Basic Info</legend>
                <div class="form-row">
                  <div class="form-group col-md-6">
                      <label for="name">Name <span class="text-danger">*</span></label>
                      <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ $organization->name }}" required>
                      @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                  <div class="form-group col-md-6">
                      <label for="logo">Logo</label>
                      <input type="file" name="logo" id="logo" accept="image/*" @change="logoPreview = URL.createObjectURL($event.target.files[0])" class="form-control-file">
                      <template x-if="logoPreview">
                        <div class="mt-2">
                          <img :src="logoPreview" class="img-fluid" style="max-height:100px;">
                        </div>
                      </template>
                      @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                </div>
              </fieldset>

              {{-- Contact Info --}}
              <fieldset class="border p-3 mb-3">
                <legend class="w-auto px-2">Contact Info</legend>
                <div class="form-row">
                  <div class="form-group col-md-6">
                      <label for="email">Email</label>
                      <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ $organization->email }}">
                      @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                  <div class="form-group col-md-6">
                      <label for="phone">Phone</label>
                      <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ $organization->phone }}">
                      @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                </div>
                 <div class="row">
                     <div class="form-group col-md-6">
                  <label for="address">Address</label>
                  <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ $organization->address }}">
                  @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="form-group col-md-6">
                  <label for="website">Website</label>
                  <input type="url" name="website" id="website" class="form-control @error('website') is-invalid @enderror" value="{{ $organization->website }}" placeholder="https://example.com">
                  @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                 </div>
              </fieldset>

              {{-- Organization Details --}}
              <fieldset class="border p-3 mb-3">
                <legend class="w-auto px-2">Organization Details</legend>
                <div class="form-group">
                  <label for="myeditorinstance">Mission</label>
                  <textarea name="mission" id="myeditorinstance" class="form-control @error('mission') is-invalid @enderror" rows="2">{{ $organization->mission }}</textarea>
                  @error('mission')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                  <label for="myeditorinstance">Vision</label>
                  <textarea name="vision" id="myeditorinstance" class="form-control @error('vision') is-invalid @enderror" rows="2">{{ $organization->vision }}</textarea>
                  @error('vision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                  <label for="myeditorinstance">About</label>
                  <textarea name="about" id="myeditorinstance" class="form-control @error('about') is-invalid @enderror" rows="4">{{ $organization->about }}</textarea>
                  @error('about')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </fieldset>

              {{-- Submit Button --}}
              <div class="form-group d-flex justify-content-between">
                  <a href="{{ route('admin.organization.index') }}" class="btn btn-outline-secondary">Cancel</a>
                  <button type="submit" class="btn btn-primary">
                      <span><i class="fa fa-save"></i> Update Organization</span>
                  </button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
</section>

@endsection
