@extends('admin.layouts.app')
@section('title', 'Categories')

@section('content')
{{-- 1. Initialize Alpine Data --}}
<div x-data="{ 
    search: '',
    {{-- This function checks if a parent or any of its children matches the search --}}
    isMatch(parentName, childrenJson) {
        if (this.search === '') return true;
        const s = this.search.toLowerCase();
        if (parentName.toLowerCase().includes(s)) return true;
        
        const children = JSON.parse(childrenJson);
        return children.some(child => child.category_name.toLowerCase().includes(s));
    }
}">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Categories <small class="text-muted">({{ $categories->count() }})</small></h1>
                </div>
                <div class="col-sm-6">
                    <div class="input-group shadow-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-primary"></i></span>
                        </div>
                        <input type="text" 
                               x-model="search" 
                               class="form-control border-left-0" 
                               placeholder="Search parent or subcategories...">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="mb-3 text-right">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fa fa-plus"></i> Add Category
            </a>
        </div>

        @forelse($categories as $parent)
            {{-- 2. Parent Card Filter --}}
            <div class="card card-outline card-primary mb-4" 
                 x-show="isMatch('{{ addslashes($parent->category_name) }}', '{{ addslashes($parent->children->toJson()) }}')"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">

                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                    <div class="d-flex align-items-center">
                        @if($parent->photo)
                            <img src="{{ asset('storage/' . $parent->photo) }}"
                                 class="img-thumbnail mr-3 shadow-sm"
                                 style="width:50px; height:50px; object-fit:cover; border-radius: 8px;">
                        @else
                            <div class="mr-3 bg-light d-flex align-items-center justify-content-center shadow-sm" style="width:50px; height:50px; border-radius: 8px;">
                                <i class="fas fa-layer-group text-muted"></i>
                            </div>
                        @endif

                        <div>
                            <h5 class="mb-0 font-weight-bold text-dark">{{ $parent->category_name }}</h5>
                            <span class="badge badge-pill badge-light border">{{ $parent->children->count() }} Subcategories</span>
                        </div>
                    </div>

                    <div class="btn-group">
                        <a href="{{ route('admin.categories.edit', $parent->id) }}" class="btn btn-tool text-info"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.categories.destroy', $parent->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete parent and all subcategories?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-tool text-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if($parent->children->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="pl-4" width="100">Photo</th>
                                        <th>Subcategory Name</th>
                                        <th class="text-center">Parts</th>
                                        <th class="text-right pr-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($parent->children as $child)
                                        {{-- 3. Row Level Filter --}}
                                        <tr x-show="search === '' || '{{ addslashes($child->category_name) }}'.toLowerCase().includes(search.toLowerCase())">
                                            <td class="pl-4">
                                                <img src="{{ $child->photo ? asset('storage/' . $child->photo) : asset('assets/img/no-image.png') }}"
                                                     class="img-circle elevation-1 border"
                                                     style="width:35px; height:35px; object-fit:cover;">
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-dark font-weight-600">{{ $child->category_name }}</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-info">{{ $child->parts_count ?? 0 }}</span>
                                            </td>
                                            <td class="text-right pr-4 align-middle">
                                                <a href="{{ route('admin.categories.edit', $child->id) }}" class="btn btn-sm btn-outline-primary mx-1"><i class="fas fa-pencil-alt"></i></a>
                                                <form action="{{ route('admin.categories.destroy', $child->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete subcategory?')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">No categories found in the database.</p>
            </div>
        @endforelse
    </section>
</div>
@endsection