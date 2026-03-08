<div>
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    {{-- Header with a softer color palette --}}
                    <div class="card-header bg-white border-bottom py-3">
                        <h3 class="card-title font-weight-bold text-primary">
                            <i class="fas fa-car-side mr-2"></i> Model Details
                        </h3>
                    </div>

                    <div class="card-body">
                        <form wire:submit.prevent="save">

                            {{-- Refined Error Alert --}}
                            @if ($errors->any())
                                <div class="alert alert-custom bg-danger-light text-danger border-0 mb-4 shadow-sm">
                                    <div class="d-flex">
                                        <i class="fas fa-exclamation-circle mt-1 mr-3"></i>
                                        <div>
                                            <span class="font-weight-bold">Validation Errors:</span>
                                            <ul class="mb-0 small ml-n3">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Details Section --}}
                            <div class="bg-light p-4 rounded mb-4 shadow-none border">
                                <h6 class="text-uppercase text-muted font-weight-bold small mb-4">Core Information</h6>
                                
                                <div class="row">
                                    {{-- Brand Selection --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Brand <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-industry text-muted"></i></span>
                                            </div>
                                            <select wire:model.live="brand_id" class="form-control border-left-0 shadow-none @error('brand_id') is-invalid @enderror">
                                                <option value="">Select Brand</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('brand_id') <span class="text-danger extra-small mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Model Name --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Model Name <span class="text-danger">*</span></label>
                                        <input type="text" wire:model.live.debounce.500ms="model_name" class="form-control shadow-none @error('model_name') is-invalid @enderror" placeholder="e.g. Corolla">
                                        @error('model_name') <span class="text-danger extra-small mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Start Year --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Production Start Year</label>
                                        <input type="number" wire:model.live="production_start_year" class="form-control shadow-none @error('production_start_year') is-invalid @enderror" placeholder="YYYY">
                                        @error('production_start_year') <span class="text-danger extra-small mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- End Year --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Production End Year</label>
                                        <input type="number" wire:model.live="production_end_year" class="form-control shadow-none @error('production_end_year') is-invalid @enderror" placeholder="YYYY (Leave blank if active)">
                                        @error('production_end_year') <span class="text-danger extra-small mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-muted">Description</label>
                                    <textarea wire:model.live="description" class="form-control shadow-none border-0 shadow-sm" rows="3" placeholder="Enter general information about this series..."></textarea>
                                </div>
                            </div>

                            {{-- Media Upload Section --}}
                            <div class="p-4 rounded border mb-4 bg-white shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-uppercase text-muted font-weight-bold small mb-0">Vehicle Gallery</h6>
                                    <span wire:loading wire:target="photos" class="badge badge-info animate-pulse">
                                        <i class="fas fa-spinner fa-spin mr-1"></i> Uploading...
                                    </span>
                                </div>

                                <div class="custom-file-upload border-dashed p-5 text-center rounded bg-light position-relative" 
                                     style="border: 2px dashed #dee2e6;">
                                    <input type="file" multiple wire:model="photos" class="position-absolute h-100 w-100 opacity-0 cursor-pointer" style="top:0; left:0; z-index:2;">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                    <p class="mb-1 font-weight-bold text-dark">Click or drag photos here to upload</p>
                                    <p class="text-muted small">Max 5MB per image. Multi-select allowed.</p>
                                </div>

                                {{-- Photo Previews --}}
                                @if($photos)
                                    <div class="row mt-4">
                                        @foreach($photos as $index => $photo)
                                            <div class="col-6 col-md-3 mb-3">
                                                <div class="card h-100 border shadow-none position-relative group">
                                                    <img src="{{ $photo->temporaryUrl() }}" class="card-img-top rounded shadow-sm" style="height:120px; object-fit:cover;">
                                                    <div class="card-footer p-1 bg-white text-center">
                                                        <button type="button" wire:click="removeUpload('photos', {{ $index }})" class="btn btn-link text-danger btn-sm p-0">
                                                            <i class="fas fa-trash-alt mr-1"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Action Bar --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small"><span class="text-danger">*</span> Required fields</span>
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm px-5" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="save">
                                        <i class="fas fa-save mr-2"></i> Save Vehicle Model
                                    </span>
                                    <span wire:loading wire:target="save">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> Processing...
                                    </span>
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>