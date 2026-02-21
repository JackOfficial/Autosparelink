<div> {{-- Single Root --}}
    <div class="container-fluid py-3">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="save">
            <div class="row">
                {{-- LEFT COLUMN --}}
                <div class="col-lg-7">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h3 class="card-title font-weight-bold mb-0"><i class="fas fa-info-circle mr-2 text-primary"></i> General Specifications</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Part Number</label>
                                    <input type="text" class="form-control" wire:model.defer="part_number" placeholder="e.g. 12345-ABC">
                                    @error('part_number') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Part Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model.defer="part_name">
                                    @error('part_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="small text-muted">Parent Category</label>
                                    <select class="form-control" wire:model.live="parentCategoryId">
                                        <option value="">-- Select Parent --</option>
                                        @foreach($parentCategories as $parent)
                                            <option value="{{ $parent->id }}">{{ $parent->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Child Category <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model.live="category_id">
                                        <option value="">-- Select Child --</option>
                                        @foreach($childCategories as $child)
                                            <option value="{{ $child->id }}">{{ $child->category_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Part Brand <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model.live="part_brand_id">
                                        <option value="">-- Select Brand --</option>
                                        @foreach($partBrands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }} ({{ $brand->type }})</option>
                                        @endforeach
                                    </select>
                                    @error('part_brand_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">OEM Number</label>
                                    <input type="text" class="form-control" wire:model.defer="oem_number">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h3 class="card-title font-weight-bold mb-0">Pricing & Inventory</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Price (RWF)</label>
                                    <input type="number" class="form-control text-success font-weight-bold" wire:model.defer="price">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Stock Qty</label>
                                    <input type="number" class="form-control" wire:model.defer="stock_quantity">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="font-weight-bold">Status</label>
                                    <select class="form-control" wire:model.defer="status">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN --}}
                <div class="col-lg-5">
                    <div class="card shadow-sm mb-4 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title font-weight-bold mb-0"><i class="fas fa-car mr-2"></i> Compatibility (Specifications)</h3>
                        </div>
                        <div class="card-body p-0">
                            <select wire:model.defer='fitment_specifications' class="form-control border-0" multiple style="height: 350px;">
                                @foreach($vehicleModels as $model)
                                    <optgroup label="{{ strtoupper($model->brand->brand_name ?? '') }} - {{ $model->model_name }}">
                                        @foreach($model->specifications as $spec)
                                            <option value="{{ $spec->id }}">
                                                {{ $spec->variant->name ?? 'Standard' }} | {{ $spec->production_start }}-{{ $spec->production_end }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="card-footer bg-white">
                            <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Hold Ctrl (Cmd) to select multiple vehicles.</small>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h3 class="card-title font-weight-bold mb-0"><i class="fas fa-images mr-2 text-warning"></i> Media Assets</h3>
                        </div>
                        <div class="card-body" x-data="{ previews: [] }">
                            <div class="custom-file mb-3">
                                <input type="file" multiple wire:model="photos" class="custom-file-input" id="partPhotos"
                                       @change="previews = []; [...$event.target.files].forEach(file => { let reader = new FileReader(); reader.onload = e => previews.push(e.target.result); reader.readAsDataURL(file); })">
                                <label class="custom-file-label" for="partPhotos">Choose images...</label>
                            </div>
                            
                            <div class="d-flex flex-wrap mt-2">
                                <template x-for="img in previews" :key="img">
                                    <img :src="img" class="img-thumbnail mr-2 mb-2" style="width:70px; height:70px; object-fit:cover;">
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sticky-top bg-white py-3 border-top mt-4 shadow-lg" style="bottom: 0; position: fixed; width: 100%; left: 0; z-index: 1000;">
                <div class="container-fluid d-flex justify-content-end">
                    <button type="submit" class="btn btn-success btn-lg px-5 shadow" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save"><i class="fas fa-save mr-2"></i> Create Spare Part</span>
                        <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin mr-2"></i> Saving...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>