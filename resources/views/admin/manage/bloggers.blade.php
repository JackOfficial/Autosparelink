@extends('admin.layouts.app')
@section('title', 'Bloggers')
@section('content')
     <!-- Content Header (Page header) -->
     <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Bloggers ({{ $bloggers->count() }})</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                <li class="breadcrumb-item active">Bloggers</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
  
      <!-- Main content -->
      <section class="content">
        <!-- Default box -->
        @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
 <div class="card">
              <div class="card-header">
                <h3 class="card-title"><a href="/admin/bloggers/create" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Blogger</a></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Photo</th>
                    <th>Created at</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @forelse ($bloggers as $blogger)
                         <tr>
                    <td> {{ $blogger->id }}</td>
                    <td>{{ $blogger->first_name }} {{ $blogger->last_name }}</td>
                    <td>{{ $blogger->phone }}</td>
                    <td>{{ $blogger->email }}</td>
                    <td><a href="{{ asset('storage/' . $blogger->photo) }}" target="__blank">
                            <img src="{{ asset('storage/' . $blogger->photo) }}" alt="{{ $blogger->first_name }} {{ $blogger->last_name }}" class="img img-responsive img-thumbnail" style="width:70px;" />
                          </a></td>
                    <td>{{  $blogger->created_at }}</td>
                    <td class="d-flex">
                        <a class="btn btn-info btn-sm mr-2" href="/admin/bloggers/{{ $blogger->id }}/edit`">
                              <i class="fas fa-pencil-alt"></i> Edit</a>
                           <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete</button>
                    </td>
                  </tr>
                    @empty
                         <tr>
                        <td colspan="7" class="text-center">There's no blogger available at the moment.</td>
                    </tr>
                    @endforelse
                 
                 
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
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
