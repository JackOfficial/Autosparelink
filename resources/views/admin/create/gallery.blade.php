@extends('admin.layouts.app')
@section('title', 'Our Gallery')
@section('content')
<section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Add Photo</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Add Photo</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
  
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Photo</h3>
  
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form method="post" action="/admin/gallery" enctype="multipart/form-data">
                  @csrf
                <div class="form-group">
                            <label for="photo">Passport Photo <span class="text-danger">*</span></label>
                            <input type="file" name="photo" id="photo" class="form-control-file" accept="image/*" @change="logoPreview = URL.createObjectURL($event.target.files[0])" required>
                        @error('photo')<div class="invalid-feedback" role="alert"> {{ $message }}</div>@enderror
                    <template x-if="logoPreview">
        <div class="mt-2">
            <img :src="logoPreview" class="img-fluid" style="max-height:100px;">
        </div>
    </template>   
                    </div>
                <div class="form-group">
                  <label for="description">Cause Description</label>
                  <textarea id="description" name="description" class="form-control" rows="2"></textarea>
                  @error('description')<div class="invalid-feedback" role="alert"> {{ $message }}</div>@enderror
                </div>
                <hr />
                <div class="form-group">
            <button type="submit" class="btn btn-primary"> 
                <span><i  class="fa fa-plus"></i> Upload Photo</span> 
            </button>
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