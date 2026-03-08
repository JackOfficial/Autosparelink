<div class="container-fluid">
    <style>
        /* Standardized Search Styles */
        .search-container { position: relative; }
        .search-results-overlay { 
            z-index: 1100; 
            max-height: 280px; 
            overflow-y: auto; 
            background: white; 
            border: 1px solid #dee2e6; 
            width: 100%; 
            top: 100%; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 0 0 4px 4px;
        }
        .search-input-group .input-group-text {
            border-right: none;
            background-color: #fff;
        }
        .search-input-group .form-control {
            border-left: none;
        }
        
        /* Badge & Utility Styles */
        .selected-badge { font-size: 0.75rem; padding: 0.4rem 0.6rem; margin: 2px; border-radius: 4px; }
        .cursor-pointer { cursor: pointer; }
        .text-hover-danger:hover { color: #dc3545 !important; }
        .form-control:focus { border-color: #80bdff; box-shadow: 0 0 0 0.1rem rgba(0,123,255,.15); }
        .card-title { letter-spacing: 0.5px; }

        /* Photo Management */
        .existing-photo-wrapper { position: relative; display: inline-block; }
        .delete-photo-btn { 
            position: absolute; top: -5px; right: -2px; 
            background: #dc3545; color: white; border-radius: 50%; 
            width: 18px; height: 18px; line-height: 18px; 
            text-align: center; font-size: 10px; cursor: pointer; border: 1px solid white;
        }
    </style>

    <form wire:submit.prevent="save">
        <div class="row">
            {{-- Left Column: Primary Data --}}
            <div class="col-md-7">
                <div class="card border shadow-none mb-4">
                    <div class="card-header bg-light py-2">
                        <h3 class="card-title text-sm font-weight-bold mb-0">
                            <i class="fas fa-edit mr-1"></i> Edit Basic Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label class="text-xs font-weight-bold">Part Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="part_name" placeholder="e.g. Front Brake Pad Set">
                                @error('part_name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="font-weight-bold">Part Number (SKU)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">#</span></div>
                                    <input type="text" class="form-control @error('part_number') is-invalid @enderror" placeholder="Internal Code" wire:model="part_number">
                                </div>
                                @error('part_number') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="text-xs font-weight-bold">OEM Number</label>
                                <input type="text" class="form-control" wire:model="oem_number" placeholder="Manufacturer Part No.">
                                @error('oem_number') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="text-xs font-weight-bold">Parent Category</label>
                                <select class="form-control" wire:model.live="parentCategoryId">
                                    <option value="">-- Select Parent --</option>
                                    @foreach($parentCategories as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="text-xs font-weight-bold">Child Category <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="category_id">
                                    <option value="">-- Select Child --</option>
                                    @foreach($childCategories as $child)
                                        <option value="{{ $child->id }}">{{ $child->category_name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="text-xs font-weight-bold">Part Brand <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="part_brand_id">
                                    <option value="">-- Select Brand --</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('part_brand_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-3 form-group">
                                <label class="text-xs font-weight-bold">Price (RWF)</label>
                                <input type="number" class="form-control" wire:model="price">
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="text-xs font-weight-bold">Stock</label>
                                <input type="number" class="form-control" wire:model="stock_quantity">
                            </div>

                            <div class="col-md-12 form-group mb-0">
                                <label class="text-xs font-weight-bold">Description / Technical Notes</label>
                                <textarea class="form-control" wire:model="description" rows="3" 
                                    placeholder="Add specifications, material info..."></textarea>
                                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="col-md-5">
                
                {{-- Vehicle Compatibility Card --}}
                <div class="card border-primary shadow-none mb-3">
                    <div class="card-header bg-primary py-2">
                        <h3 class="card-title text-sm text-white mb-0"><i class="fas fa-car mr-1"></i> Vehicle Compatibility</h3>
                    </div>
                    <div class="card-body p-3">
                        <div class="search-container" x-data="{ showVehicles: true }" @click.away="showVehicles = false">
                            <div class="input-group input-group-sm search-input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search text-muted"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="Search Brand or Model..." 
                                       wire:model.live.debounce.300ms="searchVehicle" @focus="showVehicles = true">
                            </div>
                            
                            @if(count($searchResults) > 0)
                                <div class="list-group position-absolute search-results-overlay" x-show="showVehicles">
                                    @foreach($searchResults as $spec)
                                        <button type="button" class="list-group-item list-group-item-action py-2 text-xs" 
                                                wire:click="toggleVehicle({{ $spec->id }})">
                                            <i class="fas {{ in_array($spec->id, $selectedSpecs) ? 'fa-check-circle text-success' : 'fa-plus text-muted' }} mr-2"></i>
                                            <strong>{{ $spec->vehicleModel->brand->brand_name }} {{ $spec->vehicleModel->model_name }}</strong>
                                            <span class="text-muted ml-1">({{ $spec->variant->name ?? 'Std' }})</span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="mt-2 bg-light border rounded p-2" style="min-height: 80px; max-height: 160px; overflow-y: auto;">
                            <div class="d-flex flex-wrap">
                                @forelse($displaySpecs as $s)
                                    <span class="badge badge-info selected-badge d-flex align-items-center">
                                        {{ $s->vehicleModel->brand->brand_name }} {{ $s->vehicleModel->model_name }}
                                        <i class="fas fa-times ml-2 cursor-pointer opacity-70 text-hover-danger" wire:click="toggleVehicle({{ $s->id }})"></i>
                                    </span>
                                @empty
                                    <p class="text-muted text-xs m-auto italic">No vehicles selected.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Substitutions Card --}}
                <div class="card border shadow-none mb-3">
                    <div class="card-header bg-light py-2">
                        <h3 class="card-title text-sm font-weight-bold mb-0"><i class="fas fa-exchange-alt mr-1"></i> Alternative Parts</h3>
                    </div>
                    <div class="card-body p-3">
                        <div class="search-container" x-data="{ showParts: true }" @click.away="showParts = false">
                            <div class="input-group input-group-sm search-input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search text-muted"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="Search by name or part number..." 
                                       wire:model.live.debounce.300ms="searchPart" @focus="showParts = true">
                            </div>

                            @if(count($partResults) > 0)
                                <div class="list-group position-absolute search-results-overlay" x-show="showParts">
                                    @foreach($partResults as $p)
                                        <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2"
                                                wire:click="toggleSubstitution({{ $p->id }})">
                                            <div>
                                                <span class="font-weight-bold text-xs">{{ $p->part_name }}</span>
                                                <small class="text-muted d-block text-xs">{{ $p->partBrand->name ?? 'No Brand' }} | {{ $p->part_number }}</small>
                                            </div>
                                            <i class="fas {{ in_array($p->id, $substitution_part_ids) ? 'fa-check-circle text-success' : 'fa-plus-circle text-primary' }}"></i>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="mt-2 bg-light border rounded p-2" style="min-height: 60px; max-height: 120px; overflow-y: auto;">
                            <div class="d-flex flex-wrap">
                                @forelse($selectedSubstitutions as $sub)
                                    <span class="badge badge-secondary selected-badge d-flex align-items-center">
                                        {{ $sub->part_name }}
                                        <i class="fas fa-times-circle ml-2 cursor-pointer text-hover-danger" wire:click="toggleSubstitution({{ $sub->id }})"></i>
                                    </span>
                                @empty
                                    <p class="text-muted text-xs m-auto italic">No substitutions selected.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Photos Card --}}
                <div class="card border shadow-none mb-0">
                    <div class="card-body p-3">
                        <label class="text-xs font-weight-bold mb-2">Part Images</label>
                        
                        {{-- Section: Already Uploaded Photos --}}
                        @if(count($existingPhotos) > 0)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Current Photos (Click x to delete):</small>
                                <div class="d-flex flex-wrap">
                                    @foreach($existingPhotos as $photo)
                                        <div class="existing-photo-wrapper mr-2 mb-2">
                                            <img src="{{ Storage::url($photo->file_path) }}" class="rounded border shadow-sm" style="width:55px; height:55px; object-fit:cover;">
                                            <span class="delete-photo-btn" wire:click="deletePhoto({{ $photo->id }})" wire:confirm="Are you sure you want to delete this photo?">
                                                <i class="fas fa-times"></i>
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Section: New Uploads --}}
                        <div x-data="{ previews: [] }">
                            <small class="text-muted d-block mb-1">Upload New Photos:</small>
                            <div class="custom-file custom-file-sm">
                                <input type="file" multiple wire:model="photos" class="custom-file-input" id="partPhotos"
                                       @change="previews = []; [...$event.target.files].forEach(file => { let reader = new FileReader(); reader.onload = e => previews.push(e.target.result); reader.readAsDataURL(file); })">
                                <label class="custom-file-label" for="partPhotos">Choose new files</label>
                            </div>
                            <div class="d-flex flex-wrap mt-2">
                                <template x-for="img in previews" :key="img">
                                    <img :src="img" class="rounded border mr-1 mb-1 shadow-sm" style="width:55px; height:55px; object-fit:cover;">
                                </template>
                            </div>
                        </div>
                        @error('photos.*') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>
                </div>

            </div>
        </div>

        <div class="hr mt-4 mb-4" style="border-top: 1px dashed #ddd;"></div>
        
        <div class="d-flex justify-content-between align-items-center mb-5">
            <a href="{{ route('admin.spare-parts.index') }}" class="btn btn-link text-muted"><i class="fas fa-arrow-left mr-1"></i> Back to List</a>
            <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm font-weight-bold" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Update Spare Part</span>
                <span wire:loading wire:target="save"><i class="fas fa-circle-notch fa-spin mr-2"></i>Saving Changes...</span>
            </button>
        </div>
    </form>
</div>