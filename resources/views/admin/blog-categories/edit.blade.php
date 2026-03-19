@extends('admin.layouts.app')
@section('title', 'Edit Blog Category')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Category: <span class="text-primary">{{ $category->name }}</span></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.blog-categories.index') }}">Blog Categories</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content" x-data="{ 
    name: '{{ old('category', $category->name) }}',
    imagePreview: null,
    fileName: 'Choose new file to replace...',
    get slug() {
        return this.name
            .toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-')
            .replace(/^-+|-+$/g, '');
    }
}">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">Modify Category Details</h3>
                </div>
                
                <form action="{{ route('admin.blog-categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        {{-- Category Name --}}
                        <div class="form-group">
                            <label for="category">Category Name</label>
                            <input type="text" name="category" id="category" 
                                   x-model="name"
                                   class="form-control @error('category') is-invalid @enderror" 
                                   required>
                            @error('category')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">
                                URL Slug: <code class="text-pink" x-text="slug || '...'">{{ $category->slug }}</code>
                            </small>
                        </div>

                        {{-- Photo Management --}}
                        <div class="form-group">
                            <label>Category Photo</label>
                            
                            {{-- Image Display Logic --}}
                            <div class="mb-3">
                                {{-- Show New Preview if selected, otherwise show Current Image --}}
                                <template x-if="imagePreview">
                                    <div class="animate__animated animate__fadeIn">
                                        <p class="small text-success font-weight-bold">New Image Preview:</p>
                                        <img :src="imagePreview" class="img-thumbnail shadow-sm" style="max-height: 150px;">
                                    </div>
                                </template>

                                <template x-if="!imagePreview">
                                    <div>
                                        <p class="small text-muted mb-2">Current Image:</p>
                                        @if($category->photo)
                                            <img src="{{ asset('storage/' . $category->photo) }}" 
                                                 class="img-thumbnail shadow-sm" style="max-height: 120px;">
                                        @else
                                            <span class="badge badge-secondary">No image currently set</span>
                                        @endif
                                    </div>
                                </template>
                            </div>

                            {{-- File Input --}}
                            <div class="custom-file mb-2">
                                <input type="file" name="photo" id="photo" 
                                       class="custom-file-input @error('photo') is-invalid @enderror" 
                                       accept="image/*"
                                       @change="
                                           const file = $event.target.files[0];
                                           if (file) {
                                               fileName = file.name;
                                               imagePreview = URL.createObjectURL(file);
                                           }
                                       ">
                                <label class="custom-file-label" for="photo" x-text="fileName"></label>
                                @error('photo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-sync-alt mr-1"></i> Update Category
                        </button>
                        <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-default float-right">
                            Discard Changes
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Meta Sidebar --}}
        <div class="col-md-4">
            <div class="card card-secondary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history mr-1"></i> Audit Trail</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tr>
                            <th>Created</th>
                            <td class="text-right text-muted">{{ $category->created_at->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Updated</th>
                            <td class="text-right text-muted">{{ $category->updated_at->diffForHumans() }}</td>
                        </tr>
                        <tr>
                            <th>System ID</th>
                            <td class="text-right"><code>#{{ $category->id }}</code></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection