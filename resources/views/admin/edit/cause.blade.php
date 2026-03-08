@extends('admin.layouts.app')
@section('title', 'Edit Causes')
@section('content')
<section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Edit Cause</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Edit Cause</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-8">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Add Cause</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body" x-data="{ logoPreview: null }">
                
                @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
              <form action="{{ route('admin.causes.update', $cause->id) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                <div class="form-group">
                <label for="cause">Cause</label>
    <input type="text" name="name" id="cause" class="form-control" 
                                           value="{{ old('name', $cause->name) }}" required>
                  @error('cause')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
              </div>
              <div class="form-group">
                <label for="myeditorinstance">Cause Description</label>
<textarea id="myeditorinstance" name="description" class="form-control" rows="4">{{ old('description', $cause->description) }}</textarea>
                 @error('description')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
              </div>
              
               <div class="form-group">
                            <label>Current Logo:</label>
                             @if($cause->mainPhoto)
                            <div>
                                <img src="{{ asset('storage/'.$cause->mainPhoto->file_path) }}" alt="{{ $cause->name }}" class="img-fluid mb-2" style="max-height:100px;">
                            </div>
                            @else
                            <div>No Photo</div>
                            @endif
                        </div>
                        
            <div class="form-group">
                            <label for="photo">Change Photo (optional)<span class="text-danger">*</span></label>
                            <input type="file" name="photo" id="photo" class="form-control-file" accept="image/*" @change="logoPreview = URL.createObjectURL($event.target.files[0])">
                        @error('photo')<div class="invalid-feedback" role="alert"> {{ $message }}</div>@enderror
                    <template x-if="logoPreview">
        <div class="mt-2">
            <img :src="logoPreview" class="img-fluid" style="max-height:100px;">
        </div>
    </template>   
            </div>
              <hr />
        
        <div class="form-group text-right mt-3">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.causes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times-circle"></i> Cancel
                            </a>
                        </div>
                        
              </form>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    </section>
 @endsection