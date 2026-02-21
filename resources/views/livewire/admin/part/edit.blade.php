<div class="container-fluid py-4" x-data="{ activeTab: 'general' }">
    {{-- Success Message --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form wire:submit.prevent="update" enctype="multipart/form-data">
        <div class="row">
            
            {{-- LEFT COLUMN: Essential Data --}}
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="fas fa-info-circle me-2"></i> General Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            {{-- Part Identity --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Part Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-hashtag text-muted"></i></span>
                                    <input type="text" class="form-control @error('part_number') is-invalid @enderror" wire:model.defer="part_number" placeholder="e.g. 12345-ABC">
                                </div>
                                @error('part_number') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Part Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('part_name') is-invalid @enderror" wire:model.defer="part_name" placeholder="Brake Pad Set">
                                @error('part_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- Categories --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Parent Category</label>
                                <select class="form-select bg-light" wire:model.live="parentCategoryId">
                                    <option value="">-- Select Parent --</option>
                                    @foreach($parentCategories as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Child Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" wire:model.live="category_id">
                                    <option value="">-- Select Child --</option>
                                    @foreach($childCategories as $child)
                                        <option value="{{ $child->id }}">{{ $child->category_name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- Brand & OEM --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Part Brand <span class="text-danger">*</span></label>
                                <select class="form-select @error('part_brand_id') is-invalid @enderror" wire:model.live="part_brand_id">
                                    <option value="">-- Select Brand --</option>
                                    @foreach($partBrands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }} ({{ $brand->type }})</option>
                                    @endforeach
                                </select>
                                @error('part_brand_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">OEM Number</label>
                                <input type="text" class="form-control" wire:model.defer="oem_number" placeholder="Original Equipment Manufacturer No.">
                            </div>

                            {{-- Inventory & Pricing --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Price (RWF)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control fw-bold text-success" wire:model.defer="price">
                                    <span class="input-group-text">RWF</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Stock Quantity</label>
                                <input type="number" class="form-control" wire:model.defer="stock_quantity">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                                <select class="form-select fw-bold {{ $status === 'Active' ? 'text-success' : 'text-danger' }}" wire:model.defer="status">
                                    <option value="Active">● Active</option>
                                    <option value="Inactive">○ Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 fw-bold text-secondary"><i class="fas fa-link me-2"></i>Alternatives & Substitutions</h5>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-2">Select other parts that can substitute this one.</p>
                        <select wire:model.defer="substitution_part_ids" class="form-select" multiple style="height: 150px;">
                            @foreach($allParts as $p)
                                @if($p->id !== $part->id)
                                    <option value="{{ $p->id }}">
                                        {{ $p->partBrand->name ?? 'No Brand' }} - {{ $p->part_name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Media & Compatibility --}}
            <div class="col-lg-5">
                {{-- Compatibility Card --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="fas fa-car-side me-2"></i> Vehicle Compatibility
                        </h5>
                    </div>
                    <div class="card-body">
                        <label class="form-label fw-semibold small uppercase text-muted">Select Compatible Models</label>
                        <select wire:model.defer="fitment_specifications" class="form-select border-primary-subtle" multiple style="height: 200px;">
                            @foreach($vehicleModels as $model)
                                @php $brandName = optional($model->brand)->brand_name; @endphp
                                @if($model->variants->isEmpty())
                                    @foreach($model->specifications as $spec)
                                        <option value="{{ $spec->id }}">
                                            {{ $brandName }} {{ $model->model_name }} ({{ $spec->production_start }}–{{ $spec->production_end }})
                                        </option>
                                    @endforeach
                                @else
                                    @foreach($model->variants as $variant)
                                        @foreach($variant->specifications as $spec)
                                            <option value="{{ $spec->id }}">
                                                {{ $brandName }} {{ $model->model_name }} [{{ $variant->name }}]
                                            </option>
                                        @endforeach
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Photo Management Card --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 fw-bold text-primary"><i class="fas fa-camera me-2"></i> Media Gallery</h5>
                    </div>
                    <div class="card-body">
                        {{-- Existing Photos --}}
                        @if($existingPhotos->count())
                            <div class="row g-2 mb-3">
                                @foreach($existingPhotos as $photo)
                                    <div class="col-4 position-relative group shadow-hover">
                                        <img src="{{ asset('storage/'.$photo->file_path) }}" class="img-thumbnail rounded-3 w-100" style="height:100px; object-fit:cover;">
                                        <button type="button" wire:click="deletePhoto({{ $photo->id }})" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-1 shadow">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Upload Zone --}}
                        <div class="upload-zone border-dashed rounded-3 p-4 text-center bg-light" 
                             x-data="{ isUploading: false, previews: [] }" 
                             wire:ignore>
                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                            <p class="small text-muted mb-2">Add new photos</p>
                            <input type="file" multiple wire:model="photos" class="form-control form-control-sm"
                                   @change="
                                        previews = [];
                                        [...$event.target.files].forEach(file => {
                                            let reader = new FileReader();
                                            reader.onload = e => previews.push(e.target.result);
                                            reader.readAsDataURL(file);
                                        });
                                   ">
                            <div class="d-flex flex-wrap mt-3 gap-2">
                                <template x-for="img in previews" :key="img">
                                    <img :src="img" class="rounded border" style="width:60px;height:60px;object-fit:cover;">
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Internal Notes / Description --}}
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <label class="form-label fw-semibold">Public Description</label>
                        <textarea class="form-control" rows="4" wire:model.defer="description" placeholder="Technical specifications, warranty details..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- FLOATING SUBMIT BAR --}}
        <div class="sticky-bottom bg-white border-top py-3 mt-5 mx-n4 px-4 shadow-lg" style="z-index: 1020;">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ url()->previous() }}" class="btn btn-link text-decoration-none text-muted">
                    <i class="fas fa-arrow-left me-1"></i> Discard Changes
                </a>
                <button class="btn btn-primary px-5 py-2 shadow-sm fw-bold" type="submit" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="update">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </span>
                    <span wire:loading wire:target="update">
                        <span class="spinner-border spinner-border-sm me-2"></span> Saving...
                    </span>
                </button>
            </div>
        </div>
    </form>

   <style>
    .border-dashed { border: 2px dashed #dee2e6; }
    .form-control:focus, .form-select:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1); }
    .card-title { font-size: 1rem; letter-spacing: 0.02rem; }
    .group:hover .btn-danger { display: block !important; }
    .sticky-bottom { margin-bottom: -1.5rem; } /* Ties to the footer better */
    .shadow-hover:hover { transform: translateY(-2px); transition: 0.3s; }
</style>

</div>