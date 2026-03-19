@extends('admin.layouts.app')
@section('title', 'Categories')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                {{-- Changed title to be more generic since it handles both types --}}
                <h1>Content Categories <span class="badge badge-secondary">{{ $blogCategories->total() }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content" x-data="{ 
    search: '',
    {{-- Updated filter logic to include Type and Description --}}
    filterRow(name, slug, type, description) {
        let searchTerm = this.search.toLowerCase();
        return name.toLowerCase().includes(searchTerm) || 
               slug.toLowerCase().includes(searchTerm) ||
               type.toLowerCase().includes(searchTerm) ||
               (description && description.toLowerCase().includes(searchTerm));
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
                <div class="input-group input-group-sm mr-3" style="width: 250px;">
                    <input type="text" x-model="search" class="form-control" placeholder="Search name, slug, or type...">
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
                        <th>Details</th> {{-- Merged Name & Description for better layout --}}
                        <th>Type</th>
                        <th>Slug</th>
                        <th>Posts</th>
                        <th>Created</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($blogCategories as $blogCategory)
                    <tr x-show="filterRow(
                            '{{ addslashes($blogCategory->name) }}', 
                            '{{ $blogCategory->slug }}', 
                            '{{ $blogCategory->type }}',
                            '{{ addslashes($blogCategory->description) }}'
                        )" 
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
                                <div class="img-size-50 bg-light d-flex align-items-center justify-content-center border rounded">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>

                        <td class="align-middle">
                            <strong>{{ $blogCategory->name }}</strong>
                            @if($blogCategory->description)
                                <p class="text-muted small mb-0 text-truncate" style="max-width: 200px;">
                                    {{ $blogCategory->description }}
                                </p>
                            @endif
                        </td>

                        <td class="align-middle">
                            @if($blogCategory->type === 'news')
                                <span class="badge badge-success"><i class="fas fa-bullhorn mr-1"></i> News</span>
                            @else
                                <span class="badge badge-primary"><i class="fas fa-newspaper mr-1"></i> Blog</span>
                            @endif
                        </td>

                        <td class="align-middle"><code class="text-pink">{{ $blogCategory->slug }}</code></td>
                        
                        <td class="align-middle">
                            <span class="badge badge-info">{{ $blogCategory->posts_count ?? 0 }}</span>
                        </td>

                        <td class="align-middle">
                            {{ $blogCategory->created_at->format('M d, Y') }}
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
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No categories found.</p>
                        </td>
                    </tr>
                    @endforelse
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
        if (confirm('Are you sure you want to delete this category?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>

<style>
    .img-size-50 { width: 50px; height: 50px; object-fit: cover; }
    .table td, .table th { vertical-align: middle !important; }
</style>
@endsection