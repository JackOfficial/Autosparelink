<div class="container-fluid">
    <style>
        .search-results-overlay { z-index: 1100; max-height: 300px; overflow-y: auto; background: white; border: 1px solid #ddd; width: 100%; top: 100%; }
        .selected-badge { font-size: 0.8rem; padding: 0.5rem; margin: 2px; }
        .cursor-pointer { cursor: pointer; }
    </style>

    <form wire:submit.prevent="save">
        <div class="row">
            {{-- Left Column: Primary Data --}}
            <div class="col-md-7">
                <div class="card border shadow-none">
                    <div class="card-header bg-light"><h3 class="card-title text-sm font-weight-bold">Basic Information</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Part Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="part_name">
                                @error('part_name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Part Number</label>
                                <input type="text" class="form-control" wire:model="part_number">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Parent Category</label>
                                <select class="form-control" wire:model.live="parentCategoryId">
                                    <option value="">-- Select Parent --</option>
                                    @foreach($parentCategories as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Child Category <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="category_id">
                                    <option value="">-- Select Child --</option>
                                    @foreach($childCategories as $child)
                                        <option value="{{ $child->id }}">{{ $child->category_name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Part Brand <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="part_brand_id">
                                    <option value="">-- Select Brand --</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('part_brand_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border shadow-none">
                    <div class="card-header bg-light"><h3 class="card-title text-sm font-weight-bold">Inventory & Substitutions</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Price (RWF)</label>
                                <input type="number" class="form-control" wire:model="price">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Stock Quantity</label>
                                <input type="number" class="form-control" wire:model="stock_quantity">
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Alternative Parts (Substitutions)</label>
                                <select wire:model="substitution_part_ids" class="form-control" multiple style="height: 120px;">
                                    @foreach($allParts as $p)
                                        <option value="{{ $p->id }}">{{ $p->part_name }} ({{ $p->partBrand->name ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Compatibility & Photos --}}
            <div class="col-md-5">
                <div class="card border-primary shadow-none">
                    <div class="card-header bg-primary py-2"><h3 class="card-title text-sm text-white">Vehicle Compatibility</h3></div>
                    <div class="card-body p-3">
                        <div class="form-group position-relative">
                            <label class="text-xs">Search Brand or Model</label>
                            <input type="text" class="form-control form-control-sm" placeholder="e.g. Toyota Hilux..." 
                                   wire:model.live.debounce.300ms="searchVehicle">
                            
                            @if(count($searchResults) > 0)
                                <div class="list-group position-absolute shadow-lg search-results-overlay">
                                    @foreach($searchResults as $spec)
                                        <button type="button" class="list-group-item list-group-item-action py-2 text-xs" 
                                                wire:click="toggleVehicle({{ $spec->id }})">
                                            <i class="fas {{ in_array($spec->id, $selectedSpecs) ? 'fa-check-circle text-success' : 'fa-plus text-muted' }} mr-2"></i>
                                            <strong>{{ $spec->vehicleModel->brand->brand_name }} {{ $spec->vehicleModel->model_name }}</strong>
                                            <span class="text-muted ml-1">({{ $spec->variant->name ?? 'Std' }} {{ $spec->production_start }})</span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="mt-3 bg-light border rounded p-2" style="min-height: 100px; max-height: 200px; overflow-y: auto;">
                            <label class="text-xs font-weight-bold">Selected Vehicles ({{ count($selectedSpecs) }})</label>
                            <div class="d-flex flex-wrap">
                                @forelse($displaySpecs as $s)
                                    <span class="badge badge-info selected-badge d-flex align-items-center">
                                        {{ $s->vehicleModel->brand->brand_name }} {{ $s->vehicleModel->model_name }}
                                        <i class="fas fa-times ml-2 cursor-pointer" wire:click="toggleVehicle({{ $s->id }})"></i>
                                    </span>
                                @empty
                                    <p class="text-muted text-xs m-0 italic">No vehicles selected.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Photo Upload --}}
                <div class="card border shadow-none mt-3">
                    <div class="card-body" x-data="{ previews: [] }">
                        <label class="font-weight-bold">Photos</label>
                        <div class="custom-file">
                            <input type="file" multiple wire:model="photos" class="custom-file-input" id="partPhotos"
                                   @change="previews = []; [...$event.target.files].forEach(file => { let reader = new FileReader(); reader.onload = e => previews.push(e.target.result); reader.readAsDataURL(file); })">
                            <label class="custom-file-label" for="partPhotos">Choose images</label>
                        </div>
                        <div class="d-flex flex-wrap mt-2">
                            <template x-for="img in previews" :key="img">
                                <img :src="img" class="img-thumbnail mr-1 mb-1" style="width:70px; height:70px; object-fit:cover;">
                            </template>
                        </div>
                        @error('photos.*') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="hr mt-4 mb-4"></div>
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.spare-parts.index') }}" class="btn btn-default">Cancel</a>
            <button type="submit" class="btn btn-primary px-5 shadow" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Save Spare Part</span>
                <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin mr-2"></i>Saving...</span>
            </button>
        </div>
    </form>
</div>