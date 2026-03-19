@extends('admin.layouts.app')
@section('title', 'Manage Blogs')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Blogs <span class="badge badge-secondary ml-2">{{ $blogs->total() }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Blogs</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Article Registry</h3>
            <div class="card-tools ml-auto">
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="fa fa-plus-circle mr-1"></i> Write New Post
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th style="width: 50px;" class="pl-4">#</th>
                            <th>Cover</th>
                            <th>Title & Author</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Posted Date</th>
                            <th class="text-right pr-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($blogs as $blog)
                            <tr>
                                <td class="pl-4 font-weight-bold text-muted">{{ $blog->id }}</td>
                                
                                <td>
                                    @if($blog->blogPhoto)
                                        <a href="{{ asset('storage/' . $blog->blogPhoto->file_path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $blog->blogPhoto->file_path) }}"
                                                 alt="Cover"
                                                 class="rounded shadow-sm border"
                                                 style="width: 60px; height: 45px; object-fit: cover;">
                                        </a>
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center border" style="width: 60px; height: 45px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold text-dark">{{ Str::limit($blog->title, 45) }}</span>
                                        <small class="text-muted"><i class="far fa-user mr-1"></i> {{ $blog->user->name ?? 'System' }}</small>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge badge-light border px-2 py-1">
                                        {{ $blog->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>

                                <td>
                                    @php
                                        $type = $blog->category->type ?? 'blog';
                                        $badgeClass = $type === 'news' ? 'badge-success' : 'badge-primary';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} small font-weight-normal text-uppercase">
                                        {{ $type }}
                                    </span>
                                </td>

                                <td class="text-muted small">
                                    {{ $blog->created_at->format('M d, Y') }}<br>
                                    <span class="very-small">{{ $blog->created_at->diffForHumans() }}</span>
                                </td>

                                <td class="text-right pr-4">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-default btn-sm shadow-sm" title="Edit">
                                            <i class="fas fa-edit text-info"></i>
                                        </a>
                                        <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Archive this blog post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-default btn-sm shadow-sm" title="Delete">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" style="width: 80px; opacity: 0.2;">
                                    <p class="mt-3 text-muted">No blog posts found. Time to start writing!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($blogs->hasPages())
        <div class="card-footer bg-white border-top">
            <div class="d-flex justify-content-center">
                {{ $blogs->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</section>

<style>
    .very-small { font-size: 10px; }
    .table td { vertical-align: middle !important; }
    .table thead th { border-top: 0; border-bottom: 2px solid #eee; }
    .btn-group .btn { margin-left: 2px; border-radius: 4px !important; }
    .hover-shadow:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: 0.3s; }
</style>
@endsection