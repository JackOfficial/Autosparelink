<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form wire:submit.prevent="save" enctype="multipart/form-data">
                <div class="row">

                    <!-- Part -->
                    <div class="col-md-6 mb-3">
                        <label>Part</label>
                        <select wire:model="part_id" class="form-control">
                            <option value="">-- Select Part --</option>
                            @foreach($parts as $part)
                                <option value="{{ $part->id }}">{{ $part->part_name }}</option>
                            @endforeach
                        </select>
                        @error('part_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Vehicle Model -->
                    <div class="col-md-6 mb-3">
                        <label>Vehicle Model</label>
                        <select wire:model="vehicle_model_id" class="form-control">
                            <option value="">-- Select Model --</option>
                            @foreach($vehicleModels as $model)
                                <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_model_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Variant -->
                    <div class="col-md-6 mb-3">
                        <label>Variant (optional)</label>
                        <select wire:model="variant_id" class="form-control">
                            <option value="">-- Select Variant --</option>
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                            @endforeach
                        </select>
                        @error('variant_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label>Status</label>
                        <select wire:model="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Year Start & End -->
                    <div class="col-md-3 mb-3">
                        <label>Year Start</label>
                        <input type="number" wire:model="year_start" class="form-control">
                        @error('year_start') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Year End</label>
                        <input type="number" wire:model="year_end" class="form-control">
                        @error('year_end') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Photos -->
                    <div class="col-md-12 mb-3">
                        <label>Photos</label>
                        <input type="file" wire:model="photos" multiple class="form-control" accept="image/*">
                        <div class="mt-2 flex flex-wrap">
                            @if($photos)
                                @foreach($photos as $photo)
                                    <img src="{{ $photo->temporaryUrl() }}" class="img-thumbnail me-2 mb-2" width="100">
                                @endforeach
                            @endif
                        </div>
                        @error('photos.*') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                </div>

                <button class="btn btn-primary"><i class="fas fa-save"></i> Save Fitment</button>
            </form>
        </div>
    </div>
</div>
