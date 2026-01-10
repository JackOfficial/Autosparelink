<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Variant Details</h3>
    </div>

    <form wire:submit.prevent="save">
        <div class="card-body">

            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- VEHICLE SELECTION --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto px-2">Vehicle Selection</legend>

                <div class="row">
                    <div class="col-md-4">
                        <label>Brand <span class="text-danger">*</span></label>
                        <select wire:model.live="brand_id" class="form-control">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Vehicle Model <span class="text-danger">*</span></label>
                        <select wire:model.live="vehicle_model_id"
                                class="form-control"
                                @if(empty($vehicleModels)) disabled @endif>
                            <option value="">Select Model</option>
                            @foreach($vehicleModels as $model)
                                <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_model_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- GENERAL --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto px-2">General Information</legend>

                <div class="row">
                    <div class="col-md-6">
                        <label>Variant Name <span class="text-danger">*</span></label>
                        <input type="text"
                               wire:model="name"
                               class="form-control"
                               placeholder="e.g. Corolla 1.8L Sedan">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label>Trim Level</label>
                        <input type="text"
                               wire:model="trim_level"
                               class="form-control"
                               placeholder="LE, SE, Sport">
                    </div>
                </div>
            </fieldset>

            {{-- IDENTIFIERS --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto px-2">Identifiers</legend>

                <div class="row">
                    <div class="col-md-4">
                        <label>Chassis Code</label>
                        <input type="text" wire:model="chassis_code" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label>Model Code</label>
                        <input type="text" wire:model="model_code" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label>Status</label>
                        <select wire:model="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            {{-- MEDIA --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto px-2">Media</legend>

                <div class="row">
                    <div class="col-md-6">
                        <label>Default Photo</label>
                        <input type="file" wire:model="photo" class="form-control" accept="image/*">

                        @if ($photo)
                            <div class="mt-2">
                                <img src="{{ $photo->temporaryUrl() }}"
                                     class="img-thumbnail"
                                     style="max-width:160px;">
                            </div>
                        @endif

                        <small class="text-muted">Optional default image.</small>
                    </div>
                </div>
            </fieldset>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Save Variant
            </button>

            <a href="{{ route('admin.variants.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
