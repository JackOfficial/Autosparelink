<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Variant Details</h3>
    </div>

    <form wire:submit.prevent="save">
        <div class="card-body">

            {{-- SUCCESS MESSAGE --}}
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- VEHICLE SELECTION --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto px-2">Vehicle Selection</legend>

                <div class="row">
                    {{-- Brand --}}
                   <div class="col-md-4">
    <label>Brand <span class="text-danger">*</span></label>
    <select wire:model.live="brand_id" class="form-control" @disabled($disableModelDropdown)>
        <option value="">Select Brand</option>
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
        @endforeach
    </select>
    @error('brand_id') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-md-4">
    <label>Vehicle Model <span class="text-danger">*</span></label>
    <select wire:model.live="vehicle_model_id"
            class="form-control"
            @disabled($disableModelDropdown || empty($vehicleModels))>
        <option value="">Select Model</option>
        @foreach($vehicleModels as $model)
            <option value="{{ $model->id }}">{{ $model->model_name }}</option>
        @endforeach
    </select>
    @error('vehicle_model_id') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-md-4" x-data="{ showTooltip: false }" style="position: relative;">
    <label>
        Variant Name <span class="text-danger">*</span>
        <!-- Icon wrapper -->
        <span style="position: relative; display: inline-block;">
            <i class="fa fa-question-circle text-info"
               @mouseenter="showTooltip = true"
               @mouseleave="showTooltip = false"
               style="cursor: pointer;"></i>

            <!-- Tooltip -->
            <div x-show="showTooltip"
                 x-cloak
                 class="bg-dark text-white p-2 rounded"
                 style="position: absolute; top: 50%; left: 110%; width:25%; transform: translateY(-50%); white-space: nowrap; z-index: 1000;">
                Enter only the trim/variant designation (e.g., S, XLE, Adventure). Do NOT include model name, year, or body type.
            </div>
        </span>
    </label>

    <input type="text" wire:model="name" class="form-control">
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
                </div>
            </fieldset>

            {{-- MEDIA --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto px-2">Photos</legend>

                <input type="file" wire:model="photos" multiple class="form-control" accept="image/*">

                @if(!empty($photos))
                    <div class="mt-2 d-flex flex-wrap gap-2">
                        @foreach($photos as $photo)
                            <img src="{{ $photo->temporaryUrl() }}"
                                 class="img-thumbnail"
                                 style="max-width:120px;">
                        @endforeach
                    </div>
                @endif
            </fieldset>

            {{-- SPECIFICATIONS QUESTION --}}
            <fieldset class="border p-3 mb-4">
                <legend class="w-auto px-2">Specifications</legend>

                <label>Does this variant have specifications?</label>
                <div class="mt-2 d-flex gap-3">
                    <label>
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

        {{-- FOOTER --}}
        <div class="card-footer d-flex gap-2">
            <button class="btn btn-primary">
                <i class="fa fa-save"></i> Save Variant
            </button>
            <a href="{{ route('admin.variants.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
