@extends('admin.layouts.app')
@section('title', 'Causes')
@section('content')
     <!-- Content Header (Page header) -->
     <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Causes ({{ $causes->count() }})</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                <li class="breadcrumb-item active">Causes</li>
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
            <div class="card-title"><a href="/admin/causes/create" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Cause</a></div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
 <table id="example1" class="table table-striped projects">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Cause</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                  @forelse($causes as $cause)
                    <tr>
                        <td>
                            {{ $cause->id }}
                        </td>
                        <td>
                          @if($cause->mainPhoto)
        <a href="{{ asset('storage/' . $cause->mainPhoto->file_path) }}">
            <img src="{{ asset('storage/' . $cause->mainPhoto->file_path) }}" 
                 alt="{{ $cause->name }}" 
                 class="img img-responsive img-thumbnail" 
                 style="width:100px;" />
        </a>
    @else
        <span>No photo</span>
    @endif
                        </td>
                        <td>
                            <a>
                                {{ $cause->name }}
                            </a>
                            <br/>
                            <small>
                                {{ $cause->created_at }}
                            </small>
                        </td>
                        <td>
                            {!! Str::limit($cause->description, 50) !!}
                        </td>
                        <td class="project-state">
                            @if($cause->status == 1)
                          <span class="badge badge-success">Active</span>
                            @else
                          <span class="badge badge-warning">Disactive</span>
                            @endif
                        </td>
                        <td class="d-flex">
                                <a class="btn btn-info btn-sm mr-2" href="{{ route('admin.causes.edit', $cause->id) }}">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </a>
                                <form action="{{ route('admin.causes.destroy', $cause->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this cause?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">There's no cause available at the moment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
          </div>
          </div>
      </div>
        <!-- /.card -->
  
      </section>
      @endsection