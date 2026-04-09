<div>
    <style>
        /* Professional Search Styles */
        .search-container { position: relative; }
        .search-results-overlay { 
            z-index: 1100; 
            max-height: 280px; 
            overflow-y: auto; 
            background: white; 
            border: 1px solid #e9ecef; 
            width: 100%; 
            top: 100%; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border-radius: 0 0 8px 8px;
        }
        
        /* Modern Badge & Utility Styles */
        .selected-badge { 
            font-size: 0.7rem; 
            padding: 0.4rem 0.7rem; 
            margin: 2px; 
            border-radius: 6px; 
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }
        .cursor-pointer { cursor: pointer; }
        .text-hover-danger:hover { color: #dc3545 !important; }
        
        /* Photo Preview transition */
        .preview-img {
            width: 70px; height: 70px; 
            object-fit: cover; 
            border-radius: 8px;
            transition: transform 0.2s;
        }
        .preview-img:hover { transform: scale(1.1); }
        
        .delete-photo-btn {
            width: 22px; height: 22px;
            line-height: 1;
            font-size: 12px;
            border-radius: 50%;
        }

        [x-cloak] { display: none !important; }
    </style>

    <div class="mb-4">
        <h4 class="fw-bold">Edit Spare Part: <span class="text-primary">{{ $part_name }}</span></h4>
        <p class="text-muted small">Update the specifications, compatibility, and inventory for this item.</p>
    </div>

    <form wire:submit.prevent="save">
        <div class="row g-4">
            {{-- Left Column: Primary Data --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="card-title fw-bold mb-0 text-dark">
                            <i class="fas fa-edit me-2 text-primary"></i>Basic Information
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold">Part Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('part_name') is-invalid @enderror" wire:model="part_name">
                                @error('part_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Part Number (Internal)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-hashtag text-muted"></i></span>
                                    <input type="text" class="form-control @error('part_number') is-invalid @enderror border-start-0" wire:model="part_number">
                                    @error('part_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">OEM Number</label>
                                <input type="text" class="form-control" wire:model="oem_number">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Parent Category</label>
                                <select class="form-select" wire:model.live="parentCategoryId">
                                    <option value="">-- Select Parent --</option>
                                    @foreach($parentCategories as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Child Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" wire:model="category_id">
                                    <option value="">-- Select Child --</option>
                                    @foreach($childCategories as $child)
                                        <option value="{{ $child->id }}">{{ $child->category_name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Part Brand <span class="text-danger">*</span></label>
                                <select class="form-select @error('part_brand_id') is-invalid @enderror" wire:model="part_brand_id">
                                    <option value="">-- Select Brand --</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('part_brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Price (RWF)</label>
                                <input type="number" class="form-control" wire:model="price">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Stock</label>
                                <input type="number" class="form-control" wire:model="stock_quantity">
                            </div>

                            <div class="col-12 mb-0">
                                <label class="form-label small fw-bold">Description</label>
                                <textarea class="form-control" wire:model="description" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="col-lg-5">
                
                {{-- Compatibility --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-primary py-3">
                        <h6 class="card-title fw-bold mb-0 text-white"><i class="fas fa-car me-2"></i>Vehicle Compatibility</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="search-container" x-data="{ showVehicles: true }" @click.away="showVehicles = false">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted small"></i></span>
                                <input type="text" class="form-control form-control-sm border-start-0" placeholder="Add compatibility..." 
                                       wire:model.live.debounce.300ms="searchVehicle" @focus="showVehicles = true">
                            </div>
                            
                            @if(count($searchResults) > 0)
                                <div class="list-group position-absolute search-results-overlay w-100" x-show="showVehicles" x-cloak>
                                    @foreach($searchResults as $spec)
                                        <button type="button" class="list-group-item list-group-item-action py-2 small" 
                                                wire:click="toggleVehicle({{ $spec->id }})">
                                            <i class="fas {{ in_array($spec->id, $selectedSpecs) ? 'fa-check-circle text-success' : 'fa-plus text-muted' }} me-2"></i>
                                            <span class="fw-bold">{{ $spec->vehicleModel->brand->brand_name }}</span> {{ $spec->vehicleModel->model_name }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="mt-3 bg-light rounded p-2 border border-dashed" style="min-height: 80px; max-height: 180px; overflow-y: auto;">
                            <div class="d-flex flex-wrap">
                                @forelse($displaySpecs as $s)
                                    <span class="badge bg-white text-dark border selected-badge">
                                        {{ $s->vehicleModel->brand->brand_name }} {{ $s->vehicleModel->model_name }}
                                        <i class="fas fa-times ms-2 cursor-pointer text-muted text-hover-danger" wire:click="toggleVehicle({{ $s->id }})"></i>
                                    </span>
                                @empty
                                    <div class="text-center w-100 py-3"><small class="text-muted">No compatibility linked</small></div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Substitutions --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="card-title fw-bold mb-0 text-dark"><i class="fas fa-exchange-alt me-2 text-warning"></i>Alternative Parts</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="search-container" x-data="{ showParts: true }" @click.away="showParts = false">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-plus text-muted small"></i></span>
                                <input type="text" class="form-control form-control-sm border-start-0" placeholder="Search alternatives..." 
                                       wire:model.live.debounce.300ms="searchPart" @focus="showParts = true">
                            </div>

                            @if(count($partResults) > 0)
                                <div class="list-group position-absolute search-results-overlay w-100" x-show="showParts" x-cloak>
                                    @foreach($partResults as $p)
                                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2"
                                                wire:click="toggleSubstitution({{ $p->id }})">
                                            <div>
                                                <div class="fw-bold small">{{ $p->part_name }}</div>
                                                <small class="text-muted">{{ $p->part_number }}</small>
                                            </div>
                                            <i class="fas {{ in_array($p->id, $substitution_part_ids) ? 'fa-check-circle text-success' : 'fa-plus-circle text-primary' }}"></i>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="mt-3 bg-light rounded p-2 border border-dashed" style="min-height: 60px;">
                            <div class="d-flex flex-wrap">
                                @forelse($selectedSubstitutions as $sub)
                                    <span class="badge bg-secondary selected-badge">
                                        {{ $sub->part_name }}
                                        <i class="fas fa-times-circle ms-2 cursor-pointer text-hover-danger" wire:click="toggleSubstitution({{ $sub->id }})"></i>
                                    </span>
                                @empty
                                    <div class="text-center w-100 py-2"><small class="text-muted">No alternatives linked</small></div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Photos Management --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4" x-data="{ previews: [] }">
                        <label class="form-label small fw-bold mb-2">Part Gallery</label>
                        
                        {{-- Existing Photos --}}
                        @if($existingPhotos && count($existingPhotos) > 0)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-2">Currently Uploaded:</small>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($existingPhotos as $photo)
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $photo->file_path) }}" class="preview-img border shadow-sm">
                                            <button type="button" wire:click="deletePhoto({{ $photo->id }})" 
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 delete-photo-btn shadow-sm"
                                                    title="Delete Image">&times;</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <small class="text-muted d-block mb-2">Upload New Photos:</small>
                        <input type="file" multiple wire:model="photos" class="form-control form-control-sm"
                               @change="previews = []; [...$event.target.files].forEach(file => { let reader = new FileReader(); reader.onload = e => previews.push(e.target.result); reader.readAsDataURL(file); })">
                        
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <template x-for="img in previews" :key="img">
                                <img :src="img" class="preview-img border shadow-sm" style="opacity: 0.8; border-style: dashed !important;">
                            </template>
                        </div>
                        @error('photos.*') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                    </div>
                </div>

            </div>
        </div>

        <hr class="my-5 opacity-10">
        
        <div class="d-flex justify-content-between align-items-center mb-5 pb-5">
            <a href="{{ route('admin.spare-parts.index') }}" class="btn btn-link text-muted text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i>Cancel Changes
            </a>
            <button type="submit" class="btn btn-success px-5 py-2 shadow fw-bold rounded-pill" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save"><i class="fas fa-save me-2"></i>Update Part Details</span>
                <span wire:loading wire:target="save"><i class="fas fa-circle-notch fa-spin me-2"></i>Updating...</span>
            </button>
        </div>
    </form>
</div>