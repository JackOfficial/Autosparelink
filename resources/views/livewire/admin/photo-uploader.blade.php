<div>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="fw-bold text-primary mb-3">📸 Manage Project Photos</h5>

            @if (session()->has('success'))
                <div class="alert alert-success py-2">{{ session('success') }}</div>
            @endif

            <!-- Upload Input -->
            <input type="file" wire:model="photos" multiple class="form-control mb-3" accept="image/*">
            @error('photos.*') <span class="text-danger">{{ $message }}</span> @enderror

            <!-- Preview newly selected files -->
            @if ($photos)
                <div class="d-flex flex-wrap gap-3 mb-3">
                    @foreach ($photos as $photo)
                        <div class="position-relative">
                            <img src="{{ $photo->temporaryUrl() }}" class="img-thumbnail" width="120">
                            <span class="badge bg-secondary position-absolute top-0 start-100 translate-middle rounded-pill">New</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <button wire:click="updatedPhotos" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>Upload</span>
                <span wire:loading>Uploading...</span>
            </button>
        </div>
    </div>

    <!-- Existing Photos -->
    @if ($existingPhotos->count())
        <div class="row g-3">
            @foreach ($existingPhotos as $photo)
                <div class="col-md-3 col-6 position-relative">
                    <div class="card border-0 shadow-sm">
                        <img src="{{ asset('storage/' . $photo->file_path) }}" class="card-img-top rounded-3" style="height:160px; object-fit:cover;">
                        <div class="card-body text-center p-2">
                            <button wire:click="deletePhoto({{ $photo->id }})" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">No photos uploaded yet.</p>
    @endif
</div>
