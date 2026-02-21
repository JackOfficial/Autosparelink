<div>
    <style>
        .cursor-pointer { cursor: pointer; }
        .search-results-dropdown {
            z-index: 1050; 
            max-height: 250px; 
            overflow-y: auto; 
            top: 100%;
        }
        .selected-tag { font-size: 0.75rem; padding: 0.4rem 0.6rem; }
    </style>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible shadow-sm">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-md-7">
                <div class="card border shadow-none">
                    <div class="card-header bg-light">
                        <h3 class="card-title text-sm font-weight-bold">Basic Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Part Number</label>
                                <input type="text" class="form-control" wire:model="part_number">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Part Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="part_name">
                                @error('part_name') <small class="text-danger">{{ $message }}</small> @enderror
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
                                <select class="form-control @error('category_id') is-invalid @enderror" wire:model.live="category_id">
                                    <option value="">-- Select Child --</option>
                                    @foreach($childCategories as $child)
                                        <option value="{{ $child->id }}">{{ $child->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group mt-2">
                                <label>Part Brand <span class="text-danger">*</span></label>
                                <select class="form-control @error('part_brand_id') is-invalid @enderror" wire:model="part_brand_id">
                                    <option value="">-- Select Brand --</option>
                                    @foreach($partBrands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border shadow-none">
                    <div class="card-header bg-light">
                        <h3 class="card-title text-sm font-weight-bold">Inventory & Substitutions</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Price (RWF)</label>
                                <input type="number" class="form-control" wire:model="price">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Stock</label>
                                <input type="number" class="form-control" wire:model="stock_quantity">
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Alternative Parts (Substitutions)</label>
                                <select wire:model="substitution_part_ids" class="form-control" multiple style="height: 100px;">
                                    @foreach($allParts as $p)
                                        <option value="{{ $p->id }}">{{ $p->part_name }} ({{ $p->partBrand->name ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card border-primary shadow-none" x-data="{ searching: false }">
                    <div class="card-header bg-primary py-2">
                        <h3 class="card-title text-sm">Vehicle Compatibility</h3>
                    </div>
                    
                    <div class="card-body p-3">
                        <div class="form-group mb-3 position-relative">
                            <label class="text-xs">Search Vehicle (Brand or Model)</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" placeholder="Search e.g. Toyota Hilux..." 
                                       wire:model.live.debounce.300ms="searchVehicle" @focus="searching = true">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                            </div>

                            @if(count($searchResults) > 0)
                                <div class="list-group shadow-lg position-absolute w-100 search-results-dropdown">
                                    @foreach($searchResults as $model)
                                        @foreach($model->specifications as $spec)
                                            <button type="button" class="list-group-item list-group-item-action py-2 text-xs" 
                                                    wire:click="toggleFitment({{ $spec->id }})">
                                                <i class="fas {{ in_array($spec->id, $fitment_specifications) ? 'fa-check-circle text-success' : 'fa-plus-circle text-muted' }} mr-2"></i>
                                                <strong>{{ $model->brand->brand_name }} {{ $model->model_name }}</strong> 
                                                <span class="text-muted ml-1">({{ $spec->variant->name ?? 'Standard' }} {{ $spec->production_start }})</span>
                                            </button>
                                        @endforeach
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="mt-3">
                            <label class="text-xs font-weight-bold">Selected Vehicles ({{ count($fitment_specifications) }})</label>
                            <div class="d-flex flex-wrap bg-light p-2 border rounded" style="gap: 5px; min-height: 80px; max-height: 250px; overflow-y: auto;">
                                @forelse($selectedSpecs as $s)
                                    <span class="badge badge-info selected-tag d-flex align-items-center">
                                        {{ $s->vehicleModel->brand->brand_name }} {{ $s->vehicleModel->model_name }}
                                        <i class="fas fa-times ml-2 cursor-pointer" wire:click="toggleFitment({{ $s->id }})"></i>
                                    </span>
                                @empty
                                    <p class="text-muted text-xs italic m-0">No vehicles selected. Search above to add.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    
                    @if(count($fitment_specifications) > 0)
                        <div class="card-footer py-2 bg-white">
                            <button type="button" class="btn btn-xs btn-link text-danger float-right" wire:click="$set('fitment_specifications', [])">Clear All</button>
                        </div>
                    @endif
                </div>

                <div class="card border shadow-none mt-3">
                    <div class="card-body" x-data="{ previews: [] }">
                        <label class="font-weight-bold">Photos</label>
                        <div class="custom-file">
                            <input type="file" multiple wire:model="photos" class="custom-file-input" id="customFile"
                                   @change="previews = []; [...$event.target.files].forEach(file => { let reader = new FileReader(); reader.onload = e => previews.push(e.target.result); reader.readAsDataURL(file); })">
                            <label class="custom-file-label" for="customFile">Select images</label>
                        </div>
                        <div class="d-flex flex-wrap mt-2">
                            <template x-for="img in previews" :key="img">
                                <img :src="img" class="img-thumbnail mr-1 mb-1" style="width:60px; height:60px; object-fit:cover;">
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.spare-parts.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Cancel</a>
            <button type="submit" class="btn btn-primary px-4 shadow" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save"><i class="fas fa-save mr-2"></i> Save Spare Part</span>
                <span wire:loading wire:target="save"><i class="fas fa-sync fa-spin mr-2"></i> Saving...</span>
            </button>
        </div>
    </form>
</div>