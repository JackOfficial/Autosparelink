@extends('admin.layouts.app')
@section('title', 'Blogs')

@section('content')
<!-- Content Header -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Blogs ({{ $blogs->count() }})</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin">Home</a></li>
          <li class="breadcrumb-item active">Blogs</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<!-- Main Content -->
<section class="content">
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title mb-0">All Blogs</h3>
      <a href="{{ route('admin.blogs.create') }}" class="btn btn-sm btn-primary">
        <i class="fa fa-plus"></i> Add Blog
      </a>
    </div>

    <div class="card-body">
      <table id="example1" class="table table-bordered table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Photo</th>
            <th>Cause</th>
            <th>Author</th>
            <th>Created at</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
          @forelse ($blogs as $blog)
            <tr>
              <td>{{ $blog->id }}</td>
              <td>{{ Str::limit($blog->title, 50) }}</td>
              
              <td>
                @if($blog->blogPhoto)
                  <a href="{{ asset('storage/' . $blog->blogPhoto->file_path) }}" target="_blank">
                    <img src="{{ asset('storage/' . $blog->blogPhoto->file_path) }}"
                         alt="{{ $blog->title }}"
                         class="img img-thumbnail"
                         style="width: 100px; height: 70px; object-fit: cover;">
                  </a>
                @else
                  <span class="text-muted">No Photo</span>
                @endif
              </td>

              <td>{{ $blog->cause->name ?? 'Uncategorized' }}</td>
              <td>{{ $blog->user->name ?? 'Unknown' }}</td>
              <td>{{ $blog->created_at->format('Y-m-d') }}</td>

              <td class="d-flex">
                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-info btn-sm mr-2">
                  <i class="fas fa-pencil-alt"></i> Edit
                </a>

                <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Are you sure you want to delete this blog?');">
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
              <td colspan="7" class="text-center text-muted">No blogs available at the moment.</td>
            </tr>
          @endforelse
        </tbody>

        <tfoot>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Photo</th>
            <th>Cause</th>
            <th>Author</th>
            <th>Created at</th>
            <th>Action</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</section>
@endsection
