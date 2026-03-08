<div> {{-- Mandatory Single Root --}}
    @push('styles')
    <style>
        .card { border-radius: 8px; }
        .card-header { background-color: transparent; border-bottom: 1px solid rgba(0,0,0,.1); }
        .input-group-text { background-color: #f8f9fa; }
        .border-dashed { border: 2px dashed #dee2e6; transition: 0.3s; }
        .border-dashed:hover { border-color: #007bff; background: #f1f8ff; }
        .sticky-action-bar {
            position: sticky;
            bottom: 0;
            background: #fff;
            padding: 15px 25px;
            margin: 30px -15px -15px -15px; /* Pulls to edges of parent padding */
            box-shadow: 0 -5px 15px rgba(0,0,0,0.05);
            z-index: 1000;
            border-top: 1px solid #dee2e6;
        }
        .preview-img { width: 70px; height: 70px; object-fit: cover; border-radius: 4px; }
    </style>
    @endpush

    <section class="content-header">
        <div class="container-fluid">
            <h1 class="font-weight-bold">Edit Spare Part: <span class="text-primary">{{ $part_name }}</span></h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible shadow-sm border-0">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <form wire:submit.prevent="update" enctype="multipart/form-data">
                <div class="row">
                    
                    {{-- LEFT COLUMN: Details --}}
                    <div class="col-lg-7">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-info-circle mr-2 text-primary"></i> General Specification</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">Part Number</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">#</span></div>
                                            <input type="text" class="form-control @error('part_number') is-invalid @enderror" wire:model.defer="part_number">
                                        </div>
                                        @error('part_number') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">Part Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('part_name') is-invalid @enderror" wire:model.defer="part_name">
                                        @error('part_name') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold small text-muted">Parent Category</label>
                                        <select class="form-control bg-light" wire:model.live="parentCategoryId">
                                            <option value="">-- Select Parent --</option>
                                            @foreach($parentCategories as $parent)
                                                <option value="{{ $parent->id }}">{{ $parent->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">Child Category <span class="text-danger">*</span></label>
                                        <select class="form-control @error('category_id') is-invalid @enderror" wire:model.live="category_id">
                                            <option value="">-- Select Child --</option>
                                            @foreach($childCategories as $child)
                                                <option value="{{ $child->id }}">{{ $child->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">Part Brand <span class="text-danger">*</span></label>
                                        <select class="form-control @error('part_brand_id') is-invalid @enderror" wire:model.live="part_brand_id">
                                            <option value="">-- Select Brand --</option>
                                            @foreach($partBrands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }} ({{ $brand->type }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">OEM Number</label>
                                        <input type="text" class="form-control" wire:model.defer="oem_number" placeholder="Optional">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label class="font-weight-bold">Price (RWF)</label>
                                        <input type="number" class="form-control font-weight-bold text-success" wire:model.defer="price">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label class="font-weight-bold text-dark">Stock Qty</label>
                                        <input type="number" class="form-control shadow-sm" wire:model.defer="stock_quantity">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label class="font-weight-bold">Status</label>
                                        <select class="form-control font-weight-bold {{ $status === 'Active' ? 'text-success' : 'text-danger' }}" wire:model.defer="status">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-header"><h3 class="card-title font-weight-bold">Substitution Parts</h3></div>
                            <div class="card-body">
                                <select wire:model.defer="substitution_part_ids" class="form-control" multiple style="height: 120px;">
                                    @foreach($allParts as $p)
                                        <option value="{{ $p->id }}">{{ $p->part_name }} ({{ $p->partBrand->name ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN: Compatibility & Media --}}
                    <div class="col-lg-5">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title font-weight-bold"><i class="fas fa-car mr-2"></i> Compatibility</h3>
                            </div>
                            <div class="card-body">
                                <select wire:model.defer="fitment_specifications" class="form-control" multiple style="height: 250px;">
                                    @foreach($vehicleModels as $model)
                                        <option disabled class="bg-light font-weight-bold text-dark">{{ optional($model->brand)->brand_name }} {{ $model->model_name }}</option>
                                        @foreach($model->specifications as $spec)
                                            <option value="{{ $spec->id }}">&nbsp;&nbsp;• {{ $spec->production_start }} - {{ $spec->production_end }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header"><h3 class="card-title font-weight-bold">Media Assets</h3></div>
                            <div class="card-body">
                                @if($existingPhotos->count())
                                    <div class="d-flex flex-wrap mb-3">
                                        @foreach($existingPhotos as $photo)
                                            <div class="position-relative m-1 shadow-sm border p-1 rounded bg-white">
                                                <img src="{{ asset('storage/'.$photo->file_path) }}" class="preview-img">
                                                <button type="button" wire:click="deletePhoto({{ $photo->id }})" class="btn btn-xs btn-danger position-absolute" style="top:-5px; right:-5px; border-radius: 50%;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="p-3 border-dashed text-center rounded" x-data="{ previews: [] }">
                                    <p class="small text-muted mb-2">Upload New Photos</p>
                                    <input type="file" multiple wire:model="photos" class="form-control-file" 
                                           @change="previews = []; [...$event.target.files].forEach(file => { let reader = new FileReader(); reader.onload = e => previews.push(e.target.result); reader.readAsDataURL(file); })">
                                    
                                    <div class="d-flex flex-wrap mt-2">
                                        <template x-for="img in previews" :key="img">
                                            <img :src="img" class="preview-img m-1 border shadow-sm">
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STICKY ACTION BAR --}}
                <div class="sticky-action-bar">
                    <div class="d-flex justify-content-between align-items-center container-fluid">
                        <a href="{{ url()->previous() }}" class="btn btn-default shadow-sm"><i class="fas fa-chevron-left mr-1"></i> Back</a>
                        
                        <button type="submit" class="btn btn-warning px-5 font-weight-bold shadow" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="update">
                                <i class="fas fa-save mr-2"></i> Update Spare Part
                            </span>
                            <span wire:loading wire:target="update">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Updating...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>