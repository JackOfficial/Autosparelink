@extends('admin.layouts.app')
@section('title', 'Blog Categories')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Blog Categories <span class="badge badge-secondary">{{ $blogCategories->total() }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Blog Categories</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content" x-data="{ 
    search: '',
    {{-- This function checks if the row content matches the search string --}}
    filterRow(name, slug) {
        return name.toLowerCase().includes(this.search.toLowerCase()) || 
               slug.toLowerCase().includes(this.search.toLowerCase());
    }
}">
    {{-- Success Message --}}
    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icon fas fa-check"></i> {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Category Management</h3>
            
            <div class="card-tools d-flex">
                {{-- Live Search Input --}}
                <div class="input-group input-group-sm mr-3" style="width: 200px;">
                    <input type="text" x-model="search" class="form-control" placeholder="Search categories...">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>

                <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus-circle"></i> Add New
                </a>
            </div>
        </div>
        
        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Photo</th>
                        <th>Category Name</th>
                        <th>Slug</th>
                        <th>Posts</th>
                        <th>Created Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($blogCategories as $blogCategory)
                    {{-- Alpine logic to hide/show row based on search --}}
                    <tr x-show="filterRow('{{ addslashes($blogCategory->name) }}', '{{ $blogCategory->slug }}')" 
                        x-transition.opacity>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">
                            @if($blogCategory->photo)
                                <a href="{{ asset('storage/' . $blogCategory->photo) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $blogCategory->photo) }}" 
                                         alt="{{ $blogCategory->name }}" 
                                         class="img-size-50 img-thumbnail shadow-sm">
                                </a>
                            @else
                                <span class="text-muted small">No Image</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            <strong>{{ $blogCategory->name }}</strong>
                        </td>
                        <td class="align-middle"><code class="text-pink">{{ $blogCategory->slug }}</code></td>
                        <td class="align-middle">
                            <span class="badge badge-info">{{ $blogCategory->posts_count ?? 0 }}</span>
                        </td>
                        <td class="align-middle">
                            {{ $blogCategory->created_at->format('M d, Y') }}<br>
                            <small class="text-muted">{{ $blogCategory->created_at->diffForHumans() }}</small>
                        </td>
                        <td class="text-right align-middle">
                            <div class="btn-group">
                                <a href="{{ route('admin.blog-categories.edit', $blogCategory->id) }}" 
                                   class="btn btn-default btn-sm" title="Edit">
                                    <i class="fas fa-edit text-info"></i>
                                </a>
                                
                                <button type="button" class="btn btn-default btn-sm" 
                                        onclick="deleteCategory({{ $blogCategory->id }})" title="Delete">
                                    <i class="fas fa-trash text-danger"></i>
                                </button>
                            </div>

                            <form id="delete-form-{{ $blogCategory->id }}" 
                                  action="{{ route('admin.blog-categories.destroy', $blogCategory->id) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No blog categories found.</p>
                        </td>
                    </tr>
                    @endforelse
                    
                    {{-- Alpine "No Results" row --}}
                    <tr x-show="search !== '' && document.querySelectorAll('tbody tr[style*=\'display: none\']').length === {{ count($blogCategories) }}" style="display: none;">
                        <td colspan="7" class="text-center py-4">
                            <p class="text-muted mb-0">No categories match "<span x-text="search"></span>"</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($blogCategories->hasPages())
        <div class="card-footer clearfix">
            <div class="float-right">
                {{ $blogCategories->links() }}
            </div>
        </div>
        @endif
    </div>
</section>

<script>
    function deleteCategory(id) {
        if (confirm('Are you sure you want to delete this category? It will be moved to trash.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>

<style>
    .img-size-50 { width: 50px; height: 50px; object-fit: cover; }
    .table td, .table th { vertical-align: middle !important; }
</style>
@endsection