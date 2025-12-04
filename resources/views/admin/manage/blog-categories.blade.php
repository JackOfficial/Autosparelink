@extends('admin.layouts.app')
@section('title', 'Blog Categories')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Blog Categories ({{ $blogCategories->count() }})</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin">Home</a></li>
            <li class="breadcrumb-item active">Blog Categories</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    
<div class="card">
          <div class="card-header">
            <h3 class="card-title"><a href="/admin/blogCategories/create" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Blog Category</a></h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>#</th>
                <th>Category</th>
                <th>Photo</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @forelse ($blogCategories as $blogCategory)
                   <tr>
                <td> {{ $blogCategory->id }}</td>
                <td>{{ $blogCategory->blog_category }}</td>
                <td>
                  <a href="/storage/{{$blogCategory->photo}}" target="__blank">
                <img src="/storage/{{$blogCategory->photo}}" alt="{{ $blogCategory->blog_category }}" class="img img-responsive img-thumbnail" style="width:100px;"/>
              </a></td>
                <td>{{  $blogCategory->created_at }}</td>
                <td class="d-flex">
                    <a class="btn btn-info btn-sm mr-2" href="/admin/blogCategories/{{$blogCategory->id}}/edit">
                          <i class="fas fa-pencil-alt"></i> Edit</a>
                       <button class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete</button>
                </td>
              </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center">There's no blog category available at the moment.</td>
                </tr>
                @endforelse
              </tbody>
              <tfoot>
              <tr>
                <th>#</th>
                <th>Category</th>
                <th>Photo</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
  <!-- /.content -->
@endsection