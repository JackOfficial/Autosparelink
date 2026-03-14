@extends('admin.layouts.app')
@section('title', 'Categories')

@section('content')

{{-- 1. Initialize Alpine.js for real-time filtering --}}
<div x-data="{ 
    search: '',
    {{-- This function decides if a parent card should stay visible --}}
    isParentVisible(parentName, childrenJson) {
        if (!this.search) return true;
        const s = this.search.toLowerCase();
        if (parentName.toLowerCase().includes(s)) return true;
        
        {{-- Check if any subcategory inside matches --}}
        const children = JSON.parse(childrenJson);
        return children.some(child => child.category_name.toLowerCase().includes(s));
    },
    {{-- This function decides if a specific subcategory row should stay visible --}}
    isChildVisible(childName) {
        if (!this.search) return true;
        return childName.toLowerCase().includes(this.search.toLowerCase());
    }
}">

{{-- ================= HEADER ================= --}}
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    Categories
                    <small class="text-muted" x-show="!search">({{ $categories->count() }} parent categories)</small>
                    <small class="text-primary" x-show="search" x-cloak>Filtering results...</small>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
            </div>
        </div>
    </div>
</section>

{{-- ================= CONTENT ================= --}}
<section class="content">

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- ACTION & SEARCH BAR --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <div class="input-group shadow-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                </div>
                <input type="text" 
                       x-model="search" 
                       class="form-control border-left-0" 
                       placeholder="Search by category or subcategory name...">
                <div class="input-group-append" x-show="search" x-cloak>
                    <button class="btn btn-outline-secondary border-left-0" @click="search = ''">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary shadow-sm">
                <i class="fa fa-plus"></i> Add Category
            </a>
        </div>
    </div>

    {{-- CATEGORY GROUPS --}}
    @forelse($categories as $parent)
        <div class="card card-outline card-primary mb-4 shadow-sm" 
             x-show="isParentVisible('{{ addslashes($parent->category_name) }}', '{{ addslashes($parent->children->toJson()) }}')"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100">

            {{-- PARENT HEADER --}}
            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                <div class="d-flex align-items-center">
                    @if($parent->photo)
                        <img src="{{ asset('storage/' . $parent->photo) }}"
                             class="img-thumbnail mr-3 elevation-1"
                             style="width:60px; height:60px; object-fit:cover; border-radius: 10px;">
                    @else
                        <div class="mr-3 bg-light border d-flex align-items-center justify-content-center" style="width:60px; height:60px; border-radius: 10px;">
                            <i class="fas fa-image text-muted"></i>
                        </div>
                    @endif

                    <div>
                        <h5 class="mb-0 font-weight-bold text-dark">{{ $parent->category_name }}</h5>
                        <small class="text-muted">
                            {{ $parent->children->count() }} {{ Str::plural('Subcategory', $parent->children->count()) }}
                        </small>
                    </div>
                </div>

                <div>
                    <a href="{{ route('admin.categories.edit', $parent->id) }}"
                       class="btn btn-info btn-sm shadow-sm" title="Edit Parent">
                        <i class="fas fa-edit"></i>
                    </a>

                    <form action="{{ route('admin.categories.destroy', $parent->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Delete this parent and all its subcategories?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm shadow-sm" title="Delete Parent">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- CHILD CATEGORIES --}}
            <div class="card-body p-0">
                @if($parent->children->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="pl-4" width="100">Photo</th>
                                    <th>Subcategory Name</th>
                                    <th class="text-center">Parts Count</th>
                                    <th class="text-right pr-4" width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parent->children as $child)
                                    <tr x-show="isChildVisible('{{ addslashes($child->category_name) }}')"
                                        x-transition>
                                        <td class="pl-4">
                                            @if($child->photo)
                                                <img src="{{ asset('storage/' . $child->photo) }}"
                                                     class="img-circle elevation-1 border"
                                                     style="width:40px; height:40px; object-fit:cover;">
                                            @else
                                                <div class="img-circle bg-light border d-flex align-items-center justify-content-center shadow-none" style="width:40px; height:40px;">
                                                    <i class="fas fa-folder fa-xs text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <span class="font-weight-600">{{ $child->category_name }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-pill badge-info">{{ $child->parts_count ?? 0 }}</span>
                                        </td>
                                        <td class="text-right pr-4 align-middle">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.categories.edit', $child->id) }}"
                                                   class="btn btn-default shadow-sm text-primary" title="Edit Sub">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-default shadow-sm text-danger" 
                                                        onclick="if(confirm('Delete subcategory?')) document.getElementById('delete-{{ $child->id }}').submit();"
                                                        title="Delete Sub">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <form id="delete-{{ $child->id }}" action="{{ route('admin.categories.destroy', $child->id) }}" method="POST" class="d-none">
                                                @csrf @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-info-circle mr-1"></i> No subcategories available for this group.
                    </div>
                @endif
            </div>

        </div>
    @empty
        <div class="alert alert-info text-center py-5 shadow-sm">
            <i class="fas fa-search fa-2x mb-3"></i>
            <p class="mb-0">No categories found in the system.</p>
        </div>
    @endforelse

</section>
</div> {{-- End Alpine Wrapper --}}

<style>
    [x-cloak] { display: none !important; }
    .font-weight-600 { font-weight: 600; }
    .table-hover tbody tr:hover { background-color: rgba(0,123,255,0.03); }
</style>

@endsection