@extends('admin.layouts.app')
@section('title', 'Manage News & Updates')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>News & Updates</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.news.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus-circle mr-1"></i> Add New Article
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 80px">Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($newsItems as $item)
                        <tr>
                            <td>
                                @if($item->newsPhoto)
                                    <img src="{{ asset('storage/' . $item->newsPhoto->file_path) }}" 
                                         class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded text-center py-2" style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span class="font-weight-bold">{{ $item->title }}</span>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-info">{{ $item->category->name ?? 'Uncategorized' }}</span>
                            </td>
                            <td class="align-middle small">{{ $item->user->name ?? 'Admin' }}</td>
                            <td class="align-middle small">{{ $item->created_at->format('M d, Y') }}</td>
                            <td class="text-right align-middle">
                                <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No news articles found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                {{ $newsItems->links() }}
            </div>
        </div>
    </div>
</section>
@endsection