<div class="mx-3">
  <!-- Filter bar -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-3">
      <select wire:model="modelFilter" class="form-select form-select-sm">
        <option value="all">All</option>
        <option value="App\Models\Project">Project</option>
        <option value="App\Models\Team">Team</option>
        <option value="App\Models\Event">Event</option>
      </select>

      <span class="text-muted small">
        Total Photos: {{ $totalPhotos }}
      </span>
    </div>

    <div class="d-flex gap-2">
      @if(count($selectedPhotos) > 0)
      <button wire:click="deleteSelected" class="btn btn-sm btn-danger">
        Delete Selected ({{ count($selectedPhotos) }})
      </button>
      @endif

      <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#photoModal">
        <i class="fas fa-plus"></i> Add Photo
      </button>
    </div>
  </div>

  <!-- Success message -->
  @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong><i class="fas fa-check-circle"></i></strong> {{ session('message') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Gallery cards -->
  @if(empty($photoGroups) || count($photoGroups) === 0)
    <div class="text-center text-muted py-5">No photos yet.</div>
  @else
    @foreach($photoGroups as $modelName => $group)
      <h5 class="mt-4">{{ $modelName }} ({{ $group->count() }})</h5>
      <div class="row g-3">
        @foreach($group as $photo)
          <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card photo-card position-relative">
              <input type="checkbox" wire:model="selectedPhotos" value="{{ $photo->id }}" class="position-absolute m-2" style="z-index:10; top:0; left:0;">

              <img src="{{ asset('storage/' . $photo->file_path) }}" class="card-img-top" style="height:160px; object-fit:cover;">

              <div class="card-body p-2 text-center">
                <p class="mb-1 small text-muted">{{ $photo->caption ?? 'No caption' }}</p>
                <small class="text-secondary">{{ $photo->created_at->diffForHumans() }}</small>
              </div>

              <div class="overlay d-flex justify-content-center align-items-center">
                <button 
    wire:click="edit({{ $photo->id }})"
    class="btn btn-sm btn-info me-1"
    data-bs-toggle="modal" 
    data-bs-target="#photoModal">
    Edit
</button>
                <button wire:click="delete({{ $photo->id }})" class="btn btn-sm btn-danger">Delete</button>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endforeach
  @endif

  <!-- Upload/Edit Modal -->
  <div wire:ignore.self class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form wire:submit.prevent="save">
          <div class="modal-header">
            <h5 class="modal-title">{{ $editingPhotoId ? 'Edit Photo' : 'Upload Photo' }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="file" wire:model="file" class="form-control mb-2">
            <input type="text" wire:model="caption" class="form-control" placeholder="Caption (optional)">
            @error('file') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">{{ $editingPhotoId ? 'Update' : 'Upload' }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <style>
    .photo-card {
        border-radius:8px;
        overflow:hidden;
        box-shadow:0 6px 18px rgba(0,0,0,0.05);
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
    }
    .photo-card:hover { transform: translateY(-5px); box-shadow:0 10px 25px rgba(0,0,0,0.15); }
    .photo-card img { display:block; }
    .photo-card .overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.5);
        opacity: 0;
        transition: opacity 0.3s;
        display:flex;
        justify-content:center;
        align-items:center;
        gap:5px;
    }
    .photo-card:hover .overlay { opacity:1; }
  </style>

  <script>
   $wire.on('show-photo-modal', () => {
        var modal = new bootstrap.Modal(document.getElementById('photoModal'));
        modal.show();
    });
  </script>
</div>
