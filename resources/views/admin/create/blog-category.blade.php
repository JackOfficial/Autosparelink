@extends('admin.layouts.app')
@section('title', 'HFRO - Add Blog Category')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Add Blog Category</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                <li class="breadcrumb-item active">Add Blog Category</li>
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
                <h3 class="card-title">Add Blog Category</h3>
  
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form action="/admin/blogCategories" method="post" enctype="multipart/form-data">
                  @csrf
                    <div class="form-group">
                  <label for="category">Blog Category</label>
                  <input type="text" name="category" id="category" placeholder="Enter blog category" class="form-control" required />
                  @error('name')<div class="invalid-feedback" role="alert"> {{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                  <label for="photo">Photo</label>
                  <div>
                    <input type="file" name="photo" @input="form.photo = $event.target.files[0]" accept="image/jpg, image/jpeg, image/png" id="photo" class="" />
                    @error('photo')<div class="invalid-feedback" role="alert"> {{ $message }}</div>@enderror
                  </div>
                </div> 
                <hr />
                <div class="form-group">
            <button type="submit" class="btn btn-primary"> 
                <span><i  class="fa fa-plus"></i> Save Blog Category</span> 
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