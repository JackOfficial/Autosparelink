@extends('admin.layouts.app')
@section('title', 'HFRO - Add Blogger')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Add Blogger</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                <li class="breadcrumb-item active">Add Blogger</li>
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
                <h3 class="card-title">Add Blogger</h3>
  
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form method="post" action="/admin/bloggers" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                  <label for="firstName">First Name</label>
                  <input type="text" name="first_name" placeholder="Enter first name" class="form-control" required />
                  @error('first_name')<div class="invalid-feedback" role="alert"> {{ $message }}</div>@enderror
                </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                  <label for="lastName">Last Name</label>
                  <input type="text" name="last_name" id="lastName" placeholder="Enter last name" class="form-control" required />
                   @error('last_name')<div class="invalid-feedback" role="alert"> {{ $message }}</div>@enderror
                </div>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="tel" id="phone" name="phone" placeholder="Enter your phone" class="form-control" />
                  @error('phone')<div class="invalid-feedback" role="alert"> {{ $message }}</div>@enderror
                </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" name= "email" id="email" placeholder="Enter your email" class="form-control" />
                  @error('email')<div class="invalid-feedback" role="alert"> {{ $message }}</div>@enderror
                    </div>
                </div>
                <div>
                    <input type="file" name="photo" accept="image/*" @change="logoPreview = URL.createObjectURL($event.target.files[0])" id="photo" class="" />
                    @error('photo')<div class="invalid-feedback" role="alert"> {{ $message }}</div>@enderror
                <template x-if="logoPreview">
        <div class="mt-2">
            <img :src="logoPreview" class="img-fluid" style="max-height:100px;">
        </div>
    </template>   
                </div>
                <hr />
                <div class="form-group">
            <button type="submit" class="btn btn-primary"> 
                <span><i class="fa fa-plus"></i> Save Blogger</span> 
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