@extends('admin.layouts.app')
@section('title', 'Stories')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Stories ({{ $stories->count() }})</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin">Home</a></li>
          <li class="breadcrumb-item active">Stories</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

  <!-- Success message -->
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
      <h3 class="card-title">
        <a href="{{ route('admin.stories.create') }}" class="btn btn-sm btn-primary">
          <i class="fa fa-plus"></i> Add Story
        </a>
      </h3>
    </div>
    <!-- /.card-header -->

    <div class="card-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Photo</th>
            <th>Summary</th>
            <th>Status</th>
            <th>Blogger</th>
            <th>Created at</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($stories as $story)
          <tr>
            <td>{{ $story->id }}</td>
            <td>{{ Str::limit($story->title, 40) }}</td>
            <td>
              @if($story->photo)
                <a href="{{ asset('storage/' . $story->photo->file_path) }}" target="_blank">
                  <img src="{{ asset('storage/' . $story->photo->file_path) }}" 
                       alt="{{ $story->title }}" 
                       class="img img-thumbnail" 
                       style="width: 100px; height: 70px; object-fit: cover;">
                </a>
              @else
                     <div>No Photo</div>
              @endif
            </td>
            <td>{!! Str::limit(strip_tags($story->summary ?? $story->content), 60) !!}</td>
            <td>
              <span class="badge 
                @if($story->status == 'published') bg-success 
                @elseif($story->status == 'draft') bg-warning 
                @else bg-secondary 
                @endif">
                {{ ucfirst($story->status) }}
              </span>
            </td>
            <td>{{ $story->user->name ?? 'Unknown' }}</td>
            <td>{{ $story->created_at->format('Y-m-d') }}</td>
            <td class="d-flex">
              <a class="btn btn-info btn-sm mr-2" href="{{ route('admin.stories.edit', $story->id) }}">
                <i class="fas fa-pencil-alt"></i> Edit
              </a>

              <form action="{{ route('admin.stories.destroy', $story->id) }}" method="POST" class="d-inline" 
                    onsubmit="return confirm('Are you sure you want to delete this story?');">
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
            <td colspan="8" class="text-center">There's no story available at the moment.</td>
          </tr>
          @endforelse
        </tbody>
        <tfoot>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Photo</th>
            <th>Summary</th>
            <th>Status</th>
            <th>Blogger</th>
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
