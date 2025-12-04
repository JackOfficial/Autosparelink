@extends('admin.layouts.app')
@section('title', 'HFRO - Add Cause')
@section("content")
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Add Cause</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Add Cause</li>
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
                <form method="post" action="/admin/causes" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group">
                  <label for="cause">Cause</label>
                  <input type="text" name="cause" id="cause" class="form-control" required />
                  <div v-if="form.errors.cause" v-text="form.errors.cause" class="text-danger"></div>
                </div>
                <div class="form-group">
                  <label for="myeditorinstance">Cause Description</label>
                  <textarea id="myeditorinstance" name="description" class="form-control" rows="4"></textarea>
                  <div v-if="form.errors.description" v-text="form.errors.description" class="text-danger"></div>
                </div>
                <div class="form-group">
                  <label for="photo">Photo</label>
                  <div>
                    <input type="file" name="photo" accept="image/*" @change="logoPreview = URL.createObjectURL($event.target.files[0])" id="photo" class="" />
                    @error('photo')<div class="invalid-feedback" role="alert"> {{ $message }}</div>@enderror
                <template x-if="logoPreview">
        <div class="mt-2">
            <img :src="logoPreview" class="img-fluid" style="max-height:100px;">
        </div>
    </template>   
                </div>
                </div>
                <hr />
                <div class="form-group d-flex justify-content-between ">
            <a href="#" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"> 
                <span><i  class="fa fa-plus"></i> Save Cause</span> 
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