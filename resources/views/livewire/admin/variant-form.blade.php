<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Variant Details</h3>
    </div>

    <form wire:submit.prevent="save">
        <div class="card-body">

            {{-- SUCCESS --}}
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- VEHICLE SELECTION --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto px-2">Vehicle Selection</legend>

                <div class="row">
                    <div class="col-md-4">
                        <label>Brand *</label>
                        <select wire:model.live="brand_id" class="form-control">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Vehicle Model *</label>
                        <select wire:model.live="vehicle_model_id"
                                class="form-control"
                                @disabled(empty($vehicleModels))>
                            <option value="">Select Model</option>
                            @foreach($vehicleModels as $model)
                                <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_model_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- GENERAL --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto px-2">General Information</legend>

                <div class="row">
                    <div class="col-md-6">
                        <label>Variant Name *</label>
                        <input type="text" wire:model="name" class="form-control">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6">
                        <label>Trim Level</label>
                        <input type="text" wire:model="trim_level" class="form-control">
                    </div>
                </div>
            </fieldset>

            {{-- MEDIA --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto px-2">Photos</legend>

                <input type="file" wire:model="photos" multiple class="form-control">

                <div class="mt-2 d-flex flex-wrap gap-2">
                    @foreach($photos as $photo)
                        <img src="{{ $photo->temporaryUrl() }}"
                             class="img-thumbnail"
                             style="max-width:120px;">
                    @endforeach
                </div>
            </fieldset>

            {{-- SPECIFICATIONS QUESTION --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto px-2">Specifications</legend>

                <label>Does this variant have specifications?</label>

                <div class="mt-2">
                    <label class="mr-3">
                        <input type="radio" wire:model="has_specifications" value="1">
                        Yes
                    </label>

                    <label>
                        <input type="radio" wire:model="has_specifications" value="0">
                        No
                    </label>
                </div>

                @error('has_specifications')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </fieldset>

        </div>

        <div class="card-footer">
            <button class="btn btn-primary">
                <i class="fa fa-save"></i> Save Variant
            </button>

            <a href="{{ route('admin.variants.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
