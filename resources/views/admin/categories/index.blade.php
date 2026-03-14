@extends('admin.layouts.app')
@section('title', 'Categories')

@section('content')

{{-- 1. Initialize Alpine.js for real-time filtering --}}
<div x-data="{ 
    search: '',
    isParentVisible(parentName, childrenJson) {
        if (!this.search) return true;
        const s = this.search.toLowerCase();
        if (parentName.toLowerCase().includes(s)) return true;
        
        const children = JSON.parse(childrenJson);
        return children.some(child => child.category_name.toLowerCase().includes(s));
    },
    isChildVisible(childName) {
        if (!this.search) return true;
        return childName.toLowerCase().includes(this.search.toLowerCase());
    }
}">

{{-- ================= HEADER ================= --}}
<section class="content-header py-4"> {{-- Increased padding --}}
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="display-5 font-weight-bold"> {{-- Bigger Title --}}
                    Categories
                    <small class="text-muted h4" x-show="!search">({{ $categories->count() }})</small>
                    <small class="text-primary h4" x-show="search" x-cloak>Filtering...</small>
                </h1>
            </div>
            <div class="col-sm-6 text-right">
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
    {{-- Container widened to 1100px and centered for a balanced look --}}
    <div class="container-fluid" style="max-width: 1100px;">

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        {{-- ACTION & SEARCH BAR --}}
        <div class="row mb-4 align-items-center">
            <div class="col-md-7">
                <div class="input-group input-group-lg shadow-sm"> {{-- Switched to Large input --}}
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0 px-3">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" 
                           x-model="search" 
                           class="form-control border-left-0" 
                           placeholder="Search categories or subcategories...">
                    <div class="input-group-append" x-show="search" x-cloak>
                        <button class="btn btn-white border-left-0" @click="search = ''">
                            <i class="fas fa-times text-danger"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-5 text-right">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-lg shadow-sm px-4">
                    <i class="fa fa-plus mr-2"></i> Create Category
                </a>
            </div>
        </div>

        {{-- CATEGORY GROUPS --}}
        @forelse($categories as $parent)
            <div class="card card-outline card-primary mb-4 shadow border-0" 
                 x-show="isParentVisible('{{ addslashes($parent->category_name) }}', '{{ addslashes($parent->children->toJson()) }}')"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                {{-- SPACIOUS PARENT HEADER --}}
                <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white border-bottom">
                    <div class="d-flex align-items-center">
                        @if($parent->photo)
                            <img src="{{ asset('storage/' . $parent->photo) }}"
                                 class="img-thumbnail mr-3 shadow-sm"
                                 style="width:55px; height:55px; object-fit:cover; border-radius: 8px;"
                                 onerror="this.src='https://placehold.co/55?text=No+Img'">
                        @else
                            <div class="mr-3 bg-light border d-flex align-items-center justify-content-center" style="width:55px; height:55px; border-radius: 8px;">
                                <i class="fas fa-image text-muted fa-lg"></i>
                            </div>
                        @endif

                        <div>
                            <h4 class="mb-0 font-weight-bold text-dark">{{ $parent->category_name }}</h4>
                            <span class="badge badge-pill badge-primary-soft text-primary mt-1">
                                {{ $parent->children->count() }} Subcategories
                            </span>
                        </div>
                    </div>

                    <div class="btn-group shadow-sm border rounded bg-white">
                        <a href="{{ route('admin.categories.edit', $parent->id) }}"
                           class="btn btn-light btn-sm text-info px-3" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $parent->id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Delete this parent and all its subcategories?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-light btn-sm text-danger px-3" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- SPACIOUS CHILD TABLE --}}
                <div class="card-body p-0">
                    @if($parent->children->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light text-uppercase small font-weight-bold">
                                    <tr>
                                        <th class="pl-4 py-3" width="100">Icon</th>
                                        <th class="py-3">Subcategory Name</th>
                                        <th class="text-center py-3" width="120">Items Count</th>
                                        <th class="text-right pr-4 py-3" width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($parent->children as $child)
                                        <tr x-show="isChildVisible('{{ addslashes($child->category_name) }}')"
                                            x-transition class="align-middle">
                                            <td class="pl-4 py-3">
                                                @if($child->photo)
                                                    <img src="{{ asset('storage/' . $child->photo) }}"
                                                         class="img-circle border shadow-sm"
                                                         style="width:40px; height:40px; object-fit:cover;">
                                                @else
                                                    <div class="img-circle bg-light border d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                                        <i class="fas fa-folder text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="align-middle py-3">
                                                <span class="h6 mb-0 font-weight-600">{{ $child->category_name }}</span>
                                            </td>
                                            <td class="text-center align-middle py-3">
                                                <span class="badge badge-secondary px-2 py-1">{{ $child->parts_count ?? 0 }}</span>
                                            </td>
                                            <td class="text-right pr-4 align-middle py-3">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.categories.edit', $child->id) }}"
                                                       class="btn btn-sm btn-outline-info rounded-circle mr-2" style="width:32px; height:32px; padding: 4px;" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger rounded-circle" 
                                                            style="width:32px; height:32px; padding: 4px;"
                                                            onclick="if(confirm('Delete subcategory?')) document.getElementById('delete-{{ $child->id }}').submit();"
                                                            title="Delete">
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
                        <div class="p-5 text-center text-muted">
                            <i class="fas fa-info-circle fa-2x mb-3 opacity-50"></i>
                            <p class="mb-0">No subcategories assigned to this group.</p>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5 bg-white shadow-sm border rounded">
                <i class="fas fa-search fa-3x mb-3 text-light"></i>
                <h4 class="text-muted">No categories found matching your search.</h4>
            </div>
        @endforelse
    </div>
</section>
</div>

<style>
    [x-cloak] { display: none !important; }
    
    /* Layout Polish */
    .badge-primary-soft {
        background-color: rgba(0, 123, 255, 0.1);
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .table-hover tbody tr:hover { 
        background-color: rgba(0,123,255,0.03); 
        transition: background-color 0.2s ease;
    }

    .font-weight-600 { font-weight: 600; }

    /* Custom Scrollbar for better UX */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 10px;
    }
</style>

@endsection