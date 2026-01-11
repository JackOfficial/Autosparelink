@extends('admin.layouts.app')
@section('title', 'Categories')

@section('content')

{{-- ================= HEADER ================= --}}
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    Categories
                    <small class="text-muted">({{ $categories->count() }} parent categories)</small>
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
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- ACTION BAR --}}
    <div class="mb-3 text-right">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i> Add Category
        </a>
    </div>

    {{-- CATEGORY GROUPS --}}
    @forelse($categories as $parent)
        <div class="card card-outline card-primary mb-4">

            {{-- PARENT HEADER --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    {{-- Parent Photo --}}
                    @if($parent->photo)
                        <img src="{{ asset('storage/' . $parent->photo) }}"
                             class="img-thumbnail mr-3"
                             style="width:60px; height:60px; object-fit:cover;">
                    @endif

                    <div>
                        <h5 class="mb-0">{{ $parent->category_name }}</h5>
                        <small class="text-muted">
                            {{ $parent->children->count() }}
                            {{ $parent->children->count() === 1 ? 'Subcategory' : 'Subcategories' }}
                        </small>
                    </div>
                </div>

                {{-- Parent Actions --}}
                <div>
                    <a href="{{ route('admin.categories.edit', $parent->id) }}"
                       class="btn btn-info btn-sm mr-1">
                        <i class="fas fa-edit"></i>
                    </a>

                    <form action="{{ route('admin.categories.destroy', $parent->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Delete this category and its subcategories?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- CHILD CATEGORIES --}}
            <div class="card-body p-0">
                @if($parent->children->isNotEmpty())
                    <table class="table table-striped mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th width="80">Photo</th>
                                <th>Subcategory Name</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parent->children as $child)
                                <tr>
                                    <td>
                                        @if($child->photo)
                                            <img src="{{ asset('storage/' . $child->photo) }}"
                                                 class="img-thumbnail"
                                                 style="width:50px; height:50px; object-fit:cover;">
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    <td>{{ $child->category_name }}</td>

                                    <td>
                                        <a href="{{ route('admin.categories.edit', $child->id) }}"
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.categories.destroy', $child->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Delete this subcategory?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-3 text-muted text-center">
                        No subcategories under this category.
                    </div>
                @endif
            </div>

        </div>
    @empty
        <div class="alert alert-info text-center">
            No categories found.
        </div>
    @endforelse

</section>
@endsection
