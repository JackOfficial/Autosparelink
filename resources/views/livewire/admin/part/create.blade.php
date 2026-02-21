<div> {{-- THE ONLY ROOT ELEMENT --}}
    
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible shadow-sm">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="row">
            {{-- General Info Section --}}
            <div class="col-md-7">
                <div class="card border shadow-none">
                    <div class="card-header bg-light">
                        <h3 class="card-title text-sm font-weight-bold">Basic Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Part Number</label>
                                <input type="text" class="form-control" wire:model.defer="part_number">
                                @error('part_number') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Part Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model.defer="part_name">
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
                                <input type="number" class="form-control" wire:model.defer="price">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Stock</label>
                                <input type="number" class="form-control" wire:model.defer="stock_quantity">
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Alternative Parts (Substitutions)</label>
                                <select wire:model.defer="substitution_part_ids" class="form-control" multiple style="height: 100px;">
                                    @foreach($allParts as $p)
                                        <option value="{{ $p->id }}">{{ $p->part_name }} ({{ $p->partBrand->name ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Compatibility & Media Section --}}
            <div class="col-md-5">
            <div class="card border-primary shadow-none">
    <div class="card-header bg-primary py-2">
        <h3 class="card-title text-sm">Vehicle Compatibility</h3>
    </div>
    <div class="card-body p-0" style="height: 400px; overflow-y: auto; background: #fff;">
        <ul class="list-group list-group-flush">
            @foreach($vehicleModels as $model)
                <li class="list-group-item bg-light py-1 font-weight-bold text-xs" wire:key="model-header-{{ $model->id }}">
                    {{ strtoupper($model->brand->brand_name ?? 'Brand') }} - {{ $model->model_name }}
                </li>
                
                @foreach($model->specifications as $spec)
                    {{-- KEY IS CRITICAL HERE --}}
                    <li class="list-group-item py-1 pl-4" wire:key="spec-wrapper-{{ $spec->id }}">
                        <div class="form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="spec_{{ $spec->id }}" 
                                   value="{{ $spec->id }}"
                                   wire:model.live="fitment_specifications"
                                   style="cursor: pointer; width: 18px; height: 18px;">
                            
                            <label class="form-check-label ml-2 d-inline-block" for="spec_{{ $spec->id }}" style="cursor: pointer; font-size: 14px;">
                                {{ $spec->variant->name ?? 'Standard' }} 
                                <span class="text-muted small">({{ $spec->production_start }}-{{ $spec->production_end }})</span>
                            </label>
                        </div>
                    </li>
                @endforeach
            @endforeach
        </ul>
    </div>
    <div class="card-footer py-2 bg-white border-top">
        <span class="badge badge-info">{{ count($fitment_specifications) }} Vehicles Selected</span>
        <button type="button" class="btn btn-xs btn-link float-right" wire:click="$set('fitment_specifications', [])">Clear All</button>
    </div>
</div>

                <div class="card border shadow-none">
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

</div> {{-- END ROOT --}}